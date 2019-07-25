<?php


namespace App\Http\Composers;



use App\Distretto;
use Illuminate\Contracts\View\View;


/**
 * summary
 */
class DistrettiFormComposer
{
    public function compose(View $view)
    	{

    	$distretti = Distretto::getAll($sort_by = 'nome')->pluck('nome','id')->toArray();

    	$view->with(compact('distretti'));
    	}
}