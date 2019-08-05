<?php

namespace App\Http\Controllers\Admin;

use App\Cacciatore;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModificaCacciatoreRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CacciatoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $cacciatori = Cacciatore::all();

        return view('admin.cacciatori.index', compact('cacciatori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    $cacciatore = new Cacciatore;
    
    $squadre_associate = $cacciatore->squadre->pluck('id')->toArray();
    
    return view('admin.cacciatori.form', compact('cacciatore', 'squadre_associate'));
    
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
        
        $cacciatore = Cacciatore::with('utente')->find($id);
        
        $squadre_associate = $cacciatore->squadre->pluck('id')->toArray();
        
        return view('admin.cacciatori.form', compact('cacciatore', 'squadre_associate'));
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
