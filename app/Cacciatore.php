<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cacciatore extends Model
{
 	
 	use SoftDeletes;
 	
 	protected $table = 'tblCacciatori';

 	protected $fillable = ['user_id','nome','cognome','registro', 'telefono', 'data_nascita','note','deleted_at','created_at','updated_at'];

 	protected $dates = ['data_nascita','deleted_at'];




 	public function utente()
 	{
 	  return $this->belongsTo('App\User','user_id','id');
 	}


	public function squadre()
	  {
	  return $this->belongsToMany('App\Squadra', 'tblCacciatoriSquadre', 'cacciatore_id', 'squadra_id')->withPivot('capo_squadra')->withTimestamps();
    }
    

  public function squadreACapo()
	  {
	  return $this->squadre()->wherePivot('capo_squadra',1);
	  }


  public function getSquadre()
    {
    return implode(',', $this->squadre()->pluck('nome')->toArray());
    }


	public function getSquadreACapo()
    {
    return implode(',', $this->squadre()->wherePivot('capo_squadra',1)->pluck('nome')->toArray());
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
