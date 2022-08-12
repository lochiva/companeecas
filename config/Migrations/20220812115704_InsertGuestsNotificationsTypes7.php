<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes7 extends AbstractMigration
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
                'name' => 'REQUEST_EXIT_GUEST',
                'msg_singular' => 'E\' stata inviata la richiesta di avvio della procedura di uscita di un ospite.',
                'msg_plural' => 'E\' stata inviata la richiesta di avvio della procedura di uscita di {N} ospiti.',
                'ente_type' => '1',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'REQUEST_EXIT_GUEST_UKRAINE',
                'msg_singular' => 'E\' stata inviata la richiesta di avvio della procedura di uscita di un ospite da un ente di Emergenza Ucraina.',
                'msg_plural' => 'E\' stata inviata la richiesta di avvio della procedura di uscita di {N} ospiti da enti di Emergenza Ucraina.',
                'ente_type' => '2',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}