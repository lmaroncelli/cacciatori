<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer(['admin.squadre.form','admin.utg.form'],'App\Http\Composers\DistrettiFormComposer');
        view()->composer(['admin.distretti.form','admin.cacciatori.form'],'App\Http\Composers\SquadreFormComposer');         
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
