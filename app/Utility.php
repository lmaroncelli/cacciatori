<?php
namespace App;

use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Mail\AzioneCreata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
/**
 *
 */
class Utility extends Model
{

  #IP a cui sono visibili i dettagli di debug (query ed altre info)
	private static $ip_debug = ['127.0.0.1', '2.224.168.43'];

	//////////////////////////////////////////////////////
	// coordinate di defaul per il poligono di una zona //
	//////////////////////////////////////////////////////
	private static $fake_coords = [
            [
                'lat' => '44.066493' , 'long' => '12.550754'
            ],
            [
                'lat' => '44.069207' , 'long' => '12.592095'
            ],
            [
                'lat' => '44.044657' , 'long' => '12.597757'
            ],
            [
                'lat' => '44.048605' , 'long' => '12.535472'
            ],
            
        ];

	
	private static $fake_center_map = [

                'lat' => '43.88684115537241' , 'long' => '12.686753037826975', 'zoom' => '13'
        ];  

  private static $tipoZona = ['zona' => 'Zona di braccata', 'particella' => 'Particella di girata'];
  
  private static $dipartimentoReferente = ['Carabinieri' => 'Carabinieri', 'Forestale' => 'Forestale', 'Municipale' => 'Municipale', 'Polizia' => 'Polizia'];


  

	/**
	 * Prende l'id del visitatore
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	 
	public static function get_client_ip()
	{
		
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	        
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	        
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	        
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	        
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	       
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	        
	    else
	        $ipaddress = 'UNKNOWN';
	        
	    return $ipaddress;
			
	}


	public static function diff_dalle_alle(Carbon $dalle, Carbon $alle)
		{
		$diff = $dalle->diff($alle)->format('%H:%i');
		list($h, $m) = explode(':', $diff);
		if($m == '0')
			{
			$diff .= '0';
			}
		return $diff;
		}


	/**
	 * Accetta una strina nel formato dd/mm/yyyy e la trasforma in un oggetto data Carbon; se la stringa è vuota o malformata restituisce l'oggetto Carbon da $y=0-$m=0-$d=0.
	 * 
	 * @access public
	 * @static
	 * @param string $data_str (default: "")
	 * @return void
	 */
	 
	public static function getCarbonDate($data_str = "")
	{
		try {

			$data_str = trim($data_str);
			if ($data_str == '') {
				$data_carbon = Carbon::createFromDate(0, 0, 0);
			}
			else {
				list($d, $m, $y) = explode('/', $data_str);
				$data_carbon = Carbon::createFromDate($y, $m, $d);
			}


			return $data_carbon;

		} catch (\Exception $e) {

			return Carbon::now();

		}

	}

	/**
	 * Accetta una strina nel formato dd/mm/yyyy H:i e la trasforma in un oggetto data Carbon; se la stringa è vuota o malformata restituisce l'oggetto Carbon da $y=0-$m=0-$d=0.
	 * Carbon::createFromFormat('Y-m-d H', '1975-05-21 22')
	 * @access public
	 * @static
	 * @param string $data_str (default: "")
	 * @return void
	 */
	
	public static function getCarbonDateTime($data_str = "")
	{
		try {

			$data_str = trim($data_str);
			if ($data_str == '') {
				$data_carbon = Carbon::now();
			}
			else {
				list($data, $time) = explode(' ', $data_str);

				list($d, $m, $y) = explode('/', $data);
				list($h, $min) = explode(':', $time);

				$data_carbon = Carbon::createFromFormat('Y-m-d H i', "$y-$m-$d $h $min");
			}


			return $data_carbon;

		} catch (\Exception $e) {

			return Carbon::now();

		}

	}


	public static function createQueryStringSearch($request)
		{
		$query_array = [
		   'ricerca_campo' => $request->get('ricerca_campo'),
		    'q' => $request->get('q'),
		    'cerca_dal' => $request->get('cerca_dal'),
		    'cerca_al' => $request->get('cerca_al'),
		    'associazione_id' => $request->get('associazione_id'),
		    'no_eliminati' => $request->get('no_eliminati'),
		    'anno_filtro' => $request->get('anno_filtro'),
		    ];

		$query_id = DB::table('tblQueryString')->insertGetId(
		      ['query_string' => http_build_query($query_array)]
		      );

		return $query_id;
		
		} 

	public static function addQueryStringToRequest($query_id,&$request)
		{
			$query = DB::table('tblQueryString')->where('id', $query_id)->first();


			$qs_arr = [];

			if (!is_null($query))
			  {
			  parse_str($query->query_string, $qs_arr);
			  }

			$request->request->add($qs_arr);
		}



	public static function getGoogleApiKey() 
		{ 
		return env('GOOGLE_MAPS_GEOCODING_API_KEY'); 
		}



	public static function iPDebug()
	{
		return self::$ip_debug;
	}



	public static function fakeCoords()
	{
		return self::$fake_coords;
	}

	public static function fakeCenterCoords()
	{
		$item = new \stdClass;
		$item->center_lat = self::$fake_center_map['lat'];
		$item->center_long = self::$fake_center_map['long'];
		$item->zoom = self::$fake_center_map['zoom'];
		return $item;

	}


	





	public static function isIpDebug(Request $request)
	{
    $ip = $request->ip();
    return in_array($ip, self::$ip_debug) && env('APP_ENV') !== 'production';
	}




  public static function getTipoZona()
  {
    return self::$tipoZona;
  }


    public static function getDipartimentoReferente()
  {
    return self::$dipartimentoReferente;
  }





	 public static function getHoursForView($total_minutes)
	 	{

	 	if (!is_numeric($total_minutes)) 
	 		{
	 		return $total_minutes;
	 		}

	 	$hours = intval($total_minutes/60);

	 	if ($total_minutes%60 == 0) 
	 	  {
	 	  $minutes = '';  
	 	  } 
	 	else 
	 	  {
	 	  $minutes = ' : '. $total_minutes%60;  
	 	  }
	 	

	 	return $hours . $minutes;
	 	}




	 	public static function sendMailAzione($azione, $zona, $referenti_zona_email)
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

	              Log::channel('sms_log')->info('Invio MAIL al referente con indirizzo '.$email);

	              }
	            catch (\Exception $e) 
	              {
	                //log "ERRORE MAIL zona $zona->nome";
	                Log::channel('sms_log')->info('ERRORE Invio MAIL al referente con indirizzo '.$email . ' errore: '.$e->getMessage());
	              }
	          }// if email

	        } // for email 
	      
	      }
	    catch (\Exception $e) 
	      {
	      //log "ERRORE SMS zona $zona_id";     
	      Log::channel('sms_log')->info('ERRORE Invio MAIL referenti di zona errore: '.$e->getMessage());   
	      }

    }



   	public static function  sendSmsAzione($readable_msg,$referenti_zona_tel)
    {
      // A Twilio number you own with SMS capabilities (env('TWILIO_FROM') non funziona??!!)

      $twilio_number = "+16788418799";
      
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
                                "from" => $twilio_number,
                                "body" => $readable_msg
                              )
                      );

                Log::channel('sms_log')->info('Invio SMS al referente con numero '.$number);
                } 
              catch (\Exception $e) 
                {
                //log "ERRORE SMS zona $zona_id";        
                Log::channel('sms_log')->info('ERRORE Invio SMS al referente con numero '.$number . ' errore: '.$e->getMessage());
                }
              
              } // if number

          } // for numeri
        } 
      catch (\Exception $e) 
        {
        Log::channel('sms_log')->info('ERRORE Invio SMS referenti di zona errore: '.$e->getMessage());        
        }
    }



}
