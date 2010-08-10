<?php
class Feed extends AppModel {

	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'Milestone',
		'Stack',
		'Workspace',
		'User'
	);

	public function beforeFind($queryData) {
		// You can set conditions for each model associated with the polymorphic model.
		if (isset($queryData['polyConditions'])) {
			$this->__polyConditions = $queryData['polyConditions'];
			unset($queryData['polyConditions']);
		}
		return $queryData;
	}

	public function afterFind ($results, $primary = false) {
		if (isset($results[0][$this->alias]['class'])) {
			foreach ($results as $key => $result) {
				$associated = array();
				$class = $result[$this->alias]['class'];
				$foreignId = $result[$this->alias]['foreign_id'];
				if ($class && $foreignId) {
					$associatedConditions = array(
						'conditions' => array(
							$class . '.id' => $foreignId
						)
					);
					if (isset($this->__polyConditions[$class])) {
						$associatedConditions = Set::merge($associatedConditions, $this->__polyConditions[$class]);
					}
					$result = $result[$this->alias];
					if (!isset($this->$class)) {
						$this->bindModel(array('belongsTo' => array(
							$class => array(
								'conditions' => array($this->alias . '.' . 'class' => $class),
								'foreignKey' => 'foreign_id'
							)
						)));
					}
					$associated = $this->$class->find('first', $associatedConditions);
					if (empty($associated)) {
						unset($results[$key]); // @todo temporary until I can figure out how to not return results that have empty
					} else {
						$associated[$class]['display_field'] = $associated[$class][$this->$class->displayField];
						$results[$key][$class] = $associated[$class];
						unset($associated[$class]);
						$results[$key][$class] = Set::merge($results[$key][$class], $associated);
					}
				}
			}
		}
		return $results;
	}

/*
	public function afterFind ($results) {
		// Get the user's connections
		$connectionResults = ClassRegistry::init('Connection')->find('all',
			array(
				'conditions' => array(
					'or' => array(
						'Connection.user_id' => User::get('id'),
						'Connection.receiver_id' => User::get('id')
					),
					'Connection.is_approved' => true
				)
			)
		);
		$connectionIds = array_merge(Set::extract('/Connection/receiver_id', $connectionResults), Set::extract('/Connection/user_id', $connectionResults));
		$connectionIds = array_keys(array_flip($connectionIds));
		$badKeys = array();
		foreach ($results as $key => $result) {
			if (isset($result[$this->alias]['class'])) {
				$associated = array();
				$class = $result[$this->alias]['class'];
				$foreignId = $result[$this->alias]['foreign_id'];
				if ($class && $foreignId) {
					$result = $result[$this->alias];
					if (!isset($this->$class)) {
						$this->bindModel(array('belongsTo' => array(
							$class => array(
								'conditions' => array(
									$this->alias . '.' . 'class' => $class,
								),
								'foreignKey' => 'foreign_id'
							)
						)));
					}
					$conditions = array(
						'aclConditions' => array(),
						'conditions' => array(
							'and' => array(
								$class . '.id' => $foreignId
							)
						),
						'fields' => array(
							'id',
							$this->$class->displayField
						),
						'contain' => array()
					);
					$conditions['contain']['User'] = array(
						'fields' => array(
							'id',
							'name'
						)
					);
					// contain with Receiver as well, if we are in the connections model
					// also only show connections we have permissions to see
					if ($class === 'Connection') {
						// hiding connections in the feed temporarily
						$badKeys[] = $key;
						$conditions['contain']['Receiver'] = array(
							'fields' => array(
								'id',
								'name'
							)
						);
						$this->Connection->displayField = 'name';
						$conditions['conditions']['and']['or'] = array(
							'Connection.user_id' => $connectionIds,
							'Connection.receiver_id' => $connectionIds
						);
					}
					$associated = $this->$class->find('first', $conditions);
					
					if (!$associated) {
						$badKeys[] = $key;
					}
					
					$associated[$class]['display_field'] = $associated[$class][$this->$class->displayField];
					$results[$key][$class] = $associated[$class];
				}
			}
		}
		foreach ($badKeys as $key) {
			unset($results[$key]);
		}
		return $results;
	}
*/

}