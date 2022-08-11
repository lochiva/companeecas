<?php
use Migrations\AbstractMigration;

class AddOrderingToCostsCategories extends AbstractMigration
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
        $table = $this->table('costs_categories');
        $table->addColumn('ordering', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
