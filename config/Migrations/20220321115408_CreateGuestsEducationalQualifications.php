<?php
use Migrations\AbstractMigration;

class CreateGuestsEducationalQualifications extends AbstractMigration
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
        $table = $this->table('guests_educational_qualifications');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('ordering', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('parent', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('have_children', 'boolean', [
            'default' => false,
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
                'name' => 'Non disponibile',
                'ordering' => 10,
                'parent' => 0,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Privo di istruzione',
                'ordering' => 20,
                'parent' => 0,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Scuola primaria',
                'ordering' => 30,
                'parent' => 0,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Scuola secondaria I grado',
                'ordering' => 40,
                'parent' => 0,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Scuola secondaria II grado',
                'ordering' => 50,
                'parent' => 0,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'name' => 'Università',
                'ordering' => 60,
                'parent' => 0,
                'have_children' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'name' => 'Area Sanitaria',
                'ordering' => 70,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'name' => 'Area scientifica tecnologica',
                'ordering' => 80,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 9,
                'name' => 'Giuridica - Economica',
                'ordering' => 90,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 10,
                'name' => 'Umanistica',
                'ordering' => 100,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 11,
                'name' => 'Sociale',
                'ordering' => 110,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 12,
                'name' => 'Altra area',
                'ordering' => 120,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_educational_qualifications', $rows);
    }
}