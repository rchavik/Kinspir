<?php
class DeletedMessage extends AppModel {

	public $actsAs = array(
		'Libs.Trackable'
	);

	public $belongsTo = array(
		'Message',
		'User'
	);

}