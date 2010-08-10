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
 * This helper uses a vendor to translate Markdown formatted text to HTML
 * @link http://michelf.com/projects/php-markdown/
 */

App::import('Vendor', 'Markdown');

class MarkdownHelper extends AppHelper {

	function parse($text) {
		echo $this->output(Markdown($text));
	}

	/**
	 * This action simply creates a textarea with the 'markitup-editor' class
	 */
	function textarea($fieldName, $options = array()) {
		$FormHelper = ClassRegistry::init('Form');
		$options = Set::Merge($options, array('class' => 'markitup-editor'));
		return $FormHelper->text($fieldName, $options);
	}

}