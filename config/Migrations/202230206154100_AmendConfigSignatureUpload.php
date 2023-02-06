<?php
use Migrations\AbstractMigration;

class AmendConfigSignatureUpload extends AbstractMigration
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
        $this->query('UPDATE `configurations` SET `key_conf` = "SIGNATURE_UPLOAD_PATH" WHERE `key_conf` = "SIGNATURE_UPLAOD_PATH";');
    }
}
