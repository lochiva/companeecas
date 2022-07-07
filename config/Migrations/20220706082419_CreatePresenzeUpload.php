<?php
use Migrations\AbstractMigration;

class CreatePresenzeUpload extends AbstractMigration
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
        $table = $this->table('presenze_upload');
/*         $table->addColumn('id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]); */
        $table->addColumn('sede_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('file', 'text', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('filepath', 'text', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('deleted', 'boolean', [
            'default' => null,
            'default' => false,
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
        $table->addIndex(['sede_id']);
        $table->create();
    }
}
