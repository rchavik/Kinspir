<?php
class Message extends AppModel {

	private $__replyData = null;

	public $actsAs = array(
		'Containable',
		'Libs.Trackable',
		'Libs.MultiTree' => array(
			'level' => false,
			'dependent' => true
		),
		'SuperAuth.Acl' => array(
			'type' => 'controlled',
			'parentClass'=> 'Message',
			'foreignKey' => 'parent_id'
		),
		'Notifiable',
		'Subscribable',
		'Readable'
	);

	public $belongsTo = array(
		'User',
		'Replier' => array(
			'className' => 'User',
			'foreignKey' => 'last_replier_id'
		),
		'RootMessage' => array(
			'className' => 'Message',
			'foreignKey' => 'root_id'
		)
	);

	public $hasMany = array(
		'DeletedMessage' => array(
			'dependent' => true
		),
		'MessageLocation' => array(
			'dependent' => true
		),
		'Subscription' => array(
			'foreignKey' => 'foreign_id',
			'conditions' => array(
				'Subscription.class' => 'Message'
			),
			'dependent' => true
		),
		'UnreadMessage' => array(
			'dependent' => true
		)
	);

	public function beforeSave() {
		$this->__replyData[$this->alias] = array(
			'last_reply' => date('Y-m-d H:i:s'),
			'last_replier_id' => User::get('id')
		);
		if (empty($this->data[$this->alias]['id'])) {
			$this->data = Set::merge($this->data, $this->__replyData);
		}
		return true;
	}

	public function afterSave() {
		$rootMessageId = $this->field('root_id');
		$rootMessage = $this->findById($rootMessageId);
		if ($rootMessage[$this->alias]['root_id'] != $this->id) {
			$this->__replyData[$this->alias]['reply_count'] = $this->getChildCount($rootMessageId);
			$rootMessage = Set::merge($rootMessage, $this->__replyData);
			$this->save($rootMessage, array('callbacks' => false));
		}
		return true;
	}

}