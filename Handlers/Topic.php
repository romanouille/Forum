<?php
require "Core/Forum.class.php";
require "Core/Topic.class.php";
require "Core/Captcha.class.php";
require "Core/Message.class.php";
require "Core/Sticker.class.php";

$forumId = Forum::getIdByName($match[1]);
$topicId = $match[2];
$page = $match[3];

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

$poll = $topic->getPoll();
$votedOnPoll = $userLogged ? $user->votedOnPoll($topicId) : true;

if (isset($match[5]) && isset($match[6]) && !$votedOnPoll && $userData["points"] >= $poll["points"]) {
	if ($match[6] != $hash) {
		http_response_code(400);
		require "Handlers/Error.php";
	}
	
	$user->voteOnPoll($topicId, $match[5]);
}

$topicMessages = $topic->getMessages($page);
$totalPages = $topic->getPagesNb();
$topicTitle = $topic->getTitle();
$topicSlug = slug($topicTitle);

if (isset($match[5]) || $match[4] != $topicSlug) {
	header("Location: /forums/{$match[1]}/$topicId-$page-$topicSlug");
	exit;
}

if ($userLogged && count($_POST) > 0) {
	$messages = [];
	$_POST = array_map(function($a) { return is_string($a) ? trim($a) : $a; }, $_POST);
	
	if (!isset($_POST["token"]) || !is_string($_POST["token"]) || $_POST["token"] != $hash) {
		$messages[] = "Le formulaire est invalide, veuillez réessayer.";
	}
	
	if (!isset($_POST["content"]) || !is_string($_POST["content"]) || empty(trim($_POST["content"]))) {
		$messages[] = "Vous devez spécifier le contenu de votre message.";
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$topic = new Topic($forumId, $topicId);
		$messageId = $topic->createMessage($sessionData["user_id"], $_POST["content"]);
		
		header("Location: /forums/{$match[1]}/$topicId-".$topic->getPagesNb()."-".slug($topicTitle)."#message_$messageId");
		exit;
	}
}

$breadcrumb = ["Forum ".htmlspecialchars($forumName), "Sujet « ".htmlspecialchars($topicTitle)." »"];

require "Pages/Topic.php";