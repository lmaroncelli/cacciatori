<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Cacciatore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\LoginController;

class CacciatoriController extends LoginController
{

    public function __construct()
      {
        $this->middleware('forbiddenIfRole:admin_ro')->only(['create','destroy']);
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
        $order_by='cognome';
        $order = 'asc';

        if ($request->filled('order_by'))
          {
            $order_by = $request->get('order_by');
            $order = $request->get('order');
          }
        
        $cacciatori = Cacciatore::getAll($order_by, $order);

        return view('admin.cacciatori.index', compact('cacciatori', 'order_by', 'order'));
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


    public function assegnaCapoSquadraAjax(Request $request)
      {
        $cacciatore_id = $request->cacciatore_id;
        $squadra_id = $request->squadra_id;


        DB::table('tblCacciatoriSquadre')
        ->where('squadra_id', $squadra_id)
        ->update(['capo_squadra' => 0]);

        DB::table('tblCacciatoriSquadre')
        ->where('cacciatore_id', $cacciatore_id)
        ->where('squadra_id', $squadra_id)
        ->update(['capo_squadra' => 1]);

        echo "Caposquadra assegnato correttamente";

      }
}
