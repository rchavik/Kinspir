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
class ListHelper extends AppHelper {
	
   function ul($items = array(), $options = array()) {
    	if (!$items) {
    		return;
    	}
   		$defaults = array(
    		'id' => null,
    		'class' => null
    	);
    	$options = array_merge($defaults, $options);
   		extract($options);
   		if ($id) {
    		$id = 'id=\'' . $id . '\' ';
    	}
    	if ($class) {
    		$class = 'class=\'' . $class . '\' ';
    	}
    	
    	$list = '<ul ' . $id . $class . '>';
    	foreach ($items as $item) {
    		$list .= '<li>' . $item . '</li>';
        }
        $list .= '</ul>';
        
        return $this->output($list);
    }
    
}