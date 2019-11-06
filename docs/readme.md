# seeder cacciatori

Per creare un cacciatore Auth/RegisterController.php@register

- prima devo creare l'utente (users)
- poi creo il cacciatore (tblCacciatori) su cui importo la user_id



# cambio relazione

Prima UG (1) ------- (*) Zone 

Adesso UG (*) ------- (*) Zone


- Partendo dall'UG posso selezionare più zone
- Una zona può essere associata a più UG (vincolo stesso distretto ?? Al momento no) 



**Use Case: Zone**

Distretto: DG1

UG: unita ID_00001 | unita ID_00006 | unita ID_00007 (figli di DG1)


UG: unita ID_00001 
seleziono come Zone figlie: 1 | 10 | 100


Se vado in modifica della zona 1 posso scegliere un'altra UG oltre alla unita ID_00001 e potrei anche scegliere un'unità, come la unita ID_00011, che appartiene ad un altro distretto (DG3 invece di DG1)


__VINCOLO1__ In EDIT: 
Faccio in modo che al caricamento della zona le unità selezionabili siano solo quelle che hanno lo stesso padre (lo stesso distretto) basandomi sull'unità già associata.
Se tolgo TUTTE le UG: ricarico tutte le UG per la nuova sezione


**BUG RISOLTO**

Se prendo una zona che non ha UG, dopo che ho assegnato la prima ed ho aggiornato la lista delle UG, non me ne fa più aggiungere
 $(document).on('change', '#unita_gestione_id', function() { 
   triggera prima che selezioni e non fa inserire




**Use Case: Squadre**

OK


**Use Case: Attività**

Nel momento in cui seleziono la Squadra che farà la caccia ottengo il DISTRETTO corrispondente (ajax call get_distretto)
e subito dopo faccio un'altra chiamata 

caricaUtg(distretto_id);

per avere le Unità di distretto da selezionare (getUtgFromDistrettoAjax)


OK


**Use Case: Distretti ordinamento**

OK



**Use Case: Quadranti ordinamento**

OK


**Use Case: Azioni Quadranti molti-a-molti**

Nel momento in cui seleziono la Squadra che farà la caccia ottengo il DISTRETTO corrispondente (ajax call get_distretto)
e subito dopo faccio un'altra chiamata 

caricaUtg(distretto_id);

per avere le Unità di distretto da selezionare (getUtgFromDistrettoAjax)

@Gupi 06/11/19 - i quadranti sono svincolati da tutto perché potrei dover inserire azioni per quadranti che sono anche in distretti differenti

nell'inserimento di un'azione, quando seleziono un UG

 $("#utg").change(function(){

faccio una chiamata AJAX per prendere le zone corrispondenti

jQuery.ajax({
    url: '{{ route('get_zone_form_utg') }}',

Invece adesso 

// Prendo tutte lezone della squadra, MA LA SQUADRA FA PARTE DI 1! distretto quindi non posso avere zone su 2 distretti
// $zone = Zona::getAll()->pluck('nome','id')->toArray();

OPPURE 

// prendo SEMPRE E COMUNQUE TUTTE le zone 
$zone = Zona::orderBy('nome')->pluck('nome','id')->toArray();