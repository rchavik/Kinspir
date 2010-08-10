<?php
class Widget extends AppModel {

	public $actsAs = array(
		'Containable',
		'Libs.Trackable',
	);

	public $belongsTo = array(
		'DashboardSlot',
		'User'
	);

}