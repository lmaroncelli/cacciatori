<?php

namespace App;

use App\Comune;
use App\Squadra;
use App\AzioneCaccia;
use App\UnitaGestione;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
		protected $table = 'tblZone';

		protected $fillable = ['unita_gestione_id','numero','nome','superficie','tipo','note'];


		public function unita()
		{
		    return $this->belongsTo(UnitaGestione::class, 'unita_gestione_id', 'id');
		}



		public function squadre()
		{
		    return $this->belongsToMany(Squadra::class, 'tblSquadreZone', 'zona_id', 'squadra_id')->withTimestamps();
		}


		public function comuni()
		{
		    return $this->belongsToMany(Comune::class, 'tblComuneZona', 'zona_id', 'comune_id');
		}

    public function azioni()
      {
      return $this->hasMany(AzioneCaccia::class, 'zona_id', 'id');
      }

		public function poligono()
		  { 
		      // the Poligono model is automatically assumed to have a zona_id foreign key
		      return $this->hasOne('App\Poligono','zona_id','id');
		  }
    
    public function referenti()
      {
          return $this->belongsToMany('App\Referente', 'tblReferentiZone', 'zona_id', 'referente_id');
      }


		public function setSuperficieAttribute($value)
	   {
	    $this->attributes['superficie'] =  (float) str_ireplace(',', '.', $value);
	   }


	  public function getSuperficieAttribute($value)
    	{
       return str_ireplace('.', ',', $value);
      }
      

    public function getSquadre() 
    {
    return implode(',', $this->squadre()->pluck('nome')->toArray());
    }

    public function getReferenti() 
    {
    $to_return = [];
    $arr = $this->referenti()->pluck('nome', 'dipartimento')->toArray();
    foreach ($arr as $dip => $n) {
      $to_return[] = $n . ' - ' . $dip;
    }
    return implode(', ',$to_return);
    }

    public function getReferentiIds()
      {
        return $this->referenti()->pluck('tblReferenti.id')->toArray();
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
  		$this->referenti()->sync([]);
  	 	self::delete();
		}
		


	 public static function getAll($sort_by = 'id')
      {
      if (Auth::check()) 
        {
        if(Auth::user()->hasRole('cacciatore'))
          {
					$squadre_cacciatore = Auth::user()->cacciatore->squadre;
					$zone = collect([]);
          foreach ($squadre_cacciatore as $squadra) 
          	{
          	$squadra_zone = $squadra->zone;
          	$zone = $zone->merge($squadra_zone);
          	}
          $zone = $zone->unique();
          return $zone->keyBy($sort_by)->sortBy($sort_by);
        }
        else 
          {
          return Self::with('referenti')->orderBy($sort_by)->get();
          }
        }
      }

}
