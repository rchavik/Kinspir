<?php
class FeedableBehavior extends ModelBehavior {

	private $__feed = null;

	public function beforeSave(&$Model) {
		if (empty($Model->data[$Model->alias]['id'])) {
			$this->__feed['Feed']['feed_action'] = 'created';
		} else {
			$this->__feed['Feed']['feed_action'] = 'updated';
		}
		if (!empty($Model->data[$Model->alias]['workspace_id'])) {
			$this->__feed['Feed']['workspace_id'] = $Model->data[$Model->alias]['workspace_id'];
		} elseif (isset($Model->_Session) && $Model->_Session->check('Workspace.id')) {
			$this->__feed['Feed']['workspace_id'] = $Model->_Session->read('Workspace.id');
		}
		if (!empty($Model->data[$Model->alias]['milestone_id'])) {
			$this->__feed['Feed']['milestone_id'] = $Model->data[$Model->alias]['milestone_id'];
		} elseif (isset($Model->_Session) && $Model->_Session->check('Milestone.id')) {
			$this->__feed['Feed']['milestone_id'] = $Model->_Session->read('Milestone.id');
		}
		if (!empty($Model->data[$Model->alias]['stack_id'])) {
			$this->__feed['Feed']['stack_id'] = $Model->data[$Model->alias]['stack_id'];
		} elseif (isset($Model->_Session) && $Model->_Session->check('Stack.id')) {
			$this->__feed['Feed']['stack_id'] = $Model->_Session->read('Stack.id');
		}
		if (!empty($Model->data['Feed'])) {
			$this->__feed['Feed'] = Set::merge(
				$this->__feed['Feed'],
				$Model->data['Feed']
			);
			unset($Model->data['Feed']);
		}
		return true;
	}

	public function afterSave(&$Model) {
		if (!empty($this->__feed['Feed'])) {
			$data['Feed'] = array(
				'user_id' => User::get('id'),
				'class' => $Model->name,
				'foreign_id' => $Model->id
			);
			$data['Feed'] = Set::merge(
				$data['Feed'],
				$this->__feed['Feed']
			);
			$Feed = Classregistry::init('Feed');
			if (!$Feed->saveAll($data)) {
				return;
			}
		}
		return true;
	}

	public function afterDelete(&$Model) {
		$data = array(
			'class' => $Model->name,
			'foreign_id' => $Model->id
		);
		Classregistry::init('Feed')->deleteAll($data);
		return true;
	}

}