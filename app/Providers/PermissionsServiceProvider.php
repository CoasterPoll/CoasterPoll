<?php

namespace ChaseH\Providers;

use ChaseH\Models\Permission;
use ChaseH\Models\Role;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if(Schema::hasTable('permissions')) {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        }

        $this->addBladeDirectives(); // Add role based blade directives
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    private function addBladeDirectives() {
        Blade::directive('role', function($role) {
            return "<?php if (auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function() {
            return "<?php endif; ?>";
        });
    }
}
