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
	
	public function generatePasswordResetHash() : string {
		global $db;
		
		
		$hash = sha1(microtime(1).random_bytes(100));
		$query = $db->prepare("UPDATE users SET password_reset_hash = :password_reset_hash WHERE id = :id");
		$query->bindValue(":password_reset_hash", $hash, PDO::PARAM_STR);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		return $hash;
	}
	
	public function getEmail() : string {
		global $db;
		
		$query = $db->prepare("SELECT email FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["email"]);
	}
	
	public static function getUserIdByResetHash(string $hash) : int {
		global $db;
		
		$query = $db->prepare("SELECT id FROM users WHERE password_reset_hash = :password_reset_hash");
		$query->bindValue(":password_reset_hash", $hash, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		if (empty($data)) {
			return 0;
		}
		
		return (int)$data["id"];
	}
	
	public static function getUsernameById(int $id) : string {
		global $db;
		
		$query = $db->prepare("SELECT username FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["username"]);
	}
	
	public function changePassword(string $password) : bool {
		global $db;
		
		$query = $db->prepare("UPDATE users SET password = :password, password_reset_hash = '' WHERE id = :id");
		$query->bindValue(":password", password_hash($poassword, PASSWORD_DEFAULT), PDO::PARAM_STR);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		
		return $query->execute();
	}
}