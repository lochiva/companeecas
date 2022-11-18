<?php
use Migrations\AbstractMigration;

class InsertIntoPlaceholders extends AbstractMigration
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
                'label' => '{{sede_indirizzo}}',
                'description' => 'Indirizzo della struttura',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{ospite_cui}}',
                'description' => 'CUI dell\'ospite',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
        ];
        $this->insert('surveys_placeholders', $rows);
    }
}
