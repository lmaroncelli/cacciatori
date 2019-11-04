# seeder cacciatori

Per creare un cacciatore Auth/RegisterController.php@register

- prima devo creare l'utente (users)
- poi creo il cacciatore (tblCacciatori) su cui importo la user_id



# cambio relazione

Prima UG (1) ------- (*) Zone 

Adesso UG (*) ------- (*) Zone


- Partendo dall'UG posso selezionare più zone
- Una zona può essere associata a più UG (vincolo stesso distretto ?? Al momento no) 



**Use Case**

Distretto: DG1

UG: unita ID_00001 | unita ID_00006 | unita ID_00007 (figli di DG1)


UG: unita ID_00001 
seleziono come Zone figlie: 1 | 10 | 100


Se vado in modifica della zona 1 posso scegliere un'altra UG oltre alla unita ID_00001 e potrei anche scegliere un'unità, come la unita ID_00011, che appartiene ad un altro distretto (DG3 invece di DG1)


__VINCOLO1__ In EDIT: 
Faccio in modo che al caricamento della zona le unità selezionabili siano solo quelle che hanno lo stesso padre (lo stesso distretto) basandomi sull'unità già associata.
Se tolgo TUTTE le UG: ricarico tutte le UG per la nuova sezione


