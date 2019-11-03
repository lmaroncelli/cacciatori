<?php


namespace App\Http\Composers;


use App\UnitaGestione;
use Illuminate\Contracts\View\View;


/**
 * summary
 */
class UtgFormComposer
{
    public function compose(View $view)
    	{

    	$utg = UnitaGestione::getAll($sort_by = 'nome')->pluck('nome','id')->toArray();
			
    	$view->with(compact('utg'));
    	}
}