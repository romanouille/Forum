<?php
require "Core/Captcha.class.php";
require "Core/Forum.class.php";
require "Core/Topic.class.php";

$forumId = 1;
$page = isset($match[1]) ? $match[1] : 1;

$forum = new Forum($forumId);
if (!$forum->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$topics = $forum->getTopics($page);
$pagesNb = $forum->getPagesNb();

if (count($_POST) > 0) {
	$messages = [];
	$_POST = array_map("trim", $_POST);
	
	if (!isset($_POST["hash"]) || !is_string($_POST["hash"]) || $_POST["hash"] != $hash) {
		$messages[] = "Le formulaire est invalide, veuillez réessayer.";
	}
	
	if (!isset($_POST["title"]) || !is_string($_POST["title"]) || empty($_POST["title"])) {
		$messages[] = "Vous devez spécifier le titre de votre sujet.";
	} elseif (strlen($_POST["title"]) < 1 || strlen($_POST["title"]) > 100) {
		$messages[] = "Le titre de votre sujet doit se composer d'au minimum 1 caractères et d'au maximlum 100 acaractères.";
	}
	
	if (!isset($_POST["message"]) || !is_string($_POST["message"]) || empty($_POST["message"])) {
		$messages[] = "Vous devez spécifier le contenu de votre sujet.";
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$topicId = Topic::create($forumId, $_SESSION["userId"], $_POST["title"], $_POST["message"]);
		$messages[] = "Id du topic : $topicId";
	}
}

require "Pages/Forum.php";