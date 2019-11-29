<?php

namespace App;

use App\Zona;
use App\Distretto;
use App\Documento;
use App\AzioneCaccia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Squadra extends Model
{
		protected $table = 'tblSquadre';

		protected $fillable = ['distretto_id', 'nome', 'note','unita_gestione_id'];

    
    public function cacciatori()
		  {
		  return $this->belongsToMany('App\Cacciatore', 'tblCacciatoriSquadre', 'squadra_id', 'cacciatore_id')->withPivot('capo_squadra')->withTimestamps();
      }
      
		public function distretto()
		{
		    return $this->belongsTo(Distretto::class, 'distretto_id', 'id');
		}


		public function zone()
		{
		    return $this->belongsToMany(Zona::class, 'tblSquadreZone', 'squadra_id', 'zona_id')->withTimestamps();
    }
    
    public function documenti()
		{
		    return $this->belongsToMany(Documento::class, 'tblDocumentiSquadre', 'squadra_id', 'documento_id')->withTimestamps();
		}

		public function azioni()
		{
		    return $this->hasMany(AzioneCaccia::class, 'squadra_id', 'id');
		}


    public function getZone() 
      {
      return implode(',', $this->zone()->pluck('nome')->toArray());
      }
    

    public function getZoneForTable() 
      {
      $to_show =  implode(',', $this->zone()->limit(5)->pluck('nome')->toArray());
      
      if ($this->zone()->count() > 5 ) 
        {
        return $to_show.'...';
        } 
      else 
        {
        return $to_show;
        }
      
      }
    

    public function getCapoSquadra()
      {
      return $this->cacciatori()->wherePivot('capo_squadra',1)->first();
      }


    public function getNomeCapoSquadra()
      {
        if (!is_null($caposquadra = $this->getCapoSquadra())) {
          return $caposquadra->nome . ' ' . $caposquadra->cognome;
        } else {
          return '';
        }
        
      }

    public function getCacciatoriSelect() 
      {
      $cacciatori_arr = [];
      
      foreach (self::cacciatori()->orderBy('cognome')->get() as $c) 
        {
        $cacciatori_arr[$c->id] = $c->cognome.' '.$c->nome;
        }
      
      return $cacciatori_arr;
      }
    

    public function getCacciatori() 
      {
      
      return implode(',', $this->getCacciatoriSelect());
      }


    public function destroyMe()
    	{
    	self::cacciatori()->detach();
    	self::zone()->detach();
			
			self::delete();
    	}

    public function scopeJoinDistretto($query, $order) 
    {
              
      return $query->leftJoin('tblDistretti', 'tblSquadre.distretto_id', '=', 'tblDistretti.id')
              ->select('tblSquadre.*')
              ->orderBy('tblDistretti.nome', $order)
              ->get();
    } 

    public static function getAll($sort_by = 'id', $order = 'asc')
      {
      if (Auth::check()) 
        {
        if(Auth::user()->hasRole('cacciatore'))
          {
          return Auth::user()->cacciatore->squadre;
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

}
