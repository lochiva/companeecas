<?php
use Migrations\AbstractMigration;

class InsertToConfigurations extends AbstractMigration
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
                'key_conf' => 'STATEMENTS_UPLOAD_PATH',
                'label' => 'upload path',
                'tooltip' => 'Cartella per gli upload dei rendiconti',
                'value' => 'webroot/files/statements/',
                'value_type' => 'text',
                'level' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('configurations', $rows);
    }
}
