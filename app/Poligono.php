<?php

namespace App;

use App\CoordinataPoligono;
use App\Distretto;
use App\Zona;
use Illuminate\Database\Eloquent\Model;

class Poligono extends Model
{
		protected $table = 'tblPoligoni';

		protected $guarded = ['id'];


		public function zona()
		{
		  return $this->belongsTo('App\Zona','zona_id','id');
		}

		public function distretto()
		{
		  return $this->belongsTo('App\Distretto','distretto_id','id');
    }
    
    public function unita()
		{
		  return $this->belongsTo('App\UnitaGestione','unita_gestione_id','id');
		}

		public function coordinate()
		{
		    return $this->hasMany(CoordinataPoligono::class, 'poligono_id', 'id');
		}
		
}
