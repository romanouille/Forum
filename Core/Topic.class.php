<?php
class Topic {
	public function __construct(int $id) {
		$this->id = $id;
	}
	
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public static function create(int $forum, int $author, string $title, string $message) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO topics(forum, author, title, last_message_timestamp) VALUES(:forum, :author, :title, :last_message_timestamp)");
		$query->bindValue(":forum", $forum, PDO::PARAM_INT);
		$query->bindValue(":author", $author, PDO::PARAM_INT);
		$query->bindValue(":title", $title, PDO::PARAM_STR);
		$query->bindValue(":last_message_timestamp", time(), PDO::PARAM_INT);
		$query->execute();
		$topicId = $db->lastInsertId();
		
		$topic = new Topic($topicId);
		$topic->createMessage($forum, $author, $message);
		
		return $topicId;
	}
	
	public function createMessage(int $forum, int $author, string $message) : int {
		global $db;
		
		$query = $db->prepare("INSERT INTO messages(forum, topic, author, message, timestamp) VALUES(:forum, :topic, :author, :message, :timestamp)");
		$query->bindValue(":forum", $forum, PDO::PARAM_INT);
		$query->bindValue(":topic", $this->id, PDO::PARAM_INT);
		$query->bindValue(":author", $author, PDO::PARAM_INT);
		$query->bindValue(":message", $message, PDO::PARAM_STR);
		$query->bindValue(":timestamp", time(), PDO::PARAM_INT);
		$query->execute();
		
		return $db->lastInsertId();
	}
	
	public function getMessages(int $page) : array {
		global $db;
		
		$query = $db->prepare("SELECT author, (SELECT username FROM users WHERE id = author) AS username, message timestamp FROM messages WHERE topic = :topic OFFSET ".(($page-1)*20).", 20");
		$query->bindValue(":topic", $this->topic, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"author" => (int)$value["author"],
				"username" => (string)$value["username"],
				"message" => (string)$value["message"],
				"timestamp" => (int)$value["timestamp"]
			];
		}
		
		return $result;
	}
}