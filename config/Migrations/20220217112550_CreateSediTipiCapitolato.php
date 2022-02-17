<?php
use Migrations\AbstractMigration;

class CreateSediTipiCapitolato extends AbstractMigration
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
        $table = $this->table('sedi_tipi_capitolato');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('ordering', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('color', 'string', [
            'limit' => 7,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();

        $rows = [
            [
                'id' => 1,
                'name' => 'Unità abitative',
                'ordering' => 10,
                'color' => '',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Centri collettivi fino 50 posti',
                'ordering' => 20,
                'color' => '',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Centri collettivi da 51 a 100 posti',
                'ordering' => 30,
                'color' => '',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Centri collettivi da 101 a 300 posti',
                'ordering' => 40,
                'color' => '',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('sedi_tipi_capitolato', $rows);
    }
}