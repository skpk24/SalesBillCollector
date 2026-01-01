<?php
// db.php

$config_path = 'db_credentials.properties';

if (file_exists($config_path)) {
	
	$db_details = parse_ini_file($config_path);
	
	// Access the credentials using array keys
    $host = $db_details['DB_HOST'];
    $user = $db_details['DB_USER'];
    $pass = $db_details['DB_PASS'];
    $name = $db_details['DB_NAME'];

$dsn = 'mysql:host='.$host.';dbname='.$name.';charset=utf8mb4';
$dbUser = $user;
$dbPass = $pass;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $dbUser, $dbPass, $options);

}
