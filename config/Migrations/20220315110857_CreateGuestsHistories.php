<?php
use Migrations\AbstractMigration;

class CreateGuestsHistories extends AbstractMigration
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
        $table = $this->table('guests_histories');
        $table->addColumn('guest_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['guest_id']);
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
        $table->addColumn('operator_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['operator_id']);
        $table->addColumn('operation_date', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('guest_status_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['guest_status_id']);
        $table->addColumn('exit_type_id', 'integer', [
            'limit' => 11,
            'null' => true,
        ])
        ->addIndex(['exit_type_id']);
        $table->addColumn('cloned_guest_id', 'integer', [
            'limit' => 11,
            'null' => true,
        ])
        ->addIndex(['cloned_guest_id']);
        $table->addColumn('destination_id', 'integer', [
            'limit' => 11,
            'null' => true,
        ])
        ->addIndex(['destination_id']);
        $table->addColumn('provenance_id', 'integer', [
            'limit' => 11,
            'null' => true,
        ])
        ->addIndex(['provenance_id']);
        $table->addColumn('note', 'text');
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