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
      ini_set('memory_limit', '256M');
       // Invoke parent
      parent::__construct();
    }
  
    private function getAzioniMappa(&$azioni,&$coordinate_zona, &$item, &$zone_count, &$azioni_di_zona, &$nomi_di_zona, &$coordinate_unita, &$nomi_unita, &$nomi_ug_di_zona, &$coordinate_distretto, &$nomi_distretto, $from, $to)
      {
        // cerco tutte le azioni di questa data 
        $azioni = AzioneCaccia::with('squadra', 'distretto','zone')
                  ->where('dalle','>=',$from)
                  ->where('alle','<=',$to)
                  ->get();

        // trovo tutte le zone facendo un loop su tutte le AZIONI
        $coordinate_zona = [];
        $zone_ids = [];

        $coordinate_unita = [];
        $nomi_unita = [];

        $coordinate_distretto = [];
        $nomi_distretto = [];
                    
        foreach ($azioni as $azione) 
          {
          $zone_azione = $azione->zone;

          foreach ($zone_azione as $zona_azione) 
            {
            if(!in_array($zona_azione->id, $zone_ids))
              $zone_ids[] = $zona_azione->id;
  
            $poligono = $zona_azione->poligono;
            $coordinata_zona = $poligono->coordinate->pluck('long','lat');
            $coordinate_zona[$zona_azione->id] = $coordinata_zona;
            
      

            // trovo tutte le UG della zona
            foreach ($zona_azione->unita as $unita) 
              {

              $poligono_unita =  $unita->poligono;
              $coordinata_unita = $poligono_unita->coordinate->pluck('long','lat');

              $coordinate_unita[$zona_azione->id][$unita->id] = $coordinata_unita;
              $nomi_unita[$zona_azione->id][$unita->id] = $unita->nome;
             
              $distretto = $unita->distretto;

              if(!is_null($distretto))
                {
                $poligono_distretto = $distretto->poligono;
                $coordinate_distretto[$unita->id] = $poligono_distretto->coordinate->pluck('long','lat');
                $nomi_distretto[$unita->id] = $distretto->nome;
                }
              
              }

            }
          }

              
        $azioni_di_zona = []; 
        $nomi_di_zona = []; 
        $nomi_ug_di_zona = [];
        
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
          
          $zone_azione = $azione->zone;

          foreach ($zone_azione as $zona_azione) 
            {
            $azioni_di_zona[$zona_azione->id][] = $azione;
            $nomi_di_zona[$zona_azione->id] = $zona_azione->nome;
            $u_nomi = [];
            if(isset($nomi_unita[$zona_azione->id]))
              {
              foreach ($nomi_unita[$zona_azione->id] as $u_id => $u_nome) 
                {
                $u_nomi[] = $u_nome;
                }
              }
            $nomi_ug_di_zona[$zona_azione->id] = implode(',',$u_nomi);
            }
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
        $coordinate_unita = null;
        $nomi_unita = null;
        $nomi_ug_di_zona = null;
        $coordinate_distretto = null; 
        $nomi_distretto = null;
        

        $this->getAzioniMappa($azioni,$coordinate_zona, $item, $zone_count, $azioni_di_zona, $nomi_di_zona, $coordinate_unita, $nomi_unita, $nomi_ug_di_zona, $coordinate_distretto, $nomi_distretto, $from, $to);
          
      
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


        $this->getAzioniMappa($azioni,$coordinate_zona, $item, $zone_count, $azioni_di_zona, $nomi_di_zona, $coordinate_unita, $nomi_unita, $nomi_ug_di_zona, $coordinate_distretto, $nomi_distretto, $from, $to);
        
        
        return view('admin.azioni.show_mappa_azioni', compact('azioni','coordinate_zona','item', 'zone_count','azioni_di_zona','nomi_di_zona', 'coordinate_unita', 'nomi_unita', 'nomi_ug_di_zona', 'coordinate_distretto', 'nomi_distretto'));
        }
      }


}
