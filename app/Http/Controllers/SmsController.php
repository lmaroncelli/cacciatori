<?php

namespace App\Http\Controllers;

use App\Zona;
use App\Utility;
use Carbon\Carbon;
use App\Cacciatore;
use App\AzioneCaccia;
use App\UnitaGestione;
use Twilio\Rest\Client;
use App\Mail\AzioneCreata;
use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;
use Illuminate\Support\Facades\Mail;

class SmsController extends Controller
{


  private function _sendMail($azione, $zona, $referenti_zona_email)
    {

    try 
      {

      foreach ($referenti_zona_email as $email) 
        {
        
        if(!is_null($email) && $email != '')
          {
            try 
              {
              Mail::to($email)->send(new AzioneCreata($azione, $zona));
              }
            catch (\Exception $e) 
              {
                //log "ERRORE MAIL zona $zona->nome";
              }
          }// if email

        } // for email 
      
      }
    catch (\Exception $e) 
      {
      //log "ERRORE SMS zona $zona_id";        
      }

    }
  
  private function _sendSms($readable_msg,$referenti_zona_tel)
    {
      try 
        {
        $twilio = new Client( env('TWILIO_SID'), env('TWILIO_TOKEN') );
        
        foreach ($referenti_zona_tel as $number) 
          {

            if(!is_null($number) && $number != '')
              {

              try 
                {
                $twilio->messages
                      ->create(
                              $number, // to
                              array(
                                  "body" => $readable_msg,
                                  "from" => env('TWILIO_FROM')
                              )
                      );
                } 
              catch (\Exception $e) 
                {
                //log "ERRORE SMS zona $zona_id";        
                }
              
              } // if number

          } // for numeri
        } 
      catch (\Exception $e) 
        {
        //log "ERRORE SMS zona $zona_id";        
        }
    }


  

  // https://www.twilio.com/docs/voice/twiml
  public function reply(Request $request)
    {

    // Set the content-type to XML to send back TwiML from the PHP Helper Library
    header("content-type: text/xml");
    
    try 
      {
      $body = $request->Body;
      $number = $request->From;
    
      list($data,$da,$a,$zona_id) = explode('#', $body);
      
      $zone_arr = explode(',', $zona_id);
      
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
          

      // inserisco una azione di caccia
      $azione = new AzioneCaccia;

      $azione->dalle = Utility::getCarbonDateTime($dalle);
      $azione->alle = Utility::getCarbonDateTime($alle);
      $azione->squadra_id = $squadra->id;
      $azione->distretto_id = $distretto->id;
      $azione->note = "Inserita via SMS";
      $azione->user_id = $cacciatore->id;

      $azione->save();
      
      $azione->zone()->sync($zone_arr);
      
      
      $msg = "azioni da inserire ".count($zone_arr);
      
      $azioni_scartate = 0;
      foreach ($zone_arr as $zona_id) 
        {
        
        $zona = Zona::find($zona_id);
        
        if (!is_null($zona)) 
          {
          $referenti_zona_tel = $zona->referenti->pluck('telefono')->toArray();
          // INVIO SMS A TUTTI I REFERENTI DI ZONA
          if(count($referenti_zona_tel))
            {

            // Creo un messaggio leggibile da inviare ai referenti
            $readable_msg = "È stata creata un'azione di caccia per il giorno ". $data ." dalle ore ".$da. " alle ore ". $a ." nel quadrante $zona->nome";
          
            $this->_sendSms($readable_msg,$referenti_zona_tel);
            
            }
          // FINE - INVIO SMS A TUTTI I REFERENTI DI ZONA

          $referenti_zona_email = $zona->referenti->pluck('email')->toArray();
          // INVIO MAIL A TUTTI I REFERENTI DI ZONA
          if(count($referenti_zona_email))
            {
            // invio una mail ai referenti
            $this->_sendMail($azione, $zona, $referenti_zona_email);

            }
          //  FINE - INVIO MAIL A TUTTI I REFERENTI DI ZONA

          }
        else 
          {
          $azione->zone()->detach($zona_id);
          $azioni_scartate++;
          }

      
        } // end foreach zona
        
        $msg .= ", scartate ".$azioni_scartate;

        if (!$azioni_scartate) 
          {
          $response_body = "Azione inserita correttamente dal numero ".$number. ": ".$msg;
          } 
        else 
          {
          $response_body = "Azione inserita con errori dal numero ".$number. ": ".$msg;
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
