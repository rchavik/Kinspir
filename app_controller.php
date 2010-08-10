<?php
App::import('Model', 'User');
App::import('Lib', 'Facebook.FB');
class AppController extends Controller {

	/**
	 * @var unknown_type
	 */
	public $components = array(
		'Facebook.Connect',
		'Libs.Tools',
		'SuperAuth.Auth' => array(
			'authorize' => 'actions',
			'fields' => array('username' => 'email', 'password' => 'password'),
			'actionPath' => 'controllers/',
			'loginAction' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
			'loginRedirect' => '/home',
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
			'loginError' => 'Invalid credentials',
			'authError' => 'Not authorized',
			'autoRedirect' => false,
			'allowedActions' => array('display', 'confirm', 'reset')
		),
		'SuperAuth.Acl',
		'Email',
		'Mailer.Queue',
		'Libs.Redirect',
		'RequestHandler',
		'Session',
		'DebugKit.Toolbar'
	);

	/**
	 * @var unknown_type
	 */
	public $helpers = array(
		'Chaw',
		'Facebook.Facebook',
		'Form',
		'Html',
		'Session',
		'Time',
		'Js',
		'Global',
		'Libs.Tools',
		'Libs.Javascript',
		'Libs.Layout',
		'Libs.List',
		'Libs.TimeZone',
		'Libs.Notify',
		'Libs.Gravatar',
		'Libs.Avatar'
	);

	/**
	 * @var unknown_type
	 */
	public $conditions = array();

	/**
	 * @var unknown_type
	 */
	public $dateTime = null;

	/**
	 * @var unknown_type
	 */
	public $view = 'Theme';

	/**
	 * @var unknown_type
	 */
	public $theme = 'black';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project');

	/**
	 *
	 */
	public function beforeFilter(){
		if(isset($this->RequestHandler)){
			switch (true) {
				case $this->RequestHandler->prefers('json'):
					$this->RequestHandler->setContent('json', 'text/x-json');
					Configure::write('debug', 0);
					break;
			}
		}
		$this->dateTime = date('Y-m-d H:i:s');
		$this->__setReferer();
		$this->__initializeAuth();
		Configure::write('Kinspir.basePath', FULL_BASE_URL . $this->base);
	}

	/**
	 * Default Index Behavior
	 * Uses paginate.
	 *
	 * @param Boolean $redirect If set to true will redirect to add action if 
	 * there are currently no records found
	 */
	public function index($redirect = false, $paginateModel = null) {
		if (!$paginateModel) {
			$paginateModel = $this->modelClass;
		}
		$data = $this->paginate($paginateModel);
		if ($redirect === true) {
			$redirect = array('action' => 'add');
		}
		if ($redirect && empty($data)) {
			$this->Redirect->flash(array('no_records', Inflector::humanize(Inflector::underscore(Inflector::pluralize($paginateModel)))), $redirect);
		}
		if ($paginateModel) {
			$varName = Inflector::variable(Inflector::pluralize($paginateModel));
		} else {
			$varName = Inflector::variable($this->name);
		}
		$this->set($varName, $data);
	}

	/**
	 * Default scaffold view action. Redirects if no id # is provided.
	 * Query conditions can be modified using $this->conditions
	 *
	 * @param int $id of the record being viewed
	 */
	public function view($id = null) {
		if (!$id) {
				$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		$conditions['conditions'] = array($this->{$this->modelClass}->escapeField('id') => $id);
		$conditions = Set::merge($conditions, $this->conditions);
		$record = $this->{$this->modelClass}->find('first', $conditions);
		$this->set(
			Inflector::variable($this->modelClass),
			$record
		);
		$this->{$this->modelClass}->id = $record[$this->modelClass]['id'];
	}

	/**
	 * Default scaffold add action. Runs the scaffold edit action with defaults
	 * Query conditions can be modified using $this->conditions
	 */
	public function add() {
		$this->edit();
	}

	/**
	 * Default scaffold edit action. Returns null or true (if succeeded)
	 * Query conditions can be modified using $this->conditions
	 * 
	 * @param int $id of the record to be edited. If set to null, a new record will be added
	 * @param mixed $redirect target. Accepts string url, arrays or false for no redirect
	 * @param boolean $render if set to false will not use the edit view
	 */	
	public function edit($id = null, $redirect = true, $render = true) {
		if (empty($this->data[$this->modelClass]['id']) && !$id) {
			$this->{$this->modelClass}->create();
		}
		if ($redirect === true) {
			$redirect = $this->referer;
		}
		if (!empty($this->data[$this->{$this->modelClass}->alias])) {
			if ($this->{$this->modelClass}->save($this->data)) {
				if ($redirect) {
					$this->Redirect->flash(array('save_ok', strtolower(Inflector::humanize($this->modelClass))), $redirect);
				} else {
					return true;
				}
			} else {
				$this->Redirect->flash('input_errors');
			}
		}
		if ($id) {
			$conditions['conditions'] = array($this->{$this->modelClass}->escapeField('id') => $id);
			$conditions = Set::merge($conditions, $this->conditions);
			$this->data = $this->{$this->modelClass}->find('first', $conditions);
		}
		if ($render) {
			$this->render('edit');
		}
	}

	/**
	 *
	 */
	public function delete($ids = null, $redirect = true) {
		if (!$ids) {
			$this->Redirect->flash('no_id', $this->referer);
		}
		$ids = (array)$ids;
		if ($redirect === true) {
			$redirect = $this->referer;
		}
		$count = count($ids);
		if ($this->{$this->modelClass}->deleteAll(array($this->modelClass . '.id' => $ids), true, true)) {
			if ($redirect) {
				$this->Redirect->flash(array('delete_ok', $count), $redirect);
			} else {
				return $count;
			}
		} else {
			if ($redirect) {
				$this->Redirect->flash('failed', array('action' => 'index'));
			} else {
				return;
			}
		}
	}

	/**
	 *
	 */
	protected function _allow($users, $aco, $permissions = '*') {
		$users = (array)$users;
		foreach ($users as $user) {
			$aros[]['User'] = array(
				'id' => $user
			);
		}
		if (isset($aros) && isset($aco)) {
			return $this->Acl->allow($aros, $aco, $permissions);
		}
		return;
	}

	/**
	 * Loads (and unloads) the session with the specific level and optional id
	 * Loads the breadcrumbs (and other session data) to the level specified, 
	 * using provided id # to select the relevant record
	 *
	 * @param string $level optional model name to set the level to
	 * @param int $id optional id # of the record to load. Leave empty to only
	 * unset the level (travel up the chain)
	 */
	protected function _setLevel($level = null, $id = null) {
		// update the user's permission cache
		$this->Acl->updateCache();
		$Workspace = ClassRegistry::init('Workspace');
		// DELETES SESSION DATA
		switch($level) {
			case null:
				$this->Session->delete('Workspace');
			case 'workspace':
				$this->Session->delete('Milestone');
			case 'milestone':
				$this->Session->delete('Stack');
				$this->Session->delete('Stacks');
				$this->Session->delete('Milestones');
		}
		// STORES SESSION DATA
		$data = array();
		switch ($level) {
			case 'stack':
				if (!$id) {
					if ($this->Session->check('Milestone')) {
						$id = $this->Session->read('Milestone.id');
					} else {
						$id = $this->Session->read('Workspace.id');
					}
				} else {
					$data = $Workspace->Stack->find('first',
						$this->Acl->conditions(
							array(
								'conditions' => array(
									'Stack.id' => $id
								),
								'contain' => array(
									'Milestone',
									'Workspace'
								)
							),
							array(
								'cache' => false
							)
						)
					);
					if (!empty($data['Stack']['id'])) {
						$this->Session->write('Stack', $data['Stack']);
					}
				}
			case 'milestone':
				if (!$id) {
					$id = $this->Session->read('Workspace.id');
				} else {
					if (empty($data['Milestone'])) {
						$data = $Workspace->Milestone->find('first',
							$this->Acl->conditions(
								array(
									'conditions' => array(
										'Milestone.id' => $id
									),
									'contain' => array(
										'Workspace'
									)
								),
								array(
									'cache' => false
								)
							)
						);
					}
					if (!empty($data['Milestone']['id'])) {
						$this->Session->write('Milestone', $data['Milestone']);
					}
				}
			case 'workspace':
				if ($id) {
					if (empty($data['Workspace'])) {
						$data = $Workspace->find('first',
							$this->Acl->conditions(
								array(
									'conditions' => array(
										'Workspace.id' => $id
									)
								),
								array(
									'cache' => false
								)
							)
						);
					}
					$milestoneConditions = null;
					if (!empty($data['Milestone']['id'])) {
						$milestoneConditions = array(
							'milestone_id' => $data['Milestone']['id']
						);
					}
					$stacks = $Workspace->Stack->find('list',
						array(
							'conditions' => array(
								'workspace_id' => $data['Workspace']['id'],
								$milestoneConditions
							)
						)
					);
					$milestones = $Workspace->Milestone->find('list',
						array(
							'conditions' => array(
								'workspace_id' => $data['Workspace']['id']
							)
						)
					);
					if (!empty($data['Workspace']['id'])) {
						$this->Session->write('Workspace', $data['Workspace']);
					}
					if ($stacks) {
						$this->Session->write('Stacks', $stacks);
					}
					if ($milestones) {
						$this->Session->write('Milestones', $milestones);
					}
				}
			default:
				$this->Session->write('Workspaces',
					$Workspace->findList(
						$this->Acl->conditions(
							array(
								'contain' => array()
							),
							array(
								'cache' => false
							)
						)
					)
				);
		}
		return true;
	}

	/**
	 * Applies query filters based on user's current level
	 * 
	 * @param mixed $conditions required The conditions you want to apply the filter to
	 * @param array $parents optional The parent models that the filter will be under in order
	 */
	protected function _filterLevel($conditions, $parents = null) {
		$parents = (array)$parents;
		$parents = array_reverse($parents);
		$filterConditions = array();
		switch(true){
			// if they are in a stack, apply the filter
			case $this->Session->check('Stack.id'):
				if ($this->name != 'Stacks') {
					$filterConditions['conditions'][] = array('stack_id' => $this->Session->read('Stack.id'));
					break;
				}
			// if they are in a milestone, apply the filter
			case $this->Session->check('Milestone.id'):
				if ($this->name != 'Milestones') {
					$filterConditions['conditions'][] = array('milestone_id' => $this->Session->read('Milestone.id'));
					break;
				}
			// if they are in a workspace, apply the filter
			case $this->Session->check('Workspace.id'):
				$filterConditions['conditions'][] = array('workspace_id' => $this->Session->read('Workspace.id'));
				break;
		}
		foreach ($parents as $parent) {
			$filterConditions[$parent] = $filterConditions;
		}
		return Set::merge($conditions, $filterConditions);
	}

	/**
	 *
	 */
	protected function _userConnections($fields = null, $list = false) {
		$fields = (array)$fields;
		// Get the user's connections
		$User = ClassRegistry::init('User');
		$Connection = $User->Connection;
			$connectionResults = $Connection->find('all',
				array(
				'conditions' => array(
					'or' => array(
						'User.id' => User::get('id'),
						'Receiver.id' => User::get('id')
					),
					'Connection.is_approved' => true
				),
				'contain' => array(
					'User',
					'Receiver'
				)
			)
		);
		$connections = Set::extract('/Connection/receiver_id', $connectionResults);
		$connections = array_merge($connections, Set::extract('/Connection/user_id', $connectionResults));
		$connections = array_keys(array_flip($connections));
		$badKey = array_search(User::get('id'), $connections);
		unset($connections[$badKey]);

		// set our default fields if needed
		// also set unwrap
		if (!$fields) {
			$fields = array('id');
			$unwrap = true;
		}

		// get the information for each connection
		if (!$list) {
			$connections = $User->find('all',
				array(
					'conditions' => array(
						'User.id' => $connections
					),
					'fields' => $fields
				)
			);

			// if unwrap is true, we want to return only the user id's
			if (isset($unwrap)) {
				$connections = Set::extract('/User/id', $connections);
			}
		} else {
			$field = array_shift($fields);
			$connections = $User->find('list', array('fields' => $field, 'conditions' => array('User.id' => $connections)));
		}

		// return connections
		return $connections;
	}

	/**
	 *
	 */
	protected function _formatUsers($users) {
		$users = (array)$users;
		$userList = array();
		foreach ($users as $user) {
			$userList[$user['User']['id']] = $user['User']['name'];
		}
		return $userList;
	}

	/**
	 *
	 */
	private function __setReferer() {
		$this->referer = $this->referer();
	}

	/**
	 *
	 */
	private function __initializeAuth() {
		// make sure they are not logged in if they are confirming their account
		// also hash the form inputs so they can't hack another user's account
		if ($this->action === 'confirm' && empty($this->data)) {
			$this->Session->destroy();
			$this->components[] = 'Security';
		}
		if ($this->Auth->user()) {
			$this->__initializeUser();
			$this->Auth->allowedActions = array('logout', 'home');
			// admin auth handling
			// @todo move to admin component
			if (User::get('role_id') == 1 || $this->Session->check('Admin')) {
				// allow admins access to everything by default
				$this->Auth->allowedActions = array('*');
				$this->Auth->authorize = 'actions';
				// initialize the admin session if not already initialized
				if (!$this->Session->check('Admin')) {
					$this->Session->write('Admin', $this->Auth->user());
				}
			}
			if ($this->Session->check('Admin')) {
			// Load the User class (model)
			$User = ClassRegistry::init('User');
				// find all of the users
				$viewAsUsers = $User->find('list');
				// set the users to the view
				$this->set(compact('viewAsUsers'));
			}
		} elseif (in_array($this->action, array('login', 'confirm', 'reset'))) {
			$this->layout = 'login';
		} elseif (!in_array($this->action, $this->Auth->allowedActions)) {
			$this->Redirect->flash(null, $this->Auth->loginAction);
		}
		if (in_array($this->action, array('view', 'edit', 'delete')) && isset($this->{$this->modelClass}) && $this->{$this->modelClass}->Behaviors->attached('Acl')) {
			$this->Auth->authorize = 'acl';
		}
	}

	/**
	 *
	 */
	private function __initializeUser() {
		// Update the user singleton, and auth user information
		// Load the User class (model)
		$User = ClassRegistry::init('User');
		// grab the required user information based on their user id
		$user = $User->find('first',
			array(
				'conditions' => array(
					'User.id' => $this->Auth->user('id')
				),
				'fields' => array(
					'id',
					'name',
					'username',
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
		// update the user session
		$this->Session->write('Auth', $user);
		// chaw
		$id = $user['User']['id'];
		$this->Session->write('Auth.User.ProjectPermission', $User->groups($id));
		// set their debug level
		if ($user['User']['debug_level'] < Configure::read('debug')) {
			Configure::write('debug', $user['User']['debug_level']);
		}
		// update the user singleton
		User::store($this->Auth->user());
	}

}
