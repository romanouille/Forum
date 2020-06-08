<?php
require "Core/Forum.class.php";
require "Core/Topic.class.php";
require "Core/Captcha.class.php";
require "Core/Message.class.php";

$forumId = 1;
$topicId = $match[2];
$page = $match[3];

$forum = new Forum($forumId);
if (!$forum->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$topic = new Topic($forumId, $topicId);
if (!$topic->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$topicMessages = $topic->getMessages($page);
$totalPages = $topic->getPagesNb();
$topicSlug = $topic->getSlug();


if (count($_POST) > 0) {
	$messages = [];
	$_POST = array_map("trim", $_POST);
	
	if (!isset($_POST["hash"]) || !is_string($_POST["hash"]) || $_POST["hash"] != $hash) {
		$messages[] = "Le formulaire est invalide, veuillez réessayer.";
	}
	
	if (!isset($_POST["message"]) || !is_string($_POST["message"]) || empty($_POST["message"])) {
		$messages[] = "Vous devez spécifier le contenu de votre message.";
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$topic = new Topic($forumId, $topicId);
		$messageId = $topic->createMessage($_SESSION["userId"], $_POST["message"]);
		
		header("Location: /forums/$forumId-$topicId-".$topic->getPagesNb()."-".$topic->getSlug()."#message_$messageId");
		exit;
	}
}


require "Pages/Topic.php";