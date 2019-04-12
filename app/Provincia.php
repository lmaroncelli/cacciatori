<?php

namespace App;


use App\Atc;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
		protected $table = 'tblProvince';

		protected $guarded = ['id'];


		public function atc()
		{
		    return $this->hasMany(Atc::class, 'provincia_id', 'id');
		}
}
