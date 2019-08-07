<?php

namespace App\Http\Controllers\Admin;

use App\Zona;
use App\Distretto;
use App\UnitaGestione;
use App\CoordinataPoligono;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class PoligoniController extends LoginController
{
    public function salvaCoordinatePoligonoAjax(Request $request) 
    	{

      if($request->has('zona_id'))
        {
    	   $zona_id = $request->get('zona_id');

         $zona = Zona::find($zona_id);

         $poligono = $zona->poligono;

        }
      elseif($request->has('utg_id'))
        {
        $utg_id = $request->get('utg_id');

        $unita = UnitaGestione::find($utg_id);

        $poligono = $unita->poligono;
        }
      else
        {
        $distretto_id = $request->get('distretto_id');

        $distretto = Distretto::find($distretto_id);

        $poligono = $distretto->poligono;

        }

    	$coords = $request->get('coords');
      
      if(!is_null($coords) && !empty($coords))
        {
        $poligono->coordinate()->delete();
        $poligono->coordinate()->createMany($coords);
        }
      

        echo "Nuove coordinate salvate correttamente";

    	/*
    	array:5 [
    	  0 => array:2 [
    	    "lat" => "44.066493"
    	    "lng" => "12.550753999999984"
    	  ]
    	  1 => array:2 [
    	    "lat" => "44.069207"
    	    "lng" => "12.592094999999972"
    	  ]
    	  2 => array:2 [
    	    "lat" => "44.043546732062424"
    	    "lng" => "12.60307991540526"
    	  ]
    	  3 => array:2 [
    	    "lat" => "44.04058499397212"
    	    "lng" => "12.565656322509767"
    	  ]
    	  4 => array:2 [
    	    "lat" => "44.048605"
    	    "lng" => "12.535472000000027"
    	  ]
    	]
    	dd($distretto_coords);
    	 */
    	
    	}


    public function aggiornaCentroAjax(Request $request)
      {
      $lat = $request->get('lat');
      $long = $request->get('long');
      $zoom = $request->get('zoom');

      if($request->has('zona_id'))
        {
        $zona_id = $request->get('zona_id');

        Zona::where('id',$zona_id)->update(['center_lat' => $lat , 'center_long' => $long, 'zoom' => $zoom ]); 
        }
      elseif($request->has('utg_id'))
        {
        $utg_id = $request->get('utg_id');

        UnitaGestione::where('id',$utg_id)->update(['center_lat' => $lat , 'center_long' => $long, 'zoom' => $zoom ]); 
        }
      else
        {
        $distretto_id = $request->get('distretto_id');

        Distretto::where('id',$distretto_id)->update(['center_lat' => $lat , 'center_long' => $long, 'zoom' => $zoom ]);
        }

      echo "ok";

      }


    public function readJson()
    	{
   
        //zone di braccata UTG 1-A (id=3) 
    	$string = file_get_contents(storage_path('app/public/a1_braccata.json'));
    	$json_a = json_decode($string, true);

    	//dd($json_a);

        foreach ($json_a['features'] as $element) 
          {
          $data_zona = $element['properties'];

          // "CDAD" => "11 - Via Spogna"
          $nome_zona = $data_zona['CDAD'];

          $arr = explode(' ',trim($nome_zona));
          $num = $arr[0];
          $num = 1111;
          $zona = Zona::create(['unita_gestione_id' => 3, 'nome' => $nome_zona, 'tipo' => 'zona', 'numero' => $num, 'superficie' => $data_zona['AREA']]);

          $poligono = $zona->poligono()->create(['name' => 'Poligono zona '.$nome_zona]);

          $coordinate =  $element['geometry']['coordinates'][0];

          foreach ($coordinate as $coordinata_arr) 
            {
            $my_coord = new CoordinataPoligono(['lat' => $coordinata_arr[0] , 'long' => $coordinata_arr[1]]);
            $poligono->coordinate()->save($my_coord);
            }

          }


    	}


    public function readXml()
      {
      $xml = simplexml_load_file(storage_path('app/public/distretto_b2.kml'));
      
      /*echo '<pre>';
      print_r($xml);
      echo '</pre>';*/
      
      $coords = $xml->Document->Folder->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;

      $coords = $coords->__toString();
      
      $coords_arr = explode(' ',$coords);


      //dd($coords_arr);


      $distretto = Distretto::find(3);

      $poligono = $distretto->poligono()->create(['name' => 'Poligono distretto '.$distretto->nome]);

      foreach ($coords_arr as $value) 
        {
        $coordinata_arr = explode(',', $value);
        $my_coord = new CoordinataPoligono(['lat' => $coordinata_arr[1] , 'long' => $coordinata_arr[0]]);
        $poligono->coordinate()->save($my_coord);
        }

      }

}
