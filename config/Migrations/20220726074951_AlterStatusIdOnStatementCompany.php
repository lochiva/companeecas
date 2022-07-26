<?php
use Migrations\AbstractMigration;

class AlterStatusIdOnStatementCompany extends AbstractMigration
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
        $table->changeColumn('status_id', 'integer', ['default' => 1]);
        $table->update();
    }
}
