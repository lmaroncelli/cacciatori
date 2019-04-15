<?php

namespace App;


use App\Atc;
use App\Comune;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
		protected $table = 'tblProvince';

		protected $guarded = ['id'];


		public function atc()
		{
		    return $this->hasMany(Atc::class, 'provincia_id', 'id');
		}

		public function comuni()
		{
		    return $this->hasMany(Comune::class, 'provincia_id', 'id');
		}
}
