<?php
use Migrations\AbstractMigration;

class InsertToConfigurations4 extends AbstractMigration
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
                'key_conf' => 'REPORT_LABEL',
                'label' => 'Stringa da usare nei nomi dei report',
                'tooltip' => 'Stringa da usare nei nomi dei report',
                'value' => 'TORINO',
                'value_type' => 'text',
                'level' => 900,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('configurations', $rows);
    }
}