<?php

namespace App\Http\Controllers\Admin;

use App\AzioneCaccia;
use App\Http\Controllers\Controller;
use App\Squadra;
use Illuminate\Http\Request;

class AzioniCacciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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


    public function getDistrettoFromSquadraAjax(Request $request)
      {
      $squadra_id = $request->get('squadra_id');
      if(!is_null($squadra = Squadra::find($squadra_id)))
        {
        if(!is_null($distretto = $squadra->distretto()->first()))
          {
          return $squadra->distretto()->nome; 
          }
        else
          {
          return "La squadra non ha un distretto associato";
          }
        } 
      else
        {
        return "La squadra non esiste";
        }
      }
}
