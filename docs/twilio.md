

account
lmaroncelli@gmail.com
Labietta610?!?

ho verificato il mio numero personale (per le incoming calls on Twilio)


+1209 325 0779 
You can configure this phone number to send and receive calls and messages.



composer require twilio/sdk

aggiungere file .env

TWILIO_SID=AC7576f0befa61d73c8bf20315da31ed8a
TWILIO_TOKEN=84b5fc8a254787a55ee74af215814615
TWILIO_FROM=+12093250779



Most Twilio services use webhooks to communicate with your application. For example, when Twilio receives an SMS it reaches out to a specific URL in your application and you respond with instructions on how to handle a response. 

__Quando un utente invia un SMS al mio numero Twilio, Twilio invia una WEBHOOK HTTP REQUEST alla mia application web__



siccome in locale creo una pagina che risponde ad un SMS, per essere raggiunta da fuori utilizzo ngrok

ngrok http 8000

ngrok by @inconshreveable                                                             (Ctrl+C to quit)
                                                                                                      
Session Status                online                                                                  
Account                       lmaroncelli (Plan: Free)                                                
Version                       2.3.34                                                                  
Region                        United States (us)                                                      
Web Interface                 http://127.0.0.1:4040                                                   
Forwarding                    http://3a1a1c26.ngrok.io -> http://localhost:8000                       
Forwarding                    https://3a1a1c26.ngrok.io -> http://localhost:8000                      
                                                                                                      
Connections                   ttl     opn     rt1     rt5     p50     p90                             
                              0       0       0.00    0.00    0.00    0.00                            
                                                                                

quindi il mio webhook è http://3a1a1c26.ngrok.io/reply

lo setto in  TRIAL # Phone Numbers / Manage Numbers / Active Numbers /






devo ritornare twiml, cioè un XML con tag proprietari di Twilio







