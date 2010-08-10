<?php
	// parse certain extensions
	Router::parseExtensions('json');

	// our base route
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));

	// other routes
	Router::connect('/home', array('controller' => 'feeds', 'action' => 'home'));
	Router::connect('/feed', array('controller' => 'feeds', 'action' => 'index'));
	Router::connect('/account/reset', array('controller' => 'users', 'action' => 'reset'));
	Router::connect('/account', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/compose/message', array('controller' => 'messages', 'action' => 'add'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/invite/send', array('controller' => 'users', 'action' => 'invite'));
	Router::connect('/request/connection', array('controller' => 'connections', 'action' => 'add'));
	Router::connect('/calendar', array('controller' => 'calendars', 'action' => 'index'));
	Router::connect('/users/confirm/*', array('controller' => 'users', 'action' => 'confirm'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/repositories', array('controller' => 'projects', 'action' => 'index'));
	Router::connect('/repositories/new/*', array('controller' => 'projects', 'action' => 'add'));
	Router::connect('/repositories/:action/*', array('controller' => 'projects'));
	Router::connect('/wiki', array('controller' => 'wiki_pages', 'action' => 'index'));
	Router::connect('/wiki/:action/*', array('controller' => 'wiki_pages'));
	Router::connect('/message/reply/*', array('controller' => 'messages', 'action' => 'add'));
	Router::connect('/message/view/*', array('controller' => 'messages', 'action' => 'view'));
	Router::connect('/messages/inbox/*', array('controller' => 'messages', 'action' => 'index'));
	Router::connect('/inbox/*', array('controller' => 'messages', 'action' => 'index'));
	Router::connect('/account/confirm/*', array('controller' => 'users', 'action' => 'confirm'));
	Router::connect('/:controller/new/*', array('action' => 'add'));
	Router::connect('/:controller/modify/*', array('action' => 'edit'));
	Router::connect('/mass_invite', array('controller' => 'users', 'action' => 'mass_invite'));

	// Admin Routes
	Router::connect('/admin/:controller', array('admin'=> true));
	Router::connect('/admin/:controller/:action/*', array('admin'=> true));

	// General Routes
	Router::connect('/:controller',
		array('action' => 'index', 'project' => false),
		array('controller' => 'source|commits|timeline|users|projects|announcements|calendars|comments|connections|dashboard_slots|dashboards|deleted_messages|events|feeds|message_folders|message_locations|messages|milestones|notifications|permissions|stacks|subscriptions|task_groups|tasks|uploads|user_groups|widgets|wiki_pages|workspaces|wiki|repositories')
	);
	Router::connect('/:controller/:action/*',
		array('project' => false),
		array(
			'controller' => 'source|commits|timeline|users|projects|announcements|calendars|comments|connections|dashboard_slots|dashboards|deleted_messages|events|feeds|message_folders|message_locations|messages|milestones|notifications|permissions|stacks|subscriptions|task_groups|tasks|uploads|user_groups|widgets|wiki_pages|workspaces|wiki|repositories',
			'action' => 'branches|history|branch|logs|view|start|add|edit|modify|delete|remove|activate|forgotten|verify|change|login|account|logout|forks|approve|home|order|complete|newest|confirm|reset|invite|parse|widget|collaborators')
	);
	Router::connect('/:controller/*',
		array('action' => 'index', 'project' => false),
		array('controller' => 'source|commits|timeline|users|projects|announcements|calendars|comments|connections|dashboard_slots|dashboards|deleted_messages|events|feeds|message_folders|message_locations|messages|milestones|notifications|permissions|stacks|subscriptions|task_groups|tasks|uploads|user_groups|widgets|wiki_pages|workspaces|wiki')
	);
	Router::connect('/:username', array('controller' => 'users', 'action' => 'view'));

	// General Project Routes
	Router::connect('/:username/:project', array('controller' => 'timeline', 'action' => 'index'), array('username' => '[_a-zA-Z0-9]{3,}', 'project' => '[_a-zA-Z0-9]{3,}'));
	Router::connect('/:username/:project/:controller', array('action' => 'index'), array('username' => '[_a-zA-Z0-9]{3,}', 'project' => '[_a-zA-Z0-9]{3,}'));
	Router::connect('/:username/:project/:controller/:action/*', array(), array(
		'username' => '[_a-zA-Z0-9]{3,}',
		'project' => '[_a-zA-Z0-9]{3,}',
		'controller' => 'source|commits|timeline|users|projects|repo|project_permissions|repositories',
		'action' => 'branches|history|branch|logs|merge|view|add|edit|modify|delete|remove|forks|rebase')
	);
	Router::connect('/:username/:project/:controller/*', array('action' => 'index'), array(
		'username' => '[_a-zA-Z0-9]{3,}',
		'project' => '[_a-zA-Z0-9]{3,}',
		'controller' => 'source|commits|timeline|users|projects|repositories',
		)
	);