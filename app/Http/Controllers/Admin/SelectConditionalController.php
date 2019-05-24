<?php

namespace App\Http\Controllers\Admin;

use App\Distretto;
use App\Http\Controllers\Controller;
use App\Squadra;
use App\UnitaGestione;
use App\Zona;
use Illuminate\Http\Request;

class SelectConditionalController extends Controller
{
   
  /**
   * [getUnitaGestioneAjax description]
   * @param  Request $request [distretto_id]
   * @return [type]           [select da cui selezionare UTG]
   */
	public function getUnitaGestioneAjax(Request $request)
	  {
	  $distretto_id = $request->get('distretto_id');

	  $distretto = Distretto::find($distretto_id); 

	  $utg = $distretto->unita->pluck('nome','id')->toArray();

	  return view('admin.squadre.inc_unita_select_cascade', compact('utg'));
	  }


	 /**
	  * [getZonaAjax description]
	  * @param  Request $request [unita_gestione_id]
	  * @return [type]           [select da cui selezionare le ZONE]
	  */
	 public function getZonaAjax(Request $request)
      {
      $unita_gestione_id = $request->get('unita_gestione_id');

      $unita = UnitaGestione::find($unita_gestione_id); 

      $zone = $unita->zone->pluck('nome','id')->toArray();

      return view('admin.squadre.inc_zone_select_cascade', compact('zone'));
      }  


   /**
    * [getUtgFromDistrettoAjax description]
    * @param  Request $request [distretto_id]
    * @return [type]           [select da cui selezionare UTG]
    */
   public function getUtgFromDistrettoAjax(Request $request)
     {
       if(!is_null($distretto_id = $request->get('distretto_id')))
         {
         if(!is_null($distretto = Distretto::find($distretto_id)))
           {

           $utg = $distretto->unita->pluck('nome','id')->toArray();

           return view('admin.squadre.inc_unita_select_cascade', compact('utg'));

           }
         }
       
     }


   /**
    * [getDistrettoFromSquadraAjax description]
    * @param  Request $request [squadra_id]
    * @return [type]           [JSON nome;id DISTRETTO]
    */
   public function getDistrettoFromSquadraAjax(Request $request)
     {
     $squadra_id = $request->get('squadra_id');
     if(!is_null($squadra = Squadra::find($squadra_id)))
       {
       if(!is_null($distretto = $squadra->distretto()->first()))
         {
           $res['nome'] = $distretto->nome;
           $res['id'] = $distretto->id;
           return  json_encode($res);
         }
       else
         {
           $res['nome'] = "La squadra non ha un distretto associato";
           $res['id'] = 0;
           return  json_encode($res);
         }
       } 
     else
       {
           $res['nome'] = "La squadra non esiste";
           $res['id'] = 0;
           return  json_encode($res);
       }
     }



   /**
    * [showDistrettoZonaAjax description]
    * @param  Request $request [unita_gestione_id]
    * @return [type]           [JSON nome;id DISTRETTO]
    */
   public function showDistrettoZonaAjax(Request $request)
     {
     $unita_gestione_id = $request->get('unita_gestione_id');
     if(!is_null($unita = UnitaGestione::find($unita_gestione_id)))
       {
       if(!is_null($distretto = $unita->distretto()->first()))
         {
         $res['nome'] = $distretto->nome;
         $res['id'] = $distretto->id;
         return  json_encode($res);
         }
       else
         {
         $res['nome'] = "L'unità non ha un distretto associato";
         $res['id'] = 0;
         return  json_encode($res);
         }
       } 
     else
       {
       $res['nome'] = "L'unità non esiste";
       $res['id'] = 0;
       return  json_encode($res);
       }
     }


   /**
    * [getSquadreFromDistrettoAjax distretto_id, zona_id]
    * @param  Request $request [description]
    * @return [type]           [select da cui selezionare SQUADRE]
    */
   public function getSquadreFromDistrettoAjax(Request $request)
     {
     $distretto_id = $request->get('distretto_id');
     $zona_id = $request->get('zona_id');
     $distretto = Distretto::find($distretto_id); 
     $zona = Zona::find($zona_id); 

     $squadre_associate = $zona->squadre->pluck('id')->toArray();

     $squadre = $distretto->squadre->pluck('nome','id')->toArray();

     return view('admin.inc_squadre_select', compact('squadre','squadre_associate'));


     }

}
