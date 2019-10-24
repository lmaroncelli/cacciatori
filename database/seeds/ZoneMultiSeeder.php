<?php

use App\Zona;
use App\CoordinataPoligono;
use Illuminate\Database\Seeder;

class ZoneMultiSeeder extends Seeder
{
    public function run()
    {
    $files = ['241019/ATC_RN1_Quadranti.kml'];
      

      foreach ($files as $file) 
      	{
        $this->command->info('seeding file '.$file);
	      $xml = simplexml_load_file(storage_path('app/public/'.$file));

        if($xml)
          {
          // dentro Document->folder ci sono tanti Placemark
          $root = $xml->Document->Folder;

          foreach($root->Placemark as $zona) 
            {
            $name = $zona->name->__toString();
            try 
                {
                $coords = $zona->Polygon->outerBoundaryIs->LinearRing->coordinates;
                } 
              catch (\Exception $e) 
                {
                $coords = $zona->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
                }
              
              $coords = trim($coords->__toString());
              $coords_arr = explode(' ',$coords);
              
              if(count($coords_arr))
                {
                $zona = Zona::create(['nome' => $name]);
                $poligono = $zona->poligono()->create(['name' => 'Poligono quadrante '.$zona->nome]);
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

