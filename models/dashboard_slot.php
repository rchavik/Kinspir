<?php
class DashboardSlot extends AppModel {

	public $order = 'DashboardSlot.order ASC';

	public $actsAs = array(
		'Containable',
		'Libs.Sequence' => array(
			'group_fields' => array('dashboard_id', 'column'),
			'start_at' => 1
		)
	);

	public $belongsTo = array(
		'Dashboard',
		'Widget'
	);

}