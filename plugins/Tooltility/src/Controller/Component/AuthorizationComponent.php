<?php
namespace Tooltility\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

class AuthorizationComponent extends Component
{

	public function verifyIp()
	{
		$listIps = Configure::read('dbconfig.tooltility.AUTHORIZED_IPS');

		$authorizedIps = explode(',', $listIps);

		$ip = $this->request->clientIp();

		if(in_array($ip, $authorizedIps)){
			return true;
		}

		return false;
	}

}
