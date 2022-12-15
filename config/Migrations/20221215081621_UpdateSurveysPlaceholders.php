<?php
use Migrations\AbstractMigration;

class UpdateSurveysPlaceholders extends AbstractMigration
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
        $this->query("UPDATE `surveys_placeholders` SET `label` = REPLACE(`label`, 'sede_', 'struttura_') WHERE `label` LIKE '%sede_%'");
    }
}
