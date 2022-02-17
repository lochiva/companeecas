<?php
use Migrations\AbstractMigration;

class CreateSediTipiMinistero extends AbstractMigration
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
        $table = $this->table('sedi_tipi_ministero');
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
                'name' => 'Appartamento',
                'ordering' => 10,
                'color' => '#ff0000',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Casa colonica',
                'ordering' => 20,
                'color' => '#ff8c00',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Hotel - Albergo',
                'ordering' => 30,
                'color' => '#13c100',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Moduli abitativi',
                'ordering' => 40,
                'color' => '#00e0d1',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Palazzina a 2 piani',
                'ordering' => 50,
                'color' => '#0076e5',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'name' => 'Palazzina a 3 piani',
                'ordering' => 60,
                'color' => '#b500ed',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'name' => 'Residence',
                'ordering' => 70,
                'color' => '#3f00ff',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'name' => 'Struttura ecclesiastica',
                'ordering' => 80,
                'color' => '#00bc58',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('sedi_tipi_ministero', $rows);
    }
}