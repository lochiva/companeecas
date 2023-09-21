<?php

use Migrations\AbstractMigration;

class UpdateStatementCompany extends AbstractMigration
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
        $this->execute("UPDATE
        `statement_company`
    LEFT JOIN `statements_status_history` ON `statement_company`.`id` = `statements_status_history`.`statement_company_id`
    SET
        `due_date` = DATE_ADD(
            `statements_status_history`.`created`,
            INTERVAL 1 MONTH
        )
    WHERE
        `statement_company`.`status_id` = 4 AND `due_date` IS NULL AND `statement_company`.`status_id` = 4 AND `statements_status_history`.`status_id` = 4;");
    }
}
