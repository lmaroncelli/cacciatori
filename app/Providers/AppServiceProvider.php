<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::if('role', function ($role) {
          return Auth::user()->hasRole($role);
        });

        Blade::if('not_role', function ($role) {
          return !Auth::user()->hasRole($role);
        });

        Blade::if('not_role_and', function ($roles) {
          return !Auth::user()->hasRole($roles[0]) && !Auth::user()->hasRole($roles[1]);
        });

        Blade::if('role_or', function ($roles) {
          return Auth::user()->hasRole($roles[0]) || Auth::user()->hasRole($roles[1]);
        });
    }
}
