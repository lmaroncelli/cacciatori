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

    	$utg = UnitaGestione::orderBy('nome')->pluck('nome','id');


    	$view->with(compact('utg'));
    	}
}