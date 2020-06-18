<?php
require "Core/Captcha.class.php";
require "Core/Pm.class.php";


$page = isset($match[1]) ? $match[1] : 1;

if (count($_POST) > 0) {
	$messages = [];
	$_POST = array_map("trim", $_POST);
	
	if (!isset($_POST["hash"]) || !is_string($_POST["hash"]) || $_POST["hash"] != $hash) {
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
			} elseif (strtolower($receiverUsername) == strtolower($_SESSION["username"])) {
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
		$usersId = [$_SESSION["userId"]];
		
		foreach ($receivers as $receiver) {
			$usersId[] = User::getIdByUsername($receiver);
		}
		
		$pmId = Pm::create($_SESSION["userId"], $_POST["title"], $usersId, $_POST["message"]);
		
		header("Location: /pm/$pmId-1-".slug($_POST["title"]));
		exit;
	}
}

$pagesNb = $user->getPmListPagesNb();

if ($page > $pagesNb && $page > 1) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$pmList = $user->getPmList($page);


require "Pages/Pm_list.php";