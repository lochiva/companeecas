<?php
use Migrations\AbstractMigration;

class AddToPayments extends AbstractMigration
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
        $table->addColumn('vat_amount', 'decimal', [
            'default' => null,
            'null' => false,
            'precision' => 10,
            'scale' => 2,
            'after' => 'net_amount'
        ]);
        $table->addColumn('oa_number_vat', 'string', [
            'default' => null,
            'limit' => 16,
            'null' => false,
            'after' => 'oa_number'
        ]);
        $table->addColumn('os_number_vat', 'string', [
            'default' => null,
            'limit' => 16,
            'null' => false,
            'after' => 'os_number'
        ]);
        $table->addColumn('os_date_vat', 'date', [
            'default' => null,
            'null' => false,
            'after' => 'os_date'
        ]);

        $table->update();

        $table->renameColumn('os_number', 'os_number_net');
        $table->renameColumn('oa_number', 'oa_number_net');
        $table->renameColumn('os_date', 'os_date_net');


        $table->update();

    }
}
