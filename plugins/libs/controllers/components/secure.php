<?php
/*
 * Kinspir.Libs is free software, you can redistribute it and/or modify
 * it under the terms of GNU Affero General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or (at your option) any later version.

 * You should have received a copy of the the GNU Affero
 * General Public License, along with Kinspir.Libs. If not, see

 * Additional permission under the GNU Affero GPL version 3 section 7:

 * If you modify this Program, or any covered work, by linking or
 * combining it with other code, such other code is not for that reason
 * alone subject to any of the requirements of the GNU Affero GPL
 * version 3.
 */
/**
 * This class facilitates owner-only row modification. (i.e. user_id 1 can only access/modify his own rows)
 */
class SecureComponent extends Object {

	//called before Controller::beforeFilter()
	public function initialize(&$Controller, $settings = array()) {
		$this->Controller =& $Controller;
		$defaults = array(
			'actions' => array('view', 'edit', 'delete'),
			'redirect' => array('action' => 'index'),
			'userField' => 'user_id',
			'flashMessage' => 'no_data'
		);
		$this->settings = array_merge($defaults, $settings);
	}

	//called after Controller::beforeFilter()
	public function startup(&$Controller) {
		if (in_array($this->Controller->action, $this->settings['actions'])) {
			if (!$this->checkOwner()) {
				$this->__redirect();
			}
		}
	}

	private function __redirect($value) {
		$this->Controller->Redirect->flash($this->settings['flashMessage'], $this->settings['redirect']);
	}
	
	public function checkOwner() {
		if (!empty($this->Controller->params['pass'][0])) {
			$id = $this->Controller->params['pass'][0];
		} elseif (!empty($this->Controller->{$this->Controller->modelClass}->data[$this->Controller->{$this->Controller->modelClass}->alias]['id'])) {
			$id = $this->Controller->{$this->Controller->modelClass}->data[$this->Controller->{$this->Controller->modelClass}->alias]['id'];
		} else {
			return true;
		}
		$conditions = array(
			'conditions' => array(
				$this->Controller->{$this->Controller->modelClass}->escapeField('id') => $id,
				$this->Controller->{$this->Controller->modelClass}->escapeField($this->settings['userField']) => User::get('id')
			)
		);
		return $this->Controller->{$this->Controller->modelClass}->find('count', $conditions);
	}
	
}