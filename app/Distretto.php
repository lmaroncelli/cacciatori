<?php

namespace App;

use App\Atc;
use App\Squadra;
use App\UnitaGestione;
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

		public function poligono()
		  { 
		      // the Poligono model is automatically assumed to have a distretto_id foreign key
		      return $this->hasOne('App\Poligono','distretto_id','id');
		  }

		public function cacciatori()
		  {
		  return $this->belongsToMany('App\Cacciatore', 'tblCacciatoriSquadre', 'squadra_id', 'cacciatore_id')->withPivot('capo_squadra');
		  }

		public function atc()
		{
		    return $this->belongsTo(Atc::class, 'atc_id', 'id');
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
