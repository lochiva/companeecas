<?php
namespace Crediti\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * CsvHandler component
 *
 * @author Rafael Esposito
 */
class CsvHandlerComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

   /**
    * Metodo checkCsv
    *
    * Controllo il file csv facendo i check opportuni: tra cui la presenza dei campi
    * passati con l'array $pass nella prima riga dei file; la presenza dell'azineda
    * in database con il codice sispac del file, e la presenza di tutti i campi
    * necessari in ogni riga. I campi passati possono essere in ordine qualsiasi e
    * in quantità variabile. Formatti i campi numeri per prepararli all'inserimento
    * in database. Aggiungo i giorni impostati in $pass per calcolare la data della
    * scadenza del credito.
    *
    * @param array $pass Dati della richiesta post
    * @return mixed Array contenente gli errori e gli eventuali dati risultanti o False
    */
    public function checkCsv($pass)
    {

      $campi=$pass['campi'];

      $numCampi = count($campi);
      $opz[2] = 'type:date';
      //$opz=[];

      $cols = array();
      $errors = array();
      $aziendeTable = TableRegistry::get('Crediti.Aziende');


      $csvFile = file($pass['file']['tmp_name']);

      // Controllo se la lettura del file sia andata a buon fine, altrimenti ritorno false
      if($csvFile == false)
        return false;

      $data = [];

      $first= true;
      $azienda = [];
      $azienda['cod_sispac'] = '';
      $azienda['id'] = '';
      $numLinea = 0;

      foreach ($csvFile as $line) {
        $numLinea++;
        $error = false;

        $errorString = '';
        // Nel caso non sia la prima interazione
        if(!$first){
            // prendo una linea dal array del csv

            $row = str_getcsv($line,$pass['separatore']);

            /* Ciclo sul numero dei campi , e predo gli appositi valori dal csv e se non sono vuoti
            *  li inserisco nel risultato
            */
            for($i=0; $i<$numCampi; $i++){
              if(empty($row[$cols[$i]])){

                $errorString .= " Errore: campo \"".$campi[$i]."\" mancante!";
                $error = true;
              }
              // In caso di valore numerico tolgo il punto utilizzato per le migliaia
              // ps: ho dovuto usare il preg_match, perchè un valore può avere contemporaneamnete un punto e una virgola
              if(preg_match("/^[\d\.\,]+$/",$row[$cols[$i]]) == 1){
                 $res[$i] = str_replace('.', '', $row[$cols[$i]]);
              }else{
                $res[$i] = $row[$cols[$i]];
              }
              // In caso di valore numerico sostituisco la virgola con il punto
              if(preg_match("/^[\d\,]+$/",$res[$i]) == 1){
                $res[$i] = str_replace(',', '.', $res[$i]);
              }
            }

            // Controllo le opzioni che ho impostato
            foreach($opz as $key => $val){
                if($val == 'type:date'){
                  $res[$key] = date('Y-m-d',strtotime(str_replace("/","-",$res[$key])));

                  $time = Time::createFromFormat(
                        'Y-m-d',
                        $res[$key]
                    );
                  $time->addDays($pass['giorni']);
                  $res[$i++] = $time->i18nFormat('yyyy-MM-dd');

                }elseif(strcasecmp($res[$key], $val ) != 0){

                  $errorString .= " Errore: \"".$campi[$key]."\" diverso da ".$val." !";
                  $error = true;
                }

            }


            // Cerco nel database se è presente l'azienda con il codice sispac letto
            if(!$error){
              $res[0] = $pass['prefix'].$res[0];

              if($res[0] == $azienda['cod_sispac']){
                $res[$i++] = $azienda['id'];

              }else{
                //$azienda = $aziendeTable->find()->where(['cod_sispac' => $this->request->data['prefix'].$res[0] ])->first();
                $azienda = $aziendeTable->find()->select(['id','cod_sispac'])->where(['cod_sispac' => $res[0] ])->first();
                //$azienda['id'] = 54155;
                if($azienda != null){
                  $res[$i++] = $azienda['id'];
                }else{
                  $errorString .= " Errore: azienda non trovata in database!";
                  $error = true;
                }

              }
            }
              /* Se non ho avuto nessun error fino a questo momento scrivo il dato nel risultato,
              *  altrimenti scrivo nell'array dei errori con indece corrispondente al numero di linea
              *  del file dove è presente l'errore.
              */
            if(!$error){
              $data[] = $res;
            }else{
              $errors[$numLinea] = $errorString.' ----> <i>'.$line.'</i>';
            }

          // Nel caso sia la prima interazione
        }else{
            // prendo la prima linea del csv e la trasformo in un array
            $row  = str_getcsv($line,$pass['separatore']);

            /* Cerco se siano presenti i campi che ho impostato, se si inserisco
            *  il valore della chiava del campo nell'array cols, in modo da avare
            *  gli indici dei campi di cui ho bisogno
            */
            $colsFind= 0;

            foreach( $campi as $col){
              if(in_array($col,$row )){
                  $cols[]=array_search($col,$row);
                  $colsFind++;
              }else{
                $errorString .= 'Campo '.$col.' non trovato! ';
              }
            }
            // Controllo che tutti i campi siano presenti, in caso negativo do un errore e non proseguo
            if(count($campi) != $colsFind){
              $errorString .= "Formato dei campi del file non compatibile o separatore impostato non correttamente! ";
              $errors[] = $errorString .' ----> <i>'.$line.'</i>';

              break;
            }
            $first = false;
          }

      }



        // Ritorno un array multidimensionale, con gli errori e i dati risultanti.
        return array('errors' => $errors,'data' => $data);

    }

   /**
    * Metodo saveCsv
    *
    * Cancella tutti i campi con il prefisso dato nel codice sispac dalla tabella
    * credits, e salva ogni dato passato nell'array $data. In caso di errore nel
    * salvataggio aggiunge un campo all'array errori.
    *
    * @param array $data risultato elaborazione checkCsv
    * @param string $prefix prefisso codice_sispac
    * @return array contenente gli errori e i campi cancellati
    */
    public function saveCsv($data = '',$prefix='')
    {
      $error = '';
      $deleted ;
      $num = 0;
      if(!empty($data) && is_array($data) ){

        $creditsTable = TableRegistry::get('Crediti.Credits');
        //debug($creditsTable);die;
        $deleted = $creditsTable->deleteAll(['cod_sispac LIKE'=> $prefix.'%']);
        foreach($data as $entity){

          if($creditsTable->saveFromCsv($entity)==null){
            $num++;
          }

        }
        if($num > 0)
            $error = 'Errore caricamento nel database,'.$num.' linee non salvate.';

      }else{
          $error = 'Errore caricamento nel database, dati non corretti!';
      }

      return array("error"=>$error,"deleted"=>$deleted);

    }

    /**
     * Metodo saveCreditsTotals
     *
     * Salvo tutti i crediti raggruppati per azienda estratti dalla tabella
     * credits in credits_totals . Imposto il numero importazione con un apposito
     * metodo.
     *
     * @param mixed $date Data impostata durante l'importazione
     * @param string $prefix prefisso codice_sispac
     * @return string Contenente errori nel caso si siano verificati
     */
    public function saveCreditsTotals($date,$prefix)
    {
      $errors = 0;
      $error = '';

      $creditsTable = TableRegistry::get('Crediti.Credits');
      $creditsTotalTable = TableRegistry::get('Crediti.CreditsTotals');

      $num_importazione = $creditsTotalTable->getNumImportazione($prefix);

      //debug($num_importazione);die;
      if($num_importazione == null){
          $num_importazione = 1;
      }else{
          $num_importazione = $num_importazione['num_importazione']+1;

      }


      $data = $creditsTable->retrieveCreditsGroupAzienda($date,$prefix);
      foreach($data as $new){
        $res = $creditsTotalTable->saveFromCredits($new,$date,$num_importazione);
        if($res == null){
          $errors++;
        }
      }

      if($errors > 0){
        $error = 'Errore caricamento nel database,'.$errors.' linee non salvate.';
      }
      $this->calcolaSaveIndicatore($date);

      return $error;

    }

    /**
     * Metodo calcolaSaveIndicatore
     *
     * Calcolo l'indicatore con un metodo del CreditsTotalsTalbe e lo salvo
     * nella tabella kpi.
     *
     * @param mixed $date Data impostata durante l'importazione
     *
     * @return mixed Risultato salvataggio
     */
    public function calcolaSaveIndicatore($date ='')
    {
      $creditsTotalTable = TableRegistry::get('Crediti.CreditsTotals');
      $kpiTable = TableRegistry::get('Crediti.Kpi');

      $res = $creditsTotalTable->calcolaIndicatore();
      return $kpiTable->saveIndicatoreCrediti($res,$date);

    }


}
