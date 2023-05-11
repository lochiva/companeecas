



## Configuration

Read and edit `config/app.php` and setup the 'Datasources' and any other
configuration relevant for your application.

## Bake template e controller Admin per i plugin
bake del controller se il model è gia presente, in quel caso commentare le relazioni,
per evitare che siano presenti nell'edit
$ bin/cake bake controller Nome_model --plugin nome_plugin --prefix admin
bake del template
$ bin/cake bake template Nome_model --plugin nome_plugin --prefix admin
Dopo aggiungere il nuovo controller nel config/localconfig.php nell'array del controller del
pluginUsed, con nome del controller come chiave.
Estrazione del pot file per le traduzioni
$ bin/cake i18n extract --plugin nome_plugin

Una volta estratto il file pot, copiare le parti necessari e tradotte nel file
"src/Locale/default.pot", aggiungendoli alla fine.
Bisogna svuotare la cache di cake perchè i nuovi file di traduzione vengano caricati.

## Select2 è stato modificato
Select 2  è stato modificato da una pull request per la presenza di un bug sui disabled
delle option. Indirizzo pull request https://github.com/select2/select2/pull/4537 

##installazione del progetto
## clonare il progetto dal repo
## eseguire composer install
## seguire le istruzioni
## configurare config/app.php per la connessione al DB e l'invio delle email
# caricare il file dbScheme.sql nel DB configurato
# eseguire le migrazioni con 
#bin/cake migrations:migrate --no-lock
## accedere con l'utente admin Admin.2023! e cambiare la password
# fine installazione

## aggiornamento del progetto
#aggiornare il codice dal repo con
git pull
#aggiornare il codice con composer
composer install
#eseguire le migrazioni
bin/cake migrations:migrate --no-lock