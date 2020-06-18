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
	public function getTopics(int $page, int $searchType = 0, string $searchData = "") : array {
		global $db;
		
		if ($searchType == 0) {	
			$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
		} elseif ($searchType == 1) {
			$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum AND title ILIKE :title ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
			$query->bindValue(":title", "%$searchData%", PDO::PARAM_STR);
		} elseif ($searchType == 2) {
			$query = $db->prepare("SELECT id FROM users WHERE username = :username");
			$query->bindValue(":username", $searchData, PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetch();
			if (empty($data)) {
				return [];
			}
			
			$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum AND author = :author ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
			$query->bindValue(":author", $data["id"], PDO::PARAM_INT);
		}
		
		$query->bindValue(":forum", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"id" => (int)$value["id"],
				"author" => (int)$value["author"],
				"username" => (string)$value["username"],
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
	public function getPagesNb(int $searchType = 0, string $searchData = "", int $forumId = 0) : int {
		global $db;
		
		if ($searchType == 0) {
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics");
		} elseif ($searchType == 1) {
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE forum = :forum AND title ILIKE :title");
			$query->bindValue(":forum", $forumId, PDO::PARAM_INT);
			$query->bindValue(":title", "%$searchData%", PDO::PARAM_STR);
		} elseif ($searchType == 2) {
			$query = $db->prepare("SELECT id FROM users WHERE username = :username");
			$query->bindValue(":username", $searchData, PDO::PARAM_STR);
			$query->execute();
			$data = $query->fetch();
			if (empty($data)) {
				return 0;
			}
			
			$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE forum = :forum AND author = :author");
			$query->bindValue(":forum", $forumId, PDO::PARAM_INT);
			$query->bindValue(":author", $data["id"], PDO::PARAM_INT);
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
		
		$query = $db->prepare("SELECT id FROM forums WHERE LOWER(name) = LOWER(:name)");
		$query->bindValue(":name", $name, PDO::PARAM_STR);
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