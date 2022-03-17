<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes2 extends AbstractMigration
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
                'id' => 5,
                'name' => 'EXITED_GUEST',
                'msg_singular' => 'E\' stato dimesso un ospite.',
                'msg_plural' => 'Sono stati dimessi {N} ospiti.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'name' => 'TRANSFERRED_GUEST',
                'msg_singular' => 'E\' stato trasferito un ospite.',
                'msg_plural' => 'Sono stati trasferiti {N} ospiti.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}