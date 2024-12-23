<?php
$config = parse_ini_file(".env", true);

$db = new PDO("pgsql:host={$config["db"]["server"]};dbname={$config["db"]["name"]}", $config["db"]["username"], $config["db"]["password"], [PDO::ATTR_PERSISTENT => true]);
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
	$sessionData = [
		"deleted" => false,
		"admin" => false,
		"user_id" => 0
	];
	
	$hash = sha1($_SERVER["REMOTE_ADDR"]);
}