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
use App\Squadra;
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
      
      
      /**
       * 
       * La stringa può essere di 2 tipi:
       * 
       * 1) 27/11/2019#05:00#10:00#<ID SQUADRA/>#<NOME QUADRANTE> 
       * 
       * 2) #<ID SQUADRA/>#<NOME QUADRANTE>
       * 
       * 
       * Se comincia con # allora ho solo elenco dei quadranti e devo settare 
       * 
       * data: adesso
       * 
       * dal: adesso
       * 
       * al: adesso + 3 h
       * 
       */

      Log::channel('sms_log')->info('SMS = '.$body);
      
      if ($body[0] == '#') 
        {
        $now = Carbon::now('Europe/Rome');
        $data = $now->format('d/m/Y');
        $da =  $now->format('H:i');
        $a =  $now->addHours(3)->format('H:i');
        $small_string = ltrim($body, $body[0]);
        list($squadra_id,$zona_nome) = explode('#', $small_string);
        } 
      else 
        {
        list($data,$da,$a,$squadra_id,$zona_nome) = explode('#', $body);
        }  
      

      $zone_arr = explode(',', $zona_nome);


      Log::channel('sms_log')->info('data = '.$data);
      Log::channel('sms_log')->info('da = '.$da);
      Log::channel('sms_log')->info('a = '.$a);
      Log::channel('sms_log')->info('squadra_id = '.$squadra_id);
      Log::channel('sms_log')->info('zona = '.$zona_nome);

      
      // dal numero identifico il caposquadra
      $cacciatore = Cacciatore::where('telefono',trim($number))->first();

      if(is_null($cacciatore))
        {
        Log::channel('sms_log')->info('Il numero inserito non fa riferimento ad un cacciatore! '.$number);
        throw new \Exception('Il numero inserito non fa riferimento ad un cacciatore!');
        }

      // verifico se è un caposquadra ed eventualmente prendo la PRIMA 
      //$squadra = $cacciatore->squadreACapo->first();
      
      // la squadra viene passata con il codice
      $squadra = Squadra::find($squadra_id);


      if(is_null($squadra))
        {
        Log::channel('sms_log')->info('Il codice della squadra non esiste! '.$squadra_id);
        throw new \Exception('Il codice della squadra non esiste!');
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
      
      //$azione->zone()->sync($zone_arr);
      
      Log::channel('sms_log')->info('Azione creata VIA SMS ancora senza quadranti');

      
      $msg = "quadranti da inserire ".count($zone_arr);
      
      $quadranti_scartati = 0;

      Log::channel('sms_log')->info('Loop su quadranti '.count($zone_arr));

      foreach ($zone_arr as $zona_nome) 
        {
        
        $zona = Zona::where('nome',$zona_nome)->first();
        
        if (!is_null($zona)) 
        {
          $zona_id = $zona->id;
          
          $azione->zone()->attach($zona_id);

          Log::channel('sms_log')->info('Quadrante '.$zona->nome.' assegnato alla azione');
          
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
            
            $msg_azione_tipo = "CREATA";
            // invio una mail ai referenti
            Utility::sendMailAzione($azione, $msg_azione_tipo, $zona, $referenti_zona_email);

            }
          else 
            {
            Log::channel('sms_log')->info('Nessun referente con mail');
            }
          //  FINE - INVIO MAIL A TUTTI I REFERENTI DI ZONA

          }
        else 
          {

          $quadranti_scartati++;
          Log::channel('sms_log')->info('Quadrante '.$zona_nome.' non esiste');

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
