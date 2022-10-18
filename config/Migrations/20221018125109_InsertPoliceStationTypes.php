<?php
use Migrations\AbstractMigration;

class InsertPoliceStationTypes extends AbstractMigration
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
                'type' => 'stazioneCC',
                'label_in_letter' => 'Stazione dei Carabinieri di',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'type' => 'commissariatoPS',
                'label_in_letter' => 'Commissariato di P.S.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('police_station_types', $rows);
    }
}
