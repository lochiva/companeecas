<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes4 extends AbstractMigration
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
                'name' => 'CREATE_GUEST_UKRAINE',
                'msg_singular' => 'E\' stato inserito un ospite in un ente di Emergenza Ucraina.',
                'msg_plural' => 'Sono stati inseriti {N} ospiti in enti di Emergenza Ucraina.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'UPDATE_GUEST_UKRAINE',
                'msg_singular' => 'E\' stato modificato un ospite in un ente di Emergenza Ucraina.',
                'msg_plural' => 'Sono stati modificati {N} ospiti in enti di Emergenza Ucraina.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'EXITED_GUEST_UKRAINE',
                'msg_singular' => 'E\' stato dimesso un ospite da un ente di Emergenza Ucraina.',
                'msg_plural' => 'Sono stati dimessi {N} ospiti da enti di Emergenza Ucraina.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'TRANSFERRED_GUEST_UKRAINE',
                'msg_singular' => 'E\' stato trasferito un ospite da un ente di Emergenza Ucraina.',
                'msg_plural' => 'Sono stati trasferiti {N} ospiti da enti di Emergenza Ucraina.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}