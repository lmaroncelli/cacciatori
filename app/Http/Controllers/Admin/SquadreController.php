<?php

namespace App\Http\Controllers\Admin;

use App\Squadra;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class SquadreController extends LoginController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$squadre = Squadra::all();
        $squadre = Squadra::getAll();

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
        
        $zone = [];
        $zone_associate = [];
        
        return view('admin.squadre.form', compact('squadra', 'zone', 'zone_associate'));
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
      
      if ($request->has('zone')) 
        {
        $squadra->zone()->sync($request->get('zone'));
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
      
      $zone = [];
      $zone_associate = [];


      // la squadra sta in questo distretto
      if(!is_null($squadra->distretto))
        {
        // questo distrtto ha tantio UTG e le zone associabili alla squadra sono quelle di TUTTI gli UTG
        if(!is_null($utg = $squadra->distretto->unita))
          {
          $zone = [];
          foreach ($utg as $unita) 
            {
            $zone += $unita->zone()->pluck('nome','id')->toArray();
            }
          if(!empty($zone))
            asort($zone);
          }
        }
      

      if(!is_null($squadra->zone))
        {
        $zone_associate = $squadra->zone->pluck('nome','id')->toArray();
        }
      
       return view('admin.squadre.form', compact('squadra', 'zone', 'zone_associate'));

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

        if ($request->has('zone')) 
          {
          $squadra->zone()->sync($request->get('zone'));
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

}
