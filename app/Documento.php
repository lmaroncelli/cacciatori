<?php

namespace App;

use App\Squadra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'tblDocumenti';

    protected $guarded = ['id'];
    

    public function pubblicatore()
		{
			return $this->belongsTo('App\User','user_id','id');
		}

	  public function squadre()
		{
		    return $this->belongsToMany(Squadra::class, 'tblDocumentiSquadre', 'documento_id', 'squadra_id')->withTimestamps();
    }


    public function getSquadre()
    {
    if ($this->squadre()->count()) 
      {
      return implode(',', $this->squadre()->pluck('nome')->toArray());
      } 
    else 
      {
      return "Tutte";
      }
    
    }
    /**
    * [listaDocumenti trova la lista di documenti in base all'utente loggato ed eventualmente con un limite massimo
    * utilizzata nella sezione documenti e nella dashboard]
    * @param  integer $limit [description]
    * @return [type]         [description]
    */
   public static function listaDocumenti($order_by, $order, $paginate = 15, $limit = 0)
   	{
    
    if (Auth::user()->hasRole('cacciatore')) 
      {
      $doc_ids = [];
      $squadre_cacciatore = Auth::user()->cacciatore->squadre;
      
      // trovo tutti i documenti associati a tutte le squadre del cacciatore
      foreach ($squadre_cacciatore as $squadra) 
        {
          $squadra_doc_ids = $squadra->documenti()->pluck('documento_id')->toArray();
          $doc_ids = array_merge($doc_ids, $squadra_doc_ids);
        }

      // trovo tutti i documenti visibili a tutti
      $doc_ids_visibili = DB::table('tblDocumentiSquadre')->where('squadra_id',0)->pluck('documento_id')->toArray();
      
      $doc_ids = array_merge($doc_ids, $doc_ids_visibili);
      

      $query = self::whereIn('id',$doc_ids)->orderBy($order_by, $order);

    
      } 
    else 
      {

      $query = self::orderBy($order_by, $order); 
    
      }


     if ($limit) 
        {
        $documenti = $query->limit($limit)->get();
        } 
      else 
        {
        $documenti = $query->paginate($paginate);
        }
   		  
   		
   	return $documenti;

   	}

}
