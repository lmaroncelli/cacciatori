<?php

namespace App\Http\Controllers\Admin;

use App\Squadra;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\LoginController;

class SquadreController extends LoginController
{

     public function __construct()
      {
      $this->middleware('forbiddenIfRole:cacciatore')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
      $this->middleware('forbiddenIfRole:consultatore');
      
      // Invoke parent
      parent::__construct();
      }


    private function _aggiorna_squadra(Request $request, &$squadra)
      {
      
      if ($request->has('zone')) 
        {
        $squadra->zone()->sync($request->get('zone'));
        }

      if ($request->has('cacciatori')) 
        {
        $squadra->cacciatori()->sync($request->get('cacciatori'));
        }

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

        $squadre = Squadra::getAll($order_by, $order);

        return view('admin.squadre.index', compact('squadre', 'order_by', 'order'));
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
  
      $squadra = Squadra::create($request->except(['zone','cacciatori']));
      

      $this->_aggiorna_squadra($request, $squadra);
    
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
      
      // zone dal composer
      // le zone che posso selezionare NON DIPENDONO PIU' da DISTRETTO e UG


      $zone_associate = [];

      if(!is_null($squadra->zone))
        {
        $zone_associate = $squadra->zone->pluck('nome','id')->toArray();
        }
      

      if(!is_null($squadra->cacciatori))
        {
        $cacciatori_squadra = $squadra->cacciatori->pluck('nome','id')->toArray();
        }



       return view('admin.squadre.form', compact('squadra', 'zone_associate','cacciatori_squadra'));

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

        $squadra->fill($request->except(['zone','cacciatori']))->save();

      
        $this->_aggiorna_squadra($request, $squadra);


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
      $squadra = Squadra::find($id);
      $squadra->destroyMe();

      return redirect()->route("squadre.index")->with('status', 'Squadra eliminata correttamente!');
    }

}
