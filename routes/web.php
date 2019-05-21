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

	Route::post('getUnitaGestioneAjax','Admin\SquadreController@getUnitaGestioneAjax')->name('get_unita_gestione');
	Route::post('getZonaAjax','Admin\SquadreController@getZonaAjax')->name('get_zona');
	Route::resource('squadre', 'Admin\SquadreController');

	Route::resource('utg', 'Admin\UtgController');
	
	Route::resource('cacciatori', 'Admin\CacciatoriController');
	
	Route::resource('zone', 'Admin\ZoneController');
	Route::resource('province', 'Admin\ProvinceController');
	Route::resource('comuni', 'Admin\ComuniController');


	Route::post('getDistrettoFromSquadraAjax','Admin\AzioniCacciaController@getDistrettoFromSquadraAjax')->name('get_distretto');
	Route::resource('azioni', 'Admin\AzioniCacciaController');



	Route::post('salva_coordinate_poligono_ajax','Admin\PoligoniController@salvaCoordinatePoligonoAjax')->name('aggiorna_coordinate');
	Route::post('aggiorna_centro_ajax','Admin\PoligoniController@aggiornaCentroAjax')->name('aggiorna_centro');



	Route::get('readJson','Admin\PoligoniController@readJson')->name('readJson');
	Route::get('readXml','Admin\PoligoniController@readXml')->name('readXml');

});