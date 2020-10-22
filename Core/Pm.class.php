<?php
class Pm {
	/**
	 * Constructeur
	 *
	 * @parma string int $id ID du MP
	 */
	public function __construct(int $id) {
		$this->id = $id;
	}
	
	/**
	 * Crée un MP
	 *
	 * @param int $author Auteur du MP
	 * @param string $title Titre du MP
	 * @param array $receivers Destinataires du MP
	 * @param string $content Contenu du MP
	 *
	 * @return int ID du mp créé
	 */
	public static function create(int $author, string $title, array $receivers, string $content) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO pm(title, timestamp, author) VALUES(:title, :timestamp, :author)");
		$query->bindValue(":title", $title, PDO::PARAM_STR);
		$query->bindValue(":timestamp", time(), PDO::PARAM_INT);
		$query->bindValue(":author", $author, PDO::PARAM_INT);
		$query->execute();
		$pmId = $db->lastInsertId();
		$pm = new Pm($pmId);
		
		foreach ($receivers as $receiver) {
			$query = $db->prepare("INSERT INTO pm_receivers(pm_id, user_id, timestamp) VALUES(:pm_id, :user_id, :timestamp)");
			$query->bindValue(":pm_id", $pmId, PDO::PARAM_INT);
			$query->bindValue(":user_id", $receiver, PDO::PARAM_INT);
			$query->bindValue(":timestamp", time(), PDO::PARAM_INT);
			$query->execute();
		}
		
		$pm->createMessage($author, $content);
		
		return $pmId;
	}
	
	/**
	 * Vérifie si le MP existe
	 *
	 * @return bool Résultat
	 */
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM pm WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public function getTitle() : string {
		global $db;
		
		$query = $db->prepare("SELECT title FROM pm WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["title"]);
	}
	
	/**
	 * Vérifie si un utilisatuer est dans le MP
	 *
	 * @param int $userId ID de l'utilisateur
	 *
	 * @return bool Résultat
	 */
	public function isInPm(int $userId) : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM pm_receivers WHERE pm_id = :pm_id AND user_id = :user_id");
		$query->bindValue(":pm_id", $this->id, PDO::PARAM_INT);
		$query->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public function createMessage(int $author, string $content) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO pm_messages(pm, author, content, timestamp) VALUES(:pm, :author, :content, ".time().")");
		$query->bindValue(":pm", $this->id, PDO::PARAM_INT);
		$query->bindValue(":author", $author, PDO::PARAM_INT);
		$query->bindValue(":content", $content, PDO::PARAM_STR);
		$query->execute();
		
		return $db->lastInsertId();
	}
	
	public function getMessages(int $page) : array {
		global $db;
		
		$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, content, timestamp FROM pm_messages WHERE pm = :pm ORDER BY timestamp ASC LIMIT 20 OFFSET ".(($page-1)*20));
		$query->bindValue(":pm", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"id" => (int)$value["id"],
				"author" => (int)$value["author"],
				"username" => (string)trim($value["username"]),
				"content" => (string)trim($value["content"]),
				"timestamp" => (int)$value["timestamp"]
			];
		}
		
		return $result;
	}
	
	public function getPagesNb() : int {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM pm_messages WHERE pm = :pm");
		$query->bindValue(":pm", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return ceil($data["nb"]/20);
	}
}