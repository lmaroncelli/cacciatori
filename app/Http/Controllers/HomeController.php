<?php

namespace App\Http\Controllers;

use App\AzioneCaccia;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth; 


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    private function getAzioniMappa(&$azioni,&$coordinate_zona, &$item, &$zone_count, &$azioni_di_zona, &$nomi_di_zona, $from, $to)
      {
        // cerco tutte le azioni di questa data 
        $azioni = AzioneCaccia::with('squadra', 'distretto', 'unita','zona')
                  ->where('dalle','>=',$from)
                  ->where('alle','<=',$to)
                  ->get();

        // trovo tutte le zone facendo un loop su tutte le AZIONI
        $coordinate_zona = [];
        $zone_ids = [];
        
        foreach ($azioni as $azione) 
          {
          $zona_azione = $azione->zona;
          
          if(!in_array($zona_azione->id, $zone_ids))
            $zone_ids[] = $zona_azione->id;

          $poligono = $zona_azione->poligono;
          $coordinata_zona = $poligono->coordinate->pluck('long','lat');
          $coordinate_zona[$zona_azione->id] = $coordinata_zona;
          
          }

              
        $azioni_di_zona = []; 
        $nomi_di_zona = []; 
        
        // RAGGRUPPO le mie azioni in base alla zona
        foreach ($azioni as $azione) 
          {
          if (is_null($azione->note)) 
            {
            $azione->note = '';
            }
          $azione->dalle_alle = $azione->getDalleAlle();
          $azione->nomesquadra = $azione->squadra->nome;
          $azione->caposquadra = $azione->squadra->getNomeCapoSquadra();
          $azione->tel_capo = optional($azione->squadra->getCapoSquadra())->telefono;
          $azioni_di_zona[$azione->zona_id][] = $azione;
          $nomi_di_zona[$azione->zona_id] = $azione->zona->nome;
          }
        $azioni = $azioni->keyBy('id')->toArray();         

        // mi serve per centrare la mappa e zoommarla
        $item = Utility::fakeCenterCoords();

        $zone_count = count($zone_ids);
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




        $this->getAzioniMappa($azioni,$coordinate_zona, $item, $zone_count, $azioni_di_zona, $nomi_di_zona, $from, $to);

        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item', 'zone_count','azioni_di_zona','nomi_di_zona', 'to_show'));
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


        $this->getAzioniMappa($azioni,$coordinate_zona, $item, $zone_count, $azioni_di_zona, $nomi_di_zona, $from, $to);

        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item', 'zone_count','azioni_di_zona','nomi_di_zona'));
        }
      }


}
