<?php
class TaskGroup extends AppModel {

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
		'Libs.Sequence' => array(
			'group_fields' => array('workspace_id'),
			'start_at' => 1
		)
	);

	public $belongsTo = array(
		'Stack',
		'User',
		'Milestone',
		'Workspace'
	);

	public $hasMany = array(
		'Task' => array(
			'dependent' => true
		)
	);

}