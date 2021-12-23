<?php

namespace Ficgtw\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Http\Client;

class FattureComponent extends Component
{

	public function addFattura($data){

		$data["api_uid"] = Configure::read('dbconfig.ficgtw.API_UID');
        $data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $url = 'https://api.fattureincloud.it/v1/fatture/nuovo';

        $response = $http->post(
            $url,
            json_encode($data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

        return $res;

	}

}
