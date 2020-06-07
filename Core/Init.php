<?php
if ($devMode) {
	$db = new PDO("pgsql:dbname=avenoel;host=localhost", "postgres", "azerty");
} else {
	$db = new PDO("pgsql:dbname=avenoel;host=localhost", "postgres", "azerty", [PDO::ATTR_PERSISTENT => true]);
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_set_cookie_params(86400, "/", $_SERVER["HTTP_HOST"], $_SERVER["SERVER_PORT"] == 443, true);
session_name("session");
session_start();

$staticServer = "http://127.0.0.3";

if (!empty($_SESSION)) {
	/*if ($_SERVER["REMOTE_ADDR"] != $_SESSION["ip"]) {
		session_destroy();
		header("Location: {$_SERVER["REQUEST_URI"]}");
		exit;
	}*/
	
	$hash = sha1($_COOKIE["session"]);
	$user = new User($_SESSION["userId"]);
} else {
	$_SESSION = [
		"ip" => $_SERVER["REMOTE_ADDR"],
		"userId" => 0,
		"logged" => false
	];
	
	$hash = sha1($_SERVER["REMOTE_ADDR"]);
}