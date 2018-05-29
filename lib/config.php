<?php

	$db_login  	= "buglog";
	$db_pass   	= "buglog";
	$db_alias  	= "buglog";
	$db_host	= "localhost";
	define('ALLOW',1);
	//error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(E_ALL);
define('path_template','/admin/template/');
spl_autoload_register(function ($class_name) {
    include 'classes/'.$class_name . '.php';
});
?>