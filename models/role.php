<?php
class Role extends AppModel {

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'requester'
		),
		'Containable'
	);

	public function parentNode() {
		return null;
	}

	public $hasMany = array(
		'User'
	);

}