<?php
use Migrations\AbstractMigration;

class AddDecriptionToCostCategories extends AbstractMigration
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
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
