<?php
class User {
	/**
	 * Constructeur
	 *
	 * @param int $id ID de l'utilisateur
	 */
	public function __construct(string $id) {
		$this->id = $id;
	}
	
	/**
	 * Récupère les informations du profil de l'utilisateur
	 *
	 * @return array Résultat
	 */
	public function getData() : array {
		global $db;
		
		$query = $db->prepare("SELECT messages, points, avatar FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		$result = [
			"messages" => (int)$data["messages"],
			"points" => (int)$data["points"],
			"avatar" => (int)$data["avatar"]
		];
		
		return $result;
	}
	
	/**
	 * Vérifie si un mot de passe correspond avec celui de l'utilisateur
	 *
	 * @param string $password Mot de passe à vérifier
	 *
	 * @return bool Résultat
	 */
	public function checkPassword(string $password) : bool {
		global $db;
		
		$query = $db->prepare("SELECT password FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return password_verify($password, trim($data["password"]));
	}
	
	/**
	 * Récupère l'ID de l'utilisateur en fonction de son pseudo
	 *
	 * @param string $username Pseudo
	 *
	 * @return int ID de l'utilisateur
	 */
	public static function getIdByUsername(string $username) : int {
		global $db;
		
		$query = $db->prepare("SELECT id FROM users WHERE username = :username");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["id"];
	}
	
	/**
	 * Vérifie si l'utilisateur existe en fonction de son pseudo
	 *
	 * @param string $username Pseudo
	 *
	 * @return bool Résultat
	 */
	public static function exists(string $username) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users WHERE username = :username");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	/**
	 * Vérifie s'il existe un compte avec une adresse e-mail spécifique
	 *
	 * @param string $email Adresse e-mail
	 *
	 * @return bool Résultat
	 */
	public static function emailExists(string $email) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users WHERE email = :email");
		$query->bindValue(":email", $email, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	/**
	 * Crée un utilisateur
	 *
	 * @param string $username Pseudo
	 * @param string $email Adresse e-mail
	 * @param string $password Mot de passe
	 */
	public static function create(string $username, string $email, string $password) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO users(username, email, password) VALUES(:username, :email, :password)");
		$query->bindValue(":username", $username, PDO::PARAM_STR);
		$query->bindValue(":email", $email, PDO::PARAM_STR);
		$query->bindValue(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
		$query->execute();
		
		return $db->lastInsertId();
	}
	
	/**
	 * Génère un hash de réinitialisation de mot de passe
	 *
	 * @return string Hash
	 */
	public function generatePasswordResetHash() : string {
		global $db;
		
		$hash = sha1(microtime(1).random_bytes(100));
		$query = $db->prepare("UPDATE users SET password_reset_hash = :password_reset_hash WHERE id = :id");
		$query->bindValue(":password_reset_hash", $hash, PDO::PARAM_STR);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		return $hash;
	}
	
	/**
	 * Récupère l'adresse e-mail de l'utilisateur
	 *
	 * @return string Adresse e-mail
	 */
	public function getEmail() : string {
		global $db;
		
		$query = $db->prepare("SELECT email FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["email"]);
	}
	
	/**
	 * Récupère un utilisateur en fonction d'un hash de réinitialisation de mot de passe
	 *
	 * @param string $hash Hash
	 *
	 * @return bool Résultat
	 */
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
	
	/**
	 * Récupère un pseudo en fonction de son ID
	 *
	 * @param int $id ID de l'utilisateur
	 *
	 * @return string Pseudo
	 */
	public static function getUsernameById(int $id) : string {
		global $db;
		
		$query = $db->prepare("SELECT username FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["username"]);
	}
	
	/**
	 * Modifie le mot de passe de l'utilisateur
	 *
	 * @param string $password Nouveau mot de passe
	 *
	 * @return bool Résultat
	 */
	public function changePassword(string $password) : bool {
		global $db;
		
		$query = $db->prepare("UPDATE users SET password = :password, password_reset_hash = '' WHERE id = :id");
		$query->bindValue(":password", password_hash($poassword, PASSWORD_DEFAULT), PDO::PARAM_STR);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		
		return $query->execute();
	}
}