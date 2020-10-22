<?php
class Topic {
	/**
	 * Constructeur
	 *
	 * @param int $forumId ID du forum
	 * @param int $id ID du forum
	 */
	public function __construct(int $forumId, int $id) {
		$this->forumId = $forumId;
		$this->id = $id;
	}
	
	/**
	 * Vérifie si le forum existe
	 *
	 * @return bool Résultat
	 */
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	/**
	 * Crée un topic
	 *
	 * @param int $forum ID du forum
	 * @param int $author ID de l'auteur du topic
	 * @param string $title Titre du topic
	 * @param string $message Message du topic
	 *
	 * @return int ID du topic créé
	 */
	public static function create(int $forum, int $author, string $title, string $message) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO topics(forum, author, title, last_message_timestamp) VALUES(:forum, :author, :title, :last_message_timestamp)");
		$query->bindValue(":forum", $forum, PDO::PARAM_INT);
		$query->bindValue(":author", $author, PDO::PARAM_INT);
		$query->bindValue(":title", $title, PDO::PARAM_STR);
		$query->bindValue(":last_message_timestamp", time(), PDO::PARAM_INT);
		$query->execute();
		$topicId = $db->lastInsertId();
		
		$topic = new Topic($forum, $topicId);
		$topic->createMessage($author, $message, false);
		
		return $topicId;
	}
	
	/**
	 * Crée un message sur le topic
	 *
	 * @param int $author ID de l'auteur du message
	 * @param string $message Contenu du message
	 *
	 * @return int ID du message créé
	 */
	public function createMessage(int $author, string $content, bool $incrementReplies = true) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO messages(forum, topic, author, content, timestamp) VALUES(:forum, :topic, :author, :content, :timestamp)");
		$query->bindValue(":forum", $this->forumId, PDO::PARAM_INT);
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
		$query->bindValue(":author", $author, PDO::PARAM_INT);
		$query->bindValue(":content", $content, PDO::PARAM_STR);
		$query->bindValue(":timestamp", time(), PDO::PARAM_INT);
		$query->execute();
		$messageId = $db->lastInsertId();
		
		$query = $db->prepare("UPDATE users SET points = points + 1, messages = messages + 1 WHERE id = :id");
		$query->bindValue(":id", $author, PDO::PARAM_INT);
		$query->execute();
		
		if ($incrementReplies) {
			$query = $db->prepare("UPDATE topics SET replies = replies + 1 WHERE id = :id");
			$query->bindValue(":id", $this->id, PDO::PARAM_INT);
			$query->execute();
		}
		
		return $messageId;
	}
	
	/**
	 * Récupère la liste des messages du topic
	 *
	 * @param int $page Page du topic
	 *
	 * @return array Messages
	 */
	public function getMessages(int $page) : array {
		global $db;
		
		$query = $db->prepare("SELECT id, author, (SELECT username FROM users WHERE id = author) AS username, content, timestamp FROM messages WHERE topic = :topic LIMIT 20 OFFSET ".(($page-1)*20));
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
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
	
	/**
	 * Récupère la liste des pages du topic
	 *
	 * @return int Résultat
	 */
	public function getPagesNb() : int {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM messages WHERE topic = :topic");
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return ceil($data["nb"]/20);
	}
	
	/**
	 * Récupère le titre du topic
	 *
	 * @reutrn string Slug
	 */
	public function getTitle() : string {
		global $db;
		
		$query = $db->prepare("SELECT title FROM topics WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return trim($data["title"]);
	}
	
	public function getPoll() : array {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM polls WHERE topic = :topic");
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		if ($data["nb"] == 0) {
			return [];
		}
		
		$result = [
			"question" => "",
			"points" => 0,
			"responses" => []
		];
		
		$query = $db->prepare("SELECT question, points FROM polls WHERE topic = :topic");
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		$result["question"] = (string)trim($data["question"]);
		$result["points"] = (int)$data["points"];
		
		$query = $db->prepare("SELECT id, response, votes FROM polls_responses WHERE topic = :topic ORDER BY votes DESC");
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		
		foreach ($data as $value) {
			$result["responses"][] = [
				"id" => (int)$value["id"],
				"response" => (string)trim($value["response"]),
				"votes" => (int)$value["votes"]
			];
		}
		
		return $result;
	}
}