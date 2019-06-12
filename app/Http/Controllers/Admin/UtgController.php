<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UnitaGestione;
use Illuminate\Http\Request;

class UtgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utg = UnitaGestione::all();

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

        return view('admin.utg.form', compact('utg'));
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

        return view('admin.utg.form', compact('utg'));

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
        $utg = UnitaGestione::find($id)->fill($request->all())->save();

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
