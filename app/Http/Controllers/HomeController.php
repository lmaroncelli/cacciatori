<?php

namespace App\Http\Controllers;

use Auth; 
use App\Utility;
use Carbon\Carbon;
use App\AzioneCaccia;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;


class HomeController extends LoginController
{

  public function __construct()
    {
      ini_set('memory_limit', '512M');
       // Invoke parent
      parent::__construct();
    }
  

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showMappaAttivita(Request $request)
    {
        if(Auth::user()->hasRole('cartografo'))
          {
          return view('admin.home_cartografo');
          }


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
          
        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item', 'zone_count','azioni_di_zona','nomi_di_zona', 'coordinate_unita', 'nomi_unita', 'nomi_ug_di_zona', 'coordinate_distretto', 'nomi_distretto', 'to_show') );
    }

    public function changeDateMappaAttivitaAjax(Request $request) 
      {
      if($request->has('data'))
        {
        $from = $request->get('data').' 00:00:00';
        $to = $request->get('data').' 23:59:59';
        
        $azioni = null;
        $coordinate_zona = null;

        $item = null;
        $zone_count = null;
        
        $azioni_di_zona = null;
        $nomi_di_zona = null;
        
        $coordinate_unita = null;
        $nomi_unita = null;
        
        $nomi_ug_di_zona = null;


        Utility::getAzioniMappa($azioni,$coordinate_zona, $item, $zone_count, $azioni_di_zona, $nomi_di_zona, $coordinate_unita, $nomi_unita, $nomi_ug_di_zona, $coordinate_distretto, $nomi_distretto, $from, $to);
        
        
        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item', 'zone_count','azioni_di_zona','nomi_di_zona', 'coordinate_unita', 'nomi_unita', 'nomi_ug_di_zona', 'coordinate_distretto', 'nomi_distretto'));
        }
      }


}
