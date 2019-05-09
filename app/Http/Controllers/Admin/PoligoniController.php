<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
