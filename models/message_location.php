<?php
class MessageLocation extends AppModel {

	public $belongsTo = array(
		'MessageFolder',
		'Message',
		'User'
	);

}