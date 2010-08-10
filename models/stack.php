<?php
class Stack extends AppModel {

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'controlled',
			'parentClass'=> 'Workspace',
			'foreignKey' => 'workspace_id'
		),
		'Filterable',
		'Containable',
		'Libs.Trackable',
		'Feedable',
		'Notifiable'
	);

	public $validate = array(
		'name' => 'notempty'
	);

	public $hasMany = array(
		'Event',
		'Task',
		'TaskGroup',
		'Upload',
		'WikiPage'
	);

	public $belongsTo = array(
		'AssignedTo' => array(
			'className' => 'User',
			'foreignKey' => 'assigned_to_id'
		),
		'Milestone',
		'User',
		'Workspace'
	);

}