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

        foreach ($azioni as $azione) 
          {
          $poligono = $azione->zona->poligono;
          $coordinata_zona = $poligono->coordinate->pluck('long','lat');
          $coordinate_zona[$azione->id] = $coordinata_zona;
          }




        $azioni = $azioni->keyBy('id'); // dopo posso fare ->get('id') per trovare quella con id =  

        // mi serve per centrare la mappa e zoommarla
        $item = Utility::fakeCenterCoords();

        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item'));
    }
}
