<?php

namespace App;

use App\Distretto;
use App\Squadra;
use App\UnitaGestione;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
		protected $table = 'tblZone';

		protected $guarded = ['id'];


		public function unita()
		{
		    return $this->belongsTo(UnitaGestione::class, 'unita_gestione_id', 'id');
		}



		public function squadre()
		{
		    return $this->belongsToMany(Squadra::class, 'tblSquadreZone', 'zona_id', 'squadra_id')->withPivot('tipo_caccia');
		}

		
}
