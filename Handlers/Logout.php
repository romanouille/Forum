<?php
if (isset($_GET["hash"]) && is_string($_GET["hash"]) && $_GET["hash"] == $hash) {
	$session->delete();
	setcookie("session", null, -1, "/", $_SERVER["HTTP_HOST"], $_SERVER["SERVER_PORT"] == 443, true);
	header("Location: /forums/blabla/1");
	exit;
}