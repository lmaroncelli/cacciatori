<?php

namespace App;

use App\Poligono;
use Illuminate\Database\Eloquent\Model;

class CoordinataPoligono extends Model
{
		protected $table = 'tblCoordinatePoligoni';

		protected $fillable = ['poligono_id','lat','long'];


		public function unita()
		{
		    return $this->belongsTo(Poligono::class, 'poligono_id', 'id');
		}

}
