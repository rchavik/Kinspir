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
class RedirectComponent extends Object {

/**
 * @var array Requires Cake SessionComponent
 * @access public
 */
	public $components = array(
		'Session'
	);

/**
 * Passes the controller object by reference for use later. Runs when component
 * is loaded for the first time in the request cycle.
 *
 * @param array $controller Controller object.
 * @param array $settings Settions to load into the component.
 * @access public
 * @return void
 */
	public function initialize(&$controller, $settings = array()) {

		$this->controller =& $controller;
		if ($settings) {
			$this->_set($settings);
		}

	}

/**
 * Loads a message from the a list of unique keys loaded into the configure class and
 * accessd via Configure::read('Flash.key'); can insert variables into loaded template
 * if need be.
 *
 * $message can either be a key string or a full message to display when the page next
 * loads.
 *
 * $message may also be an array. The first key in the array should be a string containing
 * the message template key, the second item is a string or an array of multiple parameters
 * to insert into the string as vsprintf() note that if an incorrect amount of parameters
 * are passed then the values will not be inserted and you will end up with a message similar
 * to "There are %1$d parameter(s)".
 *
 * Examples, calls from the controller based on templates defined flashes table:
 *
 * Input
 * $this->Redirect->flash('This is a flash message');
 *
 * Output
 * Message: This is a flash message
 * Redirect: No
 * Type: flash_note (default)
 *
 * Input
 * $this->Redirect->flash('input_errors');
 *
 * Output
 * Message: Please correct the errors bellow
 * Redirect: No
 * Type: flash_warning
 *
 * Input
 * $this->Redirect->flash(array('add_ok', 'foo-bar'), '/users/dashboard');
 *
 * Output
 * Message: New foo-bar has been saved.
 * Redirect: UsersController::dashboard()
 * Type: flash_success
 *
 * Input (verbose)
 * $this->Redirect->flash(array('delete_ok', array(10)), array('controller' => 'users', 'action' => 'dashboard'), 'success');
 *
 * Output
 * Message: 10 record(s) have been deleted.
 * Redirect: UsersController::dashboard()
 * Type: flash_success
 *
 * If $url is set flash will also redirect to given url after setting the flash message
 * in the session. Anything you can pass to HtmlHelper::link(); you can pass as this parameter.
 *
 * @param mixed $message String or array containing message key or a full message and array/string containing additional variable(s).
 * @param mixed $url Cake formatted url array or string to redirect to before displaying flash.
 * @param string $type Type of flash to display.
 * @return void
 * @access public
 */
	public function flash($message = null, $url = null, $type = 'note') {

		$message = Set::merge((array) $message, array(null, array()));
		list($message, $params) = $message;
		$params = (array) $params;
		
		if ($this->controller->RequestHandler->isAjax()) {
			$this->controller->RequestHandler->beforeRedirect($this->controller,$url);
		} elseif (is_string($message)) {
			if ($find = $this->__findByKey($message)) {
				extract($find);
			}
			$this->Session->setFlash(
				$this->controller->Tools->insertVars($message, $params),
				'flash_' . $type
			);
		}

		if (!$url) {
			return;
		}
		
		$this->controller->redirect($url);

	}

/**
 * Load and instantiate a model object (if required) and perform a simple query
 *
 * @param string $key Key to look up in Flash model.
 * @return array A result set constructed from the flash configuration array.
 * @access private
 */
	private function __findByKey($key = null) {
		$flash = null;
		$value = Configure::read('FlashMessages.' . $key);
		if (!empty($value)) {
			$flash['message'] = $value[0];
			if (isset($value[1])) {
				$flash['type'] = $value[1];
			}
		}
		return $flash;
	}

}