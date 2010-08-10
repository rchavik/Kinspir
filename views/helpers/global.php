<?php
class GlobalHelper extends HtmlHelper {

	var $helpers = array('Session');

	function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$defaults = array(
			'current' => false
		);
		$options = am($defaults, $options);
		if ($options['current']) {
			if ($options['current'] === true) {
				$set = false;
				if (!is_array($url)) {
					if ($this->params['controller'] === 'feeds' && $url === '/home' && !$this->Session->check('Workspace')) {
						$set = true;
					}
				} elseif (!empty($url['controller'])) {
					if ($url['controller'] === $this->params['controller']) {
						$set = true;
					}
					if ($this->Session->check('Stack') || $this->Session->check('Milestone') || $this->Session->check('Workspace')) {
						if ($this->Session->check('Stack')) {
							if ($url['controller'] === 'stacks' && $url[0] === $this->Session->read('Stack.id')) {
								$set = true;
							}
						} elseif ($this->Session->check('Milestone')) {
							if ($url['controller'] === 'milestones' && $url[0] === $this->Session->read('Milestone.id')) {
								$set = true;
							}
						} elseif ($this->Session->check('Workspace')) {
							if ($url['controller'] === 'workspaces' && $url[0] === $this->Session->read('Workspace.id')) {
								$set = true;
							}
						}
					}
					$repoControllers = array(
						'source',
						'commits',
						'timeline',
						'projects',
						'dashboard',
						'project_permissions',
						'repo',
						'users'
					);
					if (in_array($this->params['controller'], $repoControllers) && ($url['controller'] === 'projects')) {
						$set = true;
					}
				}
				if ($set) {
					if (!empty($options['class'])) {
						$options['class'] .= ' current';
					} else {
						$options['class'] = 'current';
					}
				}
			}
		}
		// unset our custom options
		unset($options['current']);
		// kick them out of plugins, usernames, projects, and admin
		if (is_array($url)) {
			$url = am(
				array(
					'plugin' => false,
					'username' => false,
					'project' => false,
					'admin' => false
				),
				$url
			);
		}
		return parent::link($title, $url, $options, $confirmMessage);
	}

}