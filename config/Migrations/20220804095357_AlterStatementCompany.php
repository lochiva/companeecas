<?php
use Migrations\AbstractMigration;

class AlterStatementCompany extends AbstractMigration
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
        $table->changeColumn('billing_net_amount', 'decimal', [
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->changeColumn('billing_vat_amount', 'decimal', [
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->update();
    }
}
