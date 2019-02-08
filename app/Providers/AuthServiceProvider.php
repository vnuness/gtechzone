<?php

namespace App\Providers;

use App\Models\Credentials\Permissions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (Schema::hasTable('permissions')) {

            Gate::before(function ($user) {
                if ($user->isSuperAdmin()) {
                    return true;
                }
            });

            $permissions = Permissions::with('roles')->get();

            if (!$permissions->count()) {
                return;
            }

            foreach ($permissions as $permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }
}
