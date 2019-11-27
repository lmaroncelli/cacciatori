@component('mail::message')

"Gentile referente di zona è stata creata un'azione di caccia per il giorno ". {{$azione->getData()}} ." dalle ore ".{{$azione->getDal()}}. " alle ore ". {{$azione->getAl()}} ." nel quadrante ". {{$zona->nome}};


Messaggio automatico da,<br>
{{ config('app.name') }}

@endcomponent
