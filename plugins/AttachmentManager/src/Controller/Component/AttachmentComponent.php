<?php

namespace AttachmentManager\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class AttachmentComponent extends Component
{
	public function getAttachmentsNumber($context, $idItem)
	{
		$res = TableRegistry::get('AttachmentManager.Attachments')->getAttachmentsNumber($context, $idItem);

		return count($res);
	}
}
