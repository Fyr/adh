<?php
	Router::parseExtensions('json', 'xml');

	Router::connect('/', array('controller' => 'admin', 'action' => 'index'));

	CakePlugin::routes();
	require CAKE . 'Config' . DS . 'routes.php';
