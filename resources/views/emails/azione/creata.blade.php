@component('mail::message')

"È stata creata un'azione di caccia per il giorno ". $data ." dalle ore ".$da. " alle ore ". $a ." nel quadrante $zona->nome";


Messaggio automatico da,<br>
{{ config('app.name') }}

@endcomponent
