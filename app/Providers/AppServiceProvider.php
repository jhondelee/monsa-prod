<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Factories\Auth\Factory as AuthFactory;
use App\Factories\PermissionGroup\Factory as PermissionGroupFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);     

        $navGroup = new PermissionGroupFactory();
        $authUser = new AuthFactory;
        
        view()->composer('*', function($view) use($navGroup, $authUser) {
            
            if (auth()->check()) {
                
                $view->with('auth_user', $authUser->getUser());
                $view->with('auth_role', $authUser->getRole());
                $view->with('auth_employee', $authUser->getEmployee());
                $view->with('auth_navigation', $authUser->getPermissionList());
                
                $view->with('navGroup', $navGroup->getByPermission());                
            } 
                                       
        });
    }
}
