<?php
use Migrations\AbstractMigration;

class CreateGuestsNotifications extends AbstractMigration
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
        $table = $this->table('guests_notifications');
        $table->addColumn('type_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['type_id']);
        $table->addColumn('azienda_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['azienda_id']);
        $table->addColumn('sede_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['sede_id']);
        $table->addColumn('guest_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['guest_id']);
        $table->addColumn('user_maker_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['user_maker_id']);
        $table->addColumn('text', 'text');
        $table->addColumn('user_done_id', 'integer', [
            'limit' => 11,
            'null' => true,
        ])
        ->addIndex(['user_done_id']);
        $table->addColumn('done', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('done_date', 'date', [
            'default' => null,
            'null' => true,
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