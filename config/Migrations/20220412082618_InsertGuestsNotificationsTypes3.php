<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes3 extends AbstractMigration
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
                'name' => 'CREATE_CENTER_UKRAINE',
                'msg_singular' => 'E\' stata creata una nuova struttura per un ente di Emergenza Ucraina.',
                'msg_plural' => 'Sono state create {N} strutture per enti di Emergenza Ucraina.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}