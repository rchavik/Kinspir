<?php
class UserGroup extends AppModel {

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'requester',
		),
		'Containable',
		'Libs.Trackable',
		'Feedable'
	);

	public function parentNode() {
		return null;
	}

}