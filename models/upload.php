<?php
class Upload extends AppModel {

	private $__firstVersion = null;

	public $actsAs = array(
		'SuperAuth.Acl' => array(
			'type' => 'controlled',
			'parentClass'=> 'Workspace',
			'foreignKey' => 'workspace_id'
		),
		'Filterable',
		'Libs.Trackable',
		'Feedable'
	);

	public $validate = array(
		'name' => 'notEmpty'
	);

	public $belongsTo = array(
		'ActiveVersion' => array(
			'className' => 'UploadVersion',
			'foreignKey' => 'active_version_id'
		),
		'Stack',
		'User',
		'Milestone',
		'Workspace'
	);

	public $hasMany = array(
		'UploadVersion' => array(
			'dependent' => true
		)
	);

	/**
	 * Upload the latest version of a file before saving the upload record (or potentially the first/only version)
	 */
	public function beforeSave() {
		if (!isset($this->data['Upload']['id'])) {
			$this->data['UploadVersion']['upload_id'] = null;
		} else {
			$this->data['UploadVersion']['upload_id'] = $this->data['Upload']['id'];
		}

		// Save just the uploadversion
		$data['UploadVersion'] = $this->data['UploadVersion'];
		if (!$this->UploadVersion->save($data)) {
			$this->validationErrors = Set::Merge($this->validationErrors, $this->UploadVersion->validationErrors);
			return;
		}
		$this->data['Upload']['active_version_id'] = $this->UploadVersion->id;

		// First version
		if (empty($this->data['Upload']['id'])) {
			$this->__firstVersion = true;
		}

		return true;
	}

	/**
	 * Link the upload_id field of UploadVersion to the Upload record
	 */
	public function afterSave($results = array()) {
		if ($this->__firstVersion) {
			$this->UploadVersion->saveField('upload_id', $this->id, array('callbacks' => false));
		} else {
			$count = $this->field('upload_version_count', array('Upload.id' => $this->data['Upload']['id']));
			// Update the version of it
			$this->UploadVersion->updateAll(
				array('UploadVersion.version' => $count),
				array('UploadVersion.id' => $this->data['Upload']['active_version_id'])
			);	
		}
	}

	/**
	 * Set meioupload up to delete the file from the hard disk
	 */
	public function beforeDelete() {
		$this->UploadVersion->remove = true;
		return true;
	}
	

}
