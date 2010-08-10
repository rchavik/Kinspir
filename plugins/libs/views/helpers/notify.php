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
App::import('Helper', 'Session');
class NotifyHelper extends SessionHelper {
	
	function flash($key = 'flash') {
		$out = false;
		if ($this->__active === true && $this->__start()) {
			if (parent::check('Message.' . $key)) {
				$flash = parent::read('Message.' . $key);
				if ($flash['element'] == 'default') {
					if (!empty($flash['params']['class'])) {
						$class = $flash['params']['class'];
					} else {
						$class = 'message';
					}
					$out = '<script type="text/javascript">$(function(){notify(\'noTitle\', { text:\'' . $flash['message'] . '\'});});</script>';
				} elseif ($flash['element'] == '' || $flash['element'] == null) {
					$out = $flash['message'];
				} else {
					$underscore = strpos($flash['element'], '_');
					if ($underscore) {
						$element = substr($flash['element'], ++$underscore);
					} else {
						$element = $flash['element'];
					}
					$out = '<script type="text/javascript">$(function(){notify("' . $element . '", { title:\'' . ucfirst($element) . '\', text:\'' . $flash['message'] . '\'});});</script>';
				}
				parent::delete('Message.' . $key);
			}
		}
		return $out;
	}
}