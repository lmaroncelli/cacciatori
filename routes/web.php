<?php

use App\Http\Controllers\showMappaAttivita;

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



Route::get('/home', 'HomeController@showMappaAttivita')->name('home');



Route::resource('distretti', 'Admin\DistrettiController');
Route::resource('utg', 'Admin\UtgController');
Route::resource('squadre', 'Admin\SquadreController');
Route::post('getZonaAjax','Admin\SelectConditionalController@getZonaAjax')->name('get_zona');
Route::resource('zone', 'Admin\ZoneController');
Route::post('getSquadreFromDistrettoAjax','Admin\SelectConditionalController@getSquadreFromDistrettoAjax')->name('get_squadre');
Route::post('showDistrettoZonaAjax','Admin\SelectConditionalController@showDistrettoZonaAjax')->name('show_distretto');
Route::post('getUtgFromDistrettoAjax','Admin\SelectConditionalController@getUtgFromDistrettoAjax')->name('get_utg');
Route::post('getDistrettoFromSquadraAjax','Admin\SelectConditionalController@getDistrettoFromSquadraAjax')->name('get_distretto');
Route::post('getZoneFromUtgAjax','Admin\SelectConditionalController@getZoneFromUtgAjax')->name('get_zone_form_utg');


Route::get('reset','Admin\AzioniCacciaController@reset')->name('reset');
Route::post('azioni_search','Admin\AzioniCacciaController@index')->name('azioni_search');
Route::resource('azioni', 'Admin\AzioniCacciaController')->middleware('log');

Route::group(['middleware' => ['admin']], function () {

	Route::post('getUnitaGestioneAjax','Admin\SelectConditionalController@getUnitaGestioneAjax')->name('get_unita_gestione');

  
	Route::post('assegnaCapoSquadra', 'Admin\CacciatoriController@assegnaCapoSquadraAjax')->name('assegna_capo_squadra');  
	Route::resource('cacciatori', 'Admin\CacciatoriController');
  
  
	Route::resource('province', 'Admin\ProvinceController');
	Route::resource('comuni', 'Admin\ComuniController');


	Route::post('salva_coordinate_poligono_ajax','Admin\PoligoniController@salvaCoordinatePoligonoAjax')->name('aggiorna_coordinate');
	Route::post('aggiorna_centro_ajax','Admin\PoligoniController@aggiornaCentroAjax')->name('aggiorna_centro');



	Route::get('readJson','Admin\PoligoniController@readJson')->name('readJson');
	Route::get('readXml','Admin\PoligoniController@readXml')->name('readXml');

});