<?php

namespace App;

use App\Atc;
use App\Squadra;
use App\AzioneCaccia;
use App\UnitaGestione;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Distretto extends Model
{
		protected $table = 'tblDistretti';

		protected $fillable = ['atc_id', 'nome', 'note'];


		public function squadre()
		{
		    return $this->hasMany(Squadra::class, 'distretto_id', 'id');
		}

		public function unita()
		{
		    return $this->hasMany(UnitaGestione::class, 'distretto_id', 'id')->orderBy('nome');
    }
    
    public function azioni()
		{
	  return $this->hasMany(AzioneCaccia::class, 'distretto_id', 'id');
		}

		public function poligono()
		  { 
		      // the Poligono model is automatically assumed to have a distretto_id foreign key
		      return $this->hasOne('App\Poligono','distretto_id','id');
		  }

		public function atc()
		{
		    return $this->belongsTo(Atc::class, 'atc_id', 'id');
    }
    
    public function getUnita() 
    {
    return implode(',', $this->unita()->pluck('nome')->toArray());
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
      

    

    public static function getAll($sort_by = 'id')
      {
      if (Auth::check()) 
        {
        if(Auth::user()->hasRole('cacciatore'))
          {
          // vedo solo i distretti collegati alle mie squadre
          $squadre =  Auth::user()->cacciatore->squadre;
          $distretti = [];
          foreach ($squadre as $squadra) 
            {
            $distretti[] = $squadra->distretto;
            }
          
          $distretti_collection = collect($distretti);
            
          return $distretti_collection->keyBy($sort_by)->sortBy($sort_by);
        }
        else 
          {
          return Self::orderBy($sort_by)->get();
          }
        }
      }

}
