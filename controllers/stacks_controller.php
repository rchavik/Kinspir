<?php
class StacksController extends AppController {

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
		// the calendar is requesting stacks
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
		if (!empty($this->data['Stack'])) {
			if (isset($this->data['Stack']['due']) && $this->data['Stack']['due'] === '') {
				$this->data['Stack']['due'] = null;
			} else {
				$this->data['Stack']['due'] = date('Y-m-d H:i:s', strtotime($this->data['Stack']['due']));
			}
			if (!empty($this->data['Stack']['id']) && !empty($this->data['Stack']['assigned_to_id'])) {
				$this->Stack->id = $this->data['Stack']['id'];
				$oldAssignedToId = $this->Stack->field('assigned_to_id');
			}
		}
		$Subscription = ClassRegistry::init('Subscription');
		$subscribers = $Subscription->findAllByClassAndForeignId('Workspace', $this->Session->read('Workspace.id'));
		$subscribers = Set::extract('/Subscription/user_id', $subscribers);
		$assignedTos = $this->Stack->User->findList(
			array(
				'conditions' => array(
					'User.id' => $subscribers
				),
				'fields' => array('id', 'name')
			)
		);
		$this->set(compact('assignedTos'));
		$milestones = $this->Stack->Milestone->find('list', array('conditions' => array('workspace_id' => $this->Session->read('Workspace.id'))));
		$this->set(compact('milestones'));
		if (parent::edit($id, false)) {
			if (!empty($this->data['Stack']['assigned_to_id'])) {
				if (!isset($oldAssignedToId) || (isset($oldAssignedToId) && ($oldAssignedToId !== $this->data['Stack']['assigned_to_id']))) {
					$this->Stack->notify($this->data['Stack']['assigned_to_id'], $this->Stack->id, $this->Tools->keyToId('stack_assigned', 'NotificationType'));
				}
			}
			$this->_setLevel('stack');
			$this->Redirect->flash(array('save_ok', 'Stack'), array('controller' => 'stacks', 'action' => 'index'));
		}
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash(null, array('action' => 'index'));
		}
		// load the stack
		$this->_setLevel('stack', $id);
	}

	private function __calendarResponse($conditions) {
		$results = $this->Stack->find('all', $conditions);
		$stacks = array();
		foreach ($results as $result) {
			$result = $result['Stack'];
			$stacks[] = array(
				'id' => $result['id'],
				'title' => $result['name'],
				'start' => strtotime($result['due'] . '+12 hour'), // adding 12 hours as workaround for not setting timezone (temporary)
				'allDay' => true,
				'url' => Router::url(array('controller' => 'stacks', 'action' => 'view', $result['id'])),
				'className' => 'white'
			);
		}
		$this->set(compact('stacks'));
	}

}