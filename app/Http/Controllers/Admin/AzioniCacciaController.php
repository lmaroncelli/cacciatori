<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Zona;
use App\Squadra;
use App\Utility;
use App\Distretto;
use Carbon\Carbon;
use App\AzioneCaccia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Requests\AzioneCacciaRequest;

class AzioniCacciaController extends LoginController
{



    public function __construct()
      {
      $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:consultatore')->only(['create','edit','destroy']);
      
      // Invoke parent
      parent::__construct();
      }


    public function _saveAzione(&$azione, $request, $action = 'I')
      {
        $dalle = $request->get('data'). ' ' . $request->get('dal');
        $alle = $request->get('data'). ' ' . $request->get('al');
        $azione->dalle = Utility::getCarbonDateTime($dalle);
        $azione->alle = Utility::getCarbonDateTime($alle);
        $azione->squadra_id = $request->get('squadra_id');
        $azione->distretto_id = $request->get('distretto_id');
        $azione->note = $request->get('note');
        $azione->user_id = Auth::id();

        $azione->save();

        if($request->has('zone'))
          {
          $azione->zone()->sync($request->get('zone')); 
          $action == 'I' ? $msg_azione_tipo = "CREATA" : $msg_azione_tipo = "MODIFICATA";   
          
          Utility::gestisciComunicazioneReferentiAzione($azione, $msg_azione_tipo);
          }

      }

    

    public function reset(Request $request)
      {
      $request->session()->forget('datefilter');
      $request->session()->forget('squadra');
      $request->session()->forget('zona');
      $request->session()->forget('trashed');

      return redirect()->route("azioni.index")->with('status', 'Tutti i filtri sono stati rimossi!');
      } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

        //dd($request->all());

        $filtro_pdf = [];
        $filtro_pdf[] = "<b>Filtri applicati:</b>";

        $export_pdf = 0;
        
        if($request->has('pdf'))
          {
          $export_pdf = 1;
          }
        
        if(!$export_pdf)
          {
          // SE NON HO QUERY STRING 
          if ($request->fullUrl() == $request->url()) 
            {
            $pdf_export_url = $request->url() .'?pdf'; 
            } 
          else 
            {
            $pdf_export_url = $request->fullUrl() .'&pdf'; 
            }    
          }
        else
          {
          $pdf_export_url = $request->fullUrl();
          }



        // Filtri
        
        
        
        // ANCHE QUELLI CANCELLATI LOGICAMENTE
        if( !isset($request->trashed) && $request->session()->has('trashed'))
          $request->trashed = $request->session()->get('trashed');

        if (isset($request->trashed) && $request->trashed == 1) 
          {
          $request->session()->put('trashed', $request->trashed);
          $filtro_pdf[] =  "Anche Azioni cancellate logicamente";
          $trashed = 1;
          } 
        else 
          {
          $request->session()->forget('trashed');
          $trashed = 0;
          }
        



        // DAL AL 

        // se non ho la request leggo dalla sessione
        if( (!isset($request->datefilter) || $request->datefilter == '') && $request->session()->has('datefilter'))
          $request->datefilter = $request->session()->get('datefilter');


        if (isset($request->datefilter) && $request->datefilter != '') 
          {
            $request->session()->put('datefilter', $request->datefilter);
            list($dal, $al) = explode(' - ', $request->datefilter);
            $dal_c = Carbon::createFromFormat('d/m/Y H i', $dal.' 0 00');
            $al_c = Carbon::createFromFormat('d/m/Y H i', $al.' 23 59');
            $init_value = $request->datefilter;
            $filtro_pdf[] =  "Azioni eseguite dal " .$dal . " al " . $al;
          }
        else
          {
            $request->session()->forget('datefilter');
            $init_value = "";            
            $dal_c = null;
            $al_c = null;
          }

        
        // SQUADRA

        if( !isset($request->squadra) && $request->session()->has('squadra'))
          $request->squadra = $request->session()->get('squadra');

        if (isset($request->squadra) && $request->squadra != 0) 
          {
            $request->session()->put('squadra', $request->squadra);
            $squadra_selected = $request->squadra;

            $filtro_pdf[] =  "Azioni eseguite dalla squadra '" . Squadra::find($squadra_selected)->nome ."'";
          }
        else 
          {
          $request->session()->forget('squadra');
          $squadra_selected = 0;            
          }

        
        // ZONA

        if( !isset($request->zona) && $request->session()->has('zona'))
          $request->zona = $request->session()->get('zona');

        if (isset($request->zona) && $request->zona != 0) 
          {
            $request->session()->put('zona', $request->zona);
            $zona_selected = $request->zona;
            $filtro_pdf[] =  "Azioni eseguite nella zona '" . Zona::find($zona_selected)->nome . "'";
          }
        else 
          {
          $request->session()->forget('zona');
          $zona_selected = 0;            
          }


        //////////////////
        // ordinamento  //
        //////////////////
        $order_by='dalle';
        $order = 'desc';
        $ordering = 1;

        

        if ($request->filled('order_by'))
          {
          $order_by=$request->get('order_by');
          $order = $request->get('order');
          $ordering = 1;
          }
        
        
        

        if($zona_selected > 0)
          {
          $query = AzioneCaccia::with(['squadra','distretto'])
                  ->whereHas('zone', function($q) use ($zona_selected){
                    $q->where('tblZone.id',$zona_selected);
                  });
          }
          else 
          {
          $query = AzioneCaccia::with(['squadra','distretto']);       
          }
        
        
        switch ($order_by) {
          case 'squadra_nome':
            $query = AzioneCaccia::join('tblSquadre','tblSquadre.id','=','tblAzioniCaccia.squadra_id')->orderBy('tblSquadre.nome', $order);
            break;

          case 'distretto_nome':
            $query = AzioneCaccia::join('tblDistretti','tblDistretti.id','=','tblAzioniCaccia.distretto_id')->orderBy('tblDistretti.nome', $order);
            break;
          
          default:
            $query->orderBy($order_by, $order);
        }
        
        if(!is_null($dal_c) && !is_null($al_c))
          {
            $query->where('tblAzioniCaccia.dalle','>=',$dal_c);
            $query->where('tblAzioniCaccia.alle','<=',$al_c);
          }
        
        if($squadra_selected > 0)
          {
          $query->where('tblAzioniCaccia.squadra_id',$squadra_selected);
          }
        
        // il trashed viene gestito nella model AzioneCaccia
        // if($trashed) {
        //   $query->withTrashed();
        // }

        $azioni = $query->get();
        
        $columns = [
            'dalle' => 'Data',
            'squadra_nome' => 'Squadra',
            'distretto_nome' => 'Distretto',
            '' => 'Quadranti'
        ];

        if ($export_pdf) 
          {
          $filtro_pdf[] =  "<b>N° azioni " .$azioni->count()."</b>";

          $chunked_element = 3;

          $pdf = PDF::loadView('admin.azioni.pdf', compact( 'azioni', 'columns', 'chunked_element', 'filtro_pdf'));
              
          return $pdf->stream();
          } 
        else 
          {
          return view('admin.azioni.index', compact('pdf_export_url', 'azioni', 'columns', 'init_value','squadra_selected','zona_selected','trashed'));
          }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    $azione = new AzioneCaccia;
    return view('admin.azioni.form', compact('azione'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AzioneCacciaRequest $request)
    {

        //dd($request->all());
        $azione = new AzioneCaccia;
        $this->_saveAzione($azione, $request, $action = 'I');
        
        return redirect()->route("azioni.index")->with('status', 'Azione di caccia creata correttamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $coordinate_zona = [];
      $zone_ids = [];
      $nomi_zona = [];


      $coordinate_unita = [];
      $nomi_unita = [];

      $coordinate_distretto = [];
      $nomi_distretto = [];
      $azione = AzioneCaccia::with('squadra', 'distretto','zone')->find($id);
      
      $zone_azione = $azione->zone;
      
      foreach ($zone_azione as $zona_azione) 
        {
        
        if(!in_array($zona_azione->id, $zone_ids))
            $zone_ids[] = $zona_azione->id;

        $poligono = $zona_azione->poligono;
        $coordinata_zona = $poligono->coordinate->pluck('long','lat');
        $coordinate_zona[$zona_azione->id] = $coordinata_zona;
        $nomi_zona[$zona_azione->id] = $zona_azione->nome;
        
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
          
          } // end foreach unita
        } // end foreach zone
      
      $zone_count = count($zone_ids);

      // mi serve per centrare la mappa e zoommarla
      //$item = Utility::fakeCenterCoords();

      $item = $azione->zone()->first();

      return view('admin.azioni.show_mappa', compact('azione', 'nomi_zona', 'coordinate_zona', 'item', 'zone_count', 'coordinate_unita', 'nomi_unita', 'coordinate_distretto', 'nomi_distretto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $azione = AzioneCaccia::find($id);
        $zone_associate = $azione->zone->pluck('id')->toArray();
        $distretto_associato  = $azione->distretto;

        return view('admin.azioni.form', compact('azione','zone_associate','distretto_associato'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AzioneCacciaRequest $request, $id)
    {

      $azione = AzioneCaccia::find($id);
      
      $this->_saveAzione($azione, $request, $action = 'U');
        
      return redirect()->route("azioni.index")->with('status', 'Azione di caccia modificata correttamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      $azione = AzioneCaccia::find($id);
      
      if (Auth::check()) 
      {
      if(Auth::user()->hasRole('admin'))
        {
          Utility::gestisciComunicazioneReferentiAzione($azione, $action = "ELIMINATA");
        }
      }

      $azione->destroyMe();
      
      return redirect()->route("azioni.index")->with('status', 'Azione eliminata correttamente!');        
    }

}
