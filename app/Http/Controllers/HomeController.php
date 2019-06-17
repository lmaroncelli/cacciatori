<?php

namespace App\Http\Controllers;

use App\AzioneCaccia;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showMappaAttivita(Request $request)
    {
        $now = Carbon::now('Europe/Rome');
        $oggi = $now->format('d-m-Y');

        if (!$request->has('from') && !$request->has('to')) 
          {
          $from = $now->toDateString().' 00:00:00';
          $to = $now->toDateString().' 23:59:59';
          }

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
        // RAGGRUPPO le mie azioni in base alla zona
        foreach ($azioni as $azione) 
          {
          $azioni_di_zona[$azione->zona_id][] = $azione;
          }
        
        $azioni = $azioni->keyBy('id')->toArray();         

        // mi serve per centrare la mappa e zoommarla
        $item = Utility::fakeCenterCoords();

        $zone_count = count($zone_ids);

        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item', 'zone_count','azioni_di_zona'));
    }
}
