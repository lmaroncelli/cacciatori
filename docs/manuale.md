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



