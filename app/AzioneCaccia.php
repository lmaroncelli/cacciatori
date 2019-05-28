<?php

namespace App;


use App\Squadra;
use App\Zona;
use Illuminate\Database\Eloquent\Model;

class AzioneCaccia extends Model
{
		protected $table = 'tblAzioniCaccia';

		protected $guarded = ['id'];

		 protected $dates = ['dalle','alle'];



		public function squadra()
		{
		    return $this->belongsTo(Squadra::class, 'squadra_id', 'id');
		}

		public function zona()
		{
		    return $this->belongsTo(Zona::class, 'zona_id', 'id');
		}


		public function getDalleAlle()
		  {
		  if(is_null($this->dalle) && is_null($this->alle))
		    {
		    return "";
		    }
		  
		  if ($this->dalle->toDateString() == $this->alle->toDateString()) 
		    {
		    return $this->dalle->format('d/m/Y'). ' dalle '.$this->dalle->format('H:i').' alle '.$this->alle->format('H:i'); 
		    } 
		  else 
		    {
		    return 'dal '. $this->dalle->format('d/m/Y H:i'). ' al '.$this->alle->format('d/m/Y H:i'); 
		    
		    }
		  
		  }

		

}
