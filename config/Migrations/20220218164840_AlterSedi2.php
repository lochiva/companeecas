<?php
use Migrations\AbstractMigration;

class AlterSedi2 extends AbstractMigration
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
        $table = $this->table('sedi');
        $table->removeColumn('id_procedura_affidamento');
        $table->update();
    }
}