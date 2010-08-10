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
class ToolsComponent extends Object {

/**
 * Ensures that the correct amount of parameters are passed to vsprintf() to ensure
 * no errors occur when it is run.
 *
 * @param string $string String to insert vars into, uses vspintf().
 * @param array $vars Variables to insert into string.
 * @return string String with variables
 * @access public
 */
	public function insertVars($string, $vars = array()) {

		$regex = "/%[-+]?(?:[ 0]|['].)?[a]?\d*(?:[.]\d*)?[%bcdeEufFgGosxX]/";
		preg_match_all($regex, $string, $matches);

		if(!empty($matches[0]) && count($matches[0]) === count($vars)) {
			return vsprintf($string, $vars);
		}
		return $string;

	}
	
/**
 * This method allows quick reverse lookup
 */
	public function keyToId($key = null, $model = null, $keyField = 'key', $groupField = null, $groupId = null) {
		if (!$key || !$model) {
			return;
		}
		$groupConditions = array();
		if ($groupField && $groupId) {
			$groupConditions = array($model.'.'.$groupField => $groupId);
		}
		$conditions = array(
			'conditions' => array(
				$model.'.'.$keyField => $key,
				$groupConditions
			),
			'fields' => array('id', $keyField)
		);
		$result = ClassRegistry::init($model)->find('first', $conditions);
		return $result[$model]['id'];
	}
	
}