<?php

namespace App\Http\Controllers;

use App\Zona;
use App\Utility;
use Carbon\Carbon;
use App\Cacciatore;
use App\AzioneCaccia;
use App\UnitaGestione;
use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;

class SmsController extends Controller
{

  // https://www.twilio.com/docs/voice/twiml
  public function reply(Request $request)
    {

    // Set the content-type to XML to send back TwiML from the PHP Helper Library
    header("content-type: text/xml");
    
    try 
      {
      $body = $request->Body;
      $number = $request->From;
    
      list($data,$da,$a,$ug_id,$zona_id) = explode('#', $body);
      
      
      // dal numero identifico il caposquadra
      $cacciatore = Cacciatore::where('telefono',trim($number))->first();

      if(is_null($cacciatore))
        {
        throw new \Exception('Il numero inserito non fa riferimento ad un cacciatore!');
        }

      // verifico se è un caposquadra ed eventualmente prendo la PRIMA 
      $squadra = $cacciatore->squadreACapo->first();

      if(is_null($squadra))
        {
        throw new \Exception('Il numero inserito non fa riferimento ad un caposquadra!');
        }

      $distretto = $squadra->distretto;

      
      $dalle = $data.' '.$da;
      $alle = $data.' '.$a;

      // verifico la data
      if (Carbon::createFromFormat('d/m/Y H:i',$dalle) === false || Carbon::createFromFormat('d/m/Y H:i',$alle) === false) 
        {
        throw new \Exception('Errore nelle date!');
        }
      


      // devo verificare la correttezza di UG e ZONA
      $ug = UnitaGestione::find($ug_id);

      if(is_null($ug) || $ug->distretto_id != $distretto->id)
        {
        throw new \Exception('Unita gestione non valida!');
        }
      else
        {
        
          $zona = Zona::find($zona_id);

          if(is_null($zona) || $zona->unita_gestione_id != $ug->id)
            {
            throw new \Exception('Zona non valida!');
            }
        }

      

      // inserisco una azione di caccia
      $azione = new AzioneCaccia;

      $azione->dalle = Utility::getCarbonDateTime($dalle);
      $azione->alle = Utility::getCarbonDateTime($alle);
      $azione->squadra_id = $squadra->id;
      $azione->distretto_id = $distretto->id;
      $azione->unita_gestione_id = $ug->id;
      $azione->zona_id = $zona->id;
      $azione->note = "Inserita via SMS";
      $azione->user_id = $cacciatore->id;

      $azione->save();

      $response_body = "Azione inserita correttamente dal numero: ".$number;


      $response = new MessagingResponse();

      $response->message(
          $response_body
      );

      echo $response;
      } 
    catch (\Exception $e) 
      {
       // Set the content-type to XML to send back TwiML from the PHP Helper Library
      header("content-type: text/xml");
      
      $response = new MessagingResponse();

      $response->message(
          $e->getMessage()
      );

      echo $response;
      
      }
    
    }
}
