<?php
use Migrations\AbstractMigration;

class AlterAgreements3 extends AbstractMigration
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
        $table = $this->table('agreements');
        $table->addColumn('cig', 'string', [
            'limit' => 10,
            'null' => true,
            'after' => 'guest_daily_price'
        ]);
        $table->update();
    }
}