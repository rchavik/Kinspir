<?php
class SubscriptionsController extends AppController {

	public function collaborators() {
		$subscriptions = $this->Subscription->findAllByClassAndForeignId('Workspace', $this->Session->read('Workspace.id'));
		$subscribers = Set::extract('/Subscription/user_id', $subscriptions);
		$subscribers = $this->Subscription->User->find('all',
			array(
				'conditions' => array(
					'User.id' => $subscribers
				),
				'fields' => array('id', 'name', 'email', 'facebook_id')
			)
		);
		$this->set('subscribers', $subscribers);
	}

}