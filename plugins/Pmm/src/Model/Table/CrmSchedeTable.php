<?php
namespace Pmm\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\I18n\Time;
use Cake\Core\Exception\Exception;

class CrmSchedeTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('crm_schede');
        $this->primaryKey('scheda_id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Pmm.CrmContratti',[
          'className' => 'Pmm.CrmContratti',
          'foreignKey' => 'contratto_fk_scheda',
          'propertyName' => 'CrmContratti'
        ]);

        $this->belongsTo('Pmm.Users',[
            'className' => 'Pmm.Users',
            'foreignKey' => 'scheda_fk_user',
            'propertyName' => 'Users'
        ]);

        $this->belongsTo('Pmm.CrmComuni',[
            'className' => 'Pmm.CrmComuni',
            'foreignKey' => 'scheda_fk_comune',
            'propertyName' => 'Comune'
        ]);

        $this->belongsTo('Pmm.CrmProvince',[
        	'className' => 'Pmm.CrmProvince',
            'foreignKey' => 'scheda_fk_provincia',
            'propertyName' => 'Provincia'
        ]);

    }

    public function getLibroSoci()
    {
      try
  		{
        $toRet = '';
        $opt = array();

  			$opt['conditions'] = ['CrmSchede.scheda_attiva'=>1, 'CrmSchede.scheda_coop_sostenibile LIKE'=>'s'];
  			$opt['fields'] = [
          'ragione_sociale' => 'CrmSchede.scheda_nome',
          'indirizzo'=>'CONCAT(TRIM(CrmSchede.scheda_indirizzo), SPACE(1), TRIM(CrmSchede.scheda_num_civ))',
          'citta'=>'CrmComuni.comune_nome',
          'provincia_sigla'=>'CrmComuni.comune_sigla_provincia',
          'data_adesione'=>'DATE_FORMAT(CrmSchede.scheda_data,"%Y-%m-%d")',
          'piva'=>'CrmSchede.scheda_piva',
          'cod_fiscale'=>'CrmSchede.scheda_cf'
          ] ;
  			$opt['order'] = ['CrmSchede.scheda_data'=>'ASC'];
        $opt['contain'] = ['CrmComuni'];

  			$res = $this->find('all',$opt)->toArray();
        foreach($res as $val){
          $toRet[] = [
            $val['ragione_sociale'],
            $val['indirizzo'],
            $val['citta'],
            $val['provincia_sigla'],
            $val['data_adesione'],
            $val['piva'],
            $val['cod_fiscale']

          ];

        }

        return $toRet;



  		}catch(Exception $e)
  		{
  			return [];
  		}

    }

}
