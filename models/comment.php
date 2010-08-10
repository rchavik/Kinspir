<?php
class Comment extends AppModel {

	public $actsAs = array(
		'Containable',
		'Libs.Trackable',
		'Notifiable'
	);

	public $belongsTo = array(
		'User'
	);

	public function afterSave() {
		if (!empty($this->data['Comment']['class']) && !empty($this->data['Comment']['foreign_id'])) {
			if (!ClassRegistry::init($this->data['Comment']['class'])->updateCommentCount($this->data['Comment']['foreign_id'])) {
				return;
			}
		}
		return true;
	}

}