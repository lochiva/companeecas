<?php
use Migrations\AbstractMigration;

class AlterGuests5 extends AbstractMigration
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
        $table->addColumn('minor_note', 'text', [
            'after' => 'minor_alone'
        ]);
        $table->update();
    }
}