

account
vedi .env


dentro Programmable SMS > Tools > TwiML Apps (https://www.twilio.com/console/sms/runtime/twiml-apps)

creo il mio web hook:


nome: caccia_webhook
Voice: Unconfigured
MessagingURL: https://cacciatori.app-ggv.com/reply


Acquisto un  SMS-enabled phone number

(562) 382-8121
$1.00 per month

Once you buy this phone number, you will be charged $1.00/month to use (562) 382-8121. Learn More



Congratulations
(562) 382-8121$1.00 per month
Once you buy this phone number, you will be charged $1.00/month to use (562) 382-8121. Learn More

Number Capabilities
Voice
This number can receive incoming calls and make outgoing calls.

Fax
This number can send and receive facsimiles.

SMS
This number can send and receive text messages to and from mobile numbers.

MMS
This number can send and receive multi media messages to and from mobile numbers.

About Regulatory Requirements
The regulatory requirements for this number may differ from the information being requested above. As a user of the number, it is your responsibility to collect the required documentation, listed here. We are working to add functionality to upload all compliance documents and will ask you to retroactively provide these in due course.

 
You have purchased
(562) 382-8121
Voice
This number can receive incoming calls and make outgoing calls.

Fax
This number can send and receive facsimiles.

SMS
This number can send and receive text messages to and from mobile numbers.

MMS
This number can send and receive multi media messages to and from mobile numbers.



A questo punto dalla pagina del numero vado a definire il webhook


+15623828121


Messaging
CONFIGURE WITH
TwiML App
TWIML APP
caccia_webhook




da Phone numbers > Verified caller IDS (https://www.twilio.com/console/phone-numbers/verified)

aggiungo il mio numero e dovrò aggiungere tutti quelli che manderanno SMS al numero



quindi nel .env 

TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=+15623828121



PROVA:

- devo associare il mio numero a quello di un caposquadra 
- la squadra di cui sono capo deve essere associata a quadranti



27/11/2019#05:00#10:00#626,628

Questo SMS inserisce una azione di caccia nei quadranti 1 e 10