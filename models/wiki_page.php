<?php
class WikiPage extends AppModel {

	public $actsAs = array(
		'Revision' => array(
			'limit' => 10,
		),
		'SuperAuth.Acl' => array(
			'type' => 'controlled',
			'parentClass'=> 'Workspace',
			'foreignKey' => 'workspace_id'
		),
		'Filterable',
		'Containable',
		'Libs.Trackable',
		'Libs.Sequence' => array(
			'group_fields' => array('workspace_id'),
			'start_at' => 1
		),
		'Feedable'
	);

	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a title'
			),
		),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please don\'t leave the contents empty'
			),
		),
	);

	public $belongsTo = array(
		'Stack',
		'User',
		'Workspace'
	);

}