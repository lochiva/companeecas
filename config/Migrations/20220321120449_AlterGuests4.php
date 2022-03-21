<?php
use Migrations\AbstractMigration;

class AlterGuests4 extends AbstractMigration
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
        $table->addColumn('educational_qualification_id', 'integer', [
            'default' => null,
            'null' => true,
            'after' => 'minor_alone'
        ]);
        $table->update();
    }
}