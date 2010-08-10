<?php
class FeedsController extends AppController {

	public function index() {
		$feedUsers = $this->_userConnections();
		$feedUsers[] = User::get('id');
		$Workspace = ClassRegistry::init('Workspace');
		$workspaces = $Workspace->find('all',
			$this->Acl->conditions(
				array(
					'fields' => 'id'
				),
				array(
					'cache' => false
				)
			)
		);
		$workspaceIds = Set::extract('/Workspace/id', $workspaces);
		if (empty($workspaceIds)) {
			$workspaceIds = -1;
		}
		$conditions = array();
		switch (true) {
			case (!empty($this->params['named']['view'])) :
				// keep conditions empty
				break;
			case ($this->Session->check('Stack.id')) :
				$conditions = array(
					'conditions' => array(
						'Feed.stack_id' => $this->Session->read('Stack.id')
					)
				);
				break;
			case ($this->Session->check('Milestone.id')):
				$conditions = array(
					'conditions' => array(
						'Feed.milestone_id' => $this->Session->read('Milestone.id')
					)
				);
				break;
			case ($this->Session->check('Workspace.id')) :
				$conditions = array(
					'conditions' => array(
						'Feed.workspace_id' => $this->Session->read('Workspace.id')
					)
				);
				break;
			default :
				$conditions = array(
					'conditions' => array(
						'OR' => array(
							'Feed.user_id' => $feedUsers,
							'Feed.workspace_id' => $workspaceIds
						)
					)
				);
		}
		$conditions = Set::merge($conditions,
			array(
				'order' => array(
					'Feed.id DESC'
				),
				'contain' => array(
					'User' => array(
						'fields' => array(
							'id',
							'name',
							'email',
							'facebook_id'
						)
					),
					'Milestone' => array(
						'fields' => array('id', 'name')
					),
					'Stack' => array(
						'fields' => array('id', 'name')
					),
					'Workspace' => array(
						'fields' => array('id', 'name')
					)
				),
				'limit' => 20,
				/*
				'polyConditions' => array(
					'Event' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							)
						)
					),
					'Milestone' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							)
						)
					),
					'Stack' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							),
							'Milestone' => array(
								'fields' => array('id', 'name')
							)
						)
					),
					'Task' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							),
							'Milestone' => array(
								'fields' => array('id', 'name')
							),
							'Stack' => array(
								'fields' => array('id', 'name')
							)
						)
					),
					'TaskGroup' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							),
							'Milestone' => array(
								'fields' => array('id', 'name')
							),
							'Stack' => array(
								'fields' => array('id', 'name')
							)
						)
					),
					'Upload' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							),
							'Milestone' => array(
								'fields' => array('id', 'name')
							),
							'Stack' => array(
								'fields' => array('id', 'name')
							)
						)
					),
					'Workspace' => array('fields' => array('id', 'name')),
					'Wiki' => array(
						'contain' => array(
							'Workspace' => array(
								'fields' => array('id', 'name')
							),
							'Stack' => array(
								'fields' => array('id', 'name')
							)
						)
					)
				)
			*/
			)
		);
		if (isset($this->params['named']['user'])) {
			$conditions['conditions'][] = array(
				'User.id' => $this->params['named']['user']
			);
		}
		$this->paginate = $conditions;
		
		$this->set('title_for_layout', 'Feed');
		parent::index();
	}

	public function home() {
		// reset the user
		$this->_setLevel();
		$this->index();
		$this->render('index');
	}

}