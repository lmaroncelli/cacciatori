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

/*Route::get('/', function () {
    return view('welcome');
});*/




// la home diventa il mio loginForm
Route::get('/','Auth\LoginController@showLoginForm')->name('login');

Route::post('utenti/modifica/{utente_id}', 'Auth\RegisterController@modificaUtente')->name('utenti.modifica');

Auth::routes();



Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['admin']], function () {

	Route::resource('distretti', 'Admin\DistrettiController');

	Route::post('getUnitaGestioneAjax','Admin\SelectConditionalController@getUnitaGestioneAjax')->name('get_unita_gestione');
	Route::post('getZonaAjax','Admin\SelectConditionalController@getZonaAjax')->name('get_zona');
	Route::resource('squadre', 'Admin\SquadreController');

	Route::resource('utg', 'Admin\UtgController');
	
	Route::resource('cacciatori', 'Admin\CacciatoriController');
	
	Route::resource('zone', 'Admin\ZoneController');
	Route::resource('province', 'Admin\ProvinceController');
	Route::resource('comuni', 'Admin\ComuniController');


	Route::post('getUtgFromDistrettoAjax','Admin\SelectConditionalController@getUtgFromDistrettoAjax')->name('get_utg');
	Route::post('getDistrettoFromSquadraAjax','Admin\SelectConditionalController@getDistrettoFromSquadraAjax')->name('get_distretto');
	Route::post('showDistrettoZonaAjax','Admin\SelectConditionalController@showDistrettoZonaAjax')->name('show_distretto');
	Route::post('getSquadreFromDistrettoAjax','Admin\SelectConditionalController@getSquadreFromDistrettoAjax')->name('get_squadre');
	Route::post('getZoneFromUtgAjax','Admin\SelectConditionalController@getZoneFromUtgAjax')->name('get_zone_form_utg');


	Route::resource('azioni', 'Admin\AzioniCacciaController');



	Route::post('salva_coordinate_poligono_ajax','Admin\PoligoniController@salvaCoordinatePoligonoAjax')->name('aggiorna_coordinate');
	Route::post('aggiorna_centro_ajax','Admin\PoligoniController@aggiornaCentroAjax')->name('aggiorna_centro');



	Route::get('readJson','Admin\PoligoniController@readJson')->name('readJson');
	Route::get('readXml','Admin\PoligoniController@readXml')->name('readXml');

});