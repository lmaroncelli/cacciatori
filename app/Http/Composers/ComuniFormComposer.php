<?php


namespace App\Http\Composers;


use App\Comune;
use Illuminate\Contracts\View\View;


/**
 * summary
 */
class ComuniFormComposer
{
    public function compose(View $view)
    	{

    	$comuni = Comune::orderBy('nome')->pluck('nome','id')->toArray();


    	$view->with(compact('comuni'));
    	}
}