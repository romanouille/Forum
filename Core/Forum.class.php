<?php
class Forum {
	/**
	 * Constructeur
	 *
	 * @param int $id ID du forum
	 */
	public function __construct(int $id) {
		$this->id = $id;
	}
	
	/**
	 * Vérifie si le forum existe
	 *
	 * @return bool Résultat
	 */
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM forums WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	/**
	 * Récupère la liste des topics du forum
	 *
	 * @param int $page Page
	 * @param int $searchType Optionnel, indique le type de recherche à utiliser
	 * @param string $searchData Optionnel, indique le contenu à rechercher
	 *
	 * @return array Résultat
	 */
	public function getTopics(int $page, int $searchType = 0, string $searchData = "", bool $admin = false) : array {
		global $db;
		
		if (strstr($searchData, "%")) {
			$searchData = str_replace("%", "", $searchData);
			
			if (empty($searchData)) {
				return [];
			}
		}
		
		$sql = !$admin ? " AND deleted = 0 " : "";
		
		if ($searchType == 0) {	
			$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum $sql ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
		} elseif ($searchType == 1) {
			$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum $sql AND title ILIKE :title ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
			$query->bindValue(":title", "%$searchData%", PDO::PARAM_STR);
		} elseif ($searchType == 2) {
			$query = $db->prepare("SELECT id FROM users WHERE username ILIKE :username");
			$query->bindValue(":username", "%$searchData%", PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetchAll();
			if (empty($data)) {
				return [];
			}
			
			$result = [];
			foreach ($data as $value) {
				$result[] = $value["id"];
			}
			
			$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum AND author IN (".implode(",", $result).") $sql ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
		} elseif ($searchType == 3) {
			$query = $db->prepare("SELECT id, topic, content, (SELECT username FROM users WHERE id = author) AS message_username, timestamp FROM messages WHERE forum = :forum AND content ILIKE :content AND content NOT ILIKE '[quote:%]' $sql ORDER BY timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
			$query->bindValue(":forum", $this->id, PDO::PARAM_INT);
			$query->bindValue(":content", "%$searchData%", PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetchAll();
			
			if (empty($data)) {
				return [];
			}
			
			$result = [];
			foreach ($data as $nb=>$value) {
				$result[$nb] = [
					"id" => (int)$value["id"],
					"topic" => (int)$value["topic"],
					"content" => (string)trim($value["content"]),
					"message_username" => (string)trim($value["message_username"]),
					"message_timestamp" => (int)$value["timestamp"]
				];
				
				$query = $db->prepare("SELECT id, title, (SELECT username FROM users WHERE id = author) AS topic_username, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE id = :id $sql");
				$query->bindValue(":id", (int)$value["topic"], PDO::PARAM_INT);
				$query->execute();
				$data2 = $query->fetch();
				
				$result[$nb] = array_merge($result[$nb], [
					"id" => (int)$data2["id"],
					"title" => (string)trim($data2["title"]),
					"topic_username" => (string)$data2["topic_username"],
					"replies" => (int)$data2["replies"],
					"last_message_timestamp" => (int)$data2["last_message_timestamp"],
					"pinned" => (bool)$data2["pinned"],
					"locked" => (bool)$data2["locked"],
					"deleted" => (bool)$data2["deleted"]
				]);
			}
			
			return $result;
		}
			
		
		
		$query->bindValue(":forum", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"id" => (int)$value["id"],
				"author" => (int)$value["author"],
				"topic_username" => (string)trim($value["username"]),
				"title" => (string)trim($value["title"]),
				"replies" => (int)$value["replies"],
				"last_message_timestamp" => (int)$value["last_message_timestamp"],
				"pinned" => (bool)$value["pinned"],
				"locked" => (bool)$value["locked"],
				"deleted" => (bool)$value["deleted"]
			];
		}
		
		return $result;
	}
	
	/**
	 * Récupère le nombre de pages du forum
	 *
	 * @param int $searchType Optionnel, type de recherche
	 * @param string $searchData Optionnel, Données de la recherche
	 * @param int $forumId Optionnel, ID du forum pour la recherche
	 *
	 * @return int Résultat
	 */
	public function getPagesNb(int $searchType = 0, string $searchData = "", int $forumId = 0, bool $admin = false) : int {
		global $db;
		
		if (strstr($searchData, "%")) {
			$searchData = str_replace("%", "", $searchData);
		}
		
		$sql = !$admin ? " AND deleted = 0 " : "";
		
		if ($searchType == 0) {
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE 1 = 1 $sql");
		} elseif ($searchType == 1) {
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE forum = :forum AND title ILIKE :title $sql");
			$query->bindValue(":forum", $forumId, PDO::PARAM_INT);
			$query->bindValue(":title", "%$searchData%", PDO::PARAM_STR);
		} elseif ($searchType == 2) {
			$query = $db->prepare("SELECT id FROM users WHERE username ILIKE :username");
			$query->bindValue(":username", "%$searchData%", PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetchAll();
			if (empty($data)) {
				return [];
			}
			
			$result = [];
			foreach ($data as $value) {
				$result[] = $value["id"];
			}
			
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE forum = :forum AND author IN (".implode(",", $result).") $sql");
			$query->bindValue(":forum", $forumId, PDO::PARAM_INT);
		} elseif ($searchType == 3) {
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM messages WHERE forum = :forum AND content ILIKE :content AND content NOT ILIKE '[quote:%]' $sql");
			$query->bindValue(":forum", $forumId, PDO::PARAM_INT);
			$query->bindValue(":content", "%$searchData%", PDO::PARAM_STR);
		}
			
		$query->execute();
		$data = $query->fetch();
		
		return ceil($data["nb"]/25);
	}
	
	/**
	 * Récupère l'ID d'un forum en fonction de son nom
	 *
	 * @param string $name Nom du forum
	 *
	 * @return int ID du forum
	 */
	public static function getIdByName(string $name) : int {
		global $db;
		
		$query = $db->prepare("SELECT id FROM forums WHERE slug = :slug");
		$query->bindValue(":slug", $name, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return isset($data["id"]) ? $data["id"] : 0;
	}
	
	/**
	 * Récupère le nom du forum
	 *
	 * @return string Résultat
	 */
	public function getName() : string {
		global $db;
		
		$query = $db->prepare("SELECT name FROM forums WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["name"]);
	}
}