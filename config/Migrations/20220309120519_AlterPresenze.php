<?php
use Migrations\AbstractMigration;

class AlterPresenze extends AbstractMigration
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
        $table = $this->table('presenze');
        $table->addColumn('note', 'text', [
            'null' => false,
            'after' => 'presente'
        ]);
        $table->update();
    }
}