<?php
use Migrations\AbstractMigration;

class AlterGuests7 extends AbstractMigration
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
        $table = $this->table('guests');
        $table->addColumn('exit_request_status_id', 'integer', [
            'after' => 'status_id',
            'default' => null,
            'null' => true
        ]);
        $table->update();
    }
}