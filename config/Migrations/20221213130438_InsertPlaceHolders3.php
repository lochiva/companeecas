<?php
use Migrations\AbstractMigration;

class InsertPlaceHolders3 extends AbstractMigration
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
                'label' => '{{sede_email}}',
                'description' => "Indirizzo di posta elettronica della struttura",
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('surveys_placeholders', $rows);
    }
}
