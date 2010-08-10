<?php
class UploadsController extends AppController {

	public function index()	{
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
		// setup our conditions and filter them
		$this->paginate = $this->_filterLevel($this->Acl->conditions(array('contain' => array('User' => array('fields' => array('id', 'name')))), $aclOptions));
		parent::index(true);
	}

	public function delete($id = null, $redirect = true) {
		if (!$id) {
			$this->Redirect->flash('no_id', $this->referer);
		}
		if ($redirect === true) {
			$redirect = array('controller' => 'uploads', 'action' => 'index');
		}
		$ids = (array)$id;
		$count = count($ids);
		if ($this->{$this->modelClass}->deleteAll(array($this->modelClass . '.id' => $ids), true, true)) {
			if ($redirect) {
				$this->Redirect->flash(array('delete_ok', $count), $redirect);
			} else {
				return $count;
			}
		} else {
			if ($redirect) {
				$this->Redirect->flash('failed', array('action' => 'index'));
			} else {
				return;
			}
		}
	}

	public function edit($id = null) {
		if (!$id && !$this->Session->check('Workspace.id')) {
			$this->Redirect->flash('not_in_workspace', array('controller' => 'workspaces', 'action' => 'index'));
		}
		// check if we are editing the active version
		if (!isset($this->data['ActiveVersion'])) {
			$milestones = $this->Upload->Milestone->find('list', array('conditions' => array('workspace_id' => $this->Session->read('Workspace.id'))));
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
			$stacks = $this->Upload->Stack->find('list', $stacksConditions);
			$this->set(compact('stacks'));
			parent::edit($id);
		} else {
			$this->Upload->updateAll(
				array('Upload.active_version_id' => $this->data['ActiveVersion']['id']), // set
				array(
					'Upload.id' => $this->data['ActiveVersion']['upload_id'],
					'Upload.workspace_id' => $this->Session->read('Workspace.id')
				) // where
			);
			$this->redirect(array('controller' => 'uploads', 'action' => 'view', $this->data['ActiveVersion']['upload_id']));
		}
	}

	public function view($id = null, $download = null) {
		if (!$id) {
			$this->Redirect->flash('no_id', array('action' => 'index'));
		}
		if ($download === 'download') {
			$this->__download($id);
		} else {
			$this->conditions = array(
				'contain' => array(
					'ActiveVersion' => array(
						'User' => array(
							'fields' => array('id', 'name')
						)
					),
					'User',
					'UploadVersion' => array(
						'User' => array(
							'fields' => array('id', 'name')
						)
					)
				)
			);
		}
		parent::view($id);
		// load the level
		if (($stackId = $this->Upload->field('stack_id'))) {
			$this->_setLevel('stack', $stackId);
		} elseif (($milestoneId = $this->Upload->field('milestone_id'))) {
			$this->_setLevel('milestone', $milestoneId);
		} elseif (($workspaceId = $this->Upload->field('workspace_id'))) {
			$this->_setLevel('stack', $workspaceId);
		}
	}

	private function __download($id = null) {
		$conditions = array(
			'contain' => array(
				'ActiveVersion' => array(
					'User' => array(
						'fields' => array('id', 'name')
					)
				)
			)
		);
		$upload = $this->Upload->find('first', $conditions);
		if (empty($upload)) {
			$this->Redirect->flash('no_data', array('action' => 'index'));
		}
		// grab the file extension (it will be in the last spot of the array)
		$ext = explode('.', $upload['ActiveVersion']['filename']);
		$params = array(
			'download' => true,
			'cache' => '3 days',
			'name' => $upload['Upload']['name'],
			'modified' => $upload['Upload']['updated'],
			'id' => $upload['ActiveVersion']['filename'],
			'path' => APP . 'webroot' . DS . $upload['ActiveVersion']['dir'],
			'mimeType' => $upload['ActiveVersion']['mimetype'],
			'extension' => $ext[count($ext)-1]
		);
		$this->set($params);
		$this->view = 'Media';
		$this->autoLayout = false;
		$this->render();
	}

}
