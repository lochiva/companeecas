<?php
use Migrations\AbstractMigration;

class AddToPayments3 extends AbstractMigration
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
        $table = $this->table('payments');
        $table->addColumn('billing_vat_amount', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 10,
            'scale' => 2,
            'after' => 'net_amount'
        ]);
        $table->addColumn('billing_net_amount', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 10,
            'scale' => 2,
            'after' => 'vat_amount'
        ]);

        $table->update();
    }
}
