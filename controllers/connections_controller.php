<?php
class ConnectionsController extends AppController {

	/**
	 * 
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		// this tells row-level acl that the complete method is an alias of update (edit)
		$this->Auth->mapActions(array('approve' => 'update'));
	}

	public function index() {
		if ($this->RequestHandler->prefers('json')) {
			$conditions = array(
				'fields' => array('id', 'name'),
				'conditions' => array(
					'id' => $this->_userConnections()
				)
			);
			$results = $this->Connection->User->find('all', $conditions);
			$connections = array();
			foreach ($results as $result) {
				$result = $result['User'];
				$connections[] = array(
					'value' => $result['id'],
					'caption' => $result['name']
				);
			}
			$this->set(compact('connections'));
		} else {
			$this->paginate = array(
				'conditions' => array(
					'or' => array(
						'User.id' => User::get('id'),
						'Receiver.id' => User::get('id')
					)
				),
				'order' => array(
					'Connection.is_approved' => 'ASC',
					'Connection.id' => 'DESC'
				),
				'contain' => array(
					'User' => array(
						'fields' => array('id', 'name', 'email', 'facebook_id', 'username')
					),
					'Receiver' => array(
						'fields' => array('id', 'name', 'email', 'facebook_id', 'username')
					)
				)
			);
			parent::index();
		}
	}

	public function add() {
		if (!empty($this->data)) {
			$conditions = array(
				'conditions' => array(
					'User.email' => $this->data['Connection']['email']
				),
				'fields' => array('id')
			);
			$receiver = $this->Connection->User->find('first', $conditions);
			if (!$receiver) {
				$this->Redirect->flash(
					'The connection request could not be sent, that user is not active in our system. Try sending an invite.',
					array('controller' => 'users', 'action' => 'invite'),
					'error'
				);
			}
			$conditions = array(
				'conditions' => array(
					'or' => array(
						array(
							'Connection.user_id' => User::get('id'),
							'Connection.receiver_id' => $receiver['User']['id']
						),
						array(
							'Connection.user_id' => $receiver['User']['id'],
							'Connection.receiver_id' => User::get('id')
						)
					)
				)
			);
			$count = $this->Connection->find('count', $conditions);
			if ($count > 0) {
				$this->Redirect->flash(
					'The connection request could not be sent, you are already connected.',
					'/',
					'error'
				);
			}
			if ($receiver['User']['id'] !== User::get('id')) {
				unset($this->data['Connection']['email']);
				$this->data['Connection']['receiver_id'] = $receiver['User']['id'];
				if ($this->Connection->save($this->data)) {
					$this->Connection->notify($this->data['Connection']['receiver_id'], $this->Connection->id, $this->Tools->keyToId('connection_request', 'NotificationTypes'));
					$this->Redirect->flash(
						'The connection request has been sent successfully.',
						array('action' => 'index'),
						'success'
					);
				} else {
					$this->Redirect->flash('failed', array('action' => 'index'));
				}
			} else {
				$this->Redirect->flash(
					'The connection request could not be sent, you can not connect with yourself.',
					'/',
					'error'
				);
			}
		}
		$this->render('edit');
	}

	public function view($id = null) { 
		$this->Redirect->flash(null, array('controller' => 'connections', 'action' => 'index'));
	}

	public function approve($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_data', array('action', 'index'));
		}
		$this->data = $this->Connection->findById($id);
		if ($this->data['Connection']['receiver_id'] == User::get('id')) {
			$this->data['Connection']['is_approved'] = 1;
			if($this->Connection->save($this->data)) {
				$this->Connection->notify($this->data['Connection']['user_id'], $this->Connection->id, $this->Tools->keyToId('connection_approved', 'NotificationTypes'));
				$this->Redirect->flash(array('approved', array('connection request')), array('action' => 'index'));
			} else {
				$this->Redirect->flash('failed', array('action' => 'index'));
			}
		} else {
			$this->Redirect->flash('no_access', array('action' => 'index'));
		}
	}

	public function delete($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		$this->data = $this->Connection->findById($id);
		if ($this->data['Connection']['user_id'] == User::get('id') || $this->data['Connection']['receiver_id'] == User::get('id')) {
			if ($this->Connection->delete($id)) {
				$this->Redirect->flash(array('delete_ok', array(1)), array('action' => 'index'));
			}
		} else {
			$this->Redirect->flash('no_access', array('action' => 'index'));
		}
	}

}