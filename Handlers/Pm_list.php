<?php
require "Core/Captcha.class.php";
require "Core/Pm.class.php";

if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

$page = isset($match[1]) ? $match[1] : 1;

if (count($_POST) > 0) {
	$messages = [];
	$_POST = array_map(function($a) { return is_string($a) ? trim($a) : $a; }, $_POST);
	
	if (!isset($_POST["token"]) || !is_string($_POST["token"]) || $_POST["token"] != $hash) {
		$messages[] = "Le formulaire est invalide, veuillez réessayer.";
	}
	
	if (!isset($_POST["title"]) || !is_string($_POST["title"]) || empty($_POST["title"])) {
		$messages[] = "Vous devez spécifier le titre de votre MP.";
	} elseif (strlen($_POST["title"]) < 1 || strlen($_POST["title"]) > 100) {
		$messages[] = "Le titre de votre MP doit se composer d'au minimum 1 caractère et d'au maximlum 100 caractères.";
	}
	
	if (!isset($_POST["receivers"]) || !is_string($_POST["receivers"]) || empty($_POST["receivers"])) {
		$messages[] = "Vous devez spécifier les destinataires de votre message privé.";
	} else {
		$receivers = explode(",", $_POST["receivers"]);
		
		foreach ($receivers as $receiverUsername) {
			if (!User::exists($receiverUsername)) {
				$messages[] = "Le pseudo ".htmlspecialchars($receiverUsername)." n'existe pas.";
			} elseif (strtolower($receiverUsername) == strtolower($userData["username"])) {
				$messages[] = "Vous ne pouvez pas envoyer de MP à vous-même.";
			}
		}
	}
	
	if (!isset($_POST["message"]) || !is_string($_POST["message"]) || empty($_POST["message"])) {
		$messages[] = "Vous devez spécifier le contenu de votre message.";
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$receivers = explode(",", $_POST["receivers"]);
		$usersId = [$sessionData["user_id"]];
		
		foreach ($receivers as $receiver) {
			$usersId[] = User::getIdByUsername($receiver);
		}
		
		$pmId = Pm::create($sessionData["user_id"], $_POST["title"], $usersId, $_POST["message"]);
		
		header("Location: /pm/$pmId-1");
		exit;
	}
}

$pagesNb = $user->getPmListPagesNb();

if ($page > $pagesNb && $page > 1) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$pmList = $user->getPmList($page);

$breadcrumb = ["Messages privés", "Page $page"];


require "Pages/Pm_list.php";