


## Se si intende aggiungere del codice 
### Bake template e controller Admin per i plugin
bake del controller se il model è gia presente, in quel caso commentare le relazioni,
per evitare che siano presenti nell'edit  
$ bin/cake bake controller Nome_model --plugin nome_plugin --prefix admin  
bake del template  
$ bin/cake bake template Nome_model --plugin nome_plugin --prefix admin  
Dopo aggiungere il nuovo controller nel config/localconfig.php nell'array del controller del
pluginUsed, con nome del controller come chiave.  
### se si intende usare il sistema delle traduzione
Estrazione del pot file per le traduzioni  
$ bin/cake i18n extract --plugin nome_plugin  

Una volta estratto il file pot, copiare le parti necessari e tradotte nel file  
"src/Locale/default.pot", aggiungendoli alla fine.  
Bisogna svuotare la cache di cake perchè i nuovi file di traduzione vengano caricati.  

## Select2 è stato modificato  
Select 2  è stato modificato da una pull request per la presenza di un bug sui disabled
delle option. Indirizzo pull request https://github.com/select2/select2/pull/4537 

##  caratteristiche del server: 
php 7.4  
composer 1  
mysql o mariaDB impostando sql_mode= ''  

## installazione del progetto
 * clonare il progetto dal repo  
 * eseguire composer install  
 * seguire le istruzioni  
 * configurare config/app.php per la connessione al DB e l'invio delle email  
 * caricare il file dbScheme.sql nel DB configurato  
 * eseguire le migrazioni con  
 * bin/cake migrations:migrate --no-lock  

## primo accesso
accedere con l'utente admin Admin.2023! e cambiare la password  

## inserire gli script di manutenzione
### per cancellare i file temporanei vecchi degli zip dei consuntivi
si può inserire in crontab 
``` 
5 4 * * * find /DIR--DI-INSTALLAZIONE/FILES/statements/*.zip -mtime +1  -exec rm {} \;  
``` 
sostituendo DIR--DI-INSTALLAZIONE con il percorso del file system in cui è installato il sofware  
### per cancellare i dati vecchi dalle tabelle action_log e access_log
si può usare questo codice da inserire in un file sh ed eseguirlo via cron: 
 ```  
#!/bin/bash  
UU="DBUSER"  
PP="DBPASS"  
DB="DBNAME"  

#cancello  i vecchi log  
echo " delete FROM  access_log WHERE  created < (NOW() - INTERVAL 3 MONTH ) " |  mysql -u ${UU} -p${PP} ${DB}  
echo " delete FROM  action_log WHERE  created < (NOW() - INTERVAL 3 MONTH ) " |  mysql -u ${UU} -p${PP} ${DB}  
``` 

## scaricare gli aggiornamenti per mantenere il software aggiornato
>Si può usare uno script da eseguire al bisogno

 ```  

#aggiornare il codice dal repo con
git pull  

#aggiornare il codice con composer
composer install  

#eseguire le migrazioni
bin/cake migrations:migrate --no-lock  

 ```  

## License
Il software è licenziato GNU General Public License v3.0 come descritto nel file LICENSE.txt
