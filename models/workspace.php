<?php
class Workspace extends AppModel {

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'controlled'
		),
		'Containable',
		'Libs.Trackable',
		'Feedable',
		'Subscribable',
		'Notifiable'
	);

	public function parentNode() {
		return null;
	}

	public $validate = array(
		'name' => 'notempty'
	);

	public $belongsTo = array(
		'User'
	);

	public $hasMany = array(
		'Event' => array(
			'dependent' => true
		),
		'Milestone' => array(
			'dependent' => true
		),
		'Stack' => array(
			'dependent' => true
		),
		'Task' => array(
			'dependent' => true
		),
		'TaskGroup' => array(
			'dependent' => true
		),
		'Upload' => array(
			'dependent' => true
		),
		'WikiPage' => array(
			'dependent' => true
		)
	);

}
