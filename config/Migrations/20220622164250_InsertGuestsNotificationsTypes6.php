<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes6 extends AbstractMigration
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
                'name' => 'APPROVE_NEEDED_AGREEMENT',
                'msg_singular' => 'E\' richiesto un nuovo processo di approvazione per una convenzione.',
                'msg_plural' => 'E\' richiesto un nuovo processo di approvazione per {N} convenzioni.',
                'ente_type' => '1',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}