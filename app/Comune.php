<?php

namespace App;


use App\Localita;
use App\Provincia;
use App\Zona;
use Illuminate\Database\Eloquent\Model;

class Comune extends Model
{
		protected $table = 'tblComuni';

		protected $guarded = ['id'];


		public function provincia()
		{
		    return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
		}


		public function localita()
		{
		    return $this->belongsToMany(Localita::class, 'tblComuneLocalita', 'comune_id', 'localita_id');
		}


		public function zone()
		{
		    return $this->belongsToMany(Zona::class, 'tblComuneZona', 'comune_id', 'zona_id');
		}		

}
