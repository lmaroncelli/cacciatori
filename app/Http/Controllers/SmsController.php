<?php

namespace App\Http\Controllers;

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
    
      list($data,$dal,$al,$ug,$zona) = explode('#', $body);


      $response_body = "numero:$number, data:$data, dal:$dal, al:$al, ug:$ug, zona:$zona";

      $response = new MessagingResponse();

      $response->message(
          $response_body
      );

      echo $response;
      } 
    catch (\Exception $e) 
      {
      echo "<Response>
            <Say>Errore!</Say>
          </Response>";
      }
    
    }
}
