<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cacciatore extends Model
{
 	
 	use SoftDeletes;
 	
 	protected $table = 'tblVolontari';

 	protected $guarded = ['id'];

 	protected $dates = ['data_nascita','deleted_at'];




 	public function utente()
 	{
 	  return $this->belongsTo('App\User','user_id','id');
 	}




	public function squadre()
	  {
	  return $this->belongsToMany('App\Squadra', 'tblCacciatoriSquadre', 'cacciatore_id', 'squadra_id')->withPivot('capo_squadra');
	  }



	public function setDataNascitaAttribute($value)
	 	{
	  if ($value == '0000-00-00') 
	    {
	    $this->attributes['data_nascita'] = Carbon::today();
	    } 
	  else 
	    {
	    $this->attributes['data_nascita'] = Carbon::createFromFormat('d/m/Y', $value);
	    }
	  
	 	}

	public function getDataNascitaAttribute($value)
		{
	  return Carbon::parse($value)->format('d/m/Y');
		}



}
