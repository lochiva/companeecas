<?php
use Migrations\AbstractMigration;

class AlterGuestsExitTypes5 extends AbstractMigration
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
        $table->addColumn('required_request', 'boolean', [
            'after' => 'name',
            'default' => false,
            'null' => false
        ]);
        $table->addColumn('required_request_file', 'boolean', [
            'after' => 'required_request',
            'default' => false,
            'null' => false
        ]);
        $table->addColumn('required_request_note', 'boolean', [
            'after' => 'required_request_file',
            'default' => false,
            'null' => false
        ]);
        $table->update();
    }
}