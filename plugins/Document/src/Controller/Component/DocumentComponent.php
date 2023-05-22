<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Document  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Document\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;

class DocumentComponent extends Component
{
    public function test(){
        return "test";
    }

    public function getDocumentsByParent($parent = "0", $opt = []){

        $docs = TableRegistry::get('Document.Documents');

        $opt['parent'] = $parent;
        $opt['last_saved'] = 1;

        $documets = $docs->find('all')->contain(['Aziende','Orders','Tags'])->where($opt)->Order(['title' => 'ASC']);

        //echo "<pre>"; print_r($documets); echo "</pre>";

        return $documets;

    }

    public function getDocumentsByIdDocs($idDocument = "0"){

        $docs = TableRegistry::get('Document.Documents');

        $documets = $docs->find('all')->where(['id_document' => $idDocument , 'last_saved' => 1])
                ->contain(['Orders','Aziende','Tags'])->Order(['title' => 'ASC']);

        //echo "<pre>"; print_r($documets); echo "</pre>";

        return $documets;

    }

    public function getDocumentsById($idDocument = "0"){

        $docs = TableRegistry::get('Document.Documents');

        $documets = $docs->find('all')->where(['id' => $idDocument])->Order(['title' => 'ASC']);

        //echo "<pre>"; print_r($documets); echo "</pre>";

        return $documets;

    }

    public function getDocumentsRevision($id){

        $docs = TableRegistry::get('Document.Documents');

        $documets = $docs->find('all')->where(['id_document' => $id])->Order(['created' => 'DESC']);

        //echo "<pre>"; print_r($documets); echo "</pre>";

        return $documets;

    }

    public function getTotDocuments()
    {
        $docs = TableRegistry::get('Document.Documents');
        return $docs->find('all')->where(['last_saved'=>1])->count();
    }

    public function getAllClients(){

        $contacts = TableRegistry::get('Document.Contacts');
        $clients = $contacts->find('all')->where(['client' => 1])->Order(['name' => 'ASC']);

        return $clients;

    }

    public function getAllProjectByClients($idClient){

        $projects = TableRegistry::get('Document.Projects');
        $prj = $projects->find('all')->where(['id_client' => $idClient])->Order(['name' => 'ASC']);

        return $prj;

    }

    public function getAllOrdersByClients($idAzienda){

        $projects = TableRegistry::get('Aziende.Orders');
        $prj = $projects->find('all')->where(['id_azienda' => $idAzienda])->Order(['name' => 'ASC']);

        return $prj;

    }

    public function getParentsTree(){

        $d = $this->getChildTree(0);

        //echo "<pre>"; print_r($d); echo "</pre>";
        return $d;
    }

    public function getChildTree($parent){

        $docs = TableRegistry::get('Document.Documents');
        $documets = $docs->find('all')->where(['parent' => $parent, 'last_saved' => 1])
            ->Order(['position' => 'ASC','title' => 'ASC']);

        $d = array();

        foreach ($documets as $key => $doc) {

            $d[$key]['id'] = $doc->id;
            $d[$key]['id_document'] = $doc->id_document;
            $d[$key]['label'] = $doc->title;
            $d[$key]['text'] = $doc->title;
            $d[$key]['position'] = $doc->position;
            $d[$key]['children'] = $this->getChildTree($doc->id_document);
            if(empty($d[$key]['children']) || !empty($doc->text1)){
              $d[$key]['type'] = 'file';
            }

        }

        return $d;

    }

    public function mouveParentDocument($id, $parent){

        $document = $this->_get($id);

        $document->parent = $parent;

        $this->_save($document);

        return true;

    }

    public function getMyParents($id, &$list = array()){

        $document = $this->_get($id);

        //echo "<pre>"; print_r($document); echo "</pre>";

        $parent = $document->parent;
        $list[] = $id;

        $doc = TableRegistry::get('Document.Documents');
        $document = $doc->find('all')->where(['id_document' => $parent, 'last_saved' => 1]);

        $document = $document->toArray();

        //echo "<pre>"; print_r($document); echo "</pre>";

        if(isset($document[0]->parent)){
            if($document[0]->parent != 0){
                $this->getMyParents($document[0]->id, $list);
            }else{
                $list[] = $document[0]->id;
                $list = array_reverse($list);
            }
        }


        return $list;

    }

    public function getMyChild($parent)
    {
      $docs = TableRegistry::get('Document.Documents');
      return $documets = $docs->find('all')->where(['parent' => $parent, 'last_saved' => 1])
                ->Order(['position' => 'ASC','title' => 'ASC']);

    }

    public function _newEntity(){
        $docs = TableRegistry::get('Document.Documents');
        return $docs->newEntity();
    }

    public function _patchEntity($doc,$request){
        $docs = TableRegistry::get('Document.Documents');
        return $docs->patchEntity($doc,$request);
    }

    public function _save($doc){
        $docs = TableRegistry::get('Document.Documents');
        return $docs->save($doc);
    }

    public function _get($id){
        $docs = TableRegistry::get('Document.Documents');
        return $docs->get($id , ['contain' => ['Aziende','Orders','Tags' => ['sort' => ['Tags.name' => 'ASC']]]]);

    }

    public function _delete($doc){
        $docs = TableRegistry::get('Document.Documents');
        return $docs->delete($doc);
    }

    public function moveDocument($id, $id_parent){

        $document = $this->_get($id);
        if($id_parent != 0){
          $parent = $this->_get($id_parent);
          $id_parent = $parent->id_document;
        }

        $document->parent = $id_parent;


        return $this->_save($document);

    }

    public function moveDocumnetParent($data)
    {
        $docs = TableRegistry::get('Document.Documents');
        $document = $this->_get($data['id']);
        if(!$document->last_saved){
          $document = $docs->find()->where(['id_document'=>$document->id_document,
            'last_saved' => 1])->first();
        }
        $data['old_parent'] = $document->parent;

        if($data['parent'] != 0){
          $parent = $this->_get($data['parent']);
          $data['parent'] = $parent->id_document;
        }//debug($data['parent']);die;
        $docs->updateAll([new QueryExpression('position = (position + 1)')],
            ['last_saved' => 1, 'parent' => $data['parent'], 'position >='=>$data['position']]);
        $document->parent = $data['parent'];
        $document->position = $data['position'];
        $res = $this->_save($document);

        if($res){
            $docs->updateAll([new QueryExpression('position = (position - 1)')],
              ['last_saved' => 1, 'parent' => $data['old_parent'] ,'position >=' => $data['old_position'] ]);
        }else{
            $docs->updateAll([new QueryExpression('position = (position - 1)')],
                ['last_saved' => 1, 'parent' => $data['parent'], 'position >='=>$data['position']]);
        }

        return $res;

    }

    public function moveDocumentPosition($data)
    {
        $docs = TableRegistry::get('Document.Documents');
        $document = $this->_get($data['id']);
        if(!$document->last_saved){
          $document = $docs->find()->where(['id_document'=>$document->id_document,
            'last_saved' => 1])->first();
        }
        $data['parent'] = $document->parent;

        $docs->updateAll([new QueryExpression('position = (position - 1)')],['last_saved' => 1, 'parent' => $data['parent'] ,
            'position >'=> $data['old_position'] , 'position <=' => $data['position']]);
        $docs->updateAll([new QueryExpression('position = (position + 1)')],['last_saved' => 1, 'parent' => $data['parent'] ,
            'position <'=> $data['old_position'] , 'position >=' => $data['position']]);

        $document->position = $data['position'];
        $res = $this->_save($document);
        if(!$res){
            $docs->updateAll([new QueryExpression('position = (position + 1)')],['last_saved' => 1, 'parent' => $data['parent'] ,
                'position >'=> $data['old_position'] , 'position <=' => $data['position']]);
            $docs->updateAll([new QueryExpression('position = (position - 1)')],['last_saved' => 1, 'parent' => $data['parent'] ,
                'position <'=> $data['old_position'] , 'position >=' => $data['position']]);
        }

        return $res;

    }

    public function setPositionForAll($documents)
    {
        $pos = 0;
        $docs = TableRegistry::get('Document.Documents');
        $query = $docs->query();
        $query->insert(['position', 'id']);
        foreach($documents as $doc){

            $query->values(['position' => $pos,'id' => $doc['id']]);
            $pos++;

            if(!empty($doc['children']) ){
              $this->setPositionForAll($doc['children']);
            }
        }
        $query->epilog('ON DUPLICATE KEY UPDATE `position` = VALUES(`position`) ')->execute();
    }

    public function getChildTreeGeneration($parent,$section = ''){

        $docs = TableRegistry::get('Document.Documents');
        $documets = $docs->find('all')->where(['parent' => $parent, 'last_saved' => 1])
                ->contain('Tags')->Order(['position'=>'ASC','title' => 'ASC']);

        $d = array();
        $cap = 1;
        foreach ($documets as $key => $doc) {

            $d[$key]['id'] = $doc->id;
            $d[$key]['id_document'] = $doc->id_document;
            $d[$key]['label'] = $doc->title;
            $d[$key]['text'] = $doc->title;
            $d[$key]['section'] = $section.$cap;
            $d[$key]['children'] = $this->getChildTreeGeneration($doc->id_document,$section.$cap.'.');
            if(empty($d[$key]['children']) || !empty($doc->text1)){
              $d[$key]['type'] = 'file';
            }
            $d[$key]['content'] = $doc->text1;
            $d[$key]['tags'] = $doc->tags;

            $cap++;
        }

        return $d;

    }

    public function generateDocumentFromChildren($data, $document = '' , $opt)
    {
        $optDefault = ['heading' => 1, 'headingMax' => 6, 'tags' => array(),'section' => true,'style'=>''];
        $opt = array_merge($optDefault,$opt);

        foreach($data as $doc){
            $find = true;
            if(!empty($opt['tags'])){
                $find = 0;
                foreach($doc['tags'] as $tag){
                    if(array_search($tag['id'], $opt['tags']) !== false){
                      $find++;
                    }
                }
                if($find < count($opt['tags'])){
                  $find = false;
                }
            }
            if($find){
                $section = ($opt['section'] ? $doc['section']." - " :'' );
                $document .= "<h".$opt['heading']." ".$opt['style'].">".$section.$doc['label']."</h".$opt['heading'].">";
                $document .= $doc['content'];
            }
            if(!empty($doc['children'])){
              $headingChild = ($opt['heading'] < $opt['headingMax'] ? $opt['heading']+1 : $opt['heading'] );
              $optChild = array_merge($opt,['heading' => $headingChild]);
              $document = $this->generateDocumentFromChildren($doc['children'], $document, $optChild);
            }
        }

        return $document;

    }

    public function orderDocuments($id = 0, $order = '')
    {
        $docsTable = TableRegistry::get('Document.Documents');
        try {
            $doc = $docsTable->get($id);
            switch ($order) {
              case 'ASC':
                $order = 'ASC';
                break;
              case 'DESC':
                $order = 'DESC';
                break;
              default:
                $order = '';
            }
            if(!empty($order)){

              $documets = $docsTable->find('all')->where(['parent' => $doc['id_document'], 'last_saved' => 1])
                  ->Order(['title' => $order])->toArray();
              $this->setPositionForAll($documets);
            }
            return true;

        } catch (\Exception $e) {

            return false;
        }
    }

}
