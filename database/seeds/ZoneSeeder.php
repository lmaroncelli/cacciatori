<?php

use App\Zona;
use App\Utility;
use App\Distretto;
use App\CoordinataPoligono;
use Illuminate\Database\Seeder;
use App\UnitaGestione;

class ZoneSeeder extends Seeder
{

    private function _createPoligonoUnita($utg)
      {
       //////////////////////////////////////////////////////
        // creo un poligono di 4 punti associato di default //
        //////////////////////////////////////////////////////
        $poligono = $utg->poligono()->create(['name' => 'Poligono unità gestione '.$utg->nome]);
        $poligono->coordinate()->createMany(Utility::fakeCoords());
      }

		private function _createZonaFromXml($files, $unita)
			{
      foreach ($files as $file) 
      	{
	      $xml = simplexml_load_file(storage_path('app/public/'.$file));

	      if($xml)
	      	{
	      	$name = $xml->Document->Folder->name->__toString();
	      	
	      	$coords = $xml->Document->Folder->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;
	      	$coords = $coords->__toString();
	      	$coords_arr = explode(' ',$coords);

	      	if(count($coords_arr))
		     		{
            if (str_contains($file, 'braccata') ) 
              {
                $tipo="zona";
              }
            else
              {
                $tipo="particella";
              
              }
		     		$zona = $unita->zone()->create(['nome' => $name, 'tipo' => $tipo]);
		     		$poligono = $zona->poligono()->create(['name' => 'Poligono zona '.$zona->nome]);
		     		
		     		foreach ($coords_arr as $value) 
		     		  {
		     		  $coordinata_arr = explode(',', $value);
		     		  $my_coord = new CoordinataPoligono(['lat' => $coordinata_arr[1] , 'long' => $coordinata_arr[0]]);
		     		  $poligono->coordinate()->save($my_coord);
		     		  }

		     		}

	      	}

	     	} // end for
			}


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

	 		$distretto = Distretto::where('nome','distretto_a1')->first();
      $distretto->unita()->delete();
      
      $nome_unita = 'UTG a1';
      $unita = $distretto->unita()->create(['nome' => $nome_unita]);
      
      $this->_createPoligonoUnita($unita);

    	$files = ['a1_braccata.kml', 'a1_girata.kml'];

    	$this->_createZonaFromXml($files, $unita);

      


	 		$distretto = Distretto::where('nome','distretto_a2')->first();
      $distretto->unita()->delete();
        
      $nome_unita = 'UTG a2';
      $unita = $distretto->unita()->create(['nome' => $nome_unita]);
      
      $this->_createPoligonoUnita($unita);

    	$files = ['a2_braccata.kml', 'a2_girata.kml'];

    	$this->_createZonaFromXml($files, $unita);



	 		$distretto = Distretto::where('nome','distretto_b1')->first();
	 		$distretto->unita()->delete();
	 		
      $nome_unita = 'UTG b1';
      $unita = $distretto->unita()->create(['nome' => $nome_unita]);
      
      $this->_createPoligonoUnita($unita);

    	$files = ['b1_girata.kml'];

      $this->_createZonaFromXml($files, $unita);
      

      $distretto = Distretto::where('nome','distretto_b2')->first();
      $distretto->unita()->delete();
       
      $nome_unita = 'UTG b2';
      $unita = $distretto->unita()->create(['nome' => $nome_unita]);
      
      $this->_createPoligonoUnita($unita);

	 	
    	$files = ['b2_girata.kml'];

    	$this->_createZonaFromXml($files, $unita);



    	$distretto = Distretto::where('nome','distretto_c')->first();
      $distretto->unita()->delete();
       
      $nome_unita = 'UTG c';
      $unita = $distretto->unita()->create(['nome' => $nome_unita]);      
      $this->_createPoligonoUnita($unita);

    	$files = ['c_braccata.kml'];

    	$this->_createZonaFromXml($files, $unita);



    }
}
