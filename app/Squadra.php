<?php

namespace App;

use App\AzioneCaccia;
use App\Distretto;
use App\Zona;
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


		

}
