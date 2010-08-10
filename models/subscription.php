<?php
class Subscription extends AppModel {

	public $actsAs = array(
		'Containable',
		'Libs.Polymorphic'
	);

	public $belongsTo = array(
		'User'
	);

}