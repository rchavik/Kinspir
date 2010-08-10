<?php
class MessageFoldersController extends AppController {

    public function constructClasses() {
    	$components = array('Libs.Secure');
    	$this->components = Set::merge($this->components, $components);
    	parent::constructClasses();
    }

	public function index() {
		$this->paginate = array(
			'conditions' => array(
				'user_id' => User::get('id'),
			)
		);
		parent::index();
	}
}