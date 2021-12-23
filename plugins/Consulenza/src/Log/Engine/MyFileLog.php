<?php
namespace Consulenza\Log\Engine;

use Cake\Core\Configure;
use Cake\Log\Engine\FileLog;
use Cake\Log\Engine\BaseLog;
use Cake\Utility\Text;
use Cake\Network\Session;


class MyFileLog extends FileLog
{

    
    public function log($level, $message, array $context = [])
    {
        
        $message = $this->_format($message, $context);

        $session = new Session();
        $user = $session->read('Auth.User');

        if(!isset($user['id'])){
            $user['id'] = "undefined";
        }

        $output = '[DATE:' . date('Y-m-d H:i:s') . '][UID:' . $user['id'] . '][LEVEL:' . ucfirst($level) . '][MSG:' . $message . "]\n";
        $filename = $this->_getFilename($level);
        if (!empty($this->_size)) {
            $this->_rotateFile($filename);
        }

        $pathname = $this->_path . $filename;
        $mask = $this->_config['mask'];
        if (empty($mask)) {
            return file_put_contents($pathname, $output, FILE_APPEND);
        }

        $exists = file_exists($pathname);
        $result = file_put_contents($pathname, $output, FILE_APPEND);
        static $selfError = false;

        if (!$selfError && !$exists && !chmod($pathname, (int)$mask)) {
            $selfError = true;
            trigger_error(vsprintf(
                'Could not apply permission mask "%s" on log file "%s"',
                [$mask, $pathname]
            ), E_USER_WARNING);
            $selfError = false;
        }

        return $result;
    }

}
