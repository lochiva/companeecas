<?php
use Migrations\AbstractMigration;

class AddGuestsEducationalQualifications extends AbstractMigration
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
        $rows = [
            [
                'id' => 13,
                'name' => 'Area scientifica ',
                'ordering' => 75,
                'parent' => 6,
                'have_children' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('guests_educational_qualifications', $rows);
    }
}