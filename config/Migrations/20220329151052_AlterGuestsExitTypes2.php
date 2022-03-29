<?php
use Migrations\AbstractMigration;

class AlterGuestsExitTypes2 extends AbstractMigration
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
        $table = $this->table('guests_exit_types');
        $table->addColumn('ente_type', 'integer', [
            'limit' => 11,
            'default' => 1,
            'null' => false,
            'after' => 'startable_by_ente'
        ]);
        $table->update();
    }
}