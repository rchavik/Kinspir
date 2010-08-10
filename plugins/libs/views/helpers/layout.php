<?php
/**
 * LayoutHelper
 * This Helper provides a few functions that can be used to assist the layout.
 * 
 * @author Robert Conner <rtconner>
 */

class LayoutHelper extends AppHelper {
	
	var $__blockName = null;
	
	/**
	 * Start a block of output to display in layout
	 *
	 * @param  string $name Will be prepended to form {$name}_for_layout variable
	 */
	function blockStart($name) {

		if(empty($name))
			trigger_error('LayoutHelper::blockStart - name is a required parameter');
			
		if(!is_null($this->__blockName))
			trigger_error('LayoutHelper::blockStart - Blocks cannot overlap');

		$this->__blockName = $name;
		ob_start();
	}
	
	/**
	 * Ends a block of output to display in layout
	 */
	function blockEnd() {
		$buffer = @ob_get_contents();
		@ob_end_clean();

		$out = $buffer;
			
		$view =& ClassRegistry::getObject('view');
        $view->viewVars[$this->__blockName.'_for_layout'] = $out;
		//$this->set($this->__blockName.'_for_layout', $out);	
		
		$this->__blockName = null;
		return $out;
	}
	
	/**
	 * Output a variable only if it exists. If it does not exist you may optionally pass
	 * in a second parameter to use as a default value.
	 * 
	 * @param mixed $variable Data to output
	 * @param mixed $default Value to output if first parameter does not exist
	 */
	function output($var, $default=null) {
		if(!isset($var) or $var==null) {
			return $default;
		} else {
			return $var;	
		}
	}
	
	// @todo not finished, needs refactoring and (Workspaces not Projects)
	public function actions($data, $params = array()) {
		$defaults = array(
			'description' 	=> 'description',
			'permissions' 	=> true,
			'delete'		=> true,
			'edit'			=> true,
			'model' 		=> null,
			'name'			=> null,
		);
		$result = '<div class="hidden-link-actions">';
		if (!empty($data['description'])) {
			echo $this->Javascript->toggle('Description', array(
				'update' => 'project-description-' . $data['id'],
				'div' => false,
				'class' => 'ui-icon ui-icon-folder-open',
				'title' => 'Show description for this project',
			));
		}
		$result .= $this->Html->link('Edit', array('controller' => 'projects', 'action' => 'edit', $data['id']), array('class' => 'ui-icon ui-icon-pencil', 'title' => 'Edit this project'));
		$result .= $this->Html->link('Delete', array('controller' => 'projects', 'action' => 'delete', $data['id']), array('class' => 'ui-icon ui-icon-trash', 'title' => 'Delete this project'), sprintf(__('Are you sure you want to delete %s?', true), $data['name']));
		$result .= '</div>';
		return $result;
	}
	
}