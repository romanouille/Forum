<?php
require "Core/Forum.class.php";
require "Core/Message.class.php";
require "Core/Pm.class.php";

if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

if ($sessionData["admin"] == 0) {
	http_response_code(403);
	require "Handlers/Error.php";
}

if (!isset($_POST["messageId"]) || !is_string($_POST["messageId"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier un message.";
	exit;
}

if (!isset($_POST["duration"]) || !is_string($_POST["duration"]) || $_POST["duration"] <= 0) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier la durée du kick.";
	exit;
}

if (!isset($_POST["reason"]) || !is_string($_POST["reason"]) || empty($_POST["reason"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier la raison du kick.";
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

$message = new Message($_POST["messageId"]);
if (!$message->exists()) {
	http_response_code(404);
	$result["message"] = "Le message spécifié n'existe pas.";
	exit;
}

$message = $message->load();
if ($user->isKicked($message["forum"])) {
	http_response_code(400);
	$result["message"] = "L'utilisateur est déjà kické de ce forum.";
	exit;
}

$user = new User($message["author"]);
$user->setAsKicked($_POST["duration"], $_POST["messageId"], $sessionData["user_id"], substr($_POST["reason"], 0, 100));

$result["message"] = "L'utilisateur a été kické.";