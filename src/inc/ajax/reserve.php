<?php
require_once __DIR__ . '/../bootstrap.php';
header('content-type: application/json');

$resp = [
	'class' => 'alert-success',
	'html' => 'Your room has been reserved!<br /><strong>Check your e-mail for details.</strong>'
];

$customerStatement = $db->prepare('INSERT INTO `customers` (`firstname`, `lastname`, `address`, `city`, `country`, `email`, `tel`) VALUES (?, ?, ?, ?, ?, ?, ?)');
$customerStatement->bindParam(1, $_POST['firstname']);
$customerStatement->bindParam(2, $_POST['lastname']);
$customerStatement->bindParam(3, $_POST['address']);
$customerStatement->bindParam(4, $_POST['city']);
$customerStatement->bindParam(5, $_POST['country']);
$customerStatement->bindParam(6, $_POST['email']);
$customerStatement->bindParam(7, $_POST['tel']);
$customerStatement->execute();

$customerId = $db->lastInsertId();

$reservationStatement = $db->prepare('INSERT INTO reservations (room, customer, people, date_start, date_end) VALUES (?, ?, ?, ?, ?)');
$reservationStatement->bindParam(1, $_POST['room']);
$reservationStatement->bindParam(2, $customerId);
$reservationStatement->bindParam(3, $_POST['people']);
$ci = strtotime($_POST['checkin']);
$reservationStatement->bindParam(4, $ci);
$co = strtotime($_POST['checkout']);
$reservationStatement->bindParam(5, $co);
$reservationStatement->execute();

@mail($_POST['email'], 'Reservation complete!', 'The reservation for ' . $_POST['firstname'] . ' is complete! You will receive a invoice when checking in.');

echo json_encode($resp);