<?php
require "Core/Forum.class.php";

if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

if ($sessionData["admin"] == 0) {
	http_response_code(403);
	require "Handlers/Error.php";
}

if (!isset($_POST["userId"]) || !is_string($_POST["userId"]) || !is_numeric($_POST["userId"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier l'ID de l'utilisateur à dékicker.";
	exit;
}

if (!isset($_POST["forumId"]) || !is_string($_POST["forumId"]) || !is_numeric($_POST["forumId"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier le forum où dékicker l'utilisateur.";
	exit;
} else {
	$forum = new Forum($_POST["forumId"]);
	if (!$forum->exists()) {
		http_response_code(404);
		$result["message"] = "Le forum spécifié n'existe pas.";
		exit;
	}
}

if (!isset($_POST["token"]) || !is_string($_POST["token"]) || empty($_POST["token"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier votre token.";
	exit;
} elseif ($_POST["token"] != $hash) {
	http_response_code(400);
	$result["message"] = "Le token spécifié est invalide.";
	exit;
}

$user = new User($_POST["userId"]);

if (!$user->isKicked($_POST["forumId"])) {
	http_response_code(400);
	$result["message"] = "L'utilisateur n'est pas kické de ce forum.";
	exit;
}

$user->unkick($_POST["forumId"]);
$result["message"] = "L'utilisateur a été dékické.";