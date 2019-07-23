<?php


namespace App\Http\Composers;


use App\Cacciatore;
use Illuminate\Contracts\View\View;


/**
 * summary
 */
class CacciatoriFormComposer
{
    public function compose(View $view)
    	{
      $cacciatori = [];

      $c = Cacciatore::orderBy('cognome')->get();
      
      foreach ($c as $cacciatore) 
        {
        $cacciatori[$c->id] = $cacciatore->cognome.' '.$cacciatore->nome;
        }

    	$view->with(compact('cacciatori'));

    }
}