<?php
use Migrations\AbstractMigration;

class InsertGuestsNotificationsTypes extends AbstractMigration
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
                'id' => 3,
                'name' => 'CREATE_CENTER',
                'msg_singular' => 'E\' stata creata una nuova struttura, si deve associare o creare una convenzione.',
                'msg_plural' => 'Sono state create {N} strutture, si devono associare o creare le convenzioni.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'MISSING_AGREEMENT_CENTER',
                'msg_singular' => 'Esiste una struttura non associata ad una convenzione.',
                'msg_plural' => 'Esistono {N} strutture non associate ad una convenzione.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_notifications_types', $rows);
    }
}