<?php
use Migrations\AbstractMigration;

class AlterSedi4 extends AbstractMigration
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
        $table = $this->table('sedi');
        $table->addColumn('approved', 'boolean', [
            'default' => 0,
            'null' => false,
            'after' => 'ordering'
        ]);
        $table->update();
    }
}