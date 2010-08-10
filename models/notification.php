<?php
class Notification extends AppModel {

	public $actsAs = array(
		'Containable',
		'Libs.Polymorphic'
	);

	public $belongsTo = array(
		'User',
		'Receiver' => array(
			'className' => 'User',
			'foreignKey' => 'receiver_id',
			'counterCache' => true,
			'counterScope' => array('Notification.is_read' => NULL)
		),
		'NotificationType'
	);

}