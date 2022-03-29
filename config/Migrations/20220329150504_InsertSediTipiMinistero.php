<?php
use Migrations\AbstractMigration;

class InsertSediTipiMinistero extends AbstractMigration
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
                'id' => 9,
                'name' => 'Appartamento',
                'ordering' => 90,
                'color' => '#ff0000',
                'ente_type' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 10,
                'name' => 'Albergo o altra struttura ricettiva',
                'ordering' => 100,
                'color' => '#13c100',
                'ente_type' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 11,
                'name' => 'Centro collettivo',
                'ordering' => 110,
                'color' => '#0076e5',
                'ente_type' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 12,
                'name' => 'Altra tipologia',
                'ordering' => 120,
                'color' => '#3f00ff',
                'ente_type' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('sedi_tipi_ministero', $rows);
    }
}