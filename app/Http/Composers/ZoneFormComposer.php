<?php


namespace App\Http\Composers;


use App\Zona;
use Illuminate\Contracts\View\View;


/**
 * summary
 */
class ZoneFormComposer
{
    public function compose(View $view)
    	{

    	$zone = Zona::orderBy('nome')->pluck('nome','id');


    	$view->with(compact('zone'));
    	}
}