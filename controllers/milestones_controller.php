<?php
class MilestonesController extends AppController {

	public function index() {
		// if they are not in a workspace, let's refresh their cache
		// and merge the aclConditions in
		if ($this->Session->check('Workspace.id')) {
			$aclOptions = array(
				'cache' => false,
				'merge' => false
			);
		} else {
			$aclOptions = array(
				'cache' => true,
				'merge' => true
			);
		}
		// the calendar is requesting milestones
		if ($this->RequestHandler->prefers('json')) {
			// setup our conditions
			$conditions = $this->Acl->conditions(
				array(
					'fields' => array('id', 'name', 'due')
				),
				$aclOptions
			);
			// filter our conditions
			$conditions = $this->_filterLevel($conditions);
			$this->__calendarResponse($conditions);
			return;
		}
		// setup our conditions and filter them
		$this->paginate = $this->_filterLevel($this->Acl->conditions(null, $aclOptions));
		parent::index(true);
	}

	public function edit($id = null) {
		if (!$id && !$this->Session->check('Workspace.id')) {
			$this->Redirect->flash('not_in_workspace', array('controller' => 'workspaces', 'action' => 'index'));
		}
		if (!empty($this->data['Milestone'])) {
			if (isset($this->data['Milestone']['due']) && $this->data['Milestone']['due'] === '') {
				$this->data['Milestone']['due'] = null;
			} else {
				$this->data['Milestone']['due'] = date('Y-m-d H:i:s', strtotime($this->data['Milestone']['due']));
			}
		}
		if (parent::edit($id, false)) {
			$this->_setLevel('milestone');
			// setup our default redirect
			$redirect = array('controller' => 'milestones', 'action' => 'index');
			// if they are just creating the milestone, load them into it
			if (empty($this->data['Milestone']['id'])) {
				$redirect = array('controller' => 'milestones', 'action' => 'view', $this->Milestone->id);
			}
			$this->Redirect->flash(array('save_ok', 'Milestone'), $redirect);
		}
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash(null, array('action' => 'index'));
		}
		// load the milestone
		$this->_setLevel('milestone', $id);
		$this->Redirect->flash(null, array('controller' => 'feeds', 'action' => 'index'));
	}

	private function __calendarResponse($conditions) {
		$results = $this->Milestone->find('all', $conditions);
		$milestones = array();
		foreach ($results as $result) {
			$result = $result['Milestone'];
			$milestones[] = array(
				'id' => $result['id'],
				'title' => $result['name'],
				'start' => strtotime($result['due'] . '+12 hour'), // adding 12 hours as workaround for not setting timezone (temporary)
				'allDay' => true,
				'url' => Router::url(array('controller' => 'milestones', 'action' => 'view', $result['id'])),
				'className' => 'yellow'
			);
		}
		$this->set(compact('milestones'));
	}

}