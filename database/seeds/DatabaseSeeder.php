<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        $this->call(DistrettiMultiSeeder::class);

        $this->call(UnitaMultiSeeder::class);

        $this->call(ZoneMultiSeeder::class);
 				
        // $this->call(CacciatoriSeeder::class);
     		
        //$this->call(PolignoSeeder::class);

    }
}
