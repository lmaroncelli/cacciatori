<?php

use App\Squadra;
use Carbon\Carbon;
use Faker\Factory;
use App\AzioneCaccia;
use Illuminate\Database\Seeder;

class AzioniCacciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Factory::create();

        // seleziono una squadra random

        $sq = Squadra::inRandomOrder()->first();
        $squadra_id = $sq->id;

        $distretto = $sq->distretto;
        $distretto_id = $distretto->id;

        $utg = $distretto->unita()->inRandomOrder()->first();
        $unita_gestione_id = $utg->id;

        $zona = $utg->zone()->inRandomOrder()->first();
        $zona_id = $zona->id;


        $day = mt_rand(1,15);
        $dal = Carbon::createFromDate(2019, 6, $day);
        $al = Carbon::createFromDate(2019, 6, $day)->addMinutes(mt_rand(30,300));
        
        $dalle = $dal->format('Y-m-d H:i:s');
        $alle = $al->format('Y-m-d H:i:s');
      
        $azione = AzioneCaccia::create(['user_id' => 1, 'squadra_id' => $squadra_id, 'distretto_id' => $distretto_id, 'unita_gestione_id' => $unita_gestione_id, 'zona_id' => $zona_id, 'dalle' => $dalle, 'alle' => $alle]);

    }
}
