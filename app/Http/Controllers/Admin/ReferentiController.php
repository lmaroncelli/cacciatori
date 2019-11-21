<?php

namespace App\Http\Controllers\Admin;

use App\Zona;
use App\Referente;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class ReferentiController extends LoginController
{



   public function __construct()
    {
      $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:cacciatore')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:consultatore');
      // Invoke parent
      parent::__construct();
    }


    private function _validation(Request $request)
      {
         $request->validate([
          'nome' => 'required|max:200',
          'telefono' => ['required', new PhoneNumber],
          'email' => 'required|string|email|max:255'
        ]);
      }

    

    public function aggiornaReferentiZonaAjax(Request $request)
      {
      $zona_id = $request->get('zona_id');
      $zona = Zona::find($zona_id);

      return $zona->getReferenti();
      }

    public function assegnaReferentiZonaAjax(Request $request)
      {
      
        $params_arr = explode('&', $request->get('data'));
        
        foreach ($params_arr as $value) 
          {
          list($k, $v) = explode('=',  $value);
          if ($k == 'zona_id') 
            {
            $zona_id = $v;
            }  
          else 
            {
            $referente[] = $v;
            }
          }
        
        $zona = Zona::find($zona_id);
        
        try 
          {
          $zona->referenti()->sync($referente);
          } 
        catch (\Exception $e) 
          {
          $zona->referenti()->sync([]);
          }

        
        
        echo "ok";

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
      
      $ref = Referente::with('zone')->orderBy($order_by, $order)->get();

      return view('admin.referenti.index', compact('ref', 'order_by', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ref = new Referente;

        $zone_associate = [];

        return view('admin.referenti.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

      $this->_validation($request);
      
      $ref = Referente::create($request->except('zone'));

      if ($request->has('zone')) 
        {
        $ref->zone()->sync($request->get('zone'));
        }

      return redirect()->route('referenti.index')->with('status','Referente creato correttamente!');
        
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
      $ref = Referente::find($id);

      $zone_associate = [];

      if(!is_null($ref->zone))
        {
        $zone_associate = $ref->zone->pluck('nome','id')->toArray();
        }

      return view('admin.referenti.form', compact('ref','zone_associate'));

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
      $this->_validation($request);
      
      $ref = Referente::find($id);

      $ref->fill($request->except('zone'))->save();

      if ($request->has('zone')) 
        {
        $ref->zone()->sync($request->get('zone'));
        }

      return redirect()->route('referenti.index')->with('status','Referente modificato correttamente!');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $referente = Referente::find($id);
      $referente->zone()->sync([]);
      $referente->delete();

      return redirect()->route("referenti.index")->with('status', "Referente eliminato correttamente");
    }
}
