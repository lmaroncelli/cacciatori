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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AzioniCacciaController extends Controller
{



    public function _saveAzione(&$azione, $request)
      {
        $dalle = $request->get('data'). ' ' . $request->get('dal');
        $alle = $request->get('data'). ' ' . $request->get('al');
        $azione->dalle = Utility::getCarbonDateTime($dalle);
        $azione->alle = Utility::getCarbonDateTime($alle);
        $azione->squadra_id = $request->get('squadra_id');
        $azione->distretto_id = $request->get('distretto_id');
        $azione->unita_gestione_id = $request->get('unita_gestione_id');
        $azione->zona_id = $request->get('zona_id');
        $azione->note = $request->get('note');
        $azione->user_id = Auth::id();

        $azione->save();
      }

    

    public function reset(Request $request)
      {
      $request->session()->forget('datefilter');
      $request->session()->forget('squadra');
      $request->session()->forget('zona');

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
        $ordering = 0;

        

        if ($request->filled('order_by'))
          {
          $order_by=$request->get('order_by');
          $order = $request->get('order');
          $ordering = 1;
          }
        
        $query = AzioneCaccia::with(['squadra','distretto','unita','zona']);

        switch ($order_by) {
          case 'squadra_nome':
            $query = AzioneCaccia::join('tblSquadre','tblSquadre.id','=','tblAzioniCaccia.squadra_id')->orderBy('tblSquadre.nome', $order);
            break;

          case 'distretto_nome':
            $query = AzioneCaccia::join('tblDistretti','tblDistretti.id','=','tblAzioniCaccia.distretto_id')->orderBy('tblDistretti.nome', $order);
            break;

          case 'unita_nome':
            $query = AzioneCaccia::join('tblUnitaGestione','tblUnitaGestione.id','=','tblAzioniCaccia.unita_gestione_id')->orderBy('tblUnitaGestione.nome', $order);
            break;

          case 'zona_nome':
            $query = AzioneCaccia::join('tblZone','tblZone.id','=','tblAzioniCaccia.zona_id')->orderBy('tblZone.nome', $order);
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
        
        if($zona_selected > 0)
          {
          $query->where('tblAzioniCaccia.zona_id',$zona_selected);
          }

        $azioni = $query->get();
        
        $columns = [
            'dalle' => 'Data',
            'squadra_nome' => 'Squadra',
            'distretto_nome' => 'Distretto',
            'unita_nome' => 'UTG',
            'zona_nome' => 'Zona'
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
          return view('admin.azioni.index', compact('pdf_export_url', 'azioni', 'columns', 'init_value','squadra_selected','zona_selected'));
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
    public function store(Request $request)
    {
        //dd($request->all());
        $azione = new AzioneCaccia;
        $this->_saveAzione($azione, $request);
        
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
        //
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
        return view('admin.azioni.form', compact('azione'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $azione = AzioneCaccia::find($id);
      
      $this->_saveAzione($azione, $request);
        
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
        //
    }

}
