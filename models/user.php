<?php
class User extends AppModel {

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	/*
	var $validate = array(
		'username' => array(
			'allowedChars' => array(
				'rule' => '/^[\-_\.a-zA-Z0-9]{3,}$/',
				'required' => true,
				'message' => 'Required: Minimum three (3) characters, letters (no accents), numbers and .-_ permitted.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true,
				'message' => 'Required: Username must be unique'
			)
		),
		'email' => array(
			'valid' => array(
				'rule' => 'email',
				'required' => true,
				'message' => 'Required: Valid email address'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true,
				'message' => 'Required: Email must be unique'
			)
		),
		'password' => array(
			'rule' => 'alphaNumeric',
			'message' => 'Required: Alpha-numeric passwords only'
		)
	);
	*/

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'requester',
			'parentClass'=> 'Role',
			'foreignKey' => 'role_id'
		),
		'Containable'
	);

	public $belongsTo = array(
		'Role'
	);

	public $hasMany = array(
		'Announcement',
		'Comment' => array(
			'dependent' => true
		),
		'Connection' => array(
			'dependent' => true
		),
		'ConnectionRequest' => array(
			'className' => 'Connection',
			'foreignKey' => 'receiver_id',
			'dependent' => true
		),
		'Dashboard' => array(
			'dependent' => true
		),
		'DeletedMessage' => array(
			'dependent' => true
		),
		'Event' => array(
			'dependent' => true
		),
		'Feed' => array(
			'dependent' => true
		),
		'Message' => array(
			'dependent' => true
		),
		'MessageReplies' => array(
			'className' => 'Message',
			'foreignKey' => 'last_replier_id'
		),
		'MessageFolder' => array(
			'dependent' => true
		),
		'MessageLocation' => array(
			'dependent' => true
		),
		'Milestone' => array(
			'dependent' => true
		),
		'Notification' => array(
			'dependent' => true
		),
		'ReceivedNotification' => array(
			'className' => 'Notification',
			'foreignKey' => 'receiver_id',
			'dependent' => true
		),
		'Stack' => array(
			'dependent' => true
		),
		'Subscription' => array(
			'dependent' => true
		),
		'Task' => array(
			'dependent' => true
		),
		'TaskGroup' => array(
			'dependent' => true
		),
		'UnreadMessage' => array(
			'dependent' => true
		),
		'Upload' => array(
			'dependent' => true
		),
		'UserGroup' => array(
			'dependent' => true
		),
		'Widget' => array(
			'dependent' => true
		),
		'WikiPage' => array(
			'dependent' => true
		),
		'Workspace' => array(
			'dependent' => true
		)
	);

	var $hasAndBelongsToMany = array(
		'EmailSettings' => array(
			'className' => 'NotificationType',
			'joinTable' => 'email_notification_settings',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'notification_type_id',
			'fields' => array('id', 'title')
		)
	);

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	var $hasOne = array('ProjectPermission');

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	var $SshKey = null;

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @param string $table
	 * @param string $ds
	 */
	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->SshKey = ClassRegistry::init('SshKey');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeSave() {
		if (!empty($this->data['SshKey']['content'])) {
			$this->SshKey->set(array(
				'username' => $this->data['User']['username'],
			));

			return $this->SshKey->save($this->data['SshKey']);
		}

		if (!empty($this->data['Key']) && !empty($this->data['User']['username'])) {
			$delete = array();
			foreach ($this->data['Key'] as $type => $keys) {
				foreach ($keys as $key) {
					if (!empty($key['chosen'])) {
						$delete[$type][] = $key['content'];
					}
				}
			}

			foreach ($delete as $type => $keys) {
				$result = $this->SshKey->delete(array(
					'type' => $type, 'username' => $this->data['User']['username'],
					'content' => $keys
				));
			}
		}

		return true;
	}

	/**
	 * undocumented function
	 *
	 * @param string $user
	 * @param string $conditions
	 * @return void
	 */
	function projects($user, $conditions = array()) {
		if ($user = $this->ProjectPermission->user($user)) {
			$projects = $this->ProjectPermission->find('all', array(
				'conditions' => array_merge($conditions, array('ProjectPermission.user_id' => $user, 'Project.name !=' => null))
			));
			$ids = array_filter(Set::extract('/Project/id', $projects));
			return compact('ids', 'projects');
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $user
	 * @return void
	 */
	function groups($user) {
		if ($user = $this->ProjectPermission->user($user)) {
			$results = $this->ProjectPermission->find('all', array(
				'conditions' => array('ProjectPermission.user_id' => $user, 'Project.id !=' => null),
				'contain' => 'Project'
			));
			return array_filter(Set::combine($results, '/Project/id', '/ProjectPermission/group'));
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function permit() {
		if (!empty($this->data['User']['group'])) {
			$data = array('ProjectPermission' => array(
				'user_id' => $this->id,
				'project_id' => $this->data['User']['project_id'],
				'group' => $this->data['User']['group']
			));
			$this->ProjectPermission->save($data);
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $user
	 * @return void
	 */
	function setToken($user = array()) {
		$this->set($user);
		$this->recursive = -1;

		if (!empty($this->data['User']['id'])) {
			$this->data = $this->find(array('id' => $this->data['User']['id']), array('id', 'username', 'email'));
		} else if (!empty($this->data['User']['email'])) {
			$this->data = $this->find(array('email' => $this->data['User']['email']), array('id', 'username', 'email'));
		} else if (!empty($this->data['User']['username']) && empty($this->data['User']['email'])) {
			$this->data = $this->find(array('username' => $this->data['User']['username']), array('id', 'username', 'email'));
		}

		if (empty($this->data['User']['id'])) {
			$this->invalidate('email', 'Account could not be found');
			return false;
		}

		$this->id = $this->data['User']['id'];
		$result = $this->save(array(
			'token' => String::uuid(),
			'token_expires' => date('Y-m-d', strtotime('+ 1 day'))
		));

		if (!empty($result)) {
			return $result;
		}

		$this->invalidate('email', 'Email could not be sent');
		return false;
	}

	/**
	 * undocumented function
	 *
	 * @param string $token
	 * @return void
	 */
	function setTempPassword($token = array()) {
		if (is_array($token)) {
			$this->set($token);
		} else {
			$this->data['User']['token'] = $token;
		}

		if (empty($this->data['User']['token'])) {
			$this->invalidate('token', 'token could not be found');
			return false;
		}
		$this->recursive = -1;
		$this->data = $this->find(array('token' => $this->data['User']['token']), array('id', 'username', 'email'));

		if (empty($this->data['User']['id'])) {
			$this->invalidate('token', 'token does not match');
			return false;
		}

		list($password, $hashed) = $this->__generatePassword();

		$this->id = $this->data['User']['id'];
		$result = $this->save(array(
			'tmp_pass' => $hashed,
			'token' => null,
			'token_expires' => null
		));

		if (!empty($result)) {
			$result['User']['tmp_pass'] = $password;
			return $result;
		}
		$this->invalidate('password', 'Password could not be reset');
		return false;
	}

	/**
	 * undocumented function
	 *
	 * @param string $token
	 * @return void
	 */
	function activate($token = array()) {
		if (is_array($token)) {
			$this->set($token);
		} else {
			$this->data['User']['token'] = $token;
		}

		if (empty($this->data['User']['token'])) {
			$this->invalidate('token', 'token could not be found');
			return false;
		}

		$this->recursive = -1;
		$this->data = $this->find(array('token' => $this->data['User']['token']), array('id', 'username', 'email'));

		if (empty($this->data['User']['id'])) {
			$this->invalidate('token', 'token does not match');
			return false;
		}

		$this->id = $this->data['User']['id'];
		$result = $this->save(array(
			'active' => 1,
			'token' => null,
			'token_expires' => null
		));

		if (!empty($result)) {
			return $result;
		}

		$this->invalidate('username', 'Account could not be activated');
		return false;
	}

	/**
	 * undocumented function
	 *
	 * @param string $length
	 * @return void
	 */
	function __generatePassword($length = 10) {
		srand((double)microtime() * 1000000);
		$password = '';
		$vowels = array('a', 'e', 'i', 'o', 'u');
		$cons = array('b', 'c', 'd', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'u', 'v', 'w', 'tr', 'cr', 'br', 'fr', 'th', 'dr', 'ch', 'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl');
		for ($i = 0; $i < $length; $i++) {
			$password .= $cons[mt_rand(0, 31)] . $vowels[mt_rand(0, 4)];
		}
		$password = substr($password, 0, $length);
		App::import('Core', 'Security');
		return array($password, Security::hash($password, null, true));
	}

	/**
	 * undocumented function
	 *
	 * @param string $conditions
	 * @param string $recursive
	 * @param string $extra
	 * @return void
	 */
	function paginateCount($conditions, $recursive, $extra) {
		$fields = array('fields' => 'DISTINCT User.username');
		return count($this->find('all', compact('conditions', 'fields', 'recursive', 'extra')));
	}

	/**
	 * undocumented function
	 *
	 * @param string $data
	 * @param string $options
	 * @return void
	 */
	function isUnique($data, $options = array()) {
		if (!empty($data['username'])) {
			$this->recursive = -1;
			if ($result = $this->field('id', array('username' => $data['username']))) {
				if ($this->id == $result) {
					return true;
				}
				return false;
			}
		}
		if (!empty($data['email'])) {
			$this->recursive = -1;
			if ($result = $this->field('id', array('email' => $data['email']))) {
				if ($this->id == $result) {
					return true;
				}
				return false;
			}
		}
		return true;
	}

	public function createUser($User = array()) {
		if (empty($User[$this->alias])) {
			return false;
		}
		$this->create();
		$this->data = $User;
		$defaults[$this->alias] = array(
			'invites' => Configure::read('Kinspir.Default.User.invites'),
			'role_id' => Configure::read('Kinspir.Default.User.role_id'),
			'group_id' => Configure::read('Kinspir.Default.User.group_id'),
			'level_id' => Configure::read('Kinspir.Default.User.level_id'),
			'lang' => Configure::read('Kinspir.Default.User.locale'),
			'token' => Security::hash($this->data[$this->alias]['name'] . date('Y-m-d H:i:s'), null, false)
		);
		$this->data = Set::merge($defaults, $this->data);
		if ($this->save($this->data)) {
			return $this->id;
		}
		return false;
	}

	public function &getInstance($user=null) {
		static $instance = array();
		if ($user) {
			$instance[0] =& $user;
		}
		if (!$instance) {
			trigger_error(__("User not set.", true), E_USER_WARNING);
			return false;
		}
		return $instance[0];
	}

	public function store($user) {
		User::getInstance($user);
	}

	public function get($path) {
		$_user =& User::getInstance();
		$path = str_replace('.', '/', $path);
		if (strpos($path, 'User') !== 0) {
			$path = sprintf('User/%s', $path);
		}
		if (strpos($path, '/') !== 0) {
			$path = sprintf('/%s', $path);
		}
		$value = Set::extract($path, $_user);
		if (!$value) {
			return false;
		}
		return $value[0];
	}

}