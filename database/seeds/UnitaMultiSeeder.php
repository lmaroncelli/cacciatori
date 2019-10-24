<?php

use App\Distretto;
use App\CoordinataPoligono;
use Illuminate\Database\Seeder;

class UnitaMultiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $files = ['241019/ATC_RN1_UG-cinghiale.kml'];
      

      foreach ($files as $file) 
      	{
        $this->command->info('seeding file '.$file);
	      $xml = simplexml_load_file(storage_path('app/public/'.$file));

        if($xml)
          {
          // dentro Document->folder ci sono tanti Placemark
          $root = $xml->Document->Folder;

          foreach($root->Placemark as $unita) 
            {
              // questo nome fa riferimento al distretto a cui l'unità dovrà essere collegata
              $name = $unita->name->__toString();

              $nome_unita = 'unita '.$name;

              try 
                {
                $coords = $unita->Polygon->outerBoundaryIs->LinearRing->coordinates;
                } 
              catch (\Exception $e) 
                {
                $coords = $unita->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
                }
              
              $coords = trim($coords->__toString());
              $coords_arr = explode(' ',$coords);
              //dd($coords_arr);
              if(count($coords_arr))
                {
                $distretto = Distretto::where('nome',$name)->first();

                if(!is_null($distretto))
                  {
                    
                  $unita = $distretto->unita()->create(['nome' => $nome_unita]);
                  
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


          } // end xml exists
        
        
        }// end foreach file xml

    
    } // end run

} // end class
