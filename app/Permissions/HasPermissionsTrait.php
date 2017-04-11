<?php

namespace ChaseH\Permissions;

use ChaseH\Models\Permission;
use ChaseH\Models\Role;

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
        foreach($roles as $role) {
            if($this->roles->contains('name', $role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionTo($permission) {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermission($permission) {
        return (bool) $this->permissions->where('name', $permission->name)->count();
    }

    public function hasPermissionThroughRole($permission) {
        foreach($permission->roles as $role) {
            if($this->roles->contains($role)) {
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