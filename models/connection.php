<?php
class Connection extends AppModel {

	public $recursive = 0;
	public $displayField = "title";

	public $virtualFields = array(
		"name" => "CONCAT('to ', Receiver.name)",
		"title" => "'Connection'"
	);

	public $actsAs = array(
		'Containable',
		'Libs.Trackable',
		'Notifiable',
		//'Feedable'
	);

	public $belongsTo = array(
		'User',
		'Receiver' => array(
			'className' => 'User',
			'foreignKey' => 'receiver_id'
		)
	);

}