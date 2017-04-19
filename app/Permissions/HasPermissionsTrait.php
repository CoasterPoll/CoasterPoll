<?php

namespace ChaseH\Permissions;

use ChaseH\Models\Permission;
use ChaseH\Models\Role;
use Illuminate\Support\Facades\Cache;

trait HasPermissionsTrait {
    // Relationships
    public function roles() {
        return $this->belongsToMany(Role::class, 'users_roles')->withTimestamps();
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'users_permissions')->withTimestamps();
    }

    // Functions
    public function hasRole(...$roles) {
        $user = $this;
        $roles = Cache::remember('roles:'.$this->id, 60, function() use ($user) {
            return $user->roles;
        });

        foreach($roles as $role) {
            if($roles->contains('name', $role)) {
                return true;
            }
        }

        return false;
    }

    // Use this one to check!
    public function hasPermissionTo($permission) {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermission($permission) {
        $user = $this;
        $permissions = Cache::remember('perms:'.$this->id, 60, function() use ($user) {
            return $user->permissions;
        });

        return (bool) $permissions->contains('name', $permission->name);
    }

    public function hasPermissionThroughRole($permission) {
        $user = $this;
        $roles = Cache::remember('roles:'.$this->id, 60, function() use ($user) {
            return $user->roles;
        });

        $perm_roles = Cache::remember('perm-role:'.$permission->id, 60, function() use ($permission) {
            return $permission->roles;
        });

        foreach($perm_roles as $role) {
            if($roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array ...$permissions
     * @return $this
     */
    public function givePermissionTo(...$permissions) {
        $permissions = $this->getPermissions(array_flatten($permissions));

        // If we try to set a permission that doesn't exist, we don't want an error, we want to gracefully fail.
        if($permissions === null) {
            return $this;
        }

        $this->permissions()->sync($permissions);

        return $this;
    }

    protected function getPermissions(array $permissions) {
        return Permission::whereIn('name', $permissions)->get();
    }

    public function withdrawPermissionTo(...$permissions) {
        $permissions = $this->getPermissions(array_flatten($permissions));

        $this->permissions()->detach($permissions);

        return $this;
    }

    public function updatePermissions(...$permissions) {
        $this->permissions()->detach();

        return $this->givePermissionTo($permissions);
    }

    public function giveRoleTo(...$roles) {
        $roles = $this->getRoles(array_flatten($roles));

        // Can't save no roles...
        if($roles === null) {
            return $this;
        }

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    public function getRoles(array $roles) {
        return Role::whereIn('name', $roles)->get();
    }

    public function withdrawRoleTo(...$roles) {
        $roles = $this->getRoles(array_flatten($roles));

        $this->roles()->detach($roles);

        return $this;
    }
}