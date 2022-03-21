<?php
use Migrations\AbstractMigration;

class AlterGuests3 extends AbstractMigration
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
        $table->addColumn('check_out_date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'check_in_date'
        ]);
        $table->update();
    }
}