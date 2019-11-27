@component('mail::message')

Gentile referente di zona è stata {{$tipo_azione}} un'azione di caccia per 

- giorno {{$azione->getData()}} 
- dalle ore  {{$azione->getDal()}} 
- alle ore  {{$azione->getAl()}} 
- quadrante {{$zona->nome}}


Messaggio automatico da,<br>
{{ config('app.name') }}

@endcomponent
