<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class UtilsHelper extends Helper
{
    public $helpers = ['Html'];

    public function userImage($id, $opt)
    {
        if(!is_array($opt)){
          $opt = ['alt' => 'User image', 'class' => $opt];
        }
        $opt['alt'] = 'User image';

        if (file_exists(WWW_ROOT.'img/user/'.$id.'.jpg')) {
            return $this->Html->image('user/'.$id.'.jpg', $opt);
        } else {
            return $this->Html->image('user.png', $opt);
        }
    }

    public function checkActiveMenu($opt, $menu = false)
    {
        if($menu){
          $active = 'menu-open';
        }else{
          $active = 'active';
        }

        foreach($opt as $key => $name){
            $key = explode(' ',$key);
            if(!empty($key[1]) && $key[1] === '!='){
              if($this->request->{$key[0]} == $name){
                $active = '';
                break;
              }
            }else{
              if($this->request->{$key[0]} != $name){
                $active = '';
                break;
              }
            }
        }

        return $active;
    }

    public function newCheckActiveMenu($opt, $menu = false)
    {
        if($menu){
          $active = 'menu-open';
        }else{
          $active = 'active';
        }

        foreach($opt as $key => $name){
            $key = explode(' ',$key);
            if(!empty($name)){
              if(!empty($key[1]) && $key[1] === '!='){
                //if($this->request->{$key[0]} == $name){
                if(in_array($this->request->{$key[0]}, $name)){
                  $active = '';
                  break;
                }
              }else{
                //if($this->request->{$key[0]} != $name){
                if(!in_array($this->request->{$key[0]}, $name)){
                  $active = '';
                  break;
                }
              }
            }
        }

        return $active;
    }

    public function templateCss()
    {
      $template_skin_color = Configure::read('dbconfig.generico.SKIN_COLOR');
      $template_skin_type = (Configure::read('dbconfig.generico.SKIN_LIGTH')? '-light' :'' );

      return $this->Html->css('skins/skin-'.$template_skin_color.$template_skin_type.'.min.css').
             $this->Html->css('../plugins/iCheck/flat/'.$template_skin_color.'.css');

    }

    public function templateClass()
    {
      $template_skin_color = Configure::read('dbconfig.generico.SKIN_COLOR');
      $template_skin_type = (Configure::read('dbconfig.generico.SKIN_LIGTH')? '-light' :'' );

      return $template_skin_color.$template_skin_type;

    }

    public function templateColor()
    {
        return Configure::read('dbconfig.generico.SKIN_COLOR');
    }

    public function templateType()
    {
        return (Configure::read('dbconfig.generico.SKIN_LIGTH')? '-light' :'' );
    }

    public function printSectionSurvey($section, $index, $label){
      $html = '<br>';
      $html .= '<h2 class="box-surveys-title">'.$label.($index+1).' '.$section->title.'</h2>';
      $html .= '<div class="section-div-pdf">';
      $html .= '<h3>'.$section->subtitle.'</h3>';
  
      foreach($section->questions as $key => $question){
  
        if($question->visible){
  
          $html .= '<div class="question-div-pdf">';
  
          //TESTO LIBERO
          if($question->type == 'free_text' || $question->type == 'standard_text'){ 
            $html .= '<div>'.$question->value.'</div>';
          }
  
          //IMMAGINE
          if($question->type == 'image' && $question->path != ''){ 
            $basePath = Configure::read('dbconfig.surveys.ELEMENT_IMAGE_FILE_BASE_PATH');
            $html .= '<img src="'.$basePath.$question->path.'" class="element-image-pdf" ><br>';
            $html .= '<span>'.$question->caption.'</span>';
          } 
  
          //RISPOSTA BREVE, RISPOSTA APERTA, NUMERO 
          if(($question->type == 'short_answer' || $question->type == 'free_answer' || $question->type == 'number') && $question->question != ''){  
            $html .= '<span class="question-text-pdf">'.$question->question.'</span><br>';
            $html .= '<span class="text-tooltip-pdf">'.($question->tooltip != '' ? $question->tooltip.'<br>' : '').'</span>';
            $html .= '<span>'.$question->answer.'</span>';
          } 
  
          //DATA
          if($question->type == 'date' && $question->question != ''){  
            $html .= '<span class="question-text-pdf">'.$question->question.'</span><br>';
            $html .= '<span class="text-tooltip-pdf">'.($question->tooltip != '' ? $question->tooltip.'<br>' : '').'</span>';
            $html .= '<span>'.implode('/', array_reverse(explode('-', substr($question->answer, 0 ,10)))).'</span>';
          } 
  
          //RADIO SI/NO
          if($question->type == 'yes_no' && $question->question != ''){
            $html .= '<span class="question-text-pdf">'.$question->question.'</span><br>';
            $html .= '<span class="text-tooltip-pdf">'.($question->tooltip != '' ? $question->tooltip.'<br>' : '').'</span>';
            $html .= '<span>'.($question->answer == 'yes' ? 'Sì' : 'No').'</span>';
          }
  
          //SCELTA SINGOLA
          if($question->type == 'single_choice' && $question->question != ''){ 
            $html .= '<span class="question-text-pdf">'.$question->question.'</span>';
            $html .= '<span class="text-tooltip-pdf">'.($question->tooltip != '' ? '<br>'.$question->tooltip : '').'</span>';
            foreach($question->options as $i => $option){
              if($i == $question->answer->check){
                if($option->extended){
                  $html .= '<br><input type="radio" checked /> '.$option->text.': '.$question->answer->extensions[$i];
                }else{
                  $html .= '<br><input type="radio" checked /> '.$option->text;
                }
              }else{
                $html .= '<br><input type="radio" /> '.$option->text;
              }
            }
          } 
  
          //SCELTA MULTIPLA
          if($question->type == 'multiple_choice' && $question->question != ''){ 
            $html .= '<span class="question-text-pdf">'.$question->question.'</span>';
            $html .= '<span class="text-tooltip-pdf">'.($question->tooltip != '' ? '<br>'.$question->tooltip : '').'</span>';
            foreach($question->options as $i => $option){
              if($question->answer[$i]->check){
                if($option->extended){
                  $html .= '<br><input type="checkbox" checked /> '.$option->text.': '.$question->answer[$i]->extended;
                }else{
                  $html .= '<br><input type="checkbox" checked /> '.$option->text;
                }
              }else{
                $html .= '<br><input type="checkbox" /> '.$option->text;
              }
            }
            if($question->other){
              if($question->other_answer_check){
                $html .= '<br><input type="checkbox" checked /> ALTRO: '.$question->other_answer;
              }else{
                $html .= '<br><input type="checkbox" /> ALTRO';
              }
            }
          }
  
          //TABELLA
          if($question->type == 'table' && $question->question != ''){ 
            $html .= '<span class="question-text-pdf">'.$question->question.'</span><br>';
            $html .= '<span class="text-tooltip-pdf">'.($question->tooltip != '' ? $question->tooltip.'<br>' : '').'</span>';
            $html .= '<table class="table-question-table-pdf">';
            $html .= '<thead>';
            $html .= '<tr>';
            foreach($question->headers as $header){
              $html .= '<th>'.$header.'</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            foreach($question->answer as $answer){
              $html .= '<tr>';
                foreach($answer as $a){
                  $html .= '<td>'.$a.'</td>';
                }
              $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
          } 
          
          $html .= '</div>';
        }
      }
  
      foreach($section->items as $subIndex => $item){
        $html .= $this->printSectionSurvey($item, $subIndex, $label.($index+1).'.');
      }
  
      $html .= '</div>';
  
      return $html;
    }

    public function isValidEnte($userId)
    {
      return TableRegistry::get('Aziende.Contatti')->isValidEnte($userId);
    }

    public function hasNodoLogo($userId)
    {
		$logoPath = TableRegistry::get('Aziende.Aziende')->getNodoLogo($userId);
		
      	if($logoPath){
          $path = ROOT.DS.Configure::read('dbconfig.aziende.LOGO_PATH').$logoPath;
          $type = pathinfo($path, PATHINFO_EXTENSION);
          $data = file_get_contents($path);
          $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

          return $base64;
      	}

      	return false;
    }

    public function getEnteIDByUserLoggedIn()
    {
        $userId = $this->request->session()->read('Auth.User.id');
        $contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($userId);
      	return empty($contatto) ? '' : $contatto['id_azienda'];
    }
}
