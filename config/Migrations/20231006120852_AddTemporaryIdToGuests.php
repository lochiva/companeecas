<?php
use Migrations\AbstractMigration;

class AddTemporaryIdToGuests extends AbstractMigration
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
        $table = $this->table('guests');
        $table->addColumn('temporary_id', 'string', [
            'default' => '',
            'limit' => 20,
            'null' => false,
            'after' => 'vestanet_id'
        ]);
        $table->update();
    }
}
