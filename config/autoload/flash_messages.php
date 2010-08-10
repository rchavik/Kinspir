<?php
// Types = 'success', 'note', 'warning', 'error'
	Configure::write('FlashMessages',
		array(
			'login' => array(
				'Log in to access this page.',
				'error'
			),
			'logged_in' => array(
				'You have been logged in.',
				'note'
			),
			'logged_out' => array(
				'You have been logged out.',
				'note'
			),
			'edit_ok' => array(
				'Your changes have been saved.',
				'success'
			),
			'delete_ok' => array(
				'%s record(s) have been deleted.',
				'success'
			),
			'save_ok' => array(
				'The %s has been saved.',
				'success'
			),
			'account_saved' => array(
				'Your account information has been updated.',
				'success'
			),
			'password_reset' => array(
				 'Password for %s has been reset.',
				'note'
			),
			'no_data' => array(
				'No data found for selected record, it may have been deleted.',
				'error'
			),
			'input_errors' => array(
				'Please correct the errors below.',
				'error'
			),
			'no_access' => array(
				'You do not have permission to access that page.',
				'error'
			),
			'no_edit_user' => array(
				'This user has a higher level of access than you, you cannot make changes to their account.',
				'error'
			),
			'bad_user_pass' => array(
				'Sorry, but the username and password combination you provided is not valid. Please try again.',
				'error'
			),
			'approved' => array(
				'The %s(s) have been approved.',
				'success'
			),
			'denied' => array(
				'The %s(s) have been denied.',
				'error'
			),
			'failed' => array(
				'Something broke. Please contact support.',
				'error'
			),
			'is_owner' => array(
				'That user is the owner and cannot be modified.',
				'error'
			),
			'no_id' => array(
				'You did not specify a record id.',
				'error'
			),
			'not_in_workspace' => array(
				'Please select a workspace.',
				'error'
			),
			'no_records' => array(
				'You do not have any %s',
				'note'
			),
			'task_status' => array(
				'The Task has been marked as %s',
				'success'
			)
		)
	);