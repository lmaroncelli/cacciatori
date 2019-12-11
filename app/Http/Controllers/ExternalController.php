<?php

namespace App\Http\Controllers;

use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ExternalController extends Controller
{

  public function __construct()
    {
      ini_set('memory_limit', '256M');
      $colors['zona'] = 'red';
      $colors['distretto'] = '#fff3c9';
      $colors['utg'] = '#8EA1B9';

      View::share('colors', $colors);

    }
    
  public function showMappaAttivita(Request $request) 
    {

        if($request->has('data'))
          {
          $from = $request->get('data').' 00:00:00';
          $to = $request->get('data').' 23:59:59';
          
          $to_show = Carbon::createFromFormat('Y-m-d', $request->get('data'))->format('d/m/Y');
          }
        else 
          {
          $now = Carbon::now('Europe/Rome');
          $from = $now->toDateString().' 00:00:00';
          $to = $now->toDateString().' 23:59:59';

          $to_show = $now->format('d/m/Y');

          }   

          
        $azioni = null;
        $coordinate_zona = null;
        $item = null;
        $zone_count = null;
        $azioni_di_zona = null;
        $nomi_di_zona = null;
        $coordinate_unita = null;
        $nomi_unita = null;
        $nomi_ug_di_zona = null;
        $coordinate_distretto = null; 
        $nomi_distretto = null;
        

        Utility::getAzioniMappa($azioni,$coordinate_zona, $item, $zone_count, $azioni_di_zona, $nomi_di_zona, $coordinate_unita, $nomi_unita, $nomi_ug_di_zona, $coordinate_distretto, $nomi_distretto, $from, $to);
        
        dd($azioni);
    }
}
