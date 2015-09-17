<?php
$user = 'root';
$password = '';
$database = 'hotel';
$host = 'localhost';

session_start();
error_reporting(E_ALL);
setlocale(LC_ALL, 'nl_NL');

// Unused autoloader
spl_autoload_register(function($cls){
	require_once __DIR__ . DIRECTORY_SEPARATOR . $cls . '.php';
});

try {
	$db = new PDO('mysql:host=' . $host. ';dbname=' . $database, $user, $password);
}catch(Exception $e){
	echo '<h1>Unable to connect to database!</h1>';
	echo $e->getMessage();

	die;
}