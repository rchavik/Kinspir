<?php
class WorkspacesController extends AppController {

	public function index() {
		// setup our conditions
		$this->paginate = $this->Acl->conditions();
		parent::index(array('controller' => 'workspaces', 'action' => 'add'), 'Workspace');
	}

	public function edit($id = null) {
		if (parent::edit($id, false)) {
			$this->_setLevel('workspace');
			$this->Workspace->subscribe(User::get('id'), $this->Workspace->id);
			// setup our default redirect
			$redirect = array('controller' => 'workspaces', 'action' => 'index');
			// if they are just creating the workspace, load them into it
			if (empty($this->data['Workspace']['id'])) {
				$redirect = array('controller' => 'workspaces', 'action' => 'view', $this->Workspace->id);
			}
			$this->Redirect->flash(array('save_ok', 'Workspace'), $redirect);
		}
	}

	public function view($id = null) {
		if (!$id) {
			$this->Redirect->flash(null, array('controller' => 'workspaces', 'action' => 'index'));
		}
		// load the workspace
		$this->_setLevel('workspace', $id);
		$this->Redirect->flash(null, array('controller' => 'feeds', 'action' => 'index'));
	}

}
