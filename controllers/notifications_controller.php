<?php
class NotificationsController extends AppController {

	public function index() {
		$this->paginate = array(
			'conditions' => array(
				'Notification.receiver_id' => User::get('id')
			),
			'contain' => array(
				'NotificationType',
				'User'
			),
			'order' => array('Notification.id' => 'DESC')
		);
		parent::index();
		$this->Notification->updateAll(array('is_read' => 1), array('Notification.receiver_id' => User::get('id')));
		$this->Notification->User->id = User::get('id');
		$this->Notification->User->saveField('notification_count', 0);
	}

}