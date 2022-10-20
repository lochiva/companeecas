<?php
use Migrations\AbstractMigration;

class InsertSurveysPlaceholders extends AbstractMigration
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
                'label' => '{{indirizzo_ente}}',
                'description' => 'Indirizzo dell\'ente',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{email_ente}}',
                'description' => 'Indirizzo di posta elettronica dell\'ente',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
        ];
        $this->insert('surveys_placeholders', $rows);
    }
}
