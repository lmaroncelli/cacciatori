<?php

namespace App\Http\Controllers\Admin;

use App\Distretto;
use App\Http\Controllers\Controller;
use App\UnitaGestione;
use App\Utility;
use App\Zona;
use Illuminate\Http\Request;

class ZoneController extends LoginController
{

     public function __construct()
      {
      $this->middleware('forbiddenIfRole:cacciatore')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:consultatore');

      
      // Invoke parent
      parent::__construct();
      }


    /**
     * Se la zona ha già un UTG, allora le altre selezionabili devono essere solo quelle che hanno come padre lo stesso distretto
     * altrimenti sono tutte
     *
     * @return void
     */
    private function _getUtg($zona)
      {
      if($zona->unita()->count())
        {
          $unita = $zona->unita()->first();
          $distretto = $unita->distretto;
          $utg = $distretto->unita->pluck('nome','id')->toArray();
        }
      else 
        {
        $utg = UnitaGestione::getAll($sort_by = 'nome')->pluck('nome','id')->toArray();
        }
      
        return $utg;
      }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $order_by='nome';
        $order = 'asc';

        if ($request->filled('order_by'))
         {
           $order_by = $request->get('order_by');
           $order = $request->get('order');
         }

        $zone = Zona::getAll($order_by, $order);
        return view('admin.zone.index', compact('zone','order_by', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zona = new Zona;
        $utg = $this->_getUtg($zona);

        $squadre_associate = $zona->squadre->pluck('nome','id')->toArray();

        return view('admin.zone.form', compact('zona','squadre_associate', 'utg'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zona = Zona::create($request->all());

        if ($request->has('squadre')) 
          {
          $zona->squadre()->sync($request->get('squadre'));
          }

        $request->get('tipo') == 'zona' ? $status = 'Zona di braccata creata correttamente!' : $status = 'Particella di girata creata correttamente!';

        //////////////////////////////////////////////////////
        // creo un poligono di 4 punti associato di default //
        //////////////////////////////////////////////////////
        $poligono = $zona->poligono()->create(['name' => 'Poligono zona '.$zona->nome]);
        $poligono->coordinate()->createMany(Utility::fakeCoords());


        return redirect()->route("zone.index")->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zona = Zona::with(['unita', 'referenti'])->find($id);

        $poligono = $zona->poligono;

        $coordinate = optional($poligono)->coordinate->pluck('long','lat');

        // se per qualche motivo il poligono ha perso le coordinate le creo al volo
        if(!$coordinate->count())
          {
           $poligono->coordinate()->createMany(Utility::fakeCoords()); 
          }

        // trovo tutte le UTG
        $coordinate_unita = [];
        $nomi_unita = [];
      
        $distretto = null;
        $poligono_distretto = null;
        $coordinate_distretto = null;
        
        $trovato_distretto = false;
        foreach ($zona->unita as $unita) 
          {
          
          $poligono_unita =  $unita->poligono;
          $coordinata_unita = $poligono_unita->coordinate->pluck('long','lat');
          $coordinate_unita[$unita->id] = $coordinata_unita;
          $nomi_unita[$unita->id] = $unita->nome;

          // trvo un solo distretto perché tutte le unità hanno come padre 1! distretto
          if(!$trovato_distretto)
            {
              
            $distretto = $unita->distretto;
            if(!is_null($distretto))
              {
              $poligono_distretto = $distretto->poligono;
              $coordinate_distretto = $poligono_distretto->coordinate->pluck('long','lat');
              }
            else 
              {
              $distretto = null;
              $poligono_distretto = null;
              $coordinate_distretto = null;
              }
              
              $trovato_distretto = true;
            }

          }
        
        return view('admin.zone.show_mappa', compact('zona','coordinate', 'nomi_unita', 'coordinate_unita', 'distretto', 'coordinate_distretto'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $zona = Zona::find($id);

      $squadre_associate = $zona->squadre->pluck('nome','id')->toArray();
      $unita_associate = $zona->unita->pluck('nome','id')->toArray();

      $utg = $this->_getUtg($zona);

      return view('admin.zone.form', compact('zona','squadre_associate','unita_associate','utg'));
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

        $zona = Zona::find($id);

        $zona->fill($request->all())->save();

        if ($request->has('unita_gestione_id')) 
          {
          $zona->unita()->sync($request->get('unita_gestione_id'));
          }

        if ($request->has('squadre')) 
          {
          $zona->squadre()->sync($request->get('squadre'));
          }

        $request->get('tipo') == 'zona' ? $status = 'Zona di braccata aggiornata correttamente!' : $status = 'Particella di girata aggiornata correttamente!';

        return redirect()->route("zone.index")->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $zona = Zona::find($id);
      $zona->destroyMe();

      return redirect()->route("zone.index")->with('status', "Zona eliminata correttamente");
    }

    
}
