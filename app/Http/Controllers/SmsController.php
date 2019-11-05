<?php

namespace App\Http\Controllers;

use App\Zona;
use App\Utility;
use Carbon\Carbon;
use App\Cacciatore;
use App\AzioneCaccia;
use App\UnitaGestione;
use Twilio\Rest\Client;
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

      if(is_null($ug))
        {
        throw new \Exception('Unita gestione non valida!');
        }
      else
        {
        
          $zona = Zona::find($zona_id);

          if(is_null($zona) || !in_array($zona->unita()->pluck('tblUnitaGestione.id')->toArray(), $ug->id))
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

      
      $avviso_referenti = "";


      // Creo un messaggio leggibile da inviare ai referenti
      $readable_msg = "È stata creata un'azione di caccia per il giorno ". $data ." dalle ore ".$da. " alle ore ". $a ." nella $zona->tipo $zona->nome";
      
      $referenti_zona_tel = $zona->referenti->pluck('telefono')->toArray();
      
      if(count($referenti_zona_tel))
        {
          
        try 
          {
          $twilio = new Client( env('TWILIO_SID'), env('TWILIO_TOKEN') );
          
          foreach ($referenti_zona_tel as $number) 
            {
              $twilio->messages
                    ->create(
                            $number, // to
                            array(
                                "body" => $readable_msg,
                                "from" => env('TWILIO_FROM')
                            )
                    );
            } // for numeri
          } 
        catch (\Exception $e) 
          {
          $avviso_referenti = " ATTENZIONE: ERRORE invio SMS referenti di zona!!";        
          }

        } // if telefoni
      else 
        {
        $avviso_referenti = " ATTENZIONE: Nessun numero da contattare per i referenti di zona";        
        }

      $response_body = "Azione inserita correttamente dal numero: ".$number;

      if(!empty($avviso_referenti))
        {
        $response_body .= $avviso_referenti;
        }

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
