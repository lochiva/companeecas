<?php
use Migrations\AbstractMigration;

class CreateGuestsFamilies extends AbstractMigration
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
        $table = $this->table('guests_families');
        $table->addColumn('family_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['family_id']);
        $table->addColumn('guest_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['guest_id']);
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