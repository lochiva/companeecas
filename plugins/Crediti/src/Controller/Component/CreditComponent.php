<?php
namespace Crediti\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Event\Event;
use Cake\View\Helper\NumberHelper;


/**
 * Credit component
 */
class CreditComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    protected $Number;
    protected $controller;

    public function startup()
    {
      $this->controller = $this->_registry->getController();
        //$this->Number = new NumberHelper(new \Cake\View\View());

    }

   /**
    * Metodo getCreditsTotals
    *
    * Richiama il metodo retrieveCurrentCredits del model CreditsTotals passandoli
    * i parametri ed organizza i risultati per tablesorter o nel caso per un esportazione
    * excel.
    *
    * @param bool $xls
    * @return array contiene rows e total_rows
    */
    public function getCreditsTotals($xls = false)
    {
      $creditsTotalsTable = TableRegistry::get('Crediti.CreditsTotals');

      $out = array();



      $pass =  $this->request->query;

      $res = $creditsTotalsTable->retrieveCurrentCredits($pass,false,$xls);
      $total = $creditsTotalsTable->retrieveCurrentCredits($pass,true);
      $rows = array();
      //debug($res);
      foreach($res as $credit){

        if($xls){

          $rows[] = array(
            $credit['data_conto']->i18nFormat('dd/MM/yyyy'),
            ($credit['user_partner'] == null ? '' : $credit['user_partner'] ),
            $credit['famiglia'],
            $credit['cod_sispac'],
            $credit['denominazione'],
            $credit['total'],
            $credit['total_scaduti'],
            $credit['rating'],
            ($credit['lavorato'] == 1 ? 'SI' : 'NO')

          );

        }else{

          $rows[] = array(
            $credit['data_conto']->i18nFormat('dd/MM/yyyy'),
            ($credit['user_partner'] == null ? '' : $credit['user_partner'] ),
            $credit['famiglia'],
            $credit['cod_sispac'],
            $credit['denominazione'],
            $this->valueEur($credit['total']),
            $this->valueEur($credit['total_scaduti']),
            '<span class="'.$credit['rating'].'" >'.$credit['rating'].'</span>',
            ($credit['lavorato'] == 1 ? 'SI' : 'NO'),
            '<button class="fa fa-magic fa-lg btn btn-sm btn-flat btn-default action-credit" title="" value="'.$credit['aziendaId'].'" ></button>'
          );

        }

      }



      $out['total_rows'] = $total;
      $out['rows'] = $rows;
      //debug($out); die;

      return $out;


    }

    /**
     * Metodo getCreditsAzienda
     *
     * Restituisce tutti i crediti di un azienda.
     * Richiama il metodo getCreditsAziendaById del model Credits, e formatta i
     * dati in modo opportuno.
     *
     * @param int $id Id dell'azienda
     * @return array contiene la lista dei crediti del'azienda
     */
    public function getCreditsAzienda($id)
    {
      $creditsTable = TableRegistry::get('Crediti.Credits');
      $credits = $creditsTable->getCreditsAziendaById($id);

      foreach($credits as &$credit){
          $credit['num_documento'] = $this->dotThousand($credit['num_documento']);
          $credit['importo'] = $this->valueEur($credit['importo']);
          $credit['data_emissione'] = $credit['data_emissione']->i18nFormat('dd/MM/yyyy');
          $credit['data_scadenza'] = $credit['data_scadenza']->i18nFormat('dd/MM/yyyy');



      }
      return $credits;
    }

    /**
     * Metodo getTotalsCreditsAziendaNotifiche
     *
     * Restituisce tutti i dati dalla tabella creditsTotals e notifiche di un Azineda.
     * Richiama il metodo getCreditsTotalsAziendaById del model CreditsTotals, e formatta i
     * dati in modo opportuno.
     *
     * @param int $id Id dell'azienda
     * @return array
     */
    public function getTotalsCreditsAziendaNotifiche($id)
    {
      $creditsTotalsTable = TableRegistry::get('Crediti.CreditsTotals');
      $credits = $creditsTotalsTable->getCreditsTotalsAziendaById($id);
      //debug($credits);
      foreach($credits as &$credit){
          $credit['total'] = $this->valueEur($credit['total']);
          $credit['total_scaduti'] = $this->valueEur($credit['total_scaduti']);
          $credit['data_conto'] = $credit['data_conto']->i18nFormat('dd/MM/yyyy');

      }
      //debug($credits);
      return $credits;

    }

    /**
     * Metodo getAziendaInfoForNotifiche
     *
     * Restituisce tutti i dati necessari per la creazione del testo dell'email e
     * le varie notifiche.
     *
     * @param int $id Id dell'azienda
     * @return array Contiene la somma dei crediti scaduti, l'elenco dei crediti e l'id del socio di riferimento
     */
    public function getAziendaInfoForNotifiche($id)
    {
      $creditsTable = TableRegistry::get('Crediti.Credits');
      $sumCredits = $creditsTable->getCreditsAziendaSumPartner($id);
      $credits = $this->getCreditsAzienda($id);


      return array('somma'=>$this->valueEur($sumCredits['somma']),'credits'=>$credits,'partnerId'=>$sumCredits['partnerId']);

    }

    /**
     * Metodo saveNotifica
     *
     * Salva i dati di una notifica dopo averla eseguita.
     *
     * @param int $id Id dell'azienda
     * @param array $data Contiene i dati da salvare
     * @return bool
     */
    public function saveNotifica($id,$data)
    {
      $creditsTotalsTable = TableRegistry::get('Crediti.CreditsTotals');
      $notificheTable = TableRegistry::get('Crediti.Notifiche');

      $current = $creditsTotalsTable->getCurrentCreditsTotalsAzienda($id);

      $new=[
        'azienda_id' => $id,
        'credits_totals_id' => ($current == null ? 0 : $current['id']),
        'testo' => json_encode($data['testo']),
        'type' => $data['type'],
        'author_id' => $data['author_id']
      ];

      if($current!=null){
        $current->lavorato = 1;
        $creditsTotalsTable->save($current);
      }

      return $notificheTable->saveNotifica($new);

    }

    /**
     * Metodo getCredits
     *
     * Richiama il metodo retrieveCredits del model Credits passandoli
     * i parametri ed organizza i risultati per tablesorter.
     *
     * @param bool $xls
     * @return array
     */
    public function getCredits($xls = false)
    {
      $creditsTable = TableRegistry::get('Crediti.Credits');

      $out = array();

      $pass =  $this->request->query;


      $res = $creditsTable->retrieveCredits($pass,false,$xls);
      $total = $creditsTable->retrieveCredits($pass,true);
      $rows = array();

      foreach($res as $credit){


          $rows[] = array(
            $credit['Aziende']['famiglia'],
            $credit['Aziende']['cod_sispac'],
            $credit['Aziende']['denominazione'],
            $this->dotThousand($credit['num_documento']),
            $credit['data_emissione']->i18nFormat('dd/MM/yyyy'),
            $credit['data_scadenza']->i18nFormat('dd/MM/yyyy'),
            $this->valueEur($credit['importo'])

          );
      }

      $out['total_rows'] = $total;
      $out['rows'] = $rows;
      //debug($out); die;

      return $out;

    }

    /**
     * Metodo getCurrentRatingAzienda
     *
     * Richiama il metodo getCurrentCreditsTotalsAzienda del model CreditsTotals
     * e restituisce il rating attuale dell'azienda. Usato nella pagina info del
     * Plugin Aziende controller home.
     *
     * @param int $id Id dell'azienda
     * @return string
     */
    public function getCurrentRatingAzienda($id)
    {
      $creditsTotalsTable = TableRegistry::get('Crediti.CreditsTotals');
      $current = $creditsTotalsTable->getCurrentCreditsTotalsAzienda($id);

      return $current['rating'];
    }

    /**
     * Metodo retrieveCreditsGroupAziendaById
     *
     * Richiama il metodo retrieveCreditsGroupAziendaById del model Credits
     * tutti i crediti attuali con la scadenza calcolata ad oggi. Utilizzato dalla
     * modale di gestione crediti.
     *
     * @param int $id Id dell'azienda
     * @return array
     */
    public function retrieveCreditsGroupAziendaById($id)
    {
      $creditsTable = TableRegistry::get('Crediti.Credits');
      $res = $creditsTable->retrieveCreditsGroupAziendaById(Time::now(),$id);
      $res[0]['crediti_scaduti'] = $this->valueEur($res[0]['crediti_scaduti']);
      $res[0]['crediti'] = $this->valueEur($res[0]['crediti']);

      return $res;

    }

    /**
     * Metodo getCreditsSum
     *
     * Richiama il metodo getCreditsSum del model Credits e retituisce la somma
     * di tutti i crediti presenti nella tabella credits. Usato nel report elencoCrediti.
     * Il numero viene formattato in modo appropriato.
     *
     * @return string
     */
    public function getCreditsSum()
    {
      $creditsTable = TableRegistry::get('Crediti.Credits');
      $res = $creditsTable->getCreditsSum();

      if($res != null)
        return $this->valueEur($res['somma']);
    }

    public function valueEur($val)
    {

      $dotPos = strpos($val,'.');

      if($dotPos != false){

        $dec = substr($val,$dotPos+1,strlen($val));
        if(strlen($dec)<2)
          $dec.=0;

        $int = substr($val,0,$dotPos);

      }else{
        $dec = '00';
        $int = $val;
      }


      return $this->dotThousand($int).','.$dec."€";
    }

    public function dotThousand($val)
    {
      $res = '';

      $val = strrev($val);
      $len = strlen($val);

      for($i=0; $i<$len; $i++ ){
        if($i%3 == 0 && $i!=0 ){
          $res.='.';
        }
        $res.=$val[$i];
      }

      return strrev($res);
    }

}
