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
        view()->composer(['admin.squadre.form'], 'App\Http\Composers\CacciatoriFormComposer');
        view()->composer(['admin.azioni.form','admin.azioni.index','admin.squadre.form','admin.referenti.form'],'App\Http\Composers\ZoneFormComposer');
        view()->composer(['admin.cacciatori.form','admin.zone.form','admin.azioni.form', 'admin.azioni.index'],'App\Http\Composers\SquadreFormComposer');
        view()->composer(['admin.azioni.form'],'App\Http\Composers\UtgFormComposer');         
        view()->composer(['admin.province.form'],'App\Http\Composers\ComuniFormComposer');         
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
