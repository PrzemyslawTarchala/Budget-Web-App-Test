<?php

declare(strict_types=1);

session_start();

include("../Utils/debug.php");
require_once("../request.php");

use layer_1\request;

(new RegistrationValidation()) -> registrationRequest();

class RegistrationValidation
{
	private $request;
	private $conn;
	private $newUsername;
	private $newEmail;
	private $password;
	private $comfirmPassword;
	
	public function __construct()
	{
		$this->request = new Request($_GET, $_POST, $_SERVER);
	}

	public function registrationRequest(): void
	{
		$this->createConnToDB();
		$this->setRegistrationData();
		
		if(($this->isUsernameAvailable() || $this->isEmailAvailable() || $this->isDoubledPasswored()) == false){
			//header('Location: ../../templates/pages/registration.php');
			//header('Location: ../../index.php');
			dump('Nie udało sie');
		} else {
			$this->setNewUserIntoDB();
			header('Location: ../../index.php');
		}
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

	private function setRegistrationData(): void
	{
		$this->newUsername = $this->request->postParam('newUsername');
		$this->newEmail = $this->request->postParam('newEmail');
		$this->password = $this->request->postParam('password');
		$this->comfirmPassword = $this->request->postParam('comfirmPassword');
	}

	private function isUsernameAvailable(): bool
	{
		$query = "SELECT users.username FROM users";
		$result = $this->conn->query($query);
		$usedUsernames = $result->fetchAll(PDO::FETCH_ASSOC);
	
		foreach($usedUsernames ?? [] as $singleUsername){
			if($singleUsername['username'] == $this->newUsername){
				return false;
			}
		}
		return true;
	}

	private function isEmailAvailable(): bool
	{
		$query = "SELECT users.email FROM users";
		$result = $this->conn->query($query);
		$usedEmails = $result->fetchAll(PDO::FETCH_ASSOC);
	
		foreach($usedEmails ?? [] as $singleEmail){
			if($singleEmail['email'] === $this->newEmail){
				return false;
			}
		}
		return true;
	}

	private function isDoubledPasswored(): bool
	{
		if($this->password == $this->comfirmPassword){
			return true;
		} else {
			return false;
		}
	}

	private function setNewUserIntoDB(): void
	{
		$query = "INSERT INTO users(username, password, email) VALUES(:username, :password, :email)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $this->newUsername);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':email', $this->newEmail);
    $stmt->execute();
	}
}
?>