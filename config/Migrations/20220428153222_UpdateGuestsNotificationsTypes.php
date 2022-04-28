<?php
use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;

class UpdateGuestsNotificationsTypes extends AbstractMigration
{
   /**
     * Migrate Up.
     */
    public function up()
    {
        $guestsNotificationsTypes = TableRegistry::get('GuestsNotificationsTypes');

        $fields = ['ente_type' => 2];
        $conditions = ['id IN' => [7, 8, 9, 10, 11]];

        $guestsNotificationsTypes->updateAll($fields, $conditions);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}