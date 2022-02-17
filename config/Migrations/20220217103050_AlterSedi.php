<?php
use Migrations\AbstractMigration;

class AlterSedi extends AbstractMigration
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
        $table = $this->table('sedi');
        $table->addColumn('code_centro', 'string', [
            'limit' => 8,
            'null' => false,
            'after' => 'id_azienda'
        ])
        ->addIndex(['code_centro']);
        $table->renameColumn('id_tipo', 'id_tipo_ministero');
        $table->addColumn('id_tipo_capitolato', 'integer', [
            'limit' => 255,
            'null' => false,
            'after' => 'id_tipo_ministero'
        ])
        ->addIndex(['id_tipo_capitolato']);
        $table->renameColumn('n_posti_convenzione', 'n_posti_struttura');
        $table->update();
    }
}