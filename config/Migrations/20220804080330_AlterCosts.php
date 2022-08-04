<?php
use Migrations\AbstractMigration;

class AlterCosts extends AbstractMigration
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
        $table = $this->table('costs');
        $table->changeColumn('amount', 'decimal', [
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->changeColumn('share', 'decimal', [
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->update();
    }
}
