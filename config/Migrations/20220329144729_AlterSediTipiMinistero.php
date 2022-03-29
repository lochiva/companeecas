<?php
use Migrations\AbstractMigration;

class AlterSediTipiMinistero extends AbstractMigration
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
        $table = $this->table('sedi_tipi_ministero');
        $table->addColumn('ente_type', 'integer', [
            'limit' => 11,
            'default' => 1,
            'null' => false,
            'after' => 'color'
        ]);
        $table->update();
    }
}