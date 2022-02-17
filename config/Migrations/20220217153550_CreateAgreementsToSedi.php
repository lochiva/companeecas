<?php
use Migrations\AbstractMigration;

class CreateAgreementsToSedi extends AbstractMigration
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
        $table = $this->table('agreements_to_sedi');
        $table->addColumn('agreement_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['agreement_id']);
        $table->addColumn('sede_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['sede_id']);
        $table->addColumn('capacity', 'decimal', [
            'scale' => 2,
            'precision' => 10,
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('active', 'boolean', [
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