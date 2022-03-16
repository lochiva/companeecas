<?php
use Migrations\AbstractMigration;

class CreateGuestsExitTypes extends AbstractMigration
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
        $table = $this->table('guests_exit_types');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('required_confirmation', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('required_note', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('ordering', 'integer', [
            'limit' => 11,
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
                'name' => 'Rimpatrio',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 10,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Destinazione SAI',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 20,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Destinazione Struttura per minori non accompagnati',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 30,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Decesso',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 40,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Rinuncia all’accoglienza',
                'required_confirmation' => 0,
                'required_note' => 1,
                'ordering' => 50,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'name' => 'Revoca dell’accoglienza (D.Lgs 142/2015 - art 23) - Abbandono Centro',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 60,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'name' => 'Revoca dell’accoglienza (D.Lgs 142/2015 - art 23) - Mancata Presentazione in Commissione',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 70,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'name' => 'Revoca dell’accoglienza (D.Lgs 142/2015 - art 23) - Domanda Reiterata Audizione',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 80,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 9,
                'name' => 'Revoca dell’accoglienza (D.Lgs 142/2015 - art 23) - Mezzi economici sufficienti',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 90,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 10,
                'name' => 'Revoca dell’accoglienza (D.Lgs 142/2015 - art 23) - Violazione Regole Strutture (incluso comportamenti violenti)',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 100,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 11,
                'name' => 'Ottenimento Protezione Sussidiaria',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 110,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 12,
                'name' => 'Ottenimento Protezione Speciale (ex art 19 c1bis del T.U. D.legisl 286/1998)',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 120,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 13,
                'name' => 'Ottenimento Status di Rifugiato',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 130,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 14,
                'name' => 'Note di revoca (D.Lgs 142/2015- art 14 e 15) - Rinuncia trasferimento a SAI',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 140,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 15,
                'name' => 'Note di revoca (D.Lgs 142/2015- art 14 e 15) - Rinuncia trasferimento verso altro CAS',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 150,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 16,
                'name' => 'Note di revoca (D.Lgs 142/2015- art 14 e 15) - Rigetto contenzioso',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 160,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 17,
                'name' => 'Trasferimento ad altra struttura (su CAS della provincia di competenza di altra Prefettura)',
                'required_confirmation' => 0,
                'required_note' => 0,
                'ordering' => 170,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 18,
                'name' => 'Varie',
                'required_confirmation' => 0,
                'required_note' => 1,
                'ordering' => 180,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_exit_types', $rows);
    }
}