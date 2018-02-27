<?php


Config::set('site_name', 'MVC');

Config::set('languages', array('en','ua'));

//Routers. Route name => nethod prefix

config::set('routes', array(
	'default' => '',
	'admin' => 'admin_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'ua');
Config::set('default_controller', 'pages');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'mvc');

Config::set('salt', 'sdf43fsdDfsdffs4fgsd');