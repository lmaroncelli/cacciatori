<?php

namespace App\Http\Controllers\Admin;

use App\Zona;
use App\Squadra;
use App\Distretto;
use App\UnitaGestione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
	  * @param  Request $request [distretto_id]
	  * @return [type]           [select da cui selezionare le ZONE]
	  */
	 public function getZonaAjax(Request $request)
      {
      $distretto_id = $request->get('distretto_id');

      $distretto = Distretto::find($distretto_id); 

      $zone = [];
      
      foreach ($distretto->unita as $unita) 
        {
        $zone += $unita->zone()->pluck('tblZone.nome','tblZone.id')->toArray();
        }

      
      // ATTENZIONE SE SONO UN CACCIATORE DEVO ACCEDERE SOLO ALLE ZONE ASSOCIATE ALLE MIE SQUADRE
      
      if(Auth::user()->hasRole('cacciatore'))
        {
        $squadre_cacciatore = Auth::user()->cacciatore->squadre;
        $zone_ids = [];
        foreach ($squadre_cacciatore as $squadra) 
          {
          $squadra_zone_ids = $squadra->zone()->pluck('tblZone.id')->toArray();
          $zone_ids = array_merge($zone_ids,$squadra_zone_ids);
          }
          $zone_ids = array_unique($zone_ids);

        if(count($zone_ids))
          {
          foreach ($zone as $id => $nome) 
            {
            if(!in_array($id, $zone_ids))
              {
              unset($zone[$id]);
              }
            }
          }
        }
        


      if(!empty($zone))
        asort($zone);

      return view('admin.squadre.inc_zone_select_cascade', compact('zone'));
      }  


   /**
    * [getUtgFromDistrettoAjax description]
    * @param  Request $request [distretto_id , unita_gestione_id (eventuale valore SELECTED) ]
    * @return [type]           [select da cui selezionare UTG]
    */
   public function getUtgFromDistrettoAjax(Request $request)
     {
       if(!is_null($distretto_id = $request->get('distretto_id')))
         {
         if(!is_null($distretto = Distretto::find($distretto_id)))
           {

           $selected_id = $request->get('unita_gestione_id');

           $utg = $distretto->unita->pluck('nome','id')->toArray();

           return view('admin.azioni.inc_unita_select_cascade', compact('utg','selected_id'));

           }
         }
       
     }


     /**
      * [getZoneFromUtgAjax description]
      * @param  Request $request [unita_gestione_id (eventuale valore SELECTED) ]
      * @return [type]           [select da cui selezionare ZONE]
      */
     public function getZoneFromUtgAjax(Request $request)
       {

        /**
         * @Gupi 06/11/19 - i quadranti sono svincolati da tutto perché potrei dover inserire azioni per quadranti che sono anche in distretti differenti
         * 
         */
        
        // Prendo tutte lezone della squadra, MA LA SQUADRA FA PARTE DI 1! distretto quindi non posso avere zone su 2 distretti
        // $zone = Zona::getAll()->pluck('nome','id')->toArray();


        // prendo tutte le zone 
        $zone = Zona::orderBy('nome')->pluck('nome','id')->toArray();

        return view('admin.azioni.inc_zone_select_cascade', compact('zone'));

         if(!is_null($unita_gestione_id = $request->get('unita_gestione_id')))
           {
           if(!is_null($unita = UnitaGestione::find($unita_gestione_id)))
             {


             $selected_id = $request->get('zona_id');
             
             $zone = $unita->zone->pluck('nome','id')->toArray();

             if(!empty($zone))
                  asort($zone);

             return view('admin.azioni.inc_zone_select_cascade', compact('zone', 'selected_id'));

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

     // UG può essere un array
     if (is_array($unita_gestione_id)) 
      {
      $unita_gestione_id = $unita_gestione_id[0]; 
      }

     if(!is_null($unita = UnitaGestione::find($unita_gestione_id)))
       {

       if(!is_null($distretto = $unita->distretto))
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
     * [RicaricaUgStessoDistrettoAjax 
     * se seleziono un UG devo ricaricare la select delle UG con solo quelle che hanno lo stesso distretto di quella selezionata]
     * @param Request $request [description]
     */
    public function RicaricaUgStessoDistrettoAjax(Request $request)
      {
      $unita_gestione_id = $request->get('unita_gestione_id');

      // ho azzerato le UG devoricaricarle tutte
      if(is_null($unita_gestione_id))
        {
        $unita_associate = [];
        $unita_selezionabili = UnitaGestione::getAll($sort_by = 'nome')->pluck('nome','id')->toArray();
        
        return view('admin.inc_unita_select',['utg' => $unita_selezionabili, 'unita_associate' => $unita_associate]);
        }


      $unita_associate = [];
      // UG può essere un array
      if (is_array($unita_gestione_id)) 
        {
         
        foreach ($unita_gestione_id as $u_id) 
          {
          $unita_associate[$u_id] = $u_id;          
          }
        
        $unita_gestione_id = $unita_gestione_id[0];

        } 
      else
        {
        $unita_associate[$unita_gestione_id] = $unita_gestione_id;
        }
           

      $unita = UnitaGestione::find($unita_gestione_id);
      $distretto = $unita->distretto;

      $unita_selezionabili = $distretto->unita->pluck('nome','id')->toArray();


      return view('admin.inc_unita_select',['utg' => $unita_selezionabili, 'unita_associate' => $unita_associate]);

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
