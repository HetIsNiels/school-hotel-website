<?php
require_once __DIR__ . '/../bootstrap.php';
header('content-type: application/json');

$types = isset($_GET['types']) ? implode(',', $_GET['types']) : '';

$roomStatement = $db->prepare('SELECT * FROM rooms WHERE type IN (?) ORDER BY price DESC');
$roomStatement->bindParam(1, $types);
$roomStatement->execute();

echo json_encode($roomStatement->fetchAll(2));