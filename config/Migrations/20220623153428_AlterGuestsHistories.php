<?php
use Migrations\AbstractMigration;

class AlterGuestsHistories extends AbstractMigration
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
        $table = $this->table('guests_histories');
        $table->addColumn('file', 'string', [
            'after' => 'provenance_id',
            'default' => null,
            'null' => true
        ]);
        $table->update();
    }
}