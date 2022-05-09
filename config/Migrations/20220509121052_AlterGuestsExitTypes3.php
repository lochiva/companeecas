<?php
use Migrations\AbstractMigration;

class AlterGuestsExitTypes3 extends AbstractMigration
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
        $table->addColumn('toSAI', 'boolean', [
            'after' => 'startable_by_ente',
            'default' => false,
            'null' => false
        ]);
        $table->update();
    }
}