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
        $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
        $this->middleware('forbiddenIfRole:consultatore');
      
      // Invoke parent
      parent::__construct();
      }

    private function _getZone($zone_associate = [])
      {
      $zone = Zona::getAll($sort_by = 'nome')->pluck('nome','id')->toArray();
      
      return $zone;
      }


    private function _saveZone(Request $request, $utg)
      {

      if($request->has('zone'))
        {
        $utg->zone()->sync($request->get('zone')); 
        }
      else 
        {
        $utg->zone()->detach();
        }
        
      }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $order_by='id';
        $order = 'asc';

        if ($request->filled('order_by'))
         {
           $order_by = $request->get('order_by');
           $order = $request->get('order');
         }

        $utg = UnitaGestione::getAll($order_by, $order);

        //dd($utg);

        return view('admin.utg.index', compact('utg', 'order_by', 'order'));
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

      if(!is_null($distretto))
        {
        $poligono_distretto = $distretto->poligono;
        $coordinate_distretto = $poligono_distretto->coordinate->pluck('long','lat');
        }
      else 
        {
        $poligono_distretto = null;
        $coordinate_distretto = null;
        }

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
