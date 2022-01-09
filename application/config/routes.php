<?php 
return [
	'' => [
		'controller' => 'main',
		'action' => 'index',
	],
	'index' => [
		'controller' => 'main',
		'action' => 'index',
	],
	'update_sensors' => [
		'controller' => 'main',
		'action' => 'update_sensors',
	],
	'live_actuators' => [
		'controller' => 'main',
		'action' => 'live_actuators',
	],
	'live_sensors' => [
		'controller' => 'main',
		'action' => 'live_sensors',
	],
	'get_images' => [
		'controller' => 'main',
		'action' => 'get_images',
	],
	'update_actuator' => [
		'controller' => 'main',
		'action' => 'update_actuator',
	],
	'upload' => [
		'controller' => 'main',
		'action' => 'upload',
	],
	'signin' => [
		'controller' => 'sign',
		'action' => 'signin',
	],
	'authenticate' => [
		'controller' => 'sign',
		'action' => 'authenticate',
	],
	'admin' => [
		'controller' => 'admin',
		'action' => 'index',
	],
	'automate' => [
		'controller' => 'admin',
		'action' => 'automate',
	],
	'clear_files' => [
		'controller' => 'admin',
		'action' => 'clear_files',
	],
	'logout' => [
		'controller' => 'admin',
		'action' => 'logout',
	],
];
?>