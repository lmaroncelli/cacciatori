<?php

namespace App;

use App\Zona;
use App\Distretto;
use App\AzioneCaccia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Squadra extends Model
{
		protected $table = 'tblSquadre';

		protected $fillable = ['distretto_id', 'nome', 'note','unita_gestione_id'];

		public function cacciatori()
		  {
		  return $this->belongsToMany('App\Cacciatore', 'tblCacciatoriSquadre', 'squadra_id', 'cacciatore_id')->withPivot('capo_squadra');
		  }

		public function distretto()
		{
		    return $this->belongsTo(Distretto::class, 'distretto_id', 'id');
		}


		public function zone()
		{
		    return $this->belongsToMany(Zona::class, 'tblSquadreZone', 'squadra_id', 'zona_id')->withPivot('tipo_caccia');
		}

		public function azioni()
		{
		    return $this->hasMany(AzioneCaccia::class, 'squadra_id', 'id');
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
          return Auth::user()->cacciatore->squadre;
          }
        else 
          {
          return Self::all();
          }          
        }
      }

}
