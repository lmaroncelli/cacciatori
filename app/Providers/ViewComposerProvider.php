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
        view()->composer(['admin.squadre.form'],'App\Http\Composers\ZoneFormComposer');
        view()->composer(['admin.distretti.form','admin.cacciatori.form','admin.zone.form'],'App\Http\Composers\SquadreFormComposer');         
        view()->composer(['admin.zone.form'],'App\Http\Composers\UtgFormComposer');         
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
