<?php
use Migrations\AbstractMigration;

class AddDueDateToStatementCompany extends AbstractMigration
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
        $table->addColumn('due_date', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
