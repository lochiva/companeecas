<?php
use Migrations\AbstractMigration;

class InsertPlaceHolders2 extends AbstractMigration
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
                'label' => '{{sede_cap}}',
                'description' => "CODICE di AVVIAMENTO POSTALE della struttura",
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{sede_comune}}',
                'description' => "Comune in cui è ubicata la struttura",
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{sede_provincia}}',
                'description' => "Provincia del comune in cui è ubicata la struttura",
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],

        ];
        $this->insert('surveys_placeholders', $rows);
    }
}
