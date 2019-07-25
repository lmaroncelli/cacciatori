<?php

namespace App\Http\Controllers\Admin;

use App\Zona;
use App\UnitaGestione;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class UtgController extends LoginController
{


    private function _saveZone(Request $request, $utg)
      {
        if($request->has('zone'))
        {
        foreach ($request->get('zone') as $zone_id) 
            {
            Zona::where('id', $zone_id)
                    ->update(['unita_gestione_id' => $utg->id]);
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
        //$utg = UnitaGestione::all();
        $utg = UnitaGestione::getAll();

        return view('admin.utg.index', compact('utg'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $utg = new UnitaGestione;

        $zone_associate = $utg->zone->pluck('id')->toArray();

        return view('admin.utg.form', compact('utg','zone_associate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $utg = UnitaGestione::create($request->all());

        $this->_saveZone($request, $utg);

        return redirect()->route("utg.index")->with('status', 'Unità di gestione creata correttamente!');
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
        $utg = UnitaGestione::find($id);

        $zone_associate = $utg->zone->pluck('id')->toArray();

        return view('admin.utg.form', compact('utg','zone_associate'));

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
        $utg = UnitaGestione::find($id)->fill($request->all());

        $this->_saveZone($request, $utg);

        return redirect()->route("utg.index")->with('status', 'Unità di gestione modificata correttamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UnitaGestione::destroy($id);
        
        return redirect()->route("utg.index")->with('status', 'Unità di gestione eliminata correttamente!');

    }
}
