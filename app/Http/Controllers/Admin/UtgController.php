<?php

namespace App\Http\Controllers\Admin;

use App\Zona;
use App\Utility;
use App\UnitaGestione;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class UtgController extends LoginController
{


    public function __construct()
      {
      $this->middleware('forbiddenIfRole:cacciatore')->only(['create','destroy']);
      
      // Invoke parent
      parent::__construct();
      }

    private function _getZone($zone_associate = [])
      {
      $zone = Zona::getAll($sort_by = 'nome');

      // keeping only those items that pass a given truth test:
      $zone_filtered =  $zone->filter(function ($z) use ($zone_associate) {
                          return ( !$z->unita_gestione_id || in_array($z->id, $zone_associate) );
                    });

      return $zone_filtered->pluck('nome','id');
      }


    private function _saveZone(Request $request, $utg)
      {

        Zona::where('unita_gestione_id', $utg->id)
              ->update(['unita_gestione_id' => 0]);

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

        $zone = $this->_getZone();

        return view('admin.utg.form', compact('utg', 'zone'));
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

         //////////////////////////////////////////////////////
        // creo un poligono di 4 punti associato di default //
        //////////////////////////////////////////////////////
        $poligono = $utg->poligono()->create(['name' => 'Poligono unità gestione '.$utg->nome]);
        $poligono->coordinate()->createMany(Utility::fakeCoords());

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
      $utg = UnitaGestione::find($id);

      $poligono_utg =  $utg->poligono;
      $coordinate_utg = $poligono_utg->coordinate->pluck('long','lat');

      $distretto = $utg->distretto;
      $poligono_distretto = $distretto->poligono;
      $coordinate_distretto = $poligono_distretto->coordinate->pluck('long','lat');

      // trovo tutte le zone facendo un loop su tutte le UTG
      $coordinate_zona = [];
      $nomi_zona = [];

      foreach ($utg->zone as $zona) 
        {
        $poligono = $zona->poligono;
      
        $coordinata_zona = $poligono->coordinate->pluck('long','lat');
        
        $coordinate_zona[$zona->id] = $coordinata_zona;

        $nomi_zona[$zona->id] = $zona->nome;
        }

       return view('admin.utg.show_mappa', compact('distretto', 'utg','coordinate_utg', 'coordinate_distretto', 'coordinate_zona', 'nomi_zona'));


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

        $zone = $this->_getZone($zone_associate);

        return view('admin.utg.form', compact('utg','zone_associate', 'zone'));

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

        $utg->save();

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
       
        $utg = UnitaGestione::find($id);
        $utg->destroyMe();
        
        return redirect()->route("utg.index")->with('status', 'Unità di gestione eliminata correttamente!');

    }
}
