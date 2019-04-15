<?php

namespace App;


use App\Avf;
use App\Comune;
use Illuminate\Database\Eloquent\Model;

class Localita extends Model
{
		protected $table = 'tblLocalita';

		protected $guarded = ['id'];


		public function avf()
		{
		    return $this->belongsTo(Avf::class, 'avf_id', 'id');
		}

		public function comuni()
		{
		    return $this->belongsToMany(Comune::class, 'tblComuneLocalita', 'localita_id', 'comune_id');
		}
}
