<?php
use Migrations\AbstractMigration;

class AddIdToPresenze extends AbstractMigration
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
        $this->query("ALTER TABLE `presenze` DROP PRIMARY KEY;");
        $this->query("ALTER TABLE `presenze` ADD INDEX(`guest_id`);");
        $this->query("ALTER TABLE `presenze` ADD INDEX(`date`);");
        $this->query("ALTER TABLE `presenze` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);");
    }
}
