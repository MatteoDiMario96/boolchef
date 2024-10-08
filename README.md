# Boolchef Web App

Una piattaforma multi-page che permette agli utenti di prenotare uno chef a domicilio. Il sito presenta una dashboard per gli chef e varie funzionalità per aiutare gli utenti a scegliere lo chef più adatto alle loro esigenze, basate su specializzazioni, recensioni e voti.

## Funzionalità Principali

### Sito Pubblico
1. **Pagina Homepage**: Informazioni sulla nostra missione e sulla qualità dei servizi offerti, con una lista degli chef sponsorizzati.
2. **Chef Sponsorizzati**: Gli chef con una sponsorizzazione attiva vengono visualizzati prima degli altri nelle ricerche.
3. **Ricerca Chef**:
    - **Home**: Possibilità di cercare gli chef per specializzazione (es. Italiana, Giapponese, ecc.).
    - **Pagina dedicata alla ricerca**: Filtro degli chef per specializzazione, voto medio e recensioni.
4. **Dettagli Chef**: Visualizzazione della scheda completa dello chef, con possibilità di:
    - Inviare messaggi diretti.
    - Lasciare una recensione.
    - Assegnare un voto.

### Dashboard Chef
1. **Gestione Messaggi e Recensioni**: Lo chef può visualizzare i messaggi ricevuti dagli utenti e leggere le recensioni.
2. **Sponsorizzazione**: Gli chef possono attivare una sponsorizzazione per aumentare la visibilità nel sito.
3. **Modifica Profilo**: Gli chef possono aggiornare le informazioni del proprio profilo.

## Architettura

### Frontend
- **Vue.js**: Utilizzato per la creazione dell'interfaccia utente dinamica.
- **Sass**: Utilizzato per la gestione avanzata degli stili CSS.
- **API**: Le interazioni tra frontend e backend, come la ricerca degli chef, l'invio di messaggi e recensioni, avvengono tramite chiamate API.

### Backend
- **Laravel**: Il backend è sviluppato con Laravel. La gestione dei dati, come la sponsorizzazione e le recensioni, avviene tramite il framework.
- **JavaScript**: Utilizzato per le validazioni all'interno delle funzionalità di Laravel.
- **Database**: Gestito tramite Laravel migrations e seeder (non è stato utilizzato MySQL).

### Dashboard Chef
- **Laravel**: La dashboard è interamente gestita tramite Laravel con alcune funzionalità JavaScript per l'interattività.

## Tecnologie Utilizzate
- **Frontend**: Vue.js, Sass
- **Backend**: Laravel, JavaScript (validazioni)
- **Database**: Gestito tramite Laravel migrations e seeder
- **API**: Laravel API per la comunicazione tra frontend e backend

## Installazione

1. Clonare il repository del **Frontend**:
   - Vai su [vue-boolchef](https://github.com/MatteoDiMario96/vue-boolchef) e clicca sul pulsante **Code**.
   - Copia l'URL del repository.
   - Esegui il comando per clonare il repository nel tuo terminale.

2. Clonare il repository del **Backend**:
   - Vai su [boolchef](https://github.com/MatteoDiMario96/boolchef) e clicca sul pulsante **Code**.
   - Copia l'URL del repository.
   - Esegui il comando per clonare il repository nel tuo terminale.

3. Installare le dipendenze per il **Frontend**:
   - Accedi alla cartella del progetto frontend.
   - Esegui il comando per installare le dipendenze.

4. Installare le dipendenze per il **Backend**:
   - Accedi alla cartella del progetto backend.
   - Esegui il comando per installare le dipendenze.

5. Configurare il file `.env` con i dettagli del database e altre variabili necessarie.

6. Eseguire le migrazioni e il seeding del database nel **Backend**:
   - Esegui il comando per eseguire le migrazioni e il seeding.

7. Avviare il server locale per il **Backend**:
   - Esegui il comando per avviare il server.

8. Tornare alla cartella del **Frontend** e avviare il server:
   - Accedi nuovamente alla cartella del progetto frontend.
   - Esegui il comando per avviare il server.
