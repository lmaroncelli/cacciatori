<?php

use App\Atc;
use App\Zona;
use App\Distretto;
use App\UnitaGestione;
use App\CoordinataPoligono;
use Illuminate\Database\Seeder;

class CinghialiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
      $zone_old = Zona::all();

    	foreach ($zone_old as $zona) 
    		{
    		$zona->destroyMe();
        }
        
      $unita_old = UnitaGestione::all();

    	foreach ($unita_old as $unita) 
    		{
    		
    		$unita->destroyMe(); 	
    		
         }
      
      $distretti_old = Distretto::all();

    	foreach ($distretti_old as $distretto) 
    		{
    		
    		$distretto->destroyMe(); 	
    		
    	 	} 
 


      // creo distretto
    	$files = ['ATC_RN1_DG-cinghiale.kml'];


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
      
      // FINE creo distretto
      

      // creo unita
    	$files = ['ATC_RN1_UG-cinghiale.kml'];
      
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
              $unita = $distretto->unita()->create(['nome' => $name]);
              
		       		
		       		$poligono = $unita->poligono()->create(['name' => 'Poligono unita '.$unita->nome]);

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
