<?php
use Migrations\AbstractMigration;

class AddDefaultToAgreementsCompanies extends AbstractMigration
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
        $table = $this->table('agreements_companies');
        $table->addColumn('isDefault', 'boolean', [
            'default' => 0,
            'null' => false,
            'after' => 'name'
        ]);
        $table->update();
    }
}
