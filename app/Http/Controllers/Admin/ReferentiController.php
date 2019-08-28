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
      $this->middleware('forbiddenIfRole:cacciatore')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:consultatore');
      // Invoke parent
      parent::__construct();
    }


    private function _validation(Request $request)
      {
         $request->validate([
          'nome' => 'required|max:200',
          'telefono' => ['required', new PhoneNumber]
        ]);
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
          $zona->referenti()->delete();
          }

        
        
        echo "ok";

      }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ref = Referente::with('zone')->get();

        return view('admin.referenti.index', compact('ref'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ref = new Referente;

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
      
      Referente::create($request->all());

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

      return view('admin.referenti.form', compact('ref'));

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

      $ref->fill($request->all())->save();

     
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
        //
    }
}
