<?php
require "Core/Captcha.class.php";
require "Core/Forum.class.php";
require "Core/Poll.class.php";
require "Core/Topic.class.php";

$forumId = Forum::getIdByName($match[1]);
if ($forumId < 1) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$page = isset($match[2]) ? $match[2] : 1;

$forum = new Forum($forumId);
if (!$forum->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$forumName = $forum->getName();
if (strtolower($forumName) != $match[1]) {
	http_response_code(404);
	require "Handlers/Error.php";
}


if (isset($match[3]) && isset($match[4]) && is_string($match[3]) && is_string($match[4])) {
	$isSearch = true;
	// C'est une recherche
	
	$searchText = $match[3];
	
	if ($match[4] == "title") {
		$searchType = 1;
	} elseif ($match[4] == "author") {
		$searchType = 2;
	} else {
		http_response_code(400);
		require "Handlers/Error.php";
	}
	
	$topics = $forum->getTopics($page, $searchType, $searchText);
	$pagesNb = $forum->getPagesNb($searchType, $searchText, $forumId);
} else {
	$topics = $forum->getTopics($page);
	$pagesNb = $forum->getPagesNb();
	$isSearch = false;
}

if ($page > $pagesNb && $page != 1) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if ($_SESSION["logged"] && count($_POST) > 0) {
	$messages = [];
	$poll = false;
	$_POST = array_map(function($a) { return is_string($a) ? trim($a) : $a; }, $_POST);
	
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
	
	if (isset($_POST["poll_question"]) && is_string($_POST["poll_question"]) && !empty($_POST["poll_question"]) && strlen($_POST["poll_question"]) <= 255) {
		if (!isset($_POST["poll_points"]) || !is_string($_POST["poll_points"]) || !is_numeric($_POST["poll_points"]) || $_POST["poll_points"] < 1) {
			$messages[] = "Vous devez spécifier le nombre de points nécessaire pour répondre à votre sondage.";
		}
		
		if (!isset($_POST["poll_responses"]) || !is_array($_POST["poll_responses"]) || empty($_POST["poll_responses"])) {
			$messages[] = "Vous devez spécifier les réponses à votre sondage.";
		} else {
			foreach ($_POST["poll_responses"] as $nb=>$value) {
				if (!is_string($value) || empty($value) || strlen($value) > 255) {
					unset($_POST["poll_responses"][$nb]);
				}
			}
			
			if (count($_POST["poll_responses"]) < 2) {
				$messages[] = "Votre sondage doit se composer d'au minimum 2 réponses.";
			} else {
				$poll = true;
			}
		}
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$topicId = Topic::create($forumId, $_SESSION["userId"], $_POST["title"], $_POST["message"]);
		if (!empty($_POST["poll_question"])) {
			Poll::create($topicId, $_POST["poll_question"], $_POST["poll_points"], $_POST["poll_responses"]);
		}
		
		header("Location: /forums/{$match[1]}/$topicId-1-".slug($_POST["title"]));
		exit;
	}
}


$breadcrumb = ["Forum ".htmlspecialchars($forumName), "Page $page"];

require "Pages/Forum.php";