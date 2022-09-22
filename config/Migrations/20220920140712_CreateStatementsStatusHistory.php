<?php
use Migrations\AbstractMigration;

class CreateStatementsStatusHistory extends AbstractMigration
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
        $table = $this->table('statements_status_history');
        $table->addColumn('statement_company_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['statement_company_id']);
        $table->addColumn('user_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['user_id']);
        $table->addColumn('status_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['status_id']);
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