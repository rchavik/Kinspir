<?php
class TasksController extends AppController {

	/**
	 * 
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		// this sets up row-level acl for the defined methods
		if (in_array($this->action, array('complete'))) {
			$this->Auth->authorize = 'acl';
		}
		// this tells row-level acl that the complete method is an alias of update (edit)
		$this->Auth->mapActions(array('complete' => 'update'));
	}

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
		// the calendar is requesting tasks
		if ($this->RequestHandler->prefers('json')) {
			// setup our conditions
			$conditions = $this->Acl->conditions(
				array(
					'fields' => array('id', 'name', 'due'),
					'conditions' => array(
						'NOT' => array(
							'Task.due' => NULL
						),
						'is_complete' => NULL
					)
				),
				$aclOptions
			);
			// filter our conditions
			$this->paginate = $this->_filterLevel($conditions);
			parent::index();
			return;
		}
		// the request is for a stack
		if (!empty($this->params['named']['stack'])) {
			// setup our conditions
			$conditions = $this->Acl->conditions(
				array(
					'conditions' => array(
						'Task.is_complete' => NULL,
						'Task.stack_id' => $this->params['named']['stack']
					)
				),
				$aclOptions
			);
			// filter our conditions
			$this->paginate = $conditions;
			parent::index();
			return;
		}
		// setup our conditions
		$conditions = array(
			'TaskGroup' => (
				$this->Acl->conditions(
					array(
						'contain' => array(
							'Task' => array(
								'conditions' => array(
									'is_complete' => NULL,
								),
								'order' => array('Task.order' => 'ASC'),
								'AssignedTo' => array(
									'fields' => array('id', 'name')
								),
								'Stack' => array(
									'fields' => array('id', 'name')
								)
							)
						),
						'order' => array('TaskGroup.order' => 'ASC')
					),
					$aclOptions
				)
			)
		);
		// if they pass a task-group show them only that task group
		if (!empty($this->params['named']['task-group'])) {
			$conditions['TaskGroup']['conditions'] = array('TaskGroup.id' => $this->params['named']['task-group']);
		}
		// filter our conditions
		$this->paginate = $this->_filterLevel($conditions, 'TaskGroup');
		parent::index(array('controller' => 'task_groups', 'action' => 'add'), 'TaskGroup');
	}

	public function edit($id = null) {
		if (!$id && !$this->Session->check('Workspace.id')) {
			$this->Redirect->flash('not_in_workspace', array('controller' => 'workspaces', 'action' => 'index'));
		}
		if (!empty($this->data['Task'])) {
			if (isset($this->data['Task']['due']) && $this->data['Task']['due'] === '') {
				$this->data['Task']['due'] = null;
			} else {
				$this->data['Task']['due'] = date('Y-m-d H:i:s', strtotime($this->data['Task']['due']));
			}
			if (!empty($this->data['Task']['id']) && !empty($this->data['Task']['assigned_to_id'])) {
				$this->Task->id = $this->data['Task']['id'];
				$oldAssignedToId = $this->Task->field('assigned_to_id');
			}
		}
		$Subscription = ClassRegistry::init('Subscription');
		$subscribers = $Subscription->findAllByClassAndForeignId('Workspace', $this->Session->read('Workspace.id'));
		$subscribers = Set::extract('/Subscription/user_id', $subscribers);
		$assignedTos = $this->Task->User->findList(
			array(
				'conditions' => array(
					'User.id' => $subscribers
				),
				'fields' => array('id', 'name')
			)
		);
		$this->set(compact('assignedTos'));
		$milestones = $this->Task->Milestone->find('list', array('conditions' => array('workspace_id' => $this->Session->read('Workspace.id'))));
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
		$stacks = $this->Task->Stack->find('list', $stacksConditions);
		$this->set(compact('stacks'));
		if (parent::edit($id, false, false)) {
			if (!empty($this->data['Task']['assigned_to_id'])) {
				if (!isset($oldAssignedToId) || (isset($oldAssignedToId) && ($oldAssignedToId !== $this->data['Task']['assigned_to_id']))) {
					$this->Task->notify($this->data['Task']['assigned_to_id'], $this->Task->id, $this->Tools->keyToId('task_assigned', 'NotificationType'));
				}
			}
			$this->Redirect->flash(array('save_ok', 'Task'), array('controller' => 'tasks', 'action' => 'index', '#taskgroup_' . $this->data['Task']['task_group_id']));
		}
		if (!empty($this->params['named']['task-group'])) {
			$taskGroupId = $this->params['named']['task-group'];
			$this->data['Task']['task_group_id'] = $taskGroupId;
			$this->Task->TaskGroup->id = $taskGroupId;
			$taskGroupName = $this->Task->TaskGroup->field('name');
			$this->set(compact('taskGroupName'));
		}
		$this->render('edit');
	}

	public function complete($id = null) {
		if (!$id && !$this->Session->check('Workspace.id')) {
			$this->Redirect->flash('not_in_workspace', array('controller' => 'workspaces', 'action' => 'index'));
		}
		$task['Task'] = array(
			'id' => $id,
			'completed' => $this->dateTime,
			'is_complete' => true
		);
		$task['Feed'] = array(
			'feed_action' => 'completed'
		);
		$this->Task->id = $id;
		$status = $this->Task->field('is_complete');
		if ($status) {
			$task['Task']['completed'] = null;
			$task['Task']['is_complete'] = null;
			$task['Feed']['feed_action'] = 'uncompleted';
		}
		if ($this->Task->save($task)) {
			$this->Redirect->flash(array('task_status', ($task['Task']['is_complete'] ? 'complete' : 'incomplete')), $this->referer);
		}
		$this->Redirect->flash('failed', $this->referer);
	}

	public function order() {
		if (!empty($this->params['named']['task-group']) && !empty($this->params['form']['task'])) {
			$taskGroupId = $this->params['named']['task-group'];
			$tasks = $this->params['form']['task'];
			$taskData['Task'] = array();
			foreach ($tasks as $order => $id) {
				$taskData['Task'][] = array(
					'id' => $id,
					'order' => ++$order, // shift to start at 1
					'task_group_id' => $taskGroupId
				);
			}
			$this->Task->saveAll($taskData['Task'], array('callbacks' => false));
		}
		$this->render(false);
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash('no_id', array('action' => 'index'));
		}
		$this->conditions = array(
			'contain' => array(
				'AssignedTo',
				'TaskGroup',
				'Comment' => array(
					'User' => array(
						'fields' => array('id', 'name')
					),
					'order' => array('id' => 'ASC')
				)
			)
		);
		// load the level
		if (($stackId = $this->Task->field('stack_id'))) {
			$this->_setLevel('stack', $stackId);
		} elseif (($milestoneId = $this->Task->field('milestone_id'))) {
			$this->_setLevel('milestone', $milestoneId);
		} elseif (($workspaceId = $this->Task->field('workspace_id'))) {
			$this->_setLevel('stack', $workspaceId);
		}
		parent::view($id);
	}

	public function newest() {
		$tasks = $this->Task->find('all',
			array(
				'conditions' => array(
					'Task.workspace_id' => $this->Session->read('Workspace.id')
				),
				'fields' => array('id', 'name', 'created', 'is_complete', 'due'),
				'order' => array(
					'Task.id DESC'
				),
				'limit' => 6
			)
		);
		$this->set(compact('tasks'));
	}

	private function __calendarResponse($conditions) {
		$results = $this->Task->find('all', $conditions);
		$tasks = array();
		foreach ($results as $result) {
			$result = $result['Task'];
			$tasks[] = array(
				'id' => $result['id'],
				'title' => $result['name'],
				'start' => strtotime($result['due'] . '+12 hour'), // adding 12 hours as workaround for not setting timezone (temporary)
				'allDay' => true,
				'url' => Router::url(array('controller' => 'tasks', 'action' => 'view', $result['id'])),
				'className' => 'green'
			);
		}
		$this->set(compact('tasks'));
	}

}
