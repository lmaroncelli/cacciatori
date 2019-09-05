<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class UtentiController extends LoginController
{

    public function __construct()
      {
        $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
        $this->middleware('forbiddenIfRole:cacciatore');
        $this->middleware('forbiddenIfRole:consultatore');
      
      // Invoke parent
      parent::__construct();
      }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    $order_by='name';
    $order = 'asc';

    if ($request->filled('order_by'))
      {
        $order_by = $request->get('order_by');
        $order = $request->get('order');
      }

     $utenti = User::where('ruolo','!=','cacciatore')->orderBy($order_by, $order)->get();

    return view('admin.utenti.index', compact('utenti', 'order_by', 'order'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $utente = new User;
  
      return view('admin.utenti.form', compact('utente'));
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
      $utente = User::find($id);
      
      return view('admin.utenti.form', compact('utente'));
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
