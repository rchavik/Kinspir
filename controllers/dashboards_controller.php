<?php
class DashboardsController extends AppController {
	
	public function index() {
		$this->paginate = array(
			'conditions' => array(
				'Dashboard.user_id' => User::get('id')
			),
			'order' => array(
				'Dashboard.order' => 'ASC'
			)
		);
		parent::index();
	}

	public function view($id = null) {
		if ($id === 1) {
			$this->set('title_for_layout', 'Home');
		}
		$this->conditions = $this->Acl->conditions(array(
			'contain' => array(
				'DashboardSlot' => array(
					'Widget'
				)
			)
		));
		parent::view($id);
	}

}