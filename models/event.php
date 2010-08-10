<?php
class Event extends AppModel {

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'controlled',
			'parentClass'=> 'Workspace',
			'foreignKey' => 'workspace_id'
		),
		'Filterable',
		'Containable',
		'Libs.Trackable',
		'Feedable'
	);

	public $validate = array(
		'name' => 'notempty',
		'starts' => 'notempty'
	);

	public $belongsTo = array(
		'Stack',
		'User',
		'Milestone',
		'Workspace'
	);

}