<?php
require_once __DIR__ . '/../bootstrap.php';
header('content-type: application/json');

$roomStatement = $db->prepare('SELECT room_types.* FROM room_types');
$roomStatement->execute();

echo json_encode($roomStatement->fetchAll(2));