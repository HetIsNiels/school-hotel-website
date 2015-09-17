<?php
require_once __DIR__ . '/../bootstrap.php';
header('content-type: application/json');

$name    = isset($_GET['name']) ? $_GET['name'] : '';
$email   = isset($_GET['email']) ? $_GET['email'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

$errors = [
	'name'    => false,
	'email'   => false,
	'message' => false,
	'result' => null
];

if(empty($name))
	$errors['name'] = 'Name is empty';
elseif(strlen($name) < 3 || strlen($name) > 30)
	$errors['name'] = 'Incorrect length for name';
elseif(!ctype_alnum($name))
	$errors['name'] = 'Name must be alpha numeric';

if(empty($email))
	$errors['email'] = 'Email is empty';
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
	$errors['email'] = 'Email is not valid.';

if(empty($message))
	$errors['message'] = 'Message is empty';
elseif(strlen($message) < 20)
	$errors['message'] = 'Message should be atleast 20 characters long.';
elseif(strlen($message) > 700)
	$errors['message'] = 'Message is too long';

if($errors['name'] !== false || $errors['email'] !== false || $errors['message'] !== false)
	$errors['result'] = 'Please change a few things and try submitting again.';
else{
	// Email send code

	$errors['result'] = 'The message has been sent to the hotel staff.';
}

echo json_encode($errors);