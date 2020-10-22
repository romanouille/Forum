<?php
require "Core/Forum.class.php";
require "Core/Topic.class.php";
require "Core/Captcha.class.php";
require "Core/Message.class.php";

if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

$forumId = Forum::getIdByName($match[1]);
$topicId = $match[2];
$page = $match[3];
$messageId = $match[5];

$forum = new Forum($forumId);
if (!$forum->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$forumName = $forum->getName();

$topic = new Topic($forumId, $topicId);
if (!$topic->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$topicTitle = $topic->getTitle();
$topicSlug = slug($topicTitle);

if ($match[4] != $topicSlug) {
	header("Location: /forums/{$match[1]}/$topicId-$page-$topicSlug?edit_message=$messageId");
	exit;
}

$message = new Message($messageId);
if (!$message->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$messageData = $message->load();

if ($messageData["author"] != $sessionData["user_id"]) {
	http_response_code(403);
	require "Handlers/Error.php";
}

if ($messageData["topic"] != $topicId) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if (time()-$messageData["timestamp"] >= 600) {
	http_response_code(410);
	require "Handlers/Error.php";
}

if (count($_POST) > 0) {
	$messages = [];
	$_POST = array_map(function($a) { return is_string($a) ? trim($a) : $a; }, $_POST);
	
	if (!isset($_POST["token"]) || !is_string($_POST["token"]) || $_POST["token"] != $hash) {
		$messages[] = "Le formulaire est invalide.";
	}
	
	if (!isset($_POST["content"]) || !is_string($_POST["content"]) || empty($_POST["content"])) {
		$messages[] = "Vous devez spécifier le contenu de votre message.";
	} else {		
		if ($messageData["content"] == $_POST["content"]) {
			$messages[] = "Le message que vous avez spécifié est identique.";
		}
	}
	
	if (empty($messages)) {
		$message->edit($_POST["content"], $sessionData["user_id"]);
		$messages[] = "Le message a été édité.";
	}
}

$breadcrumb = ["Forum ".htmlspecialchars($forumName), "Sujet « ".htmlspecialchars($topicTitle)." »", "Éditer le message #$messageId"];

require "Pages/Topic_edit_message.php";