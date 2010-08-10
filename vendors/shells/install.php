<?php

class InstallShell extends Shell {

	function help() {
		$helpText =<<<EOF

Description:  Initialize baseline records for a new Kinspir install.

Usage:        cake install [create_roles|create_admin]

    create_roles   create the four default Kinspir roles:
                   Administrator, User, Banned, and Deleted

    create_admin   create a default admin user for Kinspir
                   username: admin
                   email: admin
                   password: admin123

Typical order of execution:
    cake install create_roles
    cake install create_admin

EOF;
		$this->out($helpText);
	}

	function _createRole($roleName) {
		if (!isset($this->Role)) {
			$this->Role = ClassRegistry::init('Role');
		}

		$role = $this->Role->create(array(
			'name' => $roleName,
			)
		);

		$count = $this->Role->find('count', array(
			'conditions' => array(
				'Role.name' => $roleName,
				)
			)
		);

		if ($count == 0) {
			if ($this->Role->save($role)) {
				$this->out(sprintf('Created role: %d. %s', $this->Role->id, $role['Role']['name']));
			} else {
				$this->out(sprintf('Unable to create role: %s', $roleName));
			};
		} else {
			$this->out(sprintf('Role: %s already exists.', $roleName));
		}
	}

	function create_roles() {
		$this->_createRole('Administrator');
		$this->_createRole('User');
		$this->_createRole('Banned');
		$this->_createRole('Deleted');
	}

	function create_admin() {
		App::import('Component', 'SuperAuth.Auth');
		$User = ClassRegistry::init('User');
		$Auth = new AuthComponent;
		$user = $User->create(array(
			'name' => 'Kinspir Administrator',
			'username' => 'admin',
			'password' => $Auth->password('admin123'),
			'email' => 'admin',
			'role_id' => 1,
			'is_active' => true,
			)
		);

		$count = $User->find('count', array(
			'conditions' => array(
				'User.id' => 1,
				),
			)
		);

		if ($count == 0) {
			if ($User->createUser($user)) {
				$this->out('Administrative User has been created. Please make sure that you change your password immediately');
			} else {
				$this->out('Failed creating admin user');
			}
		} else {
			$this->out('User with id = 1 already exists!');
		}
	}

	function main() {
		$this->help();
	}
}
