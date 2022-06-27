<?php
use Migrations\AbstractMigration;

class AlterGuestsExitTypes4 extends AbstractMigration
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
        $table->addColumn('required_file', 'boolean', [
            'after' => 'required_confirmation',
            'limit' => 255,
            'default' => false,
            'null' => false
        ]);
        $table->update();
    }
}