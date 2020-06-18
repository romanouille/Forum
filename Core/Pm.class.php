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
		
		foreach ($receivers as $receiver) {
			$query = $db->prepare("INSERT INTO pm_receivers(pm_id, user_id, timestamp) VALUES(:pm_id, :user_id, :timestamp)");
			$query->bindValue(":pm_id", $pmId, PDO::PARAM_INT);
			$query->bindValue(":user_id", $receiver, PDO::PARAM_INT);
			$query->bindValue(":timestamp", time(), PDO::PARAM_INT);
			$query->execute();
		}
		
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
}