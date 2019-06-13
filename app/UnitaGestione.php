<?php

namespace App;

use App\Distretto;
use App\Zona;
use Illuminate\Database\Eloquent\Model;

class UnitaGestione extends Model
{
		protected $table = 'tblUnitaGestione';

		protected $fillable = ['distretto_id', 'nome', 'note'];


		public function distretto()
		{
		    return $this->belongsTo(Distretto::class, 'distretto_id', 'id');
		}

		public function zone()
		{
		    return $this->hasMany(Zona::class, 'unita_gestione_id', 'id');
		}

		public function getZone() 
    {
    return implode(',', $this->zone()->pluck('nome')->toArray());
    }
}
