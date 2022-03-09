<?php
use Migrations\AbstractMigration;

class AlterSedi6 extends AbstractMigration
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
        $table->addColumn('incremento_posti', 'integer', [
            'default' => 0,
            'null' => false,
            'after' => 'n_posti_effettivi'
        ]);
        $table->update();
    }
}