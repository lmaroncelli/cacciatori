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
        view()->composer(['admin.squadre.form','admin.utg.form'],'App\Http\Composers\DistrettiFormComposer', 'App\Http\Composers\CacciatoriFormComposer');
        view()->composer(['admin.utg.form','admin.azioni.form'],'App\Http\Composers\ZoneFormComposer');
        view()->composer(['admin.cacciatori.form','admin.zone.form','admin.azioni.form'],'App\Http\Composers\SquadreFormComposer');
        view()->composer(['admin.zone.form','admin.distretti.form','admin.azioni.form'],'App\Http\Composers\UtgFormComposer');         
        view()->composer(['admin.province.form'],'App\Http\Composers\ComuniFormComposer');         
        View()->composer(['admin.azioni.index'],'App\Http\Composers\SquadreFormComposer');
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
