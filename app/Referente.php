<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referente extends Model
{
    	protected $table = 'tblReferenti';
      protected $guarded = ['id'];
      

      public function zone()
      {
          return $this->belongsToMany('App\Zona', 'tblReferentiZone', 'referente_id', 'zona_id');
      }

    
    public function getZone() 
    {
    return implode(', ', $this->zone()->orderBy('nome')->pluck('nome')->toArray());
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


          
    public static function getAllSelect()
      {
        $referenti = [];
        foreach (Self::all() as $ref) 
          {
          $referenti[$ref->id] = $ref->nome . ': ' . $ref->telefono . ' - ' . $ref->dipartimento; 
          }
        
          return $referenti;
      }


}
