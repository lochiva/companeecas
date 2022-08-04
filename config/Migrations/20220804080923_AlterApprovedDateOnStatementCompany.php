<?php
use Migrations\AbstractMigration;

class AlterApprovedDateOnStatementCompany extends AbstractMigration
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
        $table->changeColumn('approved_date', 'date');
        $table->update();
    }
}
