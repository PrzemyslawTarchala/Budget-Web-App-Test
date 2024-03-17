<?php

declare(strict_types=1);

session_start();

include("../Utils/debug.php");
require_once("../request.php");

use layer_1\request;

(new LoginValidation()) -> loginRequest();

class LoginValidation
{
	private $request;
	private $conn;
	private $username;
	private $password;
	private $result;

	public function __construct()
	{
		$this->request = new Request($_GET, $_POST, $_SERVER);
	}

	public function loginRequest(): void
	{
		$this->createConnToDB();
		$this->setLoginData();
		$this->makeQuery();
		$this->loginValidation();
	}

	private function createConnToDB(): void
	{
		$config = require_once("../../config/config.php");
		$dsn = "mysql:dbname={$config['db']['database']};host={$config['db']['host']}";
		$this->conn = new PDO(
			$dsn,
			$config['db']['user'],
			$config['db']['password'],
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]
		);
	}

	private function setLoginData(): void 
	{
		$this->username = $this->request->postParam('username');
    $this->password = $this->request->postParam('password');
	}

	private function makeQuery(): void
	{
		$query = "SELECT users.id FROM users WHERE username = :username AND password = :password";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':password', $this->password);
		$stmt->execute();
		$this->result = $stmt->fetch(PDO::FETCH_ASSOC);
	}

	private function loginValidation(): void
	{
		if ($this->result) {
			$userId = $this->result['id'];
			$_SESSION['userId'] = $userId;
			$_SESSION['logged'] = true;
			header('Location: ../../templates/pages/main_menu.php');
		} else {
			$userId = null;
			$_SESSION['logged'] = false;
			header('Location: ../../index.php');
		}
	}
}
