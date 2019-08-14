<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referente extends Model
{
    	protected $table = 'tblReferenti';
      protected $guarded = ['id'];
      

      public function zone()
      {
          return $this->belongsToMany('App\Zone', 'tblReferentiZone', 'referente_id', 'zona_id');
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
