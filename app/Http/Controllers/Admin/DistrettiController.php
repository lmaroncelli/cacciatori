<?php

namespace App\Http\Controllers\Admin;

use App\Distretto;
use App\Http\Controllers\Controller;
use App\Squadra;
use App\UnitaGestione;
use Illuminate\Http\Request;

class DistrettiController extends Controller
{


    public function _saveDistretto(&$distretto, $request)
      {

      if($distretto->squadre->count())
        {
        Squadra::where('distretto_id', $distretto->id)
        ->update(['distretto_id' => 0]);
        }

        $distretto->nome = $request->get('nome');
        $distretto->note = $request->get('note');

        if ($request->has('squadre')) 
          {
          foreach ($request->get('squadre') as $squadra_id) 
            {
            Squadra::where('id', $squadra_id)
                    ->update(['distretto_id' => $distretto->id]);
            } 
          }

        $distretto->save();
      }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distretti = Distretto::all();

        return view('admin.distretti.index', compact('distretti'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $distretto = new Distretto;

        $squadre_associate = $distretto->squadre->pluck('id')->toArray();
        return view('admin.distretti.form', compact('distretto','squadre_associate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
      {
      $distretto = new Distretto;
      $this->_saveDistretto($distretto, $request);

      return redirect()->route("distretti.index")->with('status', 'Distretto creato correttamente!');
      }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $distretto = Distretto::find($id);

        $poligono = $distretto->poligono;
        $coordinate = $poligono->coordinate->pluck('long','lat');


        // trovo tutte le zone facendo un loop su tutte el UTG
        $coordinate_zona = [];

        foreach ($distretto->unita as $utg) 
          {
          foreach ($utg->zone as $zona) 
            {
            $poligono = $zona->poligono;
          
            $coordinata_zona = $poligono->coordinate->pluck('long','lat');
            
            $coordinate_zona[] = $coordinata_zona;
            }
          }
        

         return view('admin.distretti.show_mappa', compact('distretto','poligono','coordinate','coordinate_zona'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $distretto = Distretto::find($id);

        $squadre = Squadra::orderBy('nome')->pluck('nome','id');

        $squadre_associate = $distretto->squadre->pluck('id')->toArray();

        $unita = $distretto->unita->pluck('nome')->toArray();

        return view('admin.distretti.form', compact('distretto','squadre','squadre_associate','unita'));
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
        $distretto = Distretto::find($id);
        $this->_saveDistretto($distretto, $request);

        return redirect()->route("distretti.index")->with('status', 'Distretto modificato correttamente!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $distretto = Distretto::find($id);
    $distretto->destroyMe();

    return redirect()->route("distretti.index")->with('status', 'Distretto eliminato correttamente!');
    }

}
