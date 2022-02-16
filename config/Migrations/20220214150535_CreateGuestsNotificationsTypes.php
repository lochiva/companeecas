<?php
use Migrations\AbstractMigration;

class CreateGuestsNotificationsTypes extends AbstractMigration
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
        $table = $this->table('guests_notifications_types');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addIndex(['name']);
        $table->addColumn('msg_singular', 'text');
        $table->addColumn('msg_plural', 'text');
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
                'name' => 'CREATE_GUEST',
                'msg_singular' => 'E\' stato inserito un ospite.',
                'msg_plural' => 'Sono stati inseriti {N} ospiti.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'UPDATE_GUEST',
                'msg_singular' => 'E\' stato modificato un ospite.',
                'msg_plural' => 'Sono stati modificati {N} ospiti.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}