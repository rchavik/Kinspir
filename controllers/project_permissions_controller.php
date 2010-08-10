<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
// load the RepoAppController class
require_once(APP_PATH . 'repo_app_controller.php');
/**
 * undocumented class
 *
 * @package default
 */
class ProjectPermissionsController extends RepoAppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'ProjectPermissions';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->mapActions(array(
			'index' => 'update'
		));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		if (empty($this->params['isAdmin'])) {
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			$this->data['ProjectPermission']['username'] = $this->Auth->user('username');
			if (!empty($this->params['form']['default'])) {
				$this->data = array('username' => '@admin');
			}
			if ($this->ProjectPermission->saveFile($this->data)) {
				$this->Session->setFlash(__('Project Permissions updated',true));
			} else {
				$this->Session->setFlash(__('Project Permissions NOT updated',true));
			}
		}
		$this->data['ProjectPermission']['fine_grained'] = $this->ProjectPermission->file();

		$groups = $this->Project->groups();

		$this->set(compact('users', 'groups'));
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function remove($id = null) {
		if (!$id || empty($this->params['isAdmin'])) {
			$this->Session->setFlash(__('Invalid request',true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if ($this->ProjectPermission->delete($id)) {
			$this->Session->setFlash(__('User removed',true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
	}
}
?>