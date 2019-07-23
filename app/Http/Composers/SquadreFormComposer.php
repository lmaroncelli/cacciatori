<?php


namespace App\Http\Composers;


use App\Squadra;
use Illuminate\Contracts\View\View;


/**
 * summary
 */
class SquadreFormComposer
{
    public function compose(View $view)
    	{

    	$squadre = Squadra::orderBy('nome')->pluck('nome','id')->toArray();


    	$view->with(compact('squadre'));
    	}
}