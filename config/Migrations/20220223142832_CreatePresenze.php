<?php
use Migrations\AbstractMigration;

class CreatePresenze extends AbstractMigration
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
        $table = $this->table('presenze', ['id' => false, 'primary_key' => ['guest_id', 'date']]);
        $table->addColumn('guest_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('date', 'date', [
            'null' => false,
        ]);
        $table->addColumn('sede_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['sede_id']);
        $table->addColumn('presente', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}