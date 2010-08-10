<?php
class Milestone extends AppModel {

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
		'name' => 'notempty'
	);

	public $hasMany = array(
		'Event',
		'Stack',
		'Task',
		'TaskGroup',
		'Upload'
	);

	public $belongsTo = array(
		'User',
		'Workspace'
	);

}