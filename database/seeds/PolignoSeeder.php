<?php

use App\Poligono;
use Illuminate\Database\Seeder;

class PolignoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $poligono = Poligono::create(['zona_id'=> 2, 'name' => 'Poligono di prova']);

        $poligono->coordinate()->createMany([
            [
                'lat' => '44.066493' , 'long' => '12.550754'
            ],
            [
                'lat' => '44.069207' , 'long' => '12.592095'
            ],
            [
                'lat' => '44.044657' , 'long' => '12.597757'
            ],
            [
                'lat' => '44.048605' , 'long' => '12.535472'
            ],
            
        ]);
    }
}
