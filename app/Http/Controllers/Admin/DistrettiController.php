<?php

namespace App\Http\Controllers\Admin;

use App\Distretto;
use App\Http\Controllers\Controller;
use App\Squadra;
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
        $distretto = Distretto::find($id);

        $squadre = Squadra::orderBy('nome')->pluck('nome','id');

        $squadre_associate = $distretto->squadre->pluck('id')->toArray();

        return view('admin.distretti.form', compact('distretto','squadre','squadre_associate'));
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
      Distretto::destroy($id);

      return redirect()->route("distretti.index")->with('status', 'Distretto eliminato correttamente!');
    }
}
