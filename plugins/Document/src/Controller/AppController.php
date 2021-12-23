<?php

namespace Document\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    protected function lockWrite()
    {
        $lockFile = ROOT.DS.'src'.DS.'files'.DS.'lockDoc';
        if(file_exists($lockFile)){
            return false;
        }else{
            touch($lockFile);
            return true;
        }

    }

    protected function unlockWrite()
    {
        $lockFile = ROOT.DS.'src'.DS.'files'.DS.'lockDoc';
        unlink($lockFile);
    }

}
