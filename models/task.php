<?php
class Task extends AppModel {

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'controlled',
			'parentClass'=> 'TaskGroup',
			'foreignKey' => 'task_group_id'
		),
		'Filterable',
		'Containable',
		'Libs.Trackable',
		'Feedable',
		'Libs.Sequence' => array(
			'group_fields' => array('task_group_id', 'is_complete'),
			'start_at' => 1
		),
		'Notifiable',
		'Commentable' // @todo get a better commentable behavior?
	);

	public $validate = array(
		'name' => 'notempty'
	);

	public $belongsTo = array(
		'AssignedTo' => array(
			'className' => 'User',
			'foreignKey' => 'assigned_to_id'
		),
		'Milestone',
		'Stack',
		'TaskGroup',
		'User',
		'Workspace'
	);

}