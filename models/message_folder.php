<?php
class MessageFolder extends AppModel {

	public $actsAs = array(
		'Containable',
		'Libs.Trackable',
		'Libs.Sequence' => array(
			'group_fields' => array('user_id'),
			'start_at' => 1
		)
	);

	public $belongsTo = array(
		'Message',
		'User'
	);

	public function beforeSave() {
		if (!empty($this->data[$this->alias]['name'])) {
			$this->data[$this->alias]['name'] = strtolower(Inflector::slug($this->data[$this->alias]['name']));
		}
		return true;
	}

}