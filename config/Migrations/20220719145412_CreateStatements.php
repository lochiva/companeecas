<?php
use Migrations\AbstractMigration;

class CreateStatements extends AbstractMigration
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
        $table = $this->table('statements');
        $table->addColumn('agreement_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('year', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('period_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('period_label', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('period_start_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('period_end_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
