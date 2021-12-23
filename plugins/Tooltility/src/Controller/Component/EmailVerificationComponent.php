<?php
namespace Tooltility\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Tooltility\Utility\VerifyEmail\VerifyEmail;

class EmailVerificationComponent extends Component
{

	public function emailExists($email)
	{
		// Initialize library class
        $mail = new VerifyEmail();

        // Set debug output mode
        $mail->Debug = false; //Configure::read('debug'); 
        $mail->Debugoutput = 'html'; 

        $sender = Configure::read('dbconfig.tooltility.VERIFY_EMAIL_SENDER');
        // Set email address for SMTP request
        $mail->setEmailFrom($sender);

		return $mail->check($email);

	}
}
