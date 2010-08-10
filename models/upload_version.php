<?php
class UploadVersion extends AppModel {

	public $actsAs = array(
		'Libs.MeioUpload' => array(
			'filename' => array(
				'dir' => '..{DS}_uploads',
				'encryptedFolder' => true
			)
		),
		'Libs.Trackable',
		'Libs.Sequence' => array(
			'group_fields' => array('upload_id'),
			'start_at' => 1,
			'order_field' => 'version'
		)
	);

	var $belongsTo = array(
		'Upload' => array(
			'counterCache' => true,
			//'counterScope' => array('UploadVersion.is_active' => true)
		),
		'User'
	);

}