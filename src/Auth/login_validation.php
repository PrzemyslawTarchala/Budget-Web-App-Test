<?php

session_start();

include("../Utils/debug.php");

require_once("../request.php");
$config = require_once("../../config/config.php");

use layer_1\request;
// use PDO;

$request = new Request($_GET, $_POST, $_SERVER);

$username = $request->postParam('username');
$password = $request->postParam('password');

$dsn = "mysql:dbname={$config['db']['database']};host={$config['db']['host']}";

$conn = new PDO(
	$dsn,
	$config['db']['user'],
	$config['db']['password'],
	[
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Przy polaczeniu ustwaione wszystkie "error'y" będa traktowane jako "exception"
	]
);

$query = "SELECT users.id FROM users WHERE username = :username AND password = :password";

$stmt = $conn->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
	$userId = $result['id'];
	dump($userId);
	$_SESSION = $result['userId'];

	echo 'Zostales zalogowany. Nastąpi przekierowanie do strony logowania.';
	sleep(1.5);
	header('Location: ../../templates/pages/main_menu.php');
} else {
	$userId = null;
	echo 'Niepoprawne logowanie. Powrot do strony logowania.';
	sleep(1.5);
	header('Location: ../../index.php');
}

?>