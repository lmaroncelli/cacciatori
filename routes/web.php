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


$router->pattern('documento_id', '[0-9]+');
$router->pattern('utente_id', '[0-9]+');


// pagina esterna
Route::get('/pagina_esterna', 'ExternalController@showMappaAttivita')->name('pagina_esterna');




// la home diventa il mio loginForm
Route::get('/','Auth\LoginController@showLoginForm')->name('login');



Auth::routes();


Route::get('/home', 'HomeController@showMappaAttivita')->name('home');


Route::get('/reply', 'SmsController@reply')->name('reply_sms');
Route::get('/delete/{sid}', 'SmsController@delete')->name('delete_sms');


Route::get('/reply_nexmo', 'NexmoController@reply')->name('reply_nexmo');



Route::resource('distretti', 'Admin\DistrettiController');
Route::resource('utg', 'Admin\UtgController');
Route::resource('squadre', 'Admin\SquadreController');
Route::post('getZonaAjax','Admin\SelectConditionalController@getZonaAjax')->name('get_zona');
Route::resource('zone', 'Admin\ZoneController')/*->middleware('log')*/;
Route::post('getSquadreFromDistrettoAjax','Admin\SelectConditionalController@getSquadreFromDistrettoAjax')->name('get_squadre');
Route::post('showDistrettoZonaAjax','Admin\SelectConditionalController@showDistrettoZonaAjax')->name('show_distretto');

Route::post('RicaricaUgStessoDistrettoAjax','Admin\SelectConditionalController@RicaricaUgStessoDistrettoAjax')->name('ricarica_ug_stesso_distretto');



Route::post('getUtgFromDistrettoAjax','Admin\SelectConditionalController@getUtgFromDistrettoAjax')->name('get_utg');
Route::post('getDistrettoFromSquadraAjax','Admin\SelectConditionalController@getDistrettoFromSquadraAjax')->name('get_distretto');
Route::post('getZoneFromUtgAjax','Admin\SelectConditionalController@getZoneFromUtgAjax')->name('get_zone_form_utg');
Route::post('getZoneFromSquadraAjax','Admin\SelectConditionalController@getZoneFromSquadraAjax')->name('get_zone_from_squadra');


Route::get('reset','Admin\AzioniCacciaController@reset')->name('reset');
Route::any('azioni_search','Admin\AzioniCacciaController@index')->name('azioni_search')/*->middleware('log')*/;
Route::resource('azioni', 'Admin\AzioniCacciaController')/*->middleware('log')*/;

Route::get('admin/documenti', ['uses' => 'Admin\DocumentiController@index', 'as' => 'documenti.index']);

Route::group(['middleware' => ['admin']], function () {

  Route::post('utenti/modifica/{utente_id}', 'Auth\RegisterController@modificaUtente')->name('utenti.modifica');
  
  Route::resource('utenti', 'Admin\UtentiController');

	Route::post('getUnitaGestioneAjax','Admin\SelectConditionalController@getUnitaGestioneAjax')->name('get_unita_gestione');

  
	Route::post('assegnaCapoSquadra', 'Admin\CacciatoriController@assegnaCapoSquadraAjax')->name('assegna_capo_squadra');  
	Route::resource('cacciatori', 'Admin\CacciatoriController');
  
  
	Route::resource('province', 'Admin\ProvinceController');
  Route::resource('comuni', 'Admin\ComuniController');
  

  Route::post('assegnaReferentiZona', 'Admin\ReferentiController@assegnaReferentiZonaAjax')->name('assegna_referenti_zona');  
  Route::post('aggiornaReferentiZona', 'Admin\ReferentiController@aggiornaReferentiZonaAjax')->name('aggiorna_referenti_zona');  
  
	Route::resource('referenti', 'Admin\ReferentiController');


	Route::post('salva_coordinate_poligono_ajax','Admin\PoligoniController@salvaCoordinatePoligonoAjax')->name('aggiorna_coordinate');
	Route::post('aggiorna_centro_ajax','Admin\PoligoniController@aggiornaCentroAjax')->name('aggiorna_centro');



	Route::get('readJson','Admin\PoligoniController@readJson')->name('readJson');
  Route::get('readXml','Admin\PoligoniController@readXml')->name('readXml');
  


  Route::get('admin/documenti/upload', ['uses' => 'Admin\DocumentiController@formUpload', 'as' => 'documenti.form-upload']);
  Route::post('admin/documenti/upload', ['uses' => 'Admin\DocumentiController@upload', 'as' => 'documenti.upload']);
  Route::get('admin/documenti/modifica/{documento_id}', ['uses' => 'Admin\DocumentiController@modifica', 'as' => 'documenti.modifica']);
  Route::post('admin/documenti/aggiorna/{documento_id}', ['uses' => 'Admin\DocumentiController@aggiorna', 'as' => 'documenti.aggiorna']);
  Route::post('admin/documenti/elimina/{documento_id}', ['uses' => 'Admin\DocumentiController@elimina', 'as' => 'documenti.elimina']);

});
