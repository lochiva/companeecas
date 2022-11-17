<?php
use Migrations\AbstractMigration;

class InsertStatusintoStatuses extends AbstractMigration
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
        $rows = [
            [
                'id' => 3,
                'ordering' => 3,
                'name' => 'Annullato',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('surveys_statuses', $rows);
    }
}
