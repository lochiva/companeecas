<?php

namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class AziendaComponent extends Component
{
    public function getAziende($pass = array())
    {
        $az = TableRegistry::get('Aziende.Aziende');
        $columns = [
          0 => ['val' => 'denominazione', 'type' => 'text'],
          //1 => ['val' => 'nome_cognome', 'type' => 'text', 'having' => 1],
          1 => ['val' => 'telefono', 'type' => 'text'],
          2 => ['val' => 'email_info', 'type' => 'text'],
          3 => ['val' => 'sito_web', 'type' => 'text'],
		  //5 => ['val' => 'piva', 'type' => 'text'],
		  //6 => ['val' => 'pa_codice', 'type' => 'text'],
         ];
         $opt['fields'] = ['denominazione', 'nome_cognome' => 'CONCAT(nome,SPACE(1),cognome)', 'telefono',
                    'email_info', 'sito_web', 'piva', 'id', 'cliente', 'fornitore', 'interno', 'id_cliente_fattureincloud',
           		    'id_fornitore_fattureincloud', 'pa_codice', 'id_tipo'];
        $opt['order'] = ['Aziende.denominazione' => 'ASC', 'nome_cognome' => 'ASC'];
        $toRet['res'] = $az->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $az->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
    }

    public function getTotAziende($pass = array())
    {
        $az = TableRegistry::get('Aziende.Aziende');

        $query = $az->find('all');

        $results = $query->count();

        //echo "<pre>"; print_r($results); echo "</pre>";

        return $results;
    }

	public function getFornitori(){
		$az = TableRegistry::get('Aziende.Aziende');

		$opt['conditions'] = ['fornitore' => 1];
		$opt['order'] = ['denominazione ASC'];

		$res = $az->find('all', $opt)->toArray();

		return $res;
	}

    public function _newEntity()
    {
        $az = TableRegistry::get('Aziende.Aziende');

        return $az->newEntity();
    }

    public function _patchEntity($doc, $request)
    {
        $az = TableRegistry::get('Aziende.Aziende');

        return $az->patchEntity($doc, $request);
    }

    public function _save($doc)
    {
        $az = TableRegistry::get('Aziende.Aziende');

        return $az->save($doc);
    }

    public function _get($id)
    {
        $az = TableRegistry::get('Aziende.Aziende');
        $res = $az->get($id, ['contain' => ['Sedi' => ['sort' => ['ordering' => 'ASC']], 'Gruppi', 'Contatti' => ['sort' => ['ordering' => 'ASC'], 'Users', 'Skills'], 'Tipi']]);
        if(!empty($res['contatti'])){
          foreach ($res['contatti'] as $key => $contatto) {
            $res['contatti'][$key]['skills'] = array();
            foreach ($contatto['Skills'] as $key2 => $skill) {
                $res['contatti'][$key]['skills'][] = (string)$skill['id'];
            }
            unset($res['contatti'][$key]['Skills']);
          }

        }
        if(!empty($res['sedi'])){
            foreach ($res['sedi'] as $key => $sede) {
                if(!empty($sede['comune'])){
                    $comune = TableRegistry::get('Luoghi')->get($sede['comune']);
                    $res['sedi'][$key]['comune_des'] = $comune['des_luo'];
                }else{
                    $res['sedi'][$key]['comune_des'] = '';
                }

                $agreements = TableRegistry::get('Aziende.Agreements');
                $agreement = $agreements->find()
                    ->select(['Agreements.procedure_id', 'ats.capacity'])
                    ->where(['ats.sede_id' => $sede['id'], 'ats.active' => 1])
                    ->join([
                        [
                            'table' => 'agreements_to_sedi',
                            'alias' => 'ats',
                            'left' => 'LEFT',
                            'conditions' => 'Agreements.id = ats.agreement_id'
                        ]
                    ])
                    ->first();
                $res['sedi'][$key]['n_posti_convenzione'] = empty($agreement) ? '' : $agreement['ats']['capacity'];
                $res['sedi'][$key]['id_procedura_affidamento'] = empty($agreement) ? '' : $agreement['procedure_id'];
            }
        }
        $gruppi = array();
        foreach ($res['gruppi'] as $gruppo) {
            $gruppi[]= (string)$gruppo['id'];
        }
        $res['gruppi'] = $gruppi;

        return $res;
    }

    public function _delete($doc)
    {
        $az = TableRegistry::get('Aziende.Aziende');

        return $az->softDelete($doc);
    }

    public function getAziendaAutocomplete($nome, $type)
    {
        $az = TableRegistry::get('Aziende.Aziende');
        $az = $az->find('all')->select(['id' => 'id', 'text' => 'denominazione'])
                  ->where(['denominazione LIKE' => '%'.$nome.'%'])->order(['denominazione' => 'ASC']);
        switch ($type) {
          case 'fornitore':
            $az = $az->where(['fornitore' => 1]);
            break;
          case 'cliente':
            $az = $az->where(['cliente' => 1]);
            break;
          case 'interno':
            $az = $az->where(['interno' => 1]);
            break;
        }

        return $az->toArray();
    }
    /**
     * Recupera la lista delle ultime modifiche di un azienda prendendo i dati
     * dalla tabella action_log, formattando i risultati per mostrare solo le modifiche
     * ed eventualmente il dato precedente tra parentesi.
     *
     * @param int $id    id dell'azienda
     * @param int $limit numero di recrod da estrarre
     *
     * @return array array con i risultati formattati
     */
    public function getAzeindaHistory($id, $limit = 20)
    {
        $toRet = TableRegistry::get('ActionLog')->getRecordHistory('aziende', $id, $limit);
        $preVal = array();
        $pre = array();
        $toRet = array_reverse($toRet);
        foreach ($toRet as $key => $val) {
            unset($val['entity']['gruppi']);
            $toRet[$key]['data'] = $val['created']->i18nFormat('HH:mm - dd/MM/yy');
            if (!empty($val['user'])) {
                if (!empty($val['user']['nome'])) {
                    $toRet[$key]['utente'] = $val['user']['nome'].' '.$val['user']['cognome'];
                } else {
                    $toRet[$key]['utente'] = $val['user']['username'];
                }
            } else {
                $toRet[$key]['utente'] = 'Utente sconosciuto';
            }
            switch ($val['action']) {
            case 'insert':
              $toRet[$key]['azione'] = 'inserito';
              break;
            case 'delete':
              $toRet[$key]['azione'] = 'cancellato';
              break;
            default:
              $toRet[$key]['azione'] = 'modificato';
              break;
          }
            unset($val['entity']['created'], $val['entity']['modified'], $val['entity']['id']);
            if (isset($val['entity']['cliente'])) {
              $val['entity']['cliente'] = ($val['entity']['cliente'] == 0 ? 'No' : 'Si');
            }
            if (isset($val['entity']['fornitore'])) {
              $val['entity']['fornitore'] = ($val['entity']['fornitore'] == 0 ? 'No' : 'Si');
            }
            if (isset($val['entity']['interno'])) {
                $val['entity']['interno'] = ($val['entity']['interno'] == 0 ? 'No' : 'Si');
            }

            if (!empty($preVal)) {
                $diff = array_diff_assoc($val['entity'], $preVal['entity']);
                $pre = array_diff_assoc($preVal['entity'], $val['entity']);
            } else {
                $diff = $val['entity'];
            }
            foreach ($diff as $key2 => $value) {
                if (!empty($pre[$key2])) {
                    $diff[$key2] = $value.' ( '.$pre[$key2].' )';
                }
            }
            $preVal = $val;
            if (!empty($diff)) {
                $toRet[$key]['modifiche'] = $this->formatEntity($diff);
            } else {
                if(!empty($pre)){
                  foreach ($pre as $key2 => $value) {
                      $diff[$key2] = 'Vuoto'.' ( '.$value.' )';
                  }
                  $toRet[$key]['modifiche'] = $this->formatEntity($diff);
                }else{
                    unset($toRet[$key]);
                }
            }
        }
        $toRet = array_reverse($toRet);

        return $toRet;
    }

    public function formatEntity($entity, $string = '')
    {
        foreach ($entity as $key => $val) {
            if (is_array($val)) {
                $string .= '<b>'.strtoupper($key).'</b>: ['.$this->formatEntity($val, $string).'] ';
            } else {
                $string .= '<b>'.ucfirst($key).'</b>: '.(empty($val) ? 'Vuoto' : htmlspecialchars($val)).' ';
            }
        }

        return $string;
    }

    public function getAziendeInterne()
    {
        return TableRegistry::get('Aziende.aziende')->find()->where(['interno' => 1])->toArray();
    }

    /**
     * [saveAziendaJson description]
     * @param  [type] $json [description]
     * @return [type]       [description]
     */
    public function saveAziendaJson($json)
    {
        $azienda = $json;
        $aziendeTalbe = TableRegistry::get('Aziende.Aziende');
        $sediTable = TableRegistry::get('Aziende.Sedi');
        $contattoTable = TableRegistry::get('Aziende.Contatti');
        $sediId = array();

        unset($azienda['contatti'], $azienda['sedi'], $azienda['logo_to_save']);
        $azienda['gruppi'] = json_decode($azienda['gruppi']);

        if(!empty($azienda['logo'])){
            unset($azienda['logo']);
        }

        if ($azienda = $aziendeTalbe->saveAzienda($azienda)) {
            //salvataggio logo azienda
            if(!empty($json['logo_to_save'])){
                $uploadPath = Configure::read('dbconfig.aziende.LOGO_PATH');
                $path = ROOT.DS.$uploadPath.$azienda->id;
                $fileExtension = pathinfo($json['logo_to_save']['name'])['extension'];
                $fileName = 'logo.'.$fileExtension;
                $validTypes = ['image/jpeg', 'image/png'];
                if(in_array($json['logo_to_save']['type'], $validTypes)){
                    if(is_dir($path) || mkdir($path, 0755, true)){
                        if(move_uploaded_file($json['logo_to_save']['tmp_name'],$path.DS.$fileName) ){
                            $azienda->logo = $azienda->id.DS.$fileName;
                            $aziendeTalbe->save($azienda);
                        }
                    }
                }
            }

            foreach (json_decode($json['sedi'], true) as $sede) {

                $sede['id_azienda'] = $azienda->id;

                if ($entity = $sediTable->saveSede($sede)) {
                    if(empty($sede['id']) || !is_int($sede['id'])){
                        // Salvataggio notifica creazione struttura
                        $saveType = 'CREATE_CENTER';
                        $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
                        $notification = $guestsNotifications->newEntity();
                        $notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
                        $notificationData = [
                            'type_id' => $notificationType->id,
                            'azienda_id' => $entity->id_azienda,
                            'sede_id' => $entity->id,
                            'guest_id' => 0,
                            'user_maker_id' => $this->request->session()->read('Auth.User.id')
                        ];
                        $guestsNotifications->patchEntity($notification, $notificationData);
                        $guestsNotifications->save($notification);
                    }
                    $sediId[$sede['id']] = $entity->id;
                }
            }
            if($this->request->session()->read('Auth.User.role') != 'companee_admin'){
                foreach (json_decode($json['contatti'], true) as $contatto) {
                    $contatto['id_azienda'] = $azienda->id;
                    $contatto['id_sede'] = ( !empty($sediId[$contatto['id_sede']]) ? $sediId[$contatto['id_sede']] : 0 );

                    $contattoTable->saveContatto($contatto);
                }
            }
        } else {
            return false;
        }

        return true;
    }

    public function verifyUser($user, $idAzienda)
    {
        if (!empty($user) && !empty($idAzienda)) {

            if($user['role'] == 'admin'){
                return true;
            }

            $contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
    
            if(!empty($contatto) && $contatto['id_azienda'] == $idAzienda){
                return true;
            }
    
            return false;
        } else {
            return false;
        }
    }

    public function countGuestsForAzienda($aziendaId)
    {
        $sedi = TableRegistry::get('Aziende.Sedi')->find()->where(['id_azienda' => $aziendaId])->toArray();

        $guestsTable = TableRegistry::get('Aziende.Guests');
        $count = 0;
        foreach ($sedi as $sede) {
            $count += $guestsTable->countGuestsForSede($sede->id);
        }

        return $count;
    }

    public function countPostiForAzienda($aziendaId)
    {
        $sedi = TableRegistry::get('Aziende.Sedi')->find()->where(['id_azienda' => $aziendaId])->toArray();

        $count = 0;
        foreach ($sedi as $sede) {
            $count += $sede->n_posti_effettivi;
        }

        return $count;
    }
}
