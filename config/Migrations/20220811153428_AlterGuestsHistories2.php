<?php
use Migrations\AbstractMigration;

class AlterGuestsHistories2 extends AbstractMigration
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
        $table = $this->table('guests_histories');
        $table->addColumn('guest_exit_request_status_id', 'integer', [
            'after' => 'guest_status_id',
            'default' => null,
            'null' => true
        ]);
        $table->update();
    }
}