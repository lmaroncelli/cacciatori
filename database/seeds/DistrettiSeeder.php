<?php

use App\Atc;
use App\CoordinataPoligono;
use App\Distretto;
use Illuminate\Database\Seeder;

class DistrettiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


    	$distretti_old = Distretto::all();

    	foreach ($distretti_old as $distretto) 
    		{
    		
    		$distretto->destroyMe(); 	
    		
    	 	} 

    	$files = ['241019/ATC_RN1_DG-cinghiale.kml'];


      foreach ($files as $file) 
      	{
         $this->command->info('seeding file '.$file);
	       $xml = simplexml_load_file(storage_path('app/public/'.$file));

		     if($xml)
		     	{
		     		$name = $xml->Document->Folder->name->__toString();

            try 
              {
		     		   $coords = $xml->Document->Folder->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;
              } 
            catch (\Exception $e) 
              {
              $coords = $xml->Document->Folder->Placemark->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
              }
		     		
            $coords = $coords->__toString();
		     		$coords_arr = explode(' ',$coords);

		     		if(count($coords_arr))
		     			{

		     			$atc_rn1 = Atc::code('RN1');
		       		$distretto = $atc_rn1->distretti()->create(['nome' => $name]);
		       		$poligono = $distretto->poligono()->create(['name' => 'Poligono distretto '.$distretto->nome]);

		       		foreach ($coords_arr as $value) 
		       		  {
		       		  $coordinata_arr = explode(',', $value);
		       		  $my_coord = new CoordinataPoligono(['lat' => $coordinata_arr[1] , 'long' => $coordinata_arr[0]]);
		       		  $poligono->coordinate()->save($my_coord);
		       		  }

		     			}

		     	}
      	
      	}

    }
}
