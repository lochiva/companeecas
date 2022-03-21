<?php
use Migrations\AbstractMigration;

class AlterAziende extends AbstractMigration
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
        $table = $this->table('aziende');
        $table->addColumn('id_tipo', 'integer', [
            'default' => 1,
            'null' => false,
            'after' => 'pa_codice'
        ]);
        $table->update();
    }
}