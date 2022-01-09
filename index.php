<?php 

//require 'application/lib/Dev.php';

use application\core\Router;

spl_autoload_register(function ($class){
	$path = str_replace('\\', '/', $class.'.php');
	if(file_exists($path)){
		require $path;
	}
});

session_start();
//$router = new application\lib\Router; if we don't use 'use application\core\Router;'
$router = new Router;
$router->run();
?>