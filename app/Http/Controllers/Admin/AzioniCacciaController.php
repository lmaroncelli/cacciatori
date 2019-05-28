<?php

namespace App\Http\Controllers\Admin;

use App\AzioneCaccia;
use App\Distretto;
use App\Http\Controllers\Controller;
use App\Squadra;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AzioniCacciaController extends Controller
{



    public function _saveAzione(&$azione, $request)
      {
        $dalle = $request->get('data'). ' ' . $request->get('dal');
        $alle = $request->get('data'). ' ' . $request->get('al');
        $azione->dalle = Utility::getCarbonDateTime($dalle);
        $azione->alle = Utility::getCarbonDateTime($alle);
        $azione->squadra_id = $request->get('squadra_id');
        $azione->distretto_id = $request->get('distretto_id');
        $azione->unita_gestione_id = $request->get('unita_gestione_id');
        $azione->zona_id = $request->get('zona_id');
        $azione->note = $request->get('note');
        $azione->user_id = Auth::id();

        $azione->save();
      }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $azioni = AzioneCaccia::all();
        return view('admin.azioni.index', compact('azioni'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    $azione = new AzioneCaccia;
    return view('admin.azioni.form', compact('azione'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $azione = new AzioneCaccia;
        $this->_saveAzione($azione, $request);
        
        return redirect()->route("azioni.index")->with('status', 'Azione di caccia creata correttamente!');
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
        //
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
        //
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
