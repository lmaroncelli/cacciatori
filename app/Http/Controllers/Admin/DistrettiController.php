<?php

namespace App\Http\Controllers\Admin;

use App\Squadra;
use App\Utility;
use App\Distretto;
use App\UnitaGestione;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class DistrettiController extends LoginController
{


   public function __construct()
    {
      $this->middleware('notRole:cacciatore')->only(['create']);
      
      // Invoke parent
      parent::__construct();
    }


  /** 
   * Siccome una UG può avere solo un distretto, faccio selezionare solo le UG che NON HANNO GIA' UN DISTRETTO
   */
    private function _getUnita($unita_associate = [])
      {
      $utg = UnitaGestione::getAll($sort_by = 'nome');
      
      // keeping only those items that pass a given truth test:
      $utg_filtered =  $utg->filter(function ($u) use ($unita_associate) {
                          return ( !$u->distretto_id || in_array($u->id, $unita_associate) );
                    });

      return $utg_filtered->pluck('nome','id');
      }




    private function _saveUtg($request, $distretto)
      {

        UnitaGestione::where('distretto_id', $distretto->id)
              ->update(['distretto_id' => 0]);
        
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
        //$distretti = Distretto::all();
        $distretti = Distretto::getAll();

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

        $utg = $this->_getUnita();

        return view('admin.distretti.form', compact('distretto','unita_associate', 'utg'));
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

      //////////////////////////////////////////////////////
      // creo un poligono di 4 punti associato di default //
      //////////////////////////////////////////////////////
      $poligono = $distretto->poligono()->create(['name' => 'Poligono distretto '.$distretto->nome]);
      $poligono->coordinate()->createMany(Utility::fakeCoords());

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


        // trovo tutte le zone facendo un loop su tutte le UTG
        $coordinate_utg = [];
        $nomi_utg = [];
        $coordinate_zona = [];
        $nomi_zona = [];

        foreach ($distretto->unita as $utg) 
          {
          
          $poligono_utg =  $utg->poligono;
          $coordinata_utg = $poligono_utg->coordinate->pluck('long','lat');
          $coordinate_utg[$utg->id] = $coordinata_utg;
          $nomi_utg[$utg->id] = $utg->nome;

          foreach ($utg->zone as $zona) 
            {
            $poligono = $zona->poligono;
          
            $coordinata_zona = $poligono->coordinate->pluck('long','lat');
            
            $coordinate_zona[$zona->id] = $coordinata_zona;

            $nomi_zona[$zona->id] = $zona->nome;
            }
          }
      
         return view('admin.distretti.show_mappa', compact('distretto','coordinate', 'coordinate_utg', 'nomi_utg', 'coordinate_zona', 'nomi_zona'));
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

         $utg = $this->_getUnita($unita_associate);

        return view('admin.distretti.form', compact('distretto','unita_associate', 'utg'));
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

        $distretto->save();
        
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
