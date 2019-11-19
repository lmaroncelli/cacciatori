<?php

namespace App;


use App\Zona;
use App\Squadra;
use App\Distretto;
use App\UnitaGestione;
use App\Scopes\AzioniOwnedByScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AzioneCaccia extends Model
{
    use SoftDeletes;


		protected $table = 'tblAzioniCaccia';

		protected $guarded = ['id'];

		protected $dates = ['dalle','alle'];


	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AzioniOwnedByScope);
		}
		
	public function squadra()
	{
		return $this->belongsTo(Squadra::class, 'squadra_id', 'id');
    }
    
    public function distretto()
    {
        return $this->belongsTo(Distretto::class, 'distretto_id', 'id');
    }
    
    public function zone()
      {
      return $this->belongsToMany(Zona::class, 'tblAzioneZona', 'azione_id', 'zona_id');
      }

   public function getZone() 
    {
    return implode(',', $this->zone()->pluck('nome')->toArray());
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
    
  public function destroyMe()
    {
    if (Auth::check()) 
      {
      if(Auth::user()->hasRole('cacciatore'))
        {
        // e' una cancellazione logica e NON ELIMINO anche la relazione con i quadranti
        self::delete();
        }
      else 
        {
        self::zone()->detach();
        self::forceDelete();
        }
      }
    
    }

		

}
