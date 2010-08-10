<?php
	class FilterableBehavior extends ModelBehavior {

		public function setup(&$Model, $config = array()) {
			App::import('Component', 'Session');
			$Model->_Session = new SessionComponent();
		}

		public function beforeSave(&$Model) {
			if ($Model->_Session->check('Workspace.id') && ($Model->name != 'Workspace') && empty($Model->data[$Model->alias]['workspace_id'])) {
				$Model->data[$Model->alias]['workspace_id'] = $Model->_Session->read('Workspace.id');
			}
			if ($Model->_Session->check('Milestone.id') && ($Model->name != 'Milestone') && empty($Model->data[$Model->alias]['milestone_id'])) {
				$Model->data[$Model->alias]['milestone_id'] = $Model->_Session->read('Milestone.id');
			}
			if ($Model->_Session->check('Stack.id') && ($Model->name != 'Stack') && ($Model->name != 'Milestone') && empty($Model->data[$Model->alias]['stack_id'])) {
				$Model->data[$Model->alias]['stack_id'] = $Model->_Session->read('Stack.id');
			}
			return true;
		}

	}
