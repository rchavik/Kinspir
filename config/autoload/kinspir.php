<?php
	Configure::write('Kinspir',
		array(
			'Site' => array(
				// Kinspir URL
				'url' => 'https://www.kinspir.com',
			),
			'Email' => array(
				// Email addresses
				'Address' => array(
					'noreply' => 'no-reply@cloud.kinspir.com',
					'support' => 'support@kinspir.com',
				),
				// Email subjects
				'Subject' => array(
					'invited' => '%s has invited you to Kinspir with them',
					'reset' => 'Kinspir account reset',
					'notification' => 'You have received a notification in Kinspir',
				),
				// Email template map
				'Template' => array(
					'invited' => 'invited',
					'reset' => 'reset',
					'notification' => 'notification',
				)
			),
			'Default' => array(
				// Default user values
				'User' => array(
					'invites' => 10,
					'role_id' => 2,
					'locale' => 'en_US',
					'is_active' => 1,
					'group_id' => 4,
					'level_id' => 4
				)
			)
		)
	);

	// custom inflection
	// don't inflect the word css
	Inflector::rules('plural',
		array(
			'uninflected' => array('css')
		)
	);

	// set CakePHP timezone to GMT
	ini_set('date.timezone', 'GMT');

	// turn down error reporting
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
