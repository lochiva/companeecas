<?php
use Migrations\AbstractMigration;

class AlterGuests2 extends AbstractMigration
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
        $table->addColumn('status_id', 'integer', [
            'default' => 1,
            'null' => false,
            'after' => 'check_in_date'
        ]);
        $table->addColumn('original_guest_id', 'integer', [
            'null' => true,
            'after' => 'status_id'
        ]);
        $table->update();
    }
}