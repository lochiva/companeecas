<?php
use Migrations\AbstractMigration;

class AmendSedeLegale extends AbstractMigration
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
        $this->query('UPDATE `contatti_ruoli` SET `id` = 18 WHERE `id` = 0;');
    }
}
