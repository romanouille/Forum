<?php
require "Core/Forum.class.php";
require "Core/Topic.class.php";

if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

if ($sessionData["admin"] == 0) {
	http_response_code(403);
	require "Handlers/Error.php";
}

if (!isset($_POST["forumId"]) || !is_string($_POST["forumId"]) || !is_numeric($_POST["forumId"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier un forum.";
	exit;
}

$forum = new Forum($_POST["forumId"]);
if (!$forum->exists()) {
	http_response_code(404);
	$result["message"] = "Le forum spécifié n'existe pas.";
	exit;
}

if (!isset($_POST["topicId"]) || !is_string($_POST["topicId"]) || !is_numeric($_POST["topicId"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier un topic.";
	exit;
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

$topic = new Topic($_POST["forumId"], $_POST["topicId"]);
if (!$topic->exists()) {
	http_response_code(404);
	$result["message"] = "Le topic spécifié n'existe pas.";
	exit;
}

if (!$topic->isPinned()) {
	http_response_code(400);
	$result["message"] = "Le topic spécifié n'est pas épinglé.";
	exit;
}

$topic->unpin();
$result["message"] = "Le topic a été désépinglé.";