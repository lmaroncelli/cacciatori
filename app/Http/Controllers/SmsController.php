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
use Illuminate\Support\Facades\Log;
use Twilio\TwiML\MessagingResponse;
use Illuminate\Support\Facades\Mail;

class SmsController extends Controller
{


  // private function _sendMail($azione, $zona, $referenti_zona_email)
  //   {

  //   try 
  //     {

  //     foreach ($referenti_zona_email as $email) 
  //       {
        
  //       if(!is_null($email) && $email != '')
  //         {
  //           try 
  //             {
  //             Mail::to($email)->send(new AzioneCreata($azione, $zona));

  //             Log::channel('sms_log')->info('Invio MAIL al referente con indirizzo '.$email);

  //             }
  //           catch (\Exception $e) 
  //             {
  //               //log "ERRORE MAIL zona $zona->nome";
  //               Log::channel('sms_log')->info('ERRORE Invio MAIL al referente con indirizzo '.$email . ' errore: '.$e->getMessage());
  //             }
  //         }// if email

  //       } // for email 
      
  //     }
  //   catch (\Exception $e) 
  //     {
  //     //log "ERRORE SMS zona $zona_id";     
  //     Log::channel('sms_log')->info('ERRORE Invio MAIL referenti di zona errore: '.$e->getMessage());   
  //     }

  //   }
  
  // private function _sendSms($readable_msg,$referenti_zona_tel)
  //   {
  //     // A Twilio number you own with SMS capabilities (env('TWILIO_FROM') non funziona??!!)

  //     $twilio_number = "+16788418799";
      
  //     try 
  //       {
  //       $twilio = new Client( env('TWILIO_SID'), env('TWILIO_TOKEN') );
        
  //       foreach ($referenti_zona_tel as $number) 
  //         {

  //           if(!is_null($number) && $number != '')
  //             {

  //             try 
  //               {
                
  //               $twilio->messages
  //                     ->create(
  //                             $number, // to
  //                             array(
  //                               "from" => $twilio_number,
  //                               "body" => $readable_msg
  //                             )
  //                     );

  //               Log::channel('sms_log')->info('Invio SMS al referente con numero '.$number);
  //               } 
  //             catch (\Exception $e) 
  //               {
  //               //log "ERRORE SMS zona $zona_id";        
  //               Log::channel('sms_log')->info('ERRORE Invio SMS al referente con numero '.$number . ' errore: '.$e->getMessage());
  //               }
              
  //             } // if number

  //         } // for numeri
  //       } 
  //     catch (\Exception $e) 
  //       {
  //       Log::channel('sms_log')->info('ERRORE Invio SMS referenti di zona errore: '.$e->getMessage());        
  //       }
  //   }


  public function delete(Request $request, $sid)
    {
      $twilio = new Client( env('TWILIO_SID'), env('TWILIO_TOKEN') );

      $twilio->messages($sid)
       ->delete();
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

      Log::channel('sms_log')->info('SMS = '.$body);

      $zone_arr = explode(',', $zona_id);
      
      // dal numero identifico il caposquadra
      $cacciatore = Cacciatore::where('telefono',trim($number))->first();

      if(is_null($cacciatore))
        {
        Log::channel('sms_log')->info('Il numero inserito non fa riferimento ad un cacciatore! '.$number);
        throw new \Exception('Il numero inserito non fa riferimento ad un cacciatore!');
        }

      // verifico se è un caposquadra ed eventualmente prendo la PRIMA 
      $squadra = $cacciatore->squadreACapo->first();

      if(is_null($squadra))
        {
        Log::channel('sms_log')->info('Il numero inserito non fa riferimento ad un caposquadra! '.$number);
        throw new \Exception('Il numero inserito non fa riferimento ad un caposquadra!');
        }

      $distretto = $squadra->distretto;

      
      $dalle = $data.' '.$da;
      $alle = $data.' '.$a;

      // verifico la data
      if (Carbon::createFromFormat('d/m/Y H:i',$dalle) === false || Carbon::createFromFormat('d/m/Y H:i',$alle) === false) 
        {
        Log::channel('sms_log')->info('Errore nelle date!');
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
      
      Log::channel('sms_log')->info('Azione creata su '. count($zone_arr) .' quadranti');

      
      $msg = "quadranti da inserire ".count($zone_arr);
      
      $quadranti_scartati = 0;

      Log::channel('sms_log')->info('Loop su quadranti '.count($zone_arr));

      foreach ($zone_arr as $zona_id) 
        {
        
        $zona = Zona::find($zona_id);
        
        
        if (!is_null($zona)) 
        {
          Log::channel('sms_log')->info('Quadrante '.$zona->nome);
          
          $referenti_zona_tel = $zona->referenti->pluck('telefono')->toArray();

          // INVIO SMS A TUTTI I REFERENTI DI ZONA
          if(count($referenti_zona_tel))
            {
            
            Log::channel('sms_log')->info('Ci sono '.count($referenti_zona_tel) . ' referenti con telefono su questo quadrante');
            
            // Creo un messaggio leggibile da inviare ai referenti
            $readable_msg = "Gentile referente di zona è stata creata un'azione di caccia per il giorno ". $data ." dalle ore ".$da. " alle ore ". $a ." nel quadrante $zona->nome";
          
            Utility::sendSmsAzione($readable_msg,$referenti_zona_tel);
            
            }
          else 
            {
            Log::channel('sms_log')->info('Nessun referente con telefono');
            }
          // FINE - INVIO SMS A TUTTI I REFERENTI DI ZONA

          $referenti_zona_email = $zona->referenti->pluck('email')->toArray();
          // INVIO MAIL A TUTTI I REFERENTI DI ZONA
          if(count($referenti_zona_email))
            {

            Log::channel('sms_log')->info('Ci sono '.count($referenti_zona_email) . ' referenti con mail su questo quadrante');
            
            // invio una mail ai referenti
            Utility::sendMailAzione($azione, $zona, $referenti_zona_email);

            }
          else 
            {
            Log::channel('sms_log')->info('Nessun referente con mail');
            }
          //  FINE - INVIO MAIL A TUTTI I REFERENTI DI ZONA

          }
        else 
          {
          $azione->zone()->detach($zona_id);
          $quadranti_scartati++;
          Log::channel('sms_log')->info('Quadrante id '.$zona_id.' non esiste');

          }

      
        } // end foreach zona
        
        $msg .= ", scartati ".$quadranti_scartati;

        if (!$quadranti_scartati) 
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
