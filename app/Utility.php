<?php
namespace App;

use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Mail\AzioneCreata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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


		
	 	public static function gestisciComunicazioneReferentiAzione($azione, $tipo_azione="CREATA", $tipo_creazione = "WEB")
	 		{

	 		 Log::channel('sms_log')->info('Azione '.$tipo_azione.' VIA '.$tipo_creazione.' su '. $azione->zone()->count() .' quadranti');
	 		 Log::channel('sms_log')->info('Loop su quadranti '.$azione->zone()->count());

	 		 foreach ($azione->zone as $zona) 
	 		   {
	 		
	 		     Log::channel('sms_log')->info('Quadrante '.$zona->nome);
	 		     
	 		     $referenti_zona_tel = $zona->referenti_notice->pluck('telefono')->toArray();

	 		     // INVIO SMS A TUTTI I REFERENTI DI ZONA
	 		     if(count($referenti_zona_tel))
	 		       {
	 		       
	 		       Log::channel('sms_log')->info('Ci sono '.count($referenti_zona_tel) . ' referenti con telefono su questo quadrante');
	 		       
	 		       // Creo un messaggio leggibile da inviare ai referenti
	 		       $readable_msg = "Gentile referente di zona è stata $tipo_azione un'azione di caccia per il giorno ". $azione->getData() ." dalle ore ". $azione->getDal(). " alle ore ". $azione->getAl() ." nel quadrante $zona->nome";
	 		     
	 		       Self::sendSmsAzione($readable_msg,$referenti_zona_tel);
	 		       
	 		       }
	 		     else 
	 		       {
	 		       Log::channel('sms_log')->info('Nessun referente con telefono');
	 		       }
	 		     // FINE - INVIO SMS A TUTTI I REFERENTI DI ZONA

	 		     $referenti_zona_email = $zona->referenti_notice->pluck('email')->toArray();
	 		     // INVIO MAIL A TUTTI I REFERENTI DI ZONA
	 		     if(count($referenti_zona_email))
	 		       {

	 		       Log::channel('sms_log')->info('Ci sono '.count($referenti_zona_email) . ' referenti con mail su questo quadrante');
	 		       
	 		       // invio una mail ai referenti
	 		       Self::sendMailAzione($azione, $tipo_azione, $zona, $referenti_zona_email);

	 		       }
	 		     else 
	 		       {
	 		       Log::channel('sms_log')->info('Nessun referente con mail');
	 		       }
	 		     //  FINE - INVIO MAIL A TUTTI I REFERENTI DI ZONA

	 		

	 		   }// end foreach zone
	 		}



	 	public static function sendMailAzione($azione, $tipo_azione="CREATA", $zona, $referenti_zona_email)
    {

	    try 
	      {

	      foreach ($referenti_zona_email as $email) 
	        {
	        
	        if(!is_null($email) && $email != '')
	          {
	            try 
	              {
                Mail::to($email)
                ->bcc('luigi@info-alberghi.com')
                ->send(new AzioneCreata($azione, $tipo_azione, $zona));

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

      $twilio_number = env('TWILIO_FROM');
      
      Log::channel('sms_log')->info('Utility::sendSmsAzione -  twilio_number = '.$twilio_number);

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





    public static function getAzioniMappa(&$azioni,&$coordinate_zona, &$item, &$zone_count, &$azioni_di_zona, &$nomi_di_zona, &$coordinate_unita, &$nomi_unita, &$nomi_ug_di_zona, &$coordinate_distretto, &$nomi_distretto, $from, $to)
      {
        // cerco tutte le azioni di questa data 
        $azioni = AzioneCaccia::with('squadra', 'distretto','zone')
                  ->where('dalle','>=',$from)
                  ->where('alle','<=',$to)
                  ->get();

        // trovo tutte le zone facendo un loop su tutte le AZIONI
        $coordinate_zona = [];
        $zone_ids = [];

        $coordinate_unita = [];
        $nomi_unita = [];

        $coordinate_distretto = [];
        $nomi_distretto = [];
                    
        foreach ($azioni as $azione) 
          {
          $zone_azione = $azione->zone;

          foreach ($zone_azione as $zona_azione) 
            {
            if(!in_array($zona_azione->id, $zone_ids))
              $zone_ids[] = $zona_azione->id;
  
            $poligono = $zona_azione->poligono;
            $coordinata_zona = $poligono->coordinate->pluck('long','lat');
            $coordinate_zona[$zona_azione->id] = $coordinata_zona;
      

            // trovo tutte le UG della zona
            foreach ($zona_azione->unita as $unita) 
              {

              $poligono_unita =  $unita->poligono;
              $coordinata_unita = $poligono_unita->coordinate->pluck('long','lat');

              $coordinate_unita[$zona_azione->id][$unita->id] = $coordinata_unita;
              $nomi_unita[$zona_azione->id][$unita->id] = $unita->nome;
             
              $distretto = $unita->distretto;

              if(!is_null($distretto))
                {
                $poligono_distretto = $distretto->poligono;
                $coordinate_distretto[$unita->id] = $poligono_distretto->coordinate->pluck('long','lat');
                $nomi_distretto[$unita->id] = $distretto->nome;
                }
              
              }

            }
          }

          //dd($coordinate_zona, $coordinate_unita, $coordinate_distretto);
              
        $azioni_di_zona = []; 
        $nomi_di_zona = []; 
        $nomi_ug_di_zona = [];
        
        // RAGGRUPPO le mie azioni in base alla zona
        foreach ($azioni as $azione) 
          {
          
          if (!Auth::check() || is_null($azione->note)) 
            {
            $azione->note = '';
            }

          $azione->dalle_alle = $azione->getDalleAlle();
          $azione->nomesquadra = $azione->squadra->nome;
          if (Auth::check()) 
            {
            $azione->caposquadra = $azione->squadra->getNomeCapoSquadra();
            $azione->tel_capo = optional($azione->squadra->getCapoSquadra())->telefono;
            }
          
          $zone_azione = $azione->zone;

          foreach ($zone_azione as $zona_azione) 
            {
            $azioni_di_zona[$zona_azione->id][] = $azione;
            $nomi_di_zona[$zona_azione->id] = $zona_azione->nome;
            $u_nomi = [];
            if(isset($nomi_unita[$zona_azione->id]))
              {
              foreach ($nomi_unita[$zona_azione->id] as $u_id => $u_nome) 
                {
                $u_nomi[] = $u_nome;
                }
              }
            $nomi_ug_di_zona[$zona_azione->id] = implode(',',$u_nomi);
            }
          }

        $azioni = $azioni->keyBy('id')->toArray();         

        // mi serve per centrare la mappa e zoommarla
        $item = Utility::fakeCenterCoords();

        $zone_count = count($zone_ids);
        
      } // end getAzioniMappa

    public static function getAzioniMappaNew(&$azioni,&$coordinate_zona, &$item, &$zone_count, &$azioni_di_zona, &$nomi_di_zona, &$coordinate_unita, &$nomi_unita, &$nomi_ug_di_zona, &$coordinate_distretto, &$nomi_distretto, $from, $to, &$distretto_coo, &$unita_coo)
      {
        // cerco tutte le azioni di questa data 
        $azioni = AzioneCaccia::with('squadra', 'distretto','zone')
                  ->where('dalle','>=',$from)
                  ->where('alle','<=',$to)
                  ->get();

        // trovo tutte le zone facendo un loop su tutte le AZIONI
        $coordinate_zona = [];
        $zone_ids = [];

        $coordinate_unita = [];
        $nomi_unita = [];

        $coordinate_distretto = [];
        $nomi_distretto = [];
                    
        foreach ($azioni as $azione) 
          {
          $zone_azione = $azione->zone;

          foreach ($zone_azione as $zona_azione) 
            {
            if(!in_array($zona_azione->id, $zone_ids))
              $zone_ids[] = $zona_azione->id;
  
            $poligono = $zona_azione->poligono;
            $coordinata_zona = $poligono->coordinate->pluck('long','lat');
            $coordinate_zona[$zona_azione->id] = $coordinata_zona;
      

            // trovo tutte le UG della zona
            foreach ($zona_azione->unita as $unita) 
              {

              $poligono_unita =  $unita->poligono;
              $coordinata_unita = $poligono_unita->coordinate->pluck('long','lat');
              
              if (!isset($unita_coo[$unita->id])) 
                {
                $unita_coo[$unita->id] = $coordinata_unita;
                $nomi_unita[$unita->id] = $unita->nome;
                }
              
              $coordinate_unita[$zona_azione->id][$unita->id] = $unita->id;
             
              $distretto = $unita->distretto;

              if(!is_null($distretto))
                {
                $poligono_distretto = $distretto->poligono;
                
                if(!isset($distretto_coo[$distretto->id])) 
                  {
                  $distretto_coo[$distretto->id] = $poligono_distretto->coordinate->pluck('long','lat');
                  }

                $coordinate_distretto[$unita->id] = $distretto->id;
                $nomi_distretto[$distretto->id] = $distretto->nome;
                }
              
              }

            }
          }

          //dd($coordinate_zona, $coordinate_unita, $coordinate_distretto);
              
        $azioni_di_zona = []; 
        $nomi_di_zona = []; 
        $nomi_ug_di_zona = [];
        
        // RAGGRUPPO le mie azioni in base alla zona
        foreach ($azioni as $azione) 
          {

          $action = new \StdClass();
          
          if (!Auth::check() || is_null($azione->note)) 
            {
            $action->note = '';
            }
          else 
            {
            $action->note = str_replace(["'",'"'], "", $azione->note);
            
            }

          $action->dalle_alle = $azione->getDalleAlle();
          $action->nomesquadra = $azione->squadra->nome;
          if (Auth::check()) 
            {
            $action->caposquadra = $azione->squadra->getNomeCapoSquadra();
            $action->tel_capo = optional($azione->squadra->getCapoSquadra())->telefono;
            }
          
          $zone_azione = $azione->zone;

          foreach ($zone_azione as $zona_azione) 
            {
            $azioni_di_zona[$zona_azione->id][] = $action;
            $nomi_di_zona[$zona_azione->id] = $zona_azione->nome;
            $u_nomi = [];
            if(isset($nomi_unita[$zona_azione->id]))
              {
              foreach ($nomi_unita[$zona_azione->id] as $u_id => $u_nome) 
                {
                $u_nomi[] = $u_nome;
                }
              }
            $nomi_ug_di_zona[$zona_azione->id] = implode(',',$u_nomi);
            }
          }

        $azioni = $azioni->keyBy('id')->toArray();         

        // mi serve per centrare la mappa e zoommarla
        $item = Utility::fakeCenterCoords();

        $zone_count = count($zone_ids);
        
      } // end getAzioniMappaNew






    }
