<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NexmoController extends Controller
{   

    # https://developer.nexmo.com/concepts/guides/webhooks
    # https://developer.nexmo.com/messaging/sms/guides/inbound-sms
    public function reply(Request $request)
      {
      $str = http_build_query($request->all(),'',', ');
      
      Log::channel('sms_log')->info('request = '.$str);
      }
}
