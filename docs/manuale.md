





# Utenti, Ruoli e permessi


Ogni utente ha associato un ruolo; i ruoli sono:

- admin: può fare qualsiasi operazione all'interno del sistema (non può modificare gli altri utenti pari ruolo)

- cacciatore/capogruppo: ha accesso, solo in visualizzazione, a Distretti, UG, Zone/Particelle, Squadre che si riferiscono solo alla squadra di appartenenza; anche le attività visualizzate sono filtrate per squadra di appartenenza e le attività create posso essere associate solo alla squadra di appartenenza

- cartografo: accede solo a Distretti, UG, Zone/Particelle in visualizzazione, modifica e creazione

- consultatore: sono i profili (tipo polizia, guardia forestale,....) che vedono solo la mappa con le azioni di caccia inserite: con un calendario possono modificare la data di visualizzazione

- admin_ro (Read Only): questo profile vede tutto, ma non può agire in creazione o modifica





# Librerie di terze parti e costi


- Per quanto riguarda le GoogleMaps, qui ci sono i dettagli dei prezzi https://cloud.google.com/maps-platform/?hl=it; al momento danno 200 € ogni mese in regalo e nella tabella, calcolando che l'app utilizza "Dynamic Maps", cioè quella instanziata via javascript, risulta che si possono fare "Fino a 28.000 caricamenti". Se sfori paghi 7 dollari per altri mille caricamenti

- Per quanto rigaurda il servizio di invio SMS qui ci sono i prezzi per i msg in Italiahttps://www.twilio.com/sms/pricing/it 
al momento è $ 0.0883 / message per tutti gli operatori. Poi ci sono anche delle formule più convenienti se cresce il volume dei dati, ma non credo che al momento sia da considerare.



# Inserire azione di caccia tramite SMS

- La Provincia avrà un numero acquistato sulla piattaforma Twilio per ricevere i messaggi
- Ogni numero che invia messaggi al numero Twilio di cui sopra (numeri dei capisquadra), deve essere preventivamente registrato e abilitato sualla stessa piattaforma


Un cacciatore/caposquadra che vuole creare un'azione di caccia via SMS deve comporre un SMS del tipo

<data_azione>#<dalle>#<alle>#<ID Quadrante/>
03/09/2019#05:00#10:00#69

Il sistema verificherà la correttezza dei dati inseriti, in particolare:

- il numero di chi fa la richiesta deve appartenere ad un cacciatore/caposquadra della app
- la data contenga 2 cifre per giorno e mese e 4 per anno (dd/mm/yyyy)
- le ore e i minuti siano in doppia cifra (eventualmente con gli zeri davanti) (hh:mm)
- la validità dell'identificativo dell'unità di gestione e della zona e che siano correttamente legate (la zona sia effettivamente all'interno dell'unità nella applicazione). A questo proposito il sistema mostra nei listing zone/particelle gli ID di entrambe le entità (colonne "ID" e "ID UG")


Il sistema risponderà all'SMS per l'inserimento di un'azione di caccia con un messaggio che tutto è andato a buon fine oppure con un avviso di errore descrittivo del problema.
Successivamente all'invio del SMS da parte di un cacciatore il sistema notificherà in automatico i referenti di Zona; nel caso in cui non fosse possibile notificarli (non ci sono referenti, oppure non hanno un telefono associato), il cacciatore, oltre al messaggio di corretto inserimento dell'azione, riceverebbe un avviso di mancato recapito ai referenti.


E' possibile inviare anche una stringa con __solo i quadranti__:

#69,108

in questo caso la data sarà oggi e il periodo dal al sarà inserito automaticamente dal sistema con i valori adesso e adesso + 3h




