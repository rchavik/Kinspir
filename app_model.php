<?php
class AppModel extends Model {

	/**
	 * @var unknown_type
	 */
	public $recursive = -1;
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $addToTimeline = true;

	/**
	 * @var unknown_type
	 */
	public $actsAs = array(
		'Containable'
	);

	function beforeValidate() {
		App::import('Core', 'Sanitize');
		if (!empty($this->data)) {
			//$this->data = $this->scrub($this->data);
		}
	}

	function scrub($data) {
		//$data = Sanitize::stripWhitespace($data);
		$data = Sanitize::clean($data,
			array(
				'encode' => true,
				'odd_spaces' => true,
				'carriage' => true,
				'remove_html' => true
			)
		);
		return $data;
	}

	/**
	 *
	 */
	/*
	function find($type, $options = array()) {
		switch ($type) {
			/*
			  $users = $this->User->find('superlist', array(
			  'fields' => array('User.id', 'User.last_name', 'User.first_name'),
			  'separator' => ', '
			  ));
			  //Output:
			  //[User.id] => User.last_name, User.first_name
			
			  $users = $this->User->find('superlist', array(
			  'fields' => array('User.id', 'User.last_name', 'User.first_name'),
			  'format' => '%s, %s'
			  ));
			  //Output: same as above
			  //[User.id] => User.last_name, User.first_name
			
			  $users = $this->User->find('superlist', array(
			  'fields' => array('User.id', 'User.last_name', 'User.first_name', 'User.phone'),
			  'format' => '%s, %s -- %s '
			  ));
			  //Output:
			  //[User.id] => User.last_name, User.first_name -- User.phone
			*//*
			case 'superlist':
				$total_fields = count($options['fields']);
				if(!isset($options['fields']) || $total_fields < 3){
					return parent::find('list', $options);
				}
				if(!isset($options['separator'])){
					$options['separator'] = ' ';
				}
				if(!isset($options['format'])){
					$options['format'] = '%s';
					for($i = 2; $i<$total_fields;$i++){
						$options['format'] .= "{$options['separator']}%s";
					}
				}
				$options['recursive'] = -1;
				$list = parent::find('all', $options);
				$formatVals = array();
				$formatVals[0] = $options['format'];
				for($i = 1; $i < $total_fields; $i++){
					$formatVals[$i] = "{n}.{$this->alias}.".str_replace("{$this->alias}.", '', $options['fields'][$i]);
				}
				return Set::combine(
					$list,
					"{n}.{$this->alias}.{$this->primaryKey}",
					$formatVals
				);
				break;
			default:
				return parent::find($type, $options);
				break;
		}
	}
*/
	/**
	 *
	 */
	public function findList($passedConditions = array()) {
		$conditions = array(
			'fields' => array(
				'id',
				$this->displayField
			)
		);
		$conditions = Set::merge($passedConditions, $conditions);
		$results = $this->find('all', $conditions);
		$list = array();
		foreach ($results as $result) {
			$list[$result[$this->alias]['id']] = $result[$this->alias][$this->displayField];
		}
		return $list;
	}

	/**
	 *
	 */
	public function keyToId($key = null, $model = null, $keyField = 'key', $groupField = null, $groupId = null) {
		if (!$key || !$model) {
			return;
		}
		$groupConditions = array();
		if ($groupField && $groupId) {
			$groupConditions = array($model.'.'.$groupField => $groupId);
		}
		$conditions = array(
			'conditions' => array(
				$model.'.'.$keyField => $key,
				$groupConditions
			),
			'fields' => array('id', $keyField)
		);
		$result = ClassRegistry::init($model)->find('first', $conditions);
		return $result[$model]['id'];
	}

	/**
	 *
	 */
	public function checkOwner($id = null, $model = null, $userId = null) {
		if (!$id) {
			return;
		}
		if (!$model) {
			$Model =& $this;
		} else {
			$Model = ClassRegistry::init($model);
		}
		if (!$userId) {
			$userId = User::get('id');
		}
		$conditions = array(
			'conditions' => array(
				$Model->escapeField('id') => $id,
				$Model->escapeField('user_id') => $userId
			)
		);
		return $Model->find('count', $conditions);
	}

	/**
	 *
	 */
	public function getOwner($id = null, $model = null) {
		if (!$id) {
			return;
		}
		if (!$model) {
			$Model =& $this;
		} else {
			$Model = ClassRegistry::init($model);
		}
		$Model->id = $id;
		return $Model->field('user_id');
	}

  function unbindAll() { 
          foreach(array(
                  'hasOne' => array_keys($this->hasOne),
                  'hasMany' => array_keys($this->hasMany),
                  'belongsTo' => array_keys($this->belongsTo),
                  'hasAndBelongsToMany' => array_keys($this->hasAndBelongsToMany)
          ) as $relation => $model) {
                  $this->unbindModel(array($relation => $model));
          }
  } 

}
