<?php

namespace App;

use App\Zona;
use App\Distretto;
use App\AzioneCaccia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UnitaGestione extends Model
{
		protected $table = 'tblUnitaGestione';

    protected $fillable = ['distretto_id', 'nome', 'note'];
    


    public function poligono()
		  { 
		      // the Poligono model is automatically assumed to have a distretto_id foreign key
		      return $this->hasOne('App\Poligono','unita_gestione_id','id');
		  }


		public function distretto()
		{
		    return $this->belongsTo(Distretto::class, 'distretto_id', 'id');
		}

		public function zone()
		{
		    return $this->hasMany(Zona::class, 'unita_gestione_id', 'id');
    }
    

    public function azioni()
		{
	  return $this->hasMany(AzioneCaccia::class, 'unita_gestione_id', 'id');
		}

		public function getZone() 
    {
    return implode(',', $this->zone()->pluck('nome')->toArray());
    }


    public function scopeJoinDistretto($query, $order) 
    {
              
      return $query->leftJoin('tblDistretti', 'tblUnitaGestione.distretto_id', '=', 'tblDistretti.id')
              ->orderBy('tblDistretti.nome', $order)
              ->get();
    } 


    public static function getAll($sort_by = 'id', $order = 'asc')
      {
      if (Auth::check()) 
        {
        if(Auth::user()->hasRole('cacciatore'))
          {
          // vedo solo i distretti collegati alle mie squadre
          $squadre =  Auth::user()->cacciatore->squadre;
          $utg = [];
          foreach ($squadre as $squadra) 
            {
            foreach ($squadra->distretto->unita as $u) 
              {
              if(!array_key_exists($u->id,$utg))
                $utg[$u->id] = $u;
              }
            }

          if ($order == 'asc') 
            {
            return collect($utg)->keyBy($sort_by)->sortBy($sort_by);
            } 
          else 
            {
            return collect($utg)->keyBy($sort_by)->sortByDesc($sort_by);
            }
          
          }
        else 
          {

          if ($sort_by == 'distretto') 
            {
            return Self::with('distretto')->JoinDistretto($order);
            } 
          else 
            {
            
            if ($order == 'asc') 
              {
              return Self::all()->keyBy($sort_by)->sortBy($sort_by);
              } 
            else 
              {
              return Self::all()->keyBy($sort_by)->sortByDesc($sort_by);
              }
            
            }
          
          
          
          }
        }
      }


    // cancella anche il POLIGONO (e le COORDINATE) ASSOCIATO
    public function destroyMe()
    	{
  		$p = $this->poligono;
  		
  		if(!is_null($p))
  			{
				$p->coordinate()->delete();
		 		$p->delete();
  			} 	
  			
  	 	self::delete();
      }
}
