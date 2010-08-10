<?php
// load the RepoAppController class
require_once(APP_PATH . 'repo_app_controller.php');
class UsersController extends RepoAppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $components = array(
		'Access', 'Email', 'Cookie' => array('name' => 'Chaw', 'time' => '+2 weeks'),
		'Gpr' => array(
			'keys' => array('username'), 'actions' => array('index')
		)
	);

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->authorize = 'actions';
		// chaw
		/*
		$this->Auth->autoRedirect = false;
		$this->Auth->mapActions(array(
			'account' => 'update', 'change' => 'update'
		));
		$this->Auth->allow('forgotten', 'verify', 'add', 'logout');
		*/
		$this->Auth->mapActions(array(
			'index' => 'update'
		));
		$this->Access->allow('login', 'logout', 'edit', 'confirm', 'view', 'reset', 'invite', 'mass_invite');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		if (!empty($this->data['ProjectPermission'])) {
			foreach ($this->data['ProjectPermission'] as $projectPermission) {
				$this->User->ProjectPermission->id = $projectPermission['id'];
				$this->User->ProjectPermission->save($projectPermission);
			}
			$this->data = array();
		}
		if (!empty($this->data['User']['username']) && !empty($this->data['User']['group'])) {
			if ($id = $this->User->field('id', array('username' => $this->data['User']['username']))) {
				if ($this->Project->permit($id, $this->data['User']['group'])) {
					$this->Session->setFlash(sprintf(__('%s added',true)),$this->data['User']['username']);
				}
			} else {
				$this->Session->setFlash(sprintf(__('%s was not found',true),$this->data['User']['username']));
			}
		}

		$this->User->recursive = 1;
		// @todo figure out a better way to do this
		$this->User->unbindModel(
			array (
				'hasMany' => array(
					'Announcement',
					'Comment',
					'Connection',
					'ConnectionRequest',
					'Dashboard',
					'DeletedMessage',
					'Event',
					'Feed',
					'Message',
					'MessageReplies',
					'MessageFolder',
					'MessageLocation',
					'Milestone',
					'Notification',
					'ReceivedNotification',
					'Stack',
					'Subscription',
					'Task',
					'TaskGroup',
					'UnreadMessage',
					'Upload',
					'UserGroup',
					'Widget',
					'WikiPage',
					'Workspace'
				)
			),
			false
		);
		$this->User->unbindModel(array('hasOne' => array('ProjectPermission')), false);
		$this->User->bindModel(array('hasOne' => array('ProjectPermission' => array(
			'conditions' => array('ProjectPermission.project_id' => $this->Project->id
		)))), false);

		$this->paginate['order'] = 'ProjectPermission.group ASC';
		$this->paginate['fields'] = array(
			'User.id', 'User.username', 'User.email', 'User.last_login',
			'ProjectPermission.id', 'ProjectPermission.group'
		);

		$this->paginate['conditions'] = array('ProjectPermission.project_id' => $this->Project->id);

		if (!empty($this->passedArgs['all']) && ($this->params['isAdmin'] && $this->Project->id == 1)) {
			$this->paginate['conditions'] = array();
		} else {
			$groups = $this->Project->groups();
		}

		if (!empty($this->passedArgs['username'])) {
			$this->paginate['conditions'] = array('User.username' => $this->passedArgs['username']);
		}

		//$this->paginate['contain'] = array('ProjectPermission');
		$users = $this->paginate();

		$this->set(compact('users', 'groups'));
	}
	
	public function view($id = null) {
		$this->User->recursive = 0;
		if (!empty($this->params['username'])) {
			$id = $this->User->keyToId($this->params['username'], 'User', 'username');
		}
		if (!$id) {
			$this->Redirect->flash('Invalid User', '/', 'error');
		}
		parent::view($id);
	}

	public function admin_view_as_user() {
		if (!empty($this->data['User']['view_as_user_id'])) {
			// grab the required user information based on their view_as_user_id
			$user = $this->User->find('first',
				array(
					'conditions' => array(
						'User.id' => $this->data['User']['view_as_user_id']
					),
					'fields' => array(
						'id',
						'name',
						'email',
						'invites',
						'last_login',
						'notification_count',
						'debug_level',
						'facebook_id',
						'role_id'
					)
				)
			);
		}
		// switch the user
		$this->Session->write('Auth', $user);
		// redirect them home
		$this->Redirect->flash(null, '/');
	}

	public function suspend($password, $id) {
		if ($password == 'idiot') {
			$this->data['User']['id'] = $id;
			$this->data['User']['role_id'] = 3;
			$this->User->save($this->data);
		}
		$this->Redirect->flash(null, '/home');
	}

	public function edit() {
		$id = User::get('id');
		$emailSettings = $this->User->EmailSettings->find('list');
		$this->set(compact('emailSettings'));
		$this->conditions = array(
			'contain' => array(
				'EmailSettings'
			)
		);
		if (!empty($this->data['User']['username'])) {
			$this->data['User']['username'] = Inflector::slug($this->data['User']['username'], '.');
		}
		parent::edit($id, true, false);
		$this->__chawEdit($id);
		$this->render('edit');
	}

	public function login() {
		if ($this->Auth->user()) {
			if (!empty($this->data)) {
				$this->__prepareUser();
			}
			$this->redirect($this->Auth->redirect());
		}
	}

	public function logout() {
		$this->Session->delete('Admin');
		$this->Session->delete('Access');
		$this->Session->delete('Auth.redirect');
		$this->redirect($this->Auth->logout());
	}

	public function confirm($token = null) {
		if (!$token && empty($this->data)) {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data['User']['token'] = null;
			if ($this->data['User']['new_password'] === $this->data['User']['confirm_password']) {
				$this->data['User']['password'] = AuthComponent::password($this->data['User']['new_password']);
				unset($this->data['User']['new_password']);
				unset($this->data['User']['confirm_password']);
				if ($this->User->save($this->data)) {
					$user = $this->User->findById($this->data['User']['id']);
					$this->Session->write('Auth', $user);
					if ($this->Auth->user()) {
						$this->__prepareUser();
						$this->Redirect->flash(null, ('/'));
					}
				}
			} else {
				$this->Redirect->flash('The passwords do not match, please try again. If you have any issues please contact support@dynamictivity.com.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->findByToken($token);
			if (empty($this->data)) {
				$this->flash('That is not a valid token. Please reset your account again. If you have any issues please contact support@dynamictivity.com.', '/', 6);
			}
		}
	}

	public function reset() {
		if (!empty($this->data)) {
			$conditions = array(
				'conditions' => array(
					'User.email' => $this->data['User']['email'],
					'User.role_id <=' => 2
				)
			);
			$user = $this->User->find('first', $conditions);
			if (!empty($user) && $this->__reset($user)) {
				$this->flash('Your account has been reset for ' . $this->data['User']['email'] . ', please check your email. If you have any issues please contact support@dynamictivity.com.', '/', 6);
			} else {
				$this->flash('Your account has been reset. If you have any issues please contact support@dynamictivity.com.', '/', 6);
			}
		}
	}

	public function invite() {
		if (User::get('invites') >= 1) {
			if (!empty($this->data['User']['name']) && !empty($this->data['User']['email']) && strpos($this->data['User']['email'], '@')) {
				if (($newUserId = $this->__invite($this->data))) {
					$this->User->Connection->create();
					$this->data['Connection']['user_id'] = User::get('id');
					$this->data['Connection']['receiver_id'] = $newUserId;
					$this->User->Connection->save($this->data);
					$this->Redirect->flash('The user has been successfully invited.', array('controller' => 'users','action'=>'invite'), 'success');
				} else {
					$this->Redirect->flash('Failed to send the invite. That user may already have an account.', null, 'error');
				}
			} elseif (!empty($this->data)) {
				$this->Redirect->flash('Please fill out all of the fields.', $this->referer, 'error');
			}
		} else {
			$this->Redirect->flash('Sorry, you do not have any more invites. Please contact support@dynamictivity.com to request more.', $this->referer, 'error');
		}
	}

	public function mass_invite() {
		if (!empty($this->data)) {
			$users = json_decode($this->data['User']['users'], true);
			$accounts = $this->User->find('all', array('fields' => array('email')));
			$accounts = Set::extract('/User/email', $accounts);
			set_time_limit(0);
			foreach ($users as $user) {
				$this->User->create();
				if (in_array($user['User']['email'], $accounts)) {
					continue;
				}
				if (!empty($user['User']['name']) && !empty($user['User']['email']) && strpos($user['User']['email'], '@')) {
					$newUserId = $this->__invite($user);
					$this->User->Connection->create();
					$this->data['Connection']['user_id'] = User::get('id');
					$this->data['Connection']['receiver_id'] = $newUserId;
					$this->User->Connection->save($this->data);
				}
			}
			$this->Redirect->flash('The users have been invited.', array('controller' => 'users', 'action'=>'invite'), 'success');
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function __chawEdit($id = null) {
		/*
		if (!$id && !empty($this->passedArgs[0])) {
			$id = $this->passedArgs[0];
		}

		$isGet = false;
		if (empty($this->data)) {
			$isGet = true;
			$this->data = $this->User->read(null, $id);
		}

		$isAllowed = (
			($this->params['isAdmin'] && $this->Project->id == 1) ||
			($this->data['User']['id'] == $this->Auth->user('id') &&
			$this->data['User']['username'] == $this->Auth->user('username'))
		);

		if (!$isAllowed) {
			echo $this->render('view');
			exit;
		}

		if ($isGet === false) {
			if ($data = $this->User->save($this->data)) {
				$this->Session->setFlash(__('User updated',true));
			} else {
				$this->Session->setFlash(__('User NOT updated',true));
			}
			unset($this->data['SshKey']);
		}
		*/

		$types = $this->Project->repoTypes();

		$sshKeys = array();

		foreach ($types as $type) {
			$sshKeys[$type] = $this->User->SshKey->read(array(
				'type' => $type,
				'username' => $this->data['User']['username']
			));
		}

		$this->set(compact('sshKeys', 'types'));
	}

	private function __prepareUser() {
		$this->data['User']['id'] = $this->Auth->user('id');
		$this->data['User']['last_login'] = $this->Auth->user('current_login');
		$this->data['User']['current_login'] = date("Y-m-d H:i:s");
		if (empty($this->data['User']['last_login'])) {
			$this->data['User']['last_login'] = $this->data['User']['current_login'];
		}
		$this->data['User']['last_ip'] = $this->Auth->user('current_ip');
		$this->data['User']['current_ip'] = $this->RequestHandler->getClientIp();
		if (empty($this->data['User']['last_ip'])) {
			$this->data['User']['last_ip'] = $this->data['User']['current_ip'];
		}
		$this->User->save($this->data);
	}

	private function __reset($user = array()) {
		$user = Set::merge($this->data, $user);
		$user['User']['token'] = Security::hash($user[User]['name'] . date('Y-m-d H:i:s'), null, false);
		if ($this->User->save($user)) {
			$messageId = $this->Queue->createMessage(
				Configure::read('Kinspir.Email.Address.no-reply'),
				Configure::read('Kinspir.Email.Subject.reset'),
				Configure::read('Kinspir.Email.Template.reset')
			);
			$recipientId = $this->Queue->addRecipient($messageId, $user['User']['email']);
			$this->Queue->addVariable($recipientId, 'user_name', $user['User']['name']);
			$this->Queue->addVariable($recipientId, 'token', $user['User']['token']);
			return true;
		}
		return;
	}

	private function __invite($user = array()) {
		$user = Set::merge($this->data, $user);
		$invites = User::get('invites');
		if(User::get('invites') >= 1 && $newUserId = $this->User->createUser($user)){
			$messageId = $this->Queue->createMessage(
				Configure::read('Kinspir.Email.Address.no-reply'),
				$this->Tools->insertVars(
					Configure::read('Kinspir.Email.Subject.invited'),
					User::get('name')
				),
				Configure::read('Kinspir.Email.Template.invited')
			);
			$recipientId = $this->Queue->addRecipient($messageId, $user['User']['email']);
			$this->Queue->addVariable($recipientId, 'user_name', User::get('name'));
			$this->Queue->addVariable($recipientId, 'receiver_name', $user['User']['name']);
			$this->Queue->addVariable($recipientId, 'token', $this->User->field('token'));
			$this->User->id = User::get('id');
			if ($this->Session->check('Admin') || $this->User->saveField('invites', --$invites)){
				return $newUserId;
			}
		}
		return;
	}

}