<?php
// db.php
$dsn = 'mysql:host=localhost;dbname=u581995023_nandi_ent_col;charset=utf8mb4';
$dbUser = 'u581995023_nandiganesh';
$dbPass = 'n@nd!g@n6SH';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $dbUser, $dbPass, $options);
