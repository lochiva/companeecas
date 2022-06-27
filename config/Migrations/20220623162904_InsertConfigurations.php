<?php
use Migrations\AbstractMigration;

class InsertConfigurations extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $rows = [
            [
                'plugin' => 'aziende',
                'key_conf' => 'EXIT_FILES_PATH',
                'label' => 'Path upload documento uscita',
                'tooltip' => 'La cartella relativa alla document root, con lo / finale , ad esempio files/',
                'value' => 'FILES/exit_files/',
                'value_type' => 'text',
                'level' => 900,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('configurations', $rows);
    }
}