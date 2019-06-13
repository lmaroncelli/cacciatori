<?php

namespace App;

use App\AzioneCaccia;
use App\Comune;
use App\Squadra;
use App\UnitaGestione;
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
		    return $this->belongsToMany(Squadra::class, 'tblSquadreZone', 'zona_id', 'squadra_id')->withPivot('tipo_caccia');
		}


		public function comuni()
		{
		    return $this->belongsToMany(Comune::class, 'tblComuneZona', 'zona_id', 'comune_id');
		}


		public function poligono()
		  { 
		      // the Poligono model is automatically assumed to have a zona_id foreign key
		      return $this->hasOne('App\Poligono','zona_id','id');
		  }


	public function azioni()
		{
	  return $this->hasMany(AzioneCaccia::class, 'zona_id', 'id');
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
