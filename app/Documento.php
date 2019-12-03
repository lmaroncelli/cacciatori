<?php

namespace App;

use App\Squadra;
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
    return implode(',', $this->squadre()->pluck('nome')->toArray());
    }
    /**
    * [listaDocumenti trova la lista di documenti in base all'utente loggato ed eventualmente con un limite massimo
    * utilizzata nella sezione documenti e nella dashboard]
    * @param  integer $limit [description]
    * @return [type]         [description]
    */
   public static function listaDocumenti($order_by, $order, $paginate = 15, $limit = 0)
   	{
   	
    $query = self::orderBy($order_by, $order); 
    
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
