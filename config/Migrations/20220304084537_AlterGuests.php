<?php
use Migrations\AbstractMigration;

class AlterGuests extends AbstractMigration
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
        $table->addColumn('check_in_date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'draft_expiration'
        ]);
        $table->update();
    }
}