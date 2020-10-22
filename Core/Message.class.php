<?php
class Message {
	/**
	 * Constructeur
	 *
	 * @param int $id ID du message
	 */
	public function __construct(int $id) {
		$this->id = $id;
	}
	
	/**
	 * Vérifie si le message existe
	 *
	 * @return bool Résultat
	 */
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM messages WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	/**
	 * Vérifie si le message est supprimé
	 *
	 * @return bool Résultat
	 */
	public function isDeleted() : bool {
		global $db;
		
		$query = $db->prepare("SELECT deleted FROM messages WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return (bool)$data["deleted"];
	}
	
	/**
	 * Charge les informations du message
	 *
	 * @return array Résultat
	 */
	public function load() : array {
		global $db;
		
		$query = $db->prepare("SELECT topic, author, (SELECT username FROM users WHERE id = author) AS username, timestamp, content FROM messages WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		return $data;
	}
	
	public function edit(string $content, int $editedBy) : bool {
		global $db;
		
		$query = $db->prepare("SELECT content, timestamp, edited_by FROM messages WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		$query = $db->prepare("INSERT INTO messages_history(message, content, timestamp, edited_by) VALUES(:message, :content, :timestamp, :edited_by)");
		$query->bindValue(":message", $this->id, PDO::PARAM_INT);
		$query->bindValue(":content", $data["content"], PDO::PARAM_STR);
		$query->bindValue(":timestamp", $data["timestamp"], PDO::PARAM_INT);
		$query->bindValue(":edited_by", $data["edited_by"], PDO::PARAM_INT);
		$query->execute();
		
		$query = $db->prepare("UPDATE messages SET content = :content, last_edit = ".time().", edited_by = :edited_by WHERE id = :id");
		$query->bindValue(":content", $content, PDO::PARAM_STR);
		$query->bindValue(":edited_by", $editedBy, PDO::PARAM_INT);
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		
		return $query->execute();
	}
}