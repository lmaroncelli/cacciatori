<?php

namespace App;

use App\Atc;
use App\Localita;
use Illuminate\Database\Eloquent\Model;

class Avf extends Model
{
		protected $table = 'tblAziendeFaunisticheVenatorie';

		protected $guarded = ['id'];


		public function localita()
		{
		    return $this->hasMany(Localita::class, 'avf_id', 'id');
		}


		public function atc()
		{
		    return $this->belongsTo(Atc::class, 'atc_id', 'id');
		}
}
