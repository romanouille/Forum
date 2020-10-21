<?php
if ($devMode) {
	$db = new PDO("pgsql:dbname=avenoel;host=localhost", "postgres", "azerty");
} else {
	$db = new PDO("pgsql:dbname=avenoel;host=localhost", "postgres", "azerty", [PDO::ATTR_PERSISTENT => true]);
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$staticServer = "http://127.0.0.5";

if (isset($_COOKIE["session"])) {
	$session = new Session($_COOKIE["session"]);
	if (!$session->exists()) {
		setcookie("session", null, -1);
		header("Location: {$_SERVER["REQUEST_URI"]}");
		exit;
	}
	
	
	$sessionData = $session->getData();
	
	if ($sessionData["deleted"]) {
		setcookie("session", null, -1, "/", $_SERVER["HTTP_HOST"], $_SERVER["SERVER_PORT"] == 443, true);
		header("Location: {$_SERVER["REQUEST_URI"]}");
		exit;
	}
	
	$userLogged = true;
	$session->update();
	$user = new User($sessionData["user_id"]);
	$userData = $user->getData();
	
	$hash = sha1($_COOKIE["session"]);
} else {
	$userLogged = false;
	$hash = sha1($_SERVER["REMOTE_ADDR"]);
}