<?php
class Forum {
	public function __construct(int $id) {
		$this->id = $id;
	}
	
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM forums WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public function getTopics(int $page) : array {
		global $db;
		
		$query = $db->prepare("SELECT id, author, title, replies, last_message_timestamp FROM topics WHERE forum = :forum ORDER BY last_message_timestamp DESC LIMIT 25 OFFSET ".(($page-1)*25));
		$query->bindValue(":forum", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"id" => (int)$value["id"],
				"author" => (int)$value["author"],
				"title" => (string)$value["title"],
				"replies" => (int)$value["replies"],
				"last_message_timestamp" => (int)$value["last_message_timestamp"]
			];
		}
		
		return $result;
	}
	
	public function getPagesNb() : int {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM topics");
		$query->execute();
		$data = $query->fetch();
		
		return ceil($data["nb"]/25);
	}
}