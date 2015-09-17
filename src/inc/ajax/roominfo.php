<?php
require_once __DIR__ . '/../bootstrap.php';
header('content-type: application/json');

$roomStatement = $db->prepare('SELECT rooms.* FROM rooms');
$roomStatement->execute();

echo json_encode($roomStatement->fetchAll(2));