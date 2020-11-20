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

if (!isset($_POST["messageId"]) || !is_string($_POST["messageId"]) || !is_numeric($_POST["messageId"])) {
	http_response_code(400);
	$result["message"] = "Vous devez spécifier un message.";
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

if (!$message->isDeleted()) {
	http_response_code(400);
	$result["message"] = "Le message spécifié n'est pas supprimé.";
	exit;
}

$message->restore();
$result["message"] = "Le message a été restauré.";