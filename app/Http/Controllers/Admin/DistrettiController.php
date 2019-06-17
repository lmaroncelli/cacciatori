<?php

namespace App\Http\Controllers\Admin;

use App\Distretto;
use App\Http\Controllers\Controller;
use App\Squadra;
use App\UnitaGestione;
use Illuminate\Http\Request;

class DistrettiController extends Controller
{


    private function _saveUtg($request, $distretto)
      {

      if($request->has('utg'))
        {
        foreach ($request->get('utg') as $utg_id) 
            {
            UnitaGestione::where('id', $utg_id)
                    ->update(['distretto_id' => $distretto->id]);
            } 
        }
    
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

        $unita_associate = $distretto->unita->pluck('id')->toArray();

        return view('admin.distretti.form', compact('distretto','unita_associate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
      {
      $distretto = Distretto::create($request->all());
      $this->_saveUtg($request, $distretto);

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
        

         return view('admin.distretti.show_mappa', compact('distretto','coordinate','coordinate_zona'));
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

        $unita_associate = $distretto->unita->pluck('id')->toArray();

        return view('admin.distretti.form', compact('distretto','unita_associate'));
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
       
        $distretto = Distretto::find($id)->fill($request->all());

        $this->_saveUtg($request, $distretto);

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
