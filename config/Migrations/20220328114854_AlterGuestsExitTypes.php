<?php
use Migrations\AbstractMigration;

class AlterGuestsExitTypes extends AbstractMigration
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
        $table->addColumn('startable_by_ente', 'boolean', [
            'default' => false,
            'null' => false,
            'after' => 'required_note'
        ]);
        $table->update();
    }
}