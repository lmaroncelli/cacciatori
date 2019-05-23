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
    		
    		$p = $distretto->poligono; 	
    		
    		if(!is_null($p))
	  			{
  				$p->coordinate()->delete();
  	 			$p->delete();
  	 			}
    			
    	 	$distretto->delete();
    	 	} 

    	$files = ['distretto_a1.kml', 'distretto_a2.kml','distretto_b1.kml', 'distretto_b2.kml', 'distretto_c.kml'];


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
