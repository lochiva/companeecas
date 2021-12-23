<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use WyriHaximus\TwigView\View\TwigView;
use Cake\Core\Configure;

/**
 * App View class
 */
class AppView extends TwigView
{

    public $customElements = array();
    /**
     * Initialization hook method.
     *
     * For e.g. use this method to load a helper for all views:
     * `$this->loadHelper('Html');`
     *
     * @return void
     */
     public function initialize()
     {
         parent::initialize();
         $this->loadHelper('Utils');
         $this->loadHelper('Html');
         $this->loadHelper('Form');
         $this->loadHelper('Url');
         $this->customElements = Configure::read('custom.elements');
     }

     /**
      * Override element method of view, search for custom elements setted.
      * @param  [type] $name    [description]
      * @param  [type] $data    [description]
      * @param  [type] $options [description]
      * @return [type]          [description]
      */
     public function element($name, array $data = [], array $options = [])
     {

          if(!empty($this->customElements[$name])){
              $name = $this->customElements[$name];
          }
          return parent::element($name, $data, $options);
     }

}
