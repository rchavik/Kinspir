<?php
$content = APP;

Configure::write('Content', array(
	'base' => $content . 'content' . DS,
	'git' => $content . 'content' . DS . 'git' . DS,
	'svn' => $content . 'content' . DS . 'svn' . DS ,
));

Configure::write('Project', array(
	'id' => null,
	'user_id' => 1,
	'private' => 1,
	'active' => 1,
	'url' => null,
	'name' => Inflector::humanize(Configure::read('App.dir')),
	'repo_type' => 'Git',
	'config' => array(
		'groups' => 'user, docs, team, admin'
	),
	'remote' => array(
		'git' => 'git@kinspir.com',
		'svn' => 'svn+ssh://svn@kinspir.com'
	)
));
