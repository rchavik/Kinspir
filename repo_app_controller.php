<?php
class RepoAppController extends AppController {

	/**
	 * @var unknown_type
	 */
	public $components = array(
		'Access'
	);

	/**
	 * @var unknown_type
	 */
	public $helpers = array(
		'Chaw'
	);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project');

	/**
	 *
	 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->authorize = false;
		$this->Auth->authError = 'Invalid Project';
		$this->Auth->mapActions(array(
			'modify' => 'update',
			'remove' => 'delete'
		));
		if (!empty($this->params['admin'])) {
			$this->Auth->authorize = 'controller';
		}
		// tell ACL to back off (temporary)
		//$this->Auth->allowedActions = array('*');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function isAuthorized() {
		if (!empty($this->params['admin']) && empty($this->params['isAdmin'])) {
			if ($this->Access->check($this, array('access' => 'w', 'default' => false)) === true) {
				return true;
			}
			$this->Session->setFlash($this->Auth->authError, 'default', array(), 'auth');
			$this->redirect(array(
				'admin' => false, 'username' => false, 'project' => false, 'fork' => false,
				'controller' => 'projects', 'action' => 'index'
			));
			return false;
		}
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeRender() {
		if ($this->params['isAdmin'] !== true) {
			$this->params['admin'] = false;
		}

		$this->params['project'] = null;
		if (!empty($this->Project->current) && $this->Project->id !== '1') {
			$this->params['project'] = $this->Project->current['url'];
		}

		if (isset($this->viewVars['rssFeed'])) {
			$this->viewVars['rssFeed'] = array_merge(
				array(
				'controller' => 'timeline', 'action' => 'index', 'ext' => 'rss'
				),
				$this->viewVars['rssFeed']
			);
		}

		$this->set('CurrentUser', Set::map($this->Auth->user()));
		$this->set('CurrentProject', Set::map(Configure::read('Project'), true));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function redirect($url = array(), $status = null, $exit = true) {
		if (is_array($url)) {
			if (!empty($this->params['project'])) {
				$url = array_merge(array('project' => $this->params['project']), $url);
			}
			if (!empty($this->params['fork'])) {
				$url = array_merge(array('fork' => $this->params['fork']), $url);
			}
			$username = null;
			if (!empty($this->params['project']) && empty($this->params['fork'])) {
				$url = array_merge(array('username' => $this->params['username']), $url);
			}
		}
		parent::redirect($url, $status, $exit);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function referer($default = null, $local = true) {
		return parent::referer($default, $local);
	}

}
