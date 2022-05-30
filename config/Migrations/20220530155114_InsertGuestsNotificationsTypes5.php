<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes5 extends AbstractMigration
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
                'name' => 'CONFIRM_EXIT_GUEST',
                'msg_singular' => 'E\' richiesta conferma per la procedura di uscita di un ospite.',
                'msg_plural' => 'E\' richiesta conferma per la procedura di uscita di {N} ospiti.',
                'ente_type' => '1',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'CONFIRM_EXIT_GUEST_UKRAINE',
                'msg_singular' => 'E\' richiesta conferma per la procedura di uscita di un ospite da un ente di Emergenza Ucraina.',
                'msg_plural' => 'E\' richiesta conferma per la procedura di uscita di {N} ospiti da enti di Emergenza Ucraina.',
                'ente_type' => '2',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}