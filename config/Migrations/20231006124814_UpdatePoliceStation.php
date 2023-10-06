<?php
use Migrations\AbstractMigration;

class UpdatePoliceStation extends AbstractMigration
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
        $this->query("UPDATE `police_stations` SET `name` = REPLACE(`name`, 'Stazione', 'Stazione dei Carabinieri') WHERE `name` LIKE '%Stazione%'");
        $this->query("UPDATE `police_stations` SET `name` = REPLACE(`name`, 'Commissariato', 'Commissariato di Polizia di ') WHERE `name` LIKE '%Commissariato%'");

    }
}

