<?php
use Migrations\AbstractMigration;

class CreateStatementCompany extends AbstractMigration
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
        $table = $this->table('statement_company');
        $table->addColumn('statement_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('billing_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('billing_reference', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('billing_net_amount', 'decimal', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('billing_vat_amount', 'decimal', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('status_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('approved_date', 'time', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('uploaded_path', 'string', [
            'default' => null,
            'limit' => 255,
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
