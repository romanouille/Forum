<?php
$db = new PDO("pgsql:host=127.0.0.1;dbname=avenoel", "postgres", "azerty", [PDO::ATTR_PERSISTENT => true]);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$noelfic = new PDO("pgsql:host=127.0.0.1;dbname=noelfic", "postgres", "azerty", [PDO::ATTR_PERSISTENT => true]);
$noelfic->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = $db->prepare('TRUNCATE "messages", "polls", "polls_responses", "polls_votes", "topics", "users"');
$query->execute();

$query = $db->prepare("ALTER SEQUENCE messages_id_seq RESTART WITH 1");
$query->execute();

$query = $db->prepare("ALTER SEQUENCE topics_id_seq RESTART WITH 1");
$query->execute();

$query = $db->prepare("ALTER SEQUENCE users_id_seq RESTART WITH 1");
$query->execute();

$query = $noelfic->query("SELECT id, username, password_v1, email, avatar_id, description, birth, registration_timestamp, last_seen_timestamp FROM users ORDER BY id ASC");
$query->execute();
$data = $query->fetchAll();

echo "Importing users.\n";

foreach ($data as $value) {
	$query = $db->prepare("INSERT INTO users(id, username, email, password, noelfic_password, avatar_id, description, birth, registration_timestamp, last_seen_timestamp) VALUES(:id, :username, :email, '', :noelfic_password, :avatar_id, :description, :birth, :registration_timestamp, :last_seen_timestamp)");
	$query->bindValue(":id", $value["id"], PDO::PARAM_INT);
	$query->bindValue(":username", $value["username"], PDO::PARAM_STR);
	$query->bindValue(":email", $value["email"], PDO::PARAM_STR);
	$query->bindValue(":noelfic_password", $value["password_v1"], PDO::PARAM_STR);
	$query->bindValue(":avatar_id", $value["avatar_id"], PDO::PARAM_INT);
	$query->bindValue(":description", $value["description"], PDO::PARAM_STR);
	$query->bindValue(":birth", $value["birth"], PDO::PARAM_INT);
	$query->bindValue(":registration_timestamp", $value["registration_timestamp"], PDO::PARAM_INT);
	$query->bindValue(":last_seen_timestamp", $value["last_seen_timestamp"], PDO::PARAM_INT);
	$query->execute();
}


echo "Importing fics.\n";

$query = $noelfic->query("SELECT id, title FROM fics ORDER BY id ASC");
$query->execute();
$data = $query->fetchAll();

foreach ($data as $value) {
	$query = $noelfic->prepare("SELECT author FROM fics_chapters WHERE fic = :fic ORDER BY id ASC LIMIT 1");
	$query->bindValue(":fic", $value["id"], PDO::PARAM_INT);
	$query->execute();
	$data2 = $query->fetch();
	
	$query = $db->prepare("INSERT INTO topics(id, forum, author, title, last_message_timestamp) VALUES({$value["id"]}, 1, :author, :title, 0)");
	$query->bindValue(":author", $data2["author"], PDO::PARAM_INT);
	$query->bindValue(":title", $value["title"], PDO::PARAM_STR);
	$query->execute();
	
	$query = $noelfic->prepare("SELECT id, fic, author, created_timestamp, content FROM fics_chapters WHERE fic = :fic ORDER BY id ASC");
	$query->bindValue(":fic", $value["id"], PDO::PARAM_INT);
	$query->execute();
	$data2 = $query->fetchAll();
	
	foreach ($data2 as $value2) {
		$query = $db->prepare("INSERT INTO messages(forum, topic, author, content, timestamp) VALUES(1, :topic, :author, :content, :timestamp)");
		$query->bindValue(":topic", $value2["fic"], PDO::PARAM_INT);
		$query->bindValue(":author", $value2["author"], PDO::PARAM_INT);
		$query->bindValue(":content", $value2["content"], PDO::PARAM_STR);
		$query->bindValue(":timestamp", $value2["created_timestamp"], PDO::PARAM_INT);
		$query->execute();
		
		$query = $noelfic->prepare("SELECT author, created_timestamp, content FROM fics_comments WHERE chapter = :chapter AND deleted = 0");
		$query->bindValue(":chapter", $value2["id"], PDO::PARAM_INT);
		$query->execute();
		$data3 = $query->fetchAll();
		
		foreach ($data3 as $id=>$value3) {
			$query = $db->prepare("INSERT INTO messages(forum, topic, author, content, timestamp) VALUES(1, :topic, :author, :content, :timestamp)");
			$query->bindValue(":topic", $value2["fic"], PDO::PARAM_INT);
			$query->bindValue(":author", $value3["author"], PDO::PARAM_INT);
			$query->bindValue(":content", $value3["content"], PDO::PARAM_STR);
			$query->bindValue(":timestamp", $value3["created_timestamp"], PDO::PARAM_INT);
			$query->execute();
			
			if ($id == count($data3)-1) {
				$query = $db->prepare("UPDATE topics SET last_message_timestamp = :last_message_timestamp WHERE id = :id");
				$query->bindValue(":last_message_timestamp", $value3["created_timestamp"], PDO::PARAM_INT);
				$query->bindValue(":id", $value2["fic"], PDO::PARAM_INT);
				$query->execute();
			
				$query = $db->prepare("SELECT COUNT(*) AS nb FROM messages WHERE topic = :topic");
				$query->bindValue(":topic", $value2["fic"], PDO::PARAM_INT);
				$query->execute();
				$data4 = $query->fetch();
				
				$query = $db->prepare("UPDATE topics SET replies = :replies WHERE id = :id");
				$query->bindValue(":replies", $data4["nb"]-1, PDO::PARAM_INT);
				$query->bindValue(":id", $value2["fic"], PDO::PARAM_INT);
				$query->execute();
			}
		}
	}
}