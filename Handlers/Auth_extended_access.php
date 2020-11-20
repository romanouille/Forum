<?php
if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

if ($userData["admin"] == 0) {
	http_response_code(403);
	require "Handlers/Error.php";
} elseif ($sessionData["admin"] > 0) {
	$messages[] = "Vous êtes connecté en accès étendu.";
	$success = true;
} else {
	if (count($_POST) > 0) {
		$messages = [];
		$_POST = array_map(function($a) { return is_string($a) ? trim($a) : $a; }, $_POST);
		
		if (!isset($_POST["password"]) || !is_string($_POST["password"]) || empty($_POST["password"])) {
			$messages[] = "Vous devez spécifier votre mot de passe d'accès étendu.";
		}
		
		if (!isset($_POST["token"]) || !is_string($_POST["token"]) || $_POST["token"] != $hash) {
			$messages[] = "Le formulaire est invalide.";
		}
		
		if (empty($messages)) {
			$password = $user->getExtendedAccessPassword();
			if ($password == $_POST["password"]) {
				$session->setAdminValue($userData["admin"]);
				$messages[] = "Votre session est désormais en accès étendu.";
				$success = true;
			} else {
				$messages[] = "Le mot de passe spécifié est incorrect.";
			}
		}
	}
}

$breadcrumb = ["Accès étendu"];

require "Pages/Auth_extended_access.php";