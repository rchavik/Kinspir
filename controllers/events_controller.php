<?php
class EventsController extends AppController {

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
					'fields' => array('id', 'name', 'starts', 'ends')
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
		if (!empty($this->data['Event'])) {
			$this->data['Event']['starts'] = date('Y-m-d H:i:s', strtotime($this->data['Event']['starts']));
			if (isset($this->data['Event']['ends']) && $this->data['Event']['ends'] === '') {
				$this->data['Event']['ends'] = null;
			} else {
				$this->data['Event']['ends'] = date('Y-m-d H:i:s', strtotime($this->data['Event']['ends']));
				if ($this->data['Event']['ends'] < $this->data['Event']['starts']) {
					$this->data['Event']['ends'] = $this->data['Event']['starts'];
				}
			}
		}
		$milestones = $this->Event->Milestone->find('list', array('conditions' => array('workspace_id' => $this->Session->read('Workspace.id'))));
		$this->set(compact('milestones'));
		if ($this->Session->check('Milestone.id')) {
			$stacksConditions = array(
				'conditions' => array(
					'milestone_id' => $this->Session->read('Milestone.id')
				)
			);
		} else {
			$stacksConditions = array(
				'conditions' => array(
					'workspace_id' => $this->Session->read('Workspace.id')
				)
			);
		}
		$stacks = $this->Event->Stack->find('list', $stacksConditions);
		$this->set(compact('stacks'));
		parent::edit($id);
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_id', array('action' => 'index'));
		}
		// load the level
		$this->Event->id = $id;
		if (($stackId = $this->Event->field('stack_id'))) {
			$this->_setLevel('stack', $stackId);
		} elseif (($milestoneId = $this->Event->field('milestone_id'))) {
			$this->_setLevel('milestone', $milestoneId);
		} elseif (($workspaceId = $this->Event->field('workspace_id'))) {
			$this->_setLevel('stack', $workspaceId);
		}
		parent::view($id);
	}

	private function __calendarResponse($conditions) {
		$results = $this->Event->find('all', $conditions);
		$events = array();
		foreach ($results as $result) {
			$result = $result['Event'];
			$events[] = array(
				'id' => $result['id'],
				'title' => $result['name'],
				'start' => strtotime($result['starts'] . '+12 hour'), // adding 12 hours as workaround for not setting timezone (temporary)
				'end' => strtotime($result['ends'] . '+12 hour'), // adding 12 hours as workaround for not setting timezone (temporary)
				'url' => Router::url(array('controller' => 'events', 'action' => 'view', $result['id'])),
				//'className' => 'yellow'
			);
		}
		$this->set(compact('events'));
	}

}