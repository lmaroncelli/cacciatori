<?php

namespace App;


use App\Distretto;
use App\Provincia;
use Illuminate\Database\Eloquent\Model;

class Atc extends Model
{
		protected $table = 'tblAmbitiTerritorialiCaccia';

		protected $guarded = ['id'];


		public function provincia()
		{
		    return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
		}

		public function distretti()
		{
		    return $this->hasMany(Distretto::class, 'atc_id', 'id');
		}

		public function scopeCode($query, $code)
	   {
	       return $query->where('code', 'ATC'.$code)->first();
	   }

}
