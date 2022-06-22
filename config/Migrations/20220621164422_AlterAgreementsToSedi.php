<?php
use Migrations\AbstractMigration;

class AlterAgreementsToSedi extends AbstractMigration
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
        $table = $this->table('agreements_to_sedi');
        $table->addColumn('capacity_increment', 'integer', [
            'limit' => 11,
            'default' => 0,
            'null' => false,
            'after' => 'capacity'
        ]);
        $table->update();
    }
}