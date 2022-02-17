<?php
use Migrations\AbstractMigration;

class CreateAgreements extends AbstractMigration
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
        $table->addColumn('procedure_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['procedure_id']);
        $table->addColumn('date_agreement', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('date_agreement_expiration', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('date_extension_expiration', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('guest_daily_price','decimal', [
            'scale' => 2,
            'precision' => 10,
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}