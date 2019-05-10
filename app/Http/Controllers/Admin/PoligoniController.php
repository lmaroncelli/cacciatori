<?php

namespace App\Http\Controllers\Admin;

use App\CoordinataPoligono;
use App\Http\Controllers\Controller;
use App\Poligono;
use App\Zona;
use Illuminate\Http\Request;

class PoligoniController extends Controller
{
    public function salvaCoordinatePoligonoAjax(Request $request) 
    	{
    	$zona_id = $request->get('zona_id');
    	$distretto_coords = $request->get('distretto_coords');
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
    	$zona = Zona::find($zona_id);

    	$poligono = $zona->poligono;

    	$poligono->coordinate()->delete();

    	$poligono->coordinate()->createMany($distretto_coords);
    	
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

          $poligono = $zona->poligono()->create(['name' => 'Poligono '.$nome_zona]);

          $coordinate =  $element['geometry']['coordinates'][0];

          foreach ($coordinate as $coordinata_arr) 
            {
            $my_coord = new CoordinataPoligono(['lat' => $coordinata_arr[0] , 'long' => $coordinata_arr[1]]);
            $poligono->coordinate()->save($my_coord);
            }

          }


    	}


}
