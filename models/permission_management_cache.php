<?php
class PermissionManagementCache extends AppModel {

	public $useTable = 'permission_management_cache';
	public $alias = 'Permission';

	public function afterFind ($results) {
		$User = ClassRegistry::init('User');
		foreach ($results as $key => $result) {
			$class = 'Aro';
			$foreignId = $result[$this->alias]['aro_id'];
			if ($class && $foreignId) {
				$result = $result[$this->alias];
				if (!isset($this->{$class})) {
					$this->bindModel(
						array(
							'belongsTo' => array(
								$class => array(
									'conditions' => array(
										$this->alias . '.' . 'class' => $class,
									),
									'foreignKey' => 'aro_id'
								)
							)
						)
					);
				}
				$associated = $this->{$class}->find('first',
					array(
						'conditions' => array(
							$class . '.id' => $foreignId,
							$class . '.model' => 'User'
						),
						'fields' => array(
							'id',
							'foreign_key'
						)
					)
				);
				$results[$key][$class] = $associated[$class];
				if (!empty($results[$key][$class]['foreign_key'])) {
					$user = $User->find('first',
						array(
							'conditions' => array(
								'User.id' => $results[$key][$class]['foreign_key']
							),
							'fields' => array('id', 'name')
						)
					);
					$results[$key]['User'] = $user['User'];
				}
			}
		}
		return $results;
	}

}