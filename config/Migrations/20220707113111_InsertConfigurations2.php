<?php
use Migrations\AbstractMigration;

class InsertConfigurations2 extends AbstractMigration
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
                'plugin' => 'aziende',
                'key_conf' => 'SIGNATURE_UPLAOD_PATH',
                'label' => 'Path per i file delle firme',
                'tooltip' => 'La cartella relativa alla document root, con lo / finale , ad esempio files/',
                'value' => 'FILES/signature/',
                'value_type' => 'text',
                'level' => 900,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('configurations', $rows);
    }

}
