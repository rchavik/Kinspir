<?php
class Dashboard extends AppModel {

	public $order = 'Dashboard.order ASC';

	public $actsAs = array(
		'Containable',
		'Libs.Trackable',
		'Libs.Sequence' => array(
			'group_fields' => array('user_id'),
			'start_at' => 1
		)
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $hasMany = array(
		'DashboardSlot' => array(
			'dependent' => true
		),
	);

}