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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$zone = Zona::all();
        $zone = Zona::getAll();

        return view('admin.zone.index', compact('zone'));

        return redirect()->route("zone")->with('status', 'Zona creata correttamente!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zona = new Zona;

        $squadre_associate = $zona->squadre->pluck('nome','id')->toArray();

        return view('admin.zone.form', compact('zona','squadre_associate'));
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
        $zona = Zona::with('unita.distretto')->find($id);

        $poligono = $zona->poligono;

        $coordinate = optional($poligono)->coordinate->pluck('long','lat');

        // se per qualche motivo il poligono ha perso le coordinate le creo al volo
        if(!$coordinate->count())
          {
           $poligono->coordinate()->createMany(Utility::fakeCoords()); 
          }
        
        $poligono_distretto = $zona->unita->distretto->poligono;
        
        $coordinate_distretto = $poligono_distretto->coordinate->pluck('long','lat');

        
        return view('admin.zone.show_mappa', compact('zona','coordinate', 'coordinate_distretto'));

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

      return view('admin.zone.form', compact('zona','squadre_associate'));
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
