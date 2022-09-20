<?php
use Migrations\AbstractMigration;

class AddComplianceToStatementCompany extends AbstractMigration
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
        $table->addColumn('compliance', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('compliance_filename', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
