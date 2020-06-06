<?php
class User {
	public function __construct(string $id) {
		$this->id = $id;
	}
	
	public function checkPassword(string $password) : bool {
		global $db;
		
		$query = $db->prepare("SELECT password FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return password_verify($password, trim($data["password"]));
	}
	
	public static function getIdByUsername(string $username) : int {
		global $db;
		
		$query = $db->prepare("SELECT id FROM users WHERE username = :username");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["id"];
	}
	
	public static function exists(string $username) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users WHERE username = :username");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public static function emailExists(string $email) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users WHERE email = :email");
		$query->bindValue(":email", $email, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public function create(string $username, string $email, string $password) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO users(username, email, password) VALUES(:username, :email, :password)");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->bindValue(":email", $email, PDO::PARAM_STR);
		$query->bindValue(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
		$query->execute();
		
		return $db->lastInsertId();
	}
}