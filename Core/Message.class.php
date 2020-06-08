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
		
		$query = $db->prepare("SELECT (SELECT username FROM users WHERE id = author) AS username, timestamp, message FROM messages WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data;
	}
}