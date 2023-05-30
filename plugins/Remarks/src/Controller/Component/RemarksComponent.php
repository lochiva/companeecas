<?php
/**
* Remarks is a plugin for manage attachment
*
* Companee :    Remarks  (https://www.companee.it)
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

namespace Remarks\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class RemarksComponent extends Component
{

	public function saveRemark($data)
	{
		$remarks = TableRegistry::get('Remarks.Remarks');

		if(!empty($data['id'])){ 
			$remark = $remarks->get($data['id']);
			if($data['check_attachment'] == 'true'){
				unset($data['attachment']);
			}
			$remark = $remarks->patchEntity($remark, $data);
		}else{
			$remark = $remarks->newEntity($data);
		}

		$res = $remarks->save($remark);

		return $res;
	}

	public function deleteRemark($remarkId)
	{
		$res = TableRegistry::get('Remarks.Remarks')->deleteRemark($remarkId);
		return $res;
	}

	public function getRemarksByRef($reference, $userId, $showDeleted)
    {
		$res = TableRegistry::get('Remarks.Remarks')->getRemarksByRef($reference, $userId, $showDeleted);

		$remarks = [];
		foreach($res as $remark){
			$remarks[] = [
				'id' => $remark['id'],
				'user_id' => $remark['user_id'],
				'reference' => $remark['reference'],
				'reference_id' => $remark['reference_id'],
				'remark' => $remark['remark'],
				'rating' => $remark['rating'],
				'attachment' => $remark['attachment'],
				'deleted' => $remark['deleted'],
				'created' => $remark['created']->format('d/m/Y H:i:m'),
				'user_name' => $remark['user']['nome'],
				'user_surname' => $remark['user']['cognome'],
			];
		}

		return $remarks;
	}

	public function getRemarksByRefId($reference, $referenceId, $userId, $showDeleted)
    {
		$res = TableRegistry::get('Remarks.Remarks')->getRemarksByRefId($reference, $referenceId, $userId, $showDeleted);

		$remarks = [];
		foreach($res as $remark){

			if($userId == $remark['user_id'] && !$remark['deleted']){
				$buttonEdit = '<a href="#" class="edit-remark" data-id="'.$remark['id'].'"><i class="fa fa-pencil" title="Modifica nota"></i></a>';
			}else{
				$buttonEdit = '';
			}

			if($remark['deleted']){
				$buttonDelete = '';
			}else{
				$buttonDelete = '<a href="#" class="delete-remark" data-id="'.$remark['id'].'"><i class="fa fa-trash" title="Elimina nota"></i></a>';
			}

			if($remark['attachment'] != ''){
				$attachment = '<a href="#" class="download-attachment" data-id="'.$remark['id'].'" ><i class="fa fa-paperclip" title="Scarica allegato"></i></a>';
			}else{
				$attachment = '';
			}
			
			if($remark['visibility']){
				$private = '<i class="fa fa-lock private-icon" title="Nota privata"></i>';
			}else{
				$private = '';
			}
			
			$remarks[] = [
				'id' => $remark['id'],
				'user_id' => $remark['user_id'],
				'reference' => $remark['reference'],
				'reference_id' => $remark['reference_id'],
				'remark' => $remark['remark'],
				'rating' => $this->remarkRatingHtml($remark['rating']),
				'deleted' => $remark['deleted'],
				'created' => $remark['created']->format('d/m/Y H:i:m'),
				'user_name' => $remark['user']['nome'],
				'user_surname' => $remark['user']['cognome'],
				'user_img' => $this->userImage($remark['user_id'],'remark-img'),
				'button_edit' => $buttonEdit,
				'button_delete' => $buttonDelete,
				'attachment' => $attachment,
				'private' => $private
			];
		}

		return $remarks;
	}

	public function getRemark($remarkId)
	{
		$res = TableRegistry::get('Remarks.Remarks')->get($remarkId);

		$remark = [
			'id' => $res['id'],
			'remark' => $res['remark'],
			'rating' => $res['rating'],
			'visibility' => $res['visibility'],
			'attachment' => $res['attachment']
		];

		return $remark;
	}

	public function getRemarksNumber($reference, $referenceId, $userId)
	{
		$res = TableRegistry::get('Remarks.Remarks')->getRemarksNumber($reference, $referenceId, $userId);

		return count($res);
	}

	public function userImage($userId, $opt)
	{
		if(!is_array($opt)){
			$opt = ['alt' => 'User image', 'class' => $opt];
		}
		$opt['alt'] = 'User image';

		if(file_exists(WWW_ROOT.'img/user/'.$userId.'.jpg')){ 
			return '<img src="'.Router::url("/img/user/".$userId.".jpg").'" alt="'.$opt['alt'].'" class="'.$opt['class'].'" />';
		}else{
			return '<img src="'.Router::url("/img/user.png").'" alt="'.$opt['alt'].'" class="'.$opt['class'].'" />';
		}
	}

	public function remarkRatingHtml($rating)
	{
		$ratingHtml = '<div class="rating-stars">';
		$ratingHtml .= '<ul class="stars-readonly">';

		for($i = 1; $i <= 5; $i++){

			switch($i){
				case '1':
					$title = 'Scarso';
					break;
				case '2':
					$title = 'Mediocre';
					break;
				case '3':
					$title = 'Buono';
					break;
				case '4':
					$title = 'Ottimo';
					break;
				case '5':
					$title = 'Eccellente';
					break;
			}

			if($i <= $rating){
				$ratingHtml .= '<li class="star selected" title="'.$title.'" data-value="'.$i.'"><i class="fa fa-star fa-fw"></i></li>';
			}else{
				$ratingHtml .= '<li class="star" title="'.$title.'" data-value="'.$i.'"><i class="fa fa-star fa-fw"></i></li>';
			}

		}
		
		$ratingHtml .= '</ul>';
		$ratingHtml .= '</div>';

		return $ratingHtml;
	}

}
