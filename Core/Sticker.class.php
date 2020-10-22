<?php
class Sticker {
	public function __construct(int $id) {
		$this->id = $id;
	}
	
	public function getExt() : string {
		global $db;
		
		$query = $db->prepare("SELECT ext FROM stickers WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return (string)trim($data["ext"]);
	}
	
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM stickers WHERE id = :id");
		$query->bindValue(":id", $this->id, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public static function search(string $tags) : array {
		global $db;
		
		$tags = explode(" ", $tags);
		
		$sql = "SELECT DISTINCT id, ext FROM stickers WHERE ";
		foreach ($tags as $nb=>$tag) {
			if ($nb > 0) {
				$sql .= "AND ";
			}

			$sql .= "tags ILIKE :$nb ";
		}
		
		$sql .= "LIMIT 50";
		
		$query = $db->prepare($sql);
		foreach ($tags as $nb=>$tag) {
			$query->bindValue(":$nb", "%$tag%", PDO::PARAM_STR);
		}
		
		$query->execute();
		$data = $query->fetchAll();
		$result = [];
		
		foreach ($data as $value) {
			$result[] = [
				"id" => (int)$value["id"],
				"ext" => (string)trim($value["ext"])
			];
		}
		
		return $result;
	}
}