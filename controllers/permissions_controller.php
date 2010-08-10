<?php
class PermissionsController extends AppController {
	
	var $uses = array('SuperAuth.PermissionCache', 'PermissionManagementCache', 'Permission', 'User');

	public function beforeFilter() {
		$this->Permissions =& $this->PermissionManagementCache;
		$this->typeMap = array(
			'Workspace' => 'workspace'
		);
		parent::beforeFilter();
		$this->Auth->authorize = 'actions';
	}
	
	public function view($type = null, $id = null) {
		if (!($model = array_search($type, $this->typeMap)) || !$id) {
		    $this->Redirect->flash('no_data', $this->referer);
		}
		$Model = ClassRegistry::init($model);
		if ($Model->Behaviors->attached('Acl') && isset($Model->Aco)) {
			$aco = $this->PermissionCache->Aco->findByModelAndForeignKey($model, $id);
			if (!$aco) {
				$this->Redirect->flash('no_data', $this->referer);
			}
			if (!$this->Permissions->checkOwner($id, $model)) {
				$this->Redirect->flash('no_access', $this->referer);
			}
			$acoId = $aco['Aco']['id'];
			$this->Acl->cachePermissions('Aco', $acoId, false, 'permission_management_cache');
			$permissions = $this->Permissions->findAllByAcoId($acoId);
			$this->__sync($permissions, $model, $id);
			$this->set(compact('permissions', 'type', 'id', 'aco'));
		} else {
			$this->Redirect->flash('failed', $this->referer);
		}
	}

	private function __sync($users, $modelName, $foreignId) {
		$Model = ClassRegistry::init($modelName);
		$usersIds = Set::extract('/User/id', $users);
		$Model->subscribe($usersIds, $foreignId);
	}

	public function add($type = null, $id = null) {
		if (empty($this->data) && !($model = array_search($type, $this->typeMap)) && !$id) {
			$this->Redirect->flash('no_data', $this->referer);
		}
		if (!empty($this->data['Permissions']) && ($model = array_search($this->data['Permissions']['type'], $this->typeMap))) {
			$id = $this->data['Permissions']['foreign_key'];
			$userId = $this->data['Permissions']['user_id'];
			if (!$this->Permissions->checkOwner($id, $model)) {
				$this->Redirect->flash('no_access', $this->referer);
			}
			$ownerId = $this->Permissions->getOwner($id, $model);
			if ($userId === $ownerId) {
				$this->Redirect->flash('is_owner', $this->referer);
			}
			$aco[$model] = array(
				'id' => $id
			);
			$Model = ClassRegistry::init($model);
			if ($this->_allow($userId, $aco) && $Model->subscribe($userId, $id)) {
				$Model->notify($userId, $id, $this->Tools->keyToId('permission_' . $this->typeMap[$model] . '_granted', 'NotificationType'));
				$this->Redirect->flash(array('approved', 'User'), $this->referer);
			} else {
				$this->Redirect->flash('failed', $this->referer);
			}
		}
		$aco = $this->PermissionCache->Aco->findByModelAndForeignKey($model, $id);
		if (!$aco) {
			$this->Redirect->flash('no_data', $this->referer);
		}
		if (!$this->Permissions->checkOwner($id, $model)) {
			$this->Redirect->flash('no_access', $this->referer);
		}
		$users = $this->_userConnections(array('name'), true);
		$this->data['Permissions']['type'] = $type;
		$this->data['Permissions']['foreign_key'] = $id;
		$this->set(compact('users'));
		$this->render('edit');
	}

	public function delete($type = null, $acoId = null, $aroId = null) {
		if (!($model = array_search($type, $this->typeMap)) || !$acoId || !$aroId) {
		    $this->Redirect->flash('no_data', $this->referer);
		}
		$this->PermissionCache->Aco->id = $acoId;
		$this->PermissionCache->Aro->id = $aroId;
		$userId = $this->PermissionCache->Aro->field('foreign_key');
		$rowId = $this->PermissionCache->Aco->field('foreign_key');
		$ownerId = $this->Permissions->getOwner($rowId, $model);
		if (!$this->Permissions->checkOwner($rowId, $model)) {
			$this->Redirect->flash('no_access', $this->referer);
		}
		if ($ownerId === $userId) {
			$this->Redirect->flash('is_owner', $this->referer);
		}
		$permission = $this->Permission->find('first',
			array(
				'conditions' => array(
					'Permission.aro_id' => $aroId,
					'Permission.aco_id' => $acoId
				)
			)
		);
		$success = $this->Permission->delete($permission['Permission']['id']);
		if ($success) {
			$Model = ClassRegistry::init($model);
			$Model->notify($userId, $rowId, $this->Tools->keyToId('permission_' . $this->typeMap[$model] . '_revoked', 'NotificationType'));
			$flash = array('denied', 'User');
		} else {
			$flash = 'failed';
		}
		$this->Redirect->flash($flash, $this->referer);
	}
	
}
