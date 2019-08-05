<?php

use Illuminate\Database\Seeder;

class CacciatoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $cacciatori = factory(App\Cacciatore::class, 10)->create();
    
    }
}
