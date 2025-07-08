<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Factories\Permission\Factory as PermissionFactory;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(PermissionFactory $permission)
    {
        $this->registerPolicies();

        if (\Schema::hasTable('permissions') and $permission->get()->count() > 0) {
            $row = $permission->get()->pluck('route_name', 'id');
            foreach ($row as $id => $route_name) {                                               
                Gate::define($route_name, function($user) use($id) {                
                    return $user->roles()
                                ->first()
                                ->hasPermissionById($id);
                });                      
            }            
        }
    }
}
