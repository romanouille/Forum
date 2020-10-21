<?php
class Session {
	public function __construct(string $name) {
		$this->name = $name;
	}
	
	public function exists() : bool {
		global $db;
		
		$query = $db->prepare("SELECT COUNT(*) AS nb FROM sessions WHERE name = :name");
		$query->bindValue(":name", $this->name, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetch();
		
		return $data["nb"] == 1;
	}
	
	public static function create(int $userId) : string {
		global $db;
		
		$name = sha1(microtime(1).random_bytes(32).uniqid().$_SERVER["REMOTE_ADDR"]);
		setcookie("session", $name, time()+31536000, "/", $_SERVER["HTTP_HOST"], $_SERVER["SERVER_PORT"] == 443, true);
		
		$query = $db->prepare("INSERT INTO sessions(name, user_id, ip, first_seen, last_seen) VALUES(:name, :user_id, :ip, ".time().", ".time().")");
		$query->bindValue(":name", $name, PDO::PARAM_STR);
		$query->bindValue(":user_id", $userId, PDO::PARAM_INT);
		$query->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
		$query->execute();
		
		return $name;
	}
	
	public function getData() : array {
		global $db;
		
		$query = $db->prepare("SELECT user_id, admin, ip, first_seen, last_seen, deleted FROM sessions WHERE name = :name");
		$query->bindValue(":name", $this->name, PDO::PARAM_STR);
		$query->execute();
		$data = array_map("trim", $query->fetch());
		
		return $data;
	}
	
	public function delete() : bool {
		global $db;
		
		$query = $db->prepare("UPDATE sessions SET deleted = 1 WHERE name = :name");
		$query->bindValue(":name", $this->name, PDO::PARAM_STR);
		
		return $query->execute();
	}
	
	public function update() : bool {
		global $db;
		
		$query = $db->prepare("UPDATE sessions SET last_seen = ".time()." WHERE name = :name");
		$query->bindValue(":name", $this->name, PDO::PARAM_STR);
		
		return $query->execute();
	}
}