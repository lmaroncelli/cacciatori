<?php

use App\CoordinataPoligono;
use App\Distretto;
use App\Zona;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{



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
		     		$zona = $unita->zone()->create(['nome' => $name]);
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

	 		$distretto = Distretto::where('nome','distretto_a1')->first();
	 		$distretto->unita()->delete();
	 		$unita = $distretto->unita()->create(['nome' => 'UTG a1']);

    	$files = ['a1_braccata.kml', 'a1_girata.kml'];

    	$this->_createZonaFromXml($files, $unita);

      


	 		$distretto = Distretto::where('nome','distretto_a2')->first();
	 		$distretto->unita()->delete();
	 		$unita = $distretto->unita()->create(['nome' => 'UTG a2']);

    	$files = ['a2_braccata.kml', 'a2_girata.kml'];

    	$this->_createZonaFromXml($files, $unita);



	 		$distretto = Distretto::where('nome','distretto_b1')->first();
	 		$distretto->unita()->delete();
	 		$unita = $distretto->unita()->create(['nome' => 'UTG b1']);

    	$files = ['b1_girata.kml', 'b2_girata.kml'];

    	$this->_createZonaFromXml($files, $unita);



    	$distretto = Distretto::where('nome','distretto_c')->first();
	 		$distretto->unita()->delete();
	 		$unita = $distretto->unita()->create(['nome' => 'UTG c']);

    	$files = ['c_braccata.kml'];

    	$this->_createZonaFromXml($files, $unita);



    }
}
