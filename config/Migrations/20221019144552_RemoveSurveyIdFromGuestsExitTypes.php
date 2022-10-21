<?php
use Migrations\AbstractMigration;

class RemoveSurveyIdFromGuestsExitTypes extends AbstractMigration
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
        $table->removeColumn('survey_id');
        $table->removeColumn('survey_notes');
        $table->addColumn('modello_decreto', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modello_notifica', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
