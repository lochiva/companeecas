<?php
use Migrations\AbstractMigration;

class AddToGuestsExitTypes extends AbstractMigration
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
        $table = $this->table('guests_exit_types');
        $table->addColumn('survey_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('survey_notes', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
