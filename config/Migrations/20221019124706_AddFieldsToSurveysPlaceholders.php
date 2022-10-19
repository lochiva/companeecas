<?php
use Migrations\AbstractMigration;

class AddFieldsToSurveysPlaceholders extends AbstractMigration
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
                'label' => '{{ospite_nome}}',
                'description' => 'Nome dell\'ospite',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{ospite_cognome}}',
                'description' => 'Cognome dell\'ospite',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{ospite_data_nascita}}',
                'description' => 'Data di nascita dell\'ospite',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{ospite_luogo_nascita}}',
                'description' => 'Luogo di nascita dell\'ospite',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{ospite_vestanet}}',
                'description' => 'ID Vestanet dell\'ospite',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{denominazione_ente}}',
                'description' => 'Nome dell\'ente ospitante',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'label' => '{{responsabile_ente}}',
                'description' => 'Nome del responsabile',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
        ];
        $this->insert('surveys_placeholders', $rows);
    }
}
