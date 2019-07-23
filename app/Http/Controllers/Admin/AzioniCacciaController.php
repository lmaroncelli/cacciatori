<?php

namespace App\Http\Controllers\Admin;

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



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 

        //dd($request->all());


        // Filtri

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
          }
        else
          {
            $request->session()->forget('datefilter');
            $init_value = "";            
            $dal_c = null;
            $al_c = null;
          }

        
        if( !isset($request->squadra) && $request->session()->has('squadra'))
          $request->squadra = $request->session()->get('squadra');

        if (isset($request->squadra) && $request->squadra != 0) 
          {
            $request->session()->put('squadra', $request->squadra);
            $squadra_selected = $request->squadra;
          }
        else 
          {
          $request->session()->forget('squadra');
          $squadra_selected = 0;            
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
        

        $azioni = $query->get();
        
        $columns = [
            'dalle' => 'Data',
            'squadra_nome' => 'Squadra',
            'distretto_nome' => 'Distretto',
            'unita_nome' => 'UTG',
            'zona_nome' => 'Zona'
        ];


        return view('admin.azioni.index', compact('azioni', 'columns', 'init_value','squadra_selected'));
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
