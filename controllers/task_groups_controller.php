<?php
class TaskGroupsController extends AppController {

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
		// setup our conditions
		$conditions = array(
			'TaskGroup' => (
				$this->Acl->conditions(
					array(
						'order' => array('TaskGroup.order' => 'ASC'),
						'limit' => null
					),
					$aclOptions
				)
			)
		);
		// if they pass a stack only show them task groups under that stack
		if (!empty($this->params['named']['stack'])) {
			$conditions['TaskGroup']['conditions'] = array('TaskGroup.stack_id' => $this->params['named']['stack']);
		}
		// filter our conditions
		$this->paginate = $this->_filterLevel($conditions, 'TaskGroup');
		parent::index();
	}

	public function edit($id = null) {
		if (!$this->Session->check('Workspace.id')) {
			$this->Redirect->flash('not_in_workspace', array('controller' => 'workspaces', 'action' => 'index'));
		}
		if (!empty($this->data['TaskGroup'])) {
			if (!empty($this->data['TaskGroup']['id']) && !empty($this->data['TaskGroup']['assigned_to_id'])) {
				$this->TaskGroup->id = $this->data['TaskGroup']['id'];
				$oldAssignedToId = $this->TaskGroup->field('assigned_to_id');
			}
		}
		$Subscription = ClassRegistry::init('Subscription');
		$subscribers = $Subscription->findAllByClassAndForeignId('Workspace', $this->Session->read('Workspace.id'));
		$subscribers = Set::extract('/Subscription/user_id', $subscribers);
		$assignedTos = $this->TaskGroup->User->findList(
			array(
				'conditions' => array(
					'User.id' => $subscribers
				),
				'fields' => array('id', 'name')
			)
		);
		$this->set(compact('assignedTos'));
		$milestones = $this->TaskGroup->Milestone->find('list', array('conditions' => array('workspace_id' => $this->Session->read('Workspace.id'))));
		$this->set(compact('milestones'));
		$stacksConditions = array();
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
		$stacks = $this->TaskGroup->Stack->find('list', $stacksConditions);
		$this->set(compact('stacks'));
		parent::edit($id, array('controller' => 'tasks', 'action' => 'index'));
	}

	public function order() {
		if (!empty($this->params['named']['workspace']) && !empty($this->params['form']['taskgroup'])) {
			$taskGroups = $this->params['form']['taskgroup'];
			$taskGroupData['TaskGroup'] = array();
			foreach ($taskGroups as $order => $id) {
				$taskGroupData['TaskGroup'][] = array(
					'id' => $id,
					'order' => ++$order // shift to start at 1
				);
			}
			$this->TaskGroup->saveAll($taskGroupData['TaskGroup'], array('callbacks' => false));
		}
		$this->render(false);
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_id', array('controller' => 'tasks', 'action' => 'index'));
		}
		// load the level
		$this->TaskGroup->id = $id;
		if (($stackId = $this->TaskGroup->field('stack_id'))) {
			$this->_setLevel('stack', $stackId);
		} elseif (($milestoneId = $this->TaskGroup->field('milestone_id'))) {
			$this->_setLevel('milestone', $milestoneId);
		} elseif (($workspaceId = $this->TaskGroup->field('workspace_id'))) {
			$this->_setLevel('stack', $workspaceId);
		}
		$this->Redirect->flash(null, array('controller' => 'tasks', 'action' => 'index', 'task-group' => $id));
	}

}