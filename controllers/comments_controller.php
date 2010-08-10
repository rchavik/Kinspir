<?php
class CommentsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->authorize = 'actions';
	}

	public function edit($id = null) {
		if (parent::edit($id, false, false)) {
			$Model = ClassRegistry::init($this->data['Comment']['class']);
			$item = $Model->findById($this->data['Comment']['foreign_id']);
			switch ($this->data['Comment']['class']) {
				case 'Task' :
					if ($item['Task']['assigned_to_id'] != User::get('id')) {
						$Model->notify($item['Task']['assigned_to_id'], $this->data['Comment']['foreign_id'], $this->Tools->keyToId('commented_on', 'NotificationType'));
					}
					break;
			}
			$this->Redirect->flash(null, $this->referer);
		}
		foreach ($this->params['named'] as $class => $foreign_id) {
			$this->data['Comment']['class'] = ucfirst(Inflector::variable($class));
			$this->data['Comment']['foreign_id'] = $foreign_id;
		}
		$this->render('edit');
	}

}