<?php
use Migrations\AbstractMigration;

class AddToSurveyPlaceholders extends AbstractMigration
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
        $this->query("INSERT INTO `surveys_placeholders` (`id`, `label`, `description`, `created`, `modified`) VALUES (NULL, '{{soggetto_e_familiari}}', 'Dati relativi all\'ospite stesso e ai suoi familiari (se presenti)', NOW(), NOW());");
    }
}
