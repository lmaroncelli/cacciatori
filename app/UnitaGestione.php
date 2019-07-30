<?php

namespace App;

use App\Zona;
use App\Distretto;
use App\AzioneCaccia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UnitaGestione extends Model
{
		protected $table = 'tblUnitaGestione';

    protected $fillable = ['distretto_id', 'nome', 'note'];
    


    public function poligono()
		  { 
		      // the Poligono model is automatically assumed to have a distretto_id foreign key
		      return $this->hasOne('App\Poligono','unita_gestione_id','id');
		  }


		public function distretto()
		{
		    return $this->belongsTo(Distretto::class, 'distretto_id', 'id');
		}

		public function zone()
		{
		    return $this->hasMany(Zona::class, 'unita_gestione_id', 'id');
    }
    

    public function azioni()
		{
	  return $this->hasMany(AzioneCaccia::class, 'unita_gestione_id', 'id');
		}

		public function getZone() 
    {
    return implode(',', $this->zone()->pluck('nome')->toArray());
    }


    public static function getAll()
      {
      if (Auth::check()) 
        {
        if(Auth::user()->hasRole('cacciatore'))
          {
          // vedo solo i distretti collegati alle mie squadre
          $squadre =  Auth::user()->cacciatore->squadre;
          $utg = [];
          foreach ($squadre as $squadra) 
            {
            foreach ($squadra->distretto->unita as $u) 
              {
              if(!array_key_exists($u->id,$utg))
                $utg[$u->id] = $u;
              }
            }
          
          return collect($utg);
          }
        else 
          {
          return Self::all();
          }
        }
      }


    // cancella anche il POLIGONO (e le COORDINATE) ASSOCIATO
    public function destroyMe()
    	{
  		$p = $this->poligono;
  		
  		if(!is_null($p))
  			{
				$p->coordinate()->delete();
		 		$p->delete();
  			} 	
  			
  	 	self::delete();
      }
}
