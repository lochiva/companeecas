<?php
use Migrations\AbstractMigration;

class InsertToConfigurations3 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $rows = [
            [
                'plugin' => 'generico',
                'key_conf' => 'ENABLED_PROVINCES',
                'label' => 'Province abilitate per le strutture',
                'tooltip' => 'Elenco delle sigle delle province abilitate per le strutture, separate da virgola',
                'value' => 'TO, VC, NO, CN, AT, AL, BI, VB',
                'value_type' => 'text',
                'level' => 900,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('configurations', $rows);
    }
}