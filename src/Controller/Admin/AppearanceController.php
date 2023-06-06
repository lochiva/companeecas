<?php
/**
 * Companee :    Appearance (https://www.companee.it)
 * Copyright (c) lochiva , (http://www.lochiva.it)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;


use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class AppearanceController extends AppController
{

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {

            //Per aggiungere o cancellare configurazioni devi avere un livello superiore al 900
            if ($user['level'] < 500 && ($this->request->action == 'index' || $this->request->action == 'add_background' || $this->request->action == 'delete_background')) {
                return false;
            }

            return true;
        }

        // Default deny
        return false;
    }

    public function index()
    {
        $backgrounds = TableRegistry::get('AppearanceBackgrounds')->getList();

        $this->set('backgrounds', $backgrounds);
    }

    public function addBackground()
    {
        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');

        $result['response'] = "KO";
        $result['data'] = '';

        //Salvataggio immagine nella cartella webroot/backgrounds
        $background = $this->request->data['background_image'];

        $filepath = 'webroot'.DS.'backgrounds'.DS;
        $displaypath = 'backgrounds'.DS;
        $name = $background['name'];

        if(!is_dir(ROOT.DS.$filepath) && !mkdir(ROOT.DS.$filepath, 0755, true)){
            $result['msg'] = "Errore durante il salvataggio del file. ROOT.DS.$filepath ";
        }else{

            if(file_exists(ROOT.DS.$filepath.$name)){
                $result['msg'] = "Esiste gia un'immagine di sfondo con questo nome.";
            }else{

                if(!move_uploaded_file($background['tmp_name'], ROOT.DS.$filepath.DS.$name) ){
                    $result['msg'] = "Errore durante il salvataggio del file.";
                }else{

                    //Creazione record relativo all'immagine nel db
                    $backgrounds = TableRegistry::get('AppearanceBackgrounds');

                    $entity = $backgrounds->newEntity();

                    $entity->name = $name;
                    $entity->path = $displaypath;
                    
                    if(!$backgrounds->save($entity)){
                        $result['msg'] = "Errore durante la creazione del record nel database.";
                    }else{
                        $result['response'] = "OK";
                        $result['msg'] = "Salvataggio avvenuto.";
                    }
                }
            }
        }

       
        $this->set('result', json_encode($result));
    }

    public function deleteBackground($backgroundId = 0)
    {
        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');

        $result['response'] = "KO";
        $result['data'] = '';

        if($backgroundId != 0){
            //Spostamento immagine nella cartella webroot/backgrounds/deleted
            $backgrounds = TableRegistry::get('AppearanceBackgrounds');

            $background = $backgrounds->get($backgroundId);

            $path = ROOT.DS.'webroot'.DS.'backgrounds'.DS;
            $pathDeleted = ROOT.DS.'webroot'.DS.'backgrounds'.DS.'deleted'.DS;
            $name = $background->name;
            $nameDeleted = $background->name;

            if(!is_dir($path) || (!is_dir($pathDeleted) && !mkdir($pathDeleted, 0755, true))){
                $result['msg'] = "Errore durante l'eliminazione dell'immagine.";
            }else{

                if(file_exists($pathDeleted.$nameDeleted)){
                    $nameDeleted = uniqid().$nameDeleted;
                }

                if(!rename($path.$name, $pathDeleted.$nameDeleted) ){
                    $result['msg'] = "Errore durante l'eliminazione dell'immagine.";
                }else{

                    //deleted = 0 per il record relativo all'immagine nel db
                    $background->name = $nameDeleted;
                    $background->deleted = '1';
                    
                    if(!$backgrounds->save($background)){
                        $result['msg'] = "Errore durante l'update del record nel database.";
                    }else{
                        $result['response'] = "OK";
                        $result['msg'] = "Eliminazione avvenuta.";
                    }
                }
            }
        }else{
            $result['msg'] = "Errore durante l'eliminazione dell'immagine: id mancante.";
        }

       
        $this->set('result', json_encode($result));
    }


}
