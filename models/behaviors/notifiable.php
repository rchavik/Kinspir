<?php
class NotifiableBehavior extends ModelBehavior {

	public function notify(&$Model, $userIds, $foreignId, $notificationTypeId) {
		$userIds = (array)$userIds;
		$notification = array(
			'user_id' => User::get('id'),
			'notification_type_id' => $notificationTypeId,
			'class' => $Model->name,
			'foreign_id' => $foreignId
		);
		$userIds = array_diff($userIds, (array)User::get('id'));
		foreach ($userIds as $userId) {
			$notifications['Notification'][] = array_merge(
				$notification,
				array('receiver_id' => $userId)
			);
		}
		if(!empty($notifications['Notification']) && !Classregistry::init('Notification')->saveAll($notifications['Notification'])) {
			return;
		}
		if (!$this->__sendEmails($Model, $userIds, $notificationTypeId)) {
			return;
		}
		return true;
	}

	public function afterDelete(&$Model) {
		$data = array(
			'class' => $Model->name,
			'foreign_id' => $Model->id
		);
		Classregistry::init('Notification')->deleteAll($data);
		return true;
	}

	private function __sendEmails(&$Model, $userIds, $notificationTypeId) {
		$Notification = Classregistry::init('Notification');
		$notificationType = $Notification->NotificationType->findById($notificationTypeId);
		App::import('Component','Mailer.Queue');
		$Queue = new QueueComponent();
		$Queue->initialize();
		App::import('Component','Libs.Tools');
		$Tools = new ToolsComponent();
		$item = $Model->find('first',
			array(
				'conditions' => array(
					$Model->escapeField('id') => $Model->id
				),
				'fields' => array('id', $Model->displayField)
			)
		);
		$messageId = $Queue->createMessage(
			Configure::read('Kinspir.Email.Address.no-reply'),
			Configure::read('Kinspir.Email.Subject.notification'),
			Configure::read('Kinspir.Email.Template.notification')
		);
		$users = $Notification->User->find('all',
			array(
				'conditions' => array(
					'User.id' => $userIds
				),
				'fields' => array('id', 'name', 'email')
			)
		);
		foreach ($users as $user) {
			$recipientId = $Queue->addRecipient($messageId, $user['User']['email']);
			$notification = $Tools->insertVars(
				$notificationType['NotificationType']['text'],
				array(
					User::get('name'),
					$item[$Model->alias][$Model->displayField],
					null
				)
			);
			$Queue->addVariable($recipientId, 'user_name', $user['User']['name']);
			$Queue->addVariable($recipientId, 'notification_content', $notification);
		}
		if ($messageId) {
			return true;
		}
		return;
	}

}
