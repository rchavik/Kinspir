<?php
class DashboardSlotsController extends AppController {
	
	public function add() {
		$widgets = $this->DashboardSlot->Widget->find('list');
		$this->set(compact('widgets'));
		if ($this->data) {
			$this->DashboardSlot->create();
			if ($this->DashboardSlot->save($this->data)) {
				$this->Redirect->flash('add_ok', array('controller' => 'dashboards', 'action' => 'view', $this->data['DashboardSlot']['dashboard_id']));
			} else {
				$this->Redirect->flash('error');
			}
		}
		if (isset($this->params['named']['dashboard'])) {
			$this->data['DashboardSlot']['dashboard_id'] = $this->params['named']['dashboard'];
		}
	}
}
