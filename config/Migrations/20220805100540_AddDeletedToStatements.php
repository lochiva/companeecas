<?php
use Migrations\AbstractMigration;

class AddDeletedToStatements extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('statements');
        $table->addColumn('deleted', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
