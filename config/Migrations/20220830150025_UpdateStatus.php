<?php
use Migrations\AbstractMigration;

class UpdateStatus extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->execute("UPDATE `status` SET `name` = 'Integrazione' WHERE `status`.`id` = 3;");
    }
}
