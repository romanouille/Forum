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
		
		$query = $db->prepare("SELECT username, messages, points, avatar_id, admin FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		$result = [
			"username" => (string)trim($data["username"]),
			"messages" => (int)$data["messages"],
			"points" => (int)$data["points"],
			"avatar_id" => (int)$data["avatar_id"],
			"admin" => (int)$data["admin"]
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
		
		$query = $db->prepare("SELECT password, noelfic_password FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		if (!empty($data["noelfic_password"])) {
			if (md5($password) == $data["noelfic_password"]) {
				$query = $db->prepare("UPDATE users SET password = :password, noelfic_password = '' WHERE id = :id");
				$query->bindValue(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
				$query->bindValue(":id", $this->id, PDO::PARAM_INT);
				$query->execute();
				
				return true;
			} else {
				return false;
			}
		}
		
		return password_verify($password, $data["password"]);
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
		$query->bindValue(":id", $id, PDO::PARAM_INT);
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
	
	/**
	 * Récupère la liste des messages privés de l'utilisateur
	 *
	 * @param int $page Page*
	 *
	 * @return array Liste des messages privés
	 */
	public function getPmList(int $page) : array {
		global $db;
		
		$query = $db->prepare("SELECT pm_id, (SELECT title FROM pm WHERE id = pm_id) AS title, (SELECT author FROM pm WHERE id = pm_id) AS author, timestamp FROM pm_receivers WHERE user_id = :user_id ORDER BY timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
		$query->bindValue(":user_id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"id" => (int)$value["pm_id"],
				"title" => (string)trim($value["title"]),
				"username" => User::getUsernameById($value["author"]),
				"timestamp" => (int)$value["timestamp"]
			];
		}
		
		return $result;
	}
	
	/** 
	* Récupère le nombre de pages de messages privés de l'utilisateur
	*
	* @return int Résultat
	*/
	public function getPmListPagesNb() : int {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM pm_receivers WHERE user_id = :user_id");
		$query->bindValue(":user_id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return ceil($data["nb"]/25);
	}
	
	public function votedOnPoll(int $topicId) {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM polls_votes WHERE topic = :topic AND user_id = :user_id");
		$query->bindValue(":topic", $topicId, PDO::PARAM_INT);
		$query->bindValue(":user_id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public function voteOnPoll(int $topic, int $response) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM polls_responses WHERE topic = :topic AND id = :response");
		$query->bindValue(":topic", $topic, PDO::PARAM_INT);
		$query->bindValue(":response", $response, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		if ($data["nb"] == 0) {
			return false;
		}
		
		$query = $db->prepare("UPDATE polls_responses SET votes = votes + 1 WHERE id = :id");
		$query->bindValue(":id", $response, PDO::PARAM_INT);
		$query->execute();
		
		$query = $db->prepare("INSERT INTO polls_votes(topic, user_id, response) VALUES(:topic, :user_id, :response)");
		$query->bindValue(":topic", $topic, PDO::PARAM_INT);
		$query->bindValue(":user_id", $this->id, PDO::PARAM_INT);
		$query->bindValue(":response", $response, PDO::PARAM_STR);
		
		return $query->execute();
	}
	
	public function getExtendedAccessPassword() : string {
		global $db;
		
		$query = $db->prepare("SELECT extended_access_password FROM users WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["extended_access_password"]);
	}
	
	public function setAsKicked(int $duration, int $message, int $moderator, string $reason) : bool {
		global $db;
		
		$kickExpiration = time()+($duration*60);
		$messageData = new Message($message);
		$messageData = $messageData->load();
		
		$query = $db->prepare("INSERT INTO users_kicks(user_id, message, expiration, moderator, reason, forum_id) VALUES(:user_id, :message, $kickExpiration, :moderator, :reason, :forum_id)");
		$query->bindValue(":user_id", $this->id, PDO::PARAM_INT);
		$query->bindValue(":message", $message, PDO::PARAM_INT);
		$query->bindValue(":moderator", $moderator, PDO::PARAM_INT);
		$query->bindValue(":reason", $reason, PDO::PARAM_STR);
		$query->bindValue(":forum_id", $messageData["forum"], PDO::PARAM_INT);
		$query->execute();
		
		$forum = new Forum($messageData["forum"]);
		$forumName = $forum->getName();
		
		Pm::create(0, "Vous avez été kické du forum $forumName", [$this->id], "Bonjour,\n\nvotre pseudo a été kické du forum $forumName jusqu'au ".date("d/m/Y à H:i:s", $kickExpiration).".\nLe message ayant causé votre kick est le suivant :\n[quote:$message]\nLe motif du kick est : $reason\n\nCordialement,");
		
		return true;
	}
	
	public function unkick(int $forumId) : bool {
		global $db;
		
		$query = $db->prepare("SELECT id FROM users_kicks WHERE expiration > ".time());
		$query->execute();
		$data = $query->fetch();
		
		if (empty($data)) {
			return true;
		}
		
		$query = $db->prepare("UPDATE users_kicks SET expiration = 0 WHERE id = :id");
		$query->bindValue(":id", $data["id"], PDO::PARAM_INT);
		
		return $query->execute();
	}
	
	public function isKicked(int $forumId) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM users_kicks WHERE forum_id = :forum_id AND user_id = :user_id AND expiration > ".time());
		$query->bindValue(":forum_id", $forumId, PDO::PARAM_INT);
		$query->bindValue(":user_id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] > 0;
	}
}