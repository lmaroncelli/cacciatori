<?php

namespace App\Http\Controllers\Admin;

use App\Distretto;
use App\Http\Controllers\Controller;
use App\Squadra;
use App\UnitaGestione;
use Illuminate\Http\Request;

class SquadreController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $squadre = Squadra::all();

        return view('admin.squadre.index', compact('squadre'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $squadra = new Squadra;

        $utg = $squadra->distretto->unita->pluck('nome','id');
        
        $zone_associate = $squadra->zone->pluck('nome','id')->toArray();

        return view('admin.squadre.form', compact('squadra', 'utg', 'zone_associate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  
      $squadra = Squadra::create($request->all());
      
      if ($request->has('zona_id')) 
        {
        $squadra->zone()->sync($request->get('zona_id'));
        }

      return redirect()->route("squadre.index")->with('status', 'Squadra creata correttamente!');

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
      $squadra = Squadra::find($id);
      
      $utg = collect(); 
      $zone_associate = [];

      if(!is_null($squadra->distretto))
        {
        if(!is_null($squadra->distretto->unita))
          $utg = $squadra->distretto->unita->pluck('nome','id');
        }
      

      if(!is_null($squadra->zone))
        {
        $zone_associate = $squadra->zone->pluck('nome','id')->toArray();
        }
      
       return view('admin.squadre.form', compact('squadra', 'utg', 'zone_associate'));

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

        $squadra = Squadra::find($id);

        $squadra->fill($request->all())->save();

        if ($request->has('zona_id')) 
          {
          $squadra->zone()->sync($request->get('zona_id'));
          }

        return redirect()->route("squadre.index")->with('status', 'Squadra modificata correttamente!');
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



    public function getUnitaGestioneAjax(Request $request)
      {
      $distretto_id = $request->get('distretto_id');

      $distretto = Distretto::find($distretto_id); 

      $utg = $distretto->unita->pluck('nome','id')->toArray();

      return view('admin.squadre.inc_unita_select_cascade', compact('utg'));
      }


    public function getZonaAjax(Request $request)
      {
      $unita_gestione_id = $request->get('unita_gestione_id');

      $unita = UnitaGestione::find($unita_gestione_id); 

      $zone = $unita->zone->pluck('nome','id')->toArray();

      return view('admin.squadre.inc_zone_select_cascade', compact('zone'));
      }  
}
