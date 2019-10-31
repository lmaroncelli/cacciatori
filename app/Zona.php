<?php

namespace App;

use App\Comune;
use App\Squadra;
use App\AzioneCaccia;
use App\UnitaGestione;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
		protected $table = 'tblZone';

		protected $fillable = ['unita_gestione_id','numero','nome','superficie','tipo','note'];


		public function unita()
		{
      return $this->belongsToMany('App\UnitaGestione', 'tblUnitaZone', 'zona_id', 'unita_id');

    }

    public function getUnita() 
    {
    return implode(',', $this->unita()->pluck('nome')->toArray());
    }


    public function roles()
      {
          return $this->belongsToMany('App\Role', 'role_user_table', 'user_id', 'role_id');
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
		

   public function scopeJoinUtg($query, $sort_by, $order) 
    {
      
    if ($sort_by == 'utg') 
      {
      $campo_utg = 'nome'; 
      } 
    else 
      {
      $campo_utg = 'id';
      }
       
    return $query->leftJoin('tblUnitaGestione', 'tblZone.unita_gestione_id', '=', 'tblUnitaGestione.id')
            ->select('tblZone.*')
            ->orderBy('tblUnitaGestione.'.$campo_utg,$order)
            ->get();
    }


	 public static function getAll($sort_by = 'id', $order = 'asc')
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
          if ($order == 'asc') 
            {
            return $zone->keyBy($sort_by)->sortBy($sort_by);
            } 
          else 
            {
            return $zone->keyBy($sort_by)->sortByDesc($sort_by);
            }
          }
        else 
          {

          if ($sort_by == 'utg' || $sort_by == 'id_utg') 
            {

            return Self::with('referenti','unita')->joinUtg($sort_by, $order);
             

            } 
          else 
            {

            if ($order == 'asc') 
              {
              return Self::with('referenti')->orderBy($sort_by)->get();
              } 
            else 
              {
              return Self::with('referenti')->orderByDesc($sort_by)->get();
              }
            
            }

          
          }
        }
      }

}
