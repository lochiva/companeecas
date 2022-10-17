<?php
namespace Surveys\Controller\Admin;

use Cake\Routing\Router;
use Surveys\Controller\AppController as BaseController;

class AppController extends BaseController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }

}
