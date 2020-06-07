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
	 * @return array Résultat
	 */
	public function getTopics(int $page) : array {
		global $db;
		
		$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, title, replies, last_message_timestamp, pinned, locked, deleted FROM topics WHERE forum = :forum ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
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
	 * @return int Résultat
	 */
	public function getPagesNb() : int {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics");
		$query->execute();
		$data = $query->fetch();
		
		return ceil($data["nb"]/25);
	}
}