<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['admin']], function () {

	Route::resource('distretti', 'Admin\DistrettiController');
	Route::resource('squadre', 'Admin\SquadreController');
	Route::resource('utg', 'Admin\UtgController');
	Route::resource('cacciatori', 'Admin\CacciatoriController');
	Route::resource('zone', 'Admin\ZoneController');
	Route::resource('province', 'Admin\ProvinceController');
	Route::resource('comuni', 'Admin\ComuniController');


	Route::post('salva_coordinate_poligono_ajax','Admin\PoligoniController@salvaCoordinatePoligonoAjax')->name('aggiorna_coordinate');
	Route::post('salva_coordinate_poligono_ajax_distretto','Admin\PoligoniController@salvaCoordinatePoligonoAjaxDistretto')->name('aggiorna_coordinate_distretto');

	Route::get('readJson','Admin\PoligoniController@readJson')->name('readJson');
	Route::get('readXml','Admin\PoligoniController@readXml')->name('readXml');

});