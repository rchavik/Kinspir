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
class JavascriptHelper extends AppHelper {
	
	public $helpers = array('Html');

	/**
	 * Returns a link that toggles the display of another element on the page.
	 * Options for $params:
	 *  - $params['div'] set to false to prevent auto-generating a related div
	 * 	- $params['update'] the selector used to locate the element to toggle.
	 * 		If a div is being auto-generated, the # symbol will automatically
	 * 		be prefixed to the selector
	 *	- $params['url'] loads the target url using ajax into the target div
	 * 	- $params['button'] set to true applies the button-skin class
	 * 	- $params[etc] any other params are passed to the generated anchor element
	 *
	 * @param string $text 
	 * @param string $url 
	 * @param string $params 
	 * @return void
	 * @author Dean Sofer
	 */
	public function toggle($text, $params = array()) {
		$result = '';
		if (isset($params['update'])) {
			$id = $params['update'];
			unset($params['update']);
		} else {
			$id = Inflector::slug($text) . '-panel';
		}	
		
		if (!isset($params['div'])) {
			$result .= "<div id='$id' class='loading'></div>\n";
			$params['rel'] = '#' . $id;
		} else {
			$params['rel'] = $id;
		}
		
		$class = 'toggle';
		if (isset($params['class'])) {
			$params['class'] .= ' ' . $class;
		} else {
			$params['class'] = $class;
		}
		
		if (isset($params['url'])) {
			$url = $params['url'];
			$params['class'] .= ' ajax';
			unset($params['url']);
		} else {
			$url = '#';
		}

		$result .= $this->Html->link($text, $url, $params);
		return $result;
	}
	
	/**
	 * Generates a link that uses ajax to load the target page within the update element
	 *
	 * @param string $title 
	 * @param string $url 
	 * @param string $update 
	 * @param string $params 
	 * @return String Link
	 * @author Dean Sofer
	 */
	public function link($title, $url, $update = null, $params = array(), $confirmMessage = false) {
		$class = 'ajax';
		if ($update) {
			$params['rel'] = $update;
		}

		if (isset($params['class'])) {
			$class .= ' ' . $params['class'];
			unset($params['class']);
		}
		$params = array_merge($params, array('class' => $class));
		$link = $this->Html->link($title, $url, $params, $confirmMessage);
		return $link;
	}
	
	/**
	 * Loads a target url into a target element. Can be wrapped in script tags
	 *
	 * @param string $url 
	 * @param string $update 
	 * @param string $wrap 
	 * @return void
	 * @author Dean Sofer
	 */
	public function load($url, $update, $params = array()) {
		if (isset($params['replace']) && $params['replace']) {
			$result = "$.get('" . $this->Html->url($url) . "', function(data) { \$('$update').replaceWith(data); });";
		} else {
			$result = "\$('$update').load('" . $this->Html->url($url) . "', function() { \$(this).removeClass('loading'); });";
		}
		if (!isset($params['wrap']) || (isset($params['wrap']) && $params['wrap'])) {
			$result = '<script type="text/javascript">' . $result . '</script>';
		}
		if (isset($params['div']) && $params['div']) {
			$result = "<div id='$update'>" . $result . '</div>';
		}
		return $result;
	}
	
}