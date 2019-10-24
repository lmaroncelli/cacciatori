<?php

use App\Atc;
use App\Zona;
use App\Distretto;
use App\UnitaGestione;
use App\CoordinataPoligono;
use Illuminate\Database\Seeder;

class DistrettiMultiSeeder extends Seeder
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
         
    	$files = ['241019/ATC_RN1_DG-cinghiale.kml'];
      

      foreach ($files as $file) 
      	{
        $this->command->info('seeding file '.$file);
	      $xml = simplexml_load_file(storage_path('app/public/'.$file));

        if($xml)
          {
          // dentro Document->folder ci sono tanti Placemark
          $root = $xml->Document->Folder;

          foreach($root->Placemark as $distretto) 
            {
              $name = $distretto->name->__toString();

              try 
                {
                $coords = $distretto->Polygon->outerBoundaryIs->LinearRing->coordinates;
                } 
              catch (\Exception $e) 
                {
                $coords = $distretto->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
                }
              
              $coords = trim($coords->__toString());
              $coords_arr = explode(' ',$coords);
              //dd($coords_arr);

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


          } // end xml exists
        
        
        }// end foreach file xml

    
    } // end run

} // end class
