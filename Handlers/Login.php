<?php
require "Core/Captcha.class.php";

if ($_SESSION["logged"]) {
	header("Location: /");
	exit;
}

if (count($_POST) > 0) {
	$messages = [];
	
	if (!isset($_POST["username"]) || !is_string($_POST["username"]) || empty($_POST["username"])) {
		$messages[] = "Vous devez spécifier votre pseudo.";
	} elseif (!preg_match("#^([A-Za-z0-9-_\[\]]{3,20}+)$#", $_POST["username"])) {
		$messages[] = "Votre pseudo doit se composer d'au minimum 3 caractères et d'au maximum 20 caractères.";
	} elseif (!User::exists($_POST["username"])) {
		$messages[] = "Ce pseudo n'existe pas.";
	}
	
	if (!isset($_POST["password"]) || !is_string($_POST["password"]) || empty($_POST["password"])) {
		$messages[] = "Vous devez spécifier votre mot de passe.";
	} elseif (strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 72) {
		$messages[] = "Votre mot de passe doit se composer d'au minimlum 8 caractères et d'au maximum 72 caractères.";
	}
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$userId = User::getIdByUsername($_POST["username"]);
		$user = new User($userId);
		
		if ($user->checkPassword($_POST["password"])) {
			$_SESSION["logged"] = true;
			$_SESSION["userId"] = $userId;
			
			header("Location: /forum");
			exit;
		} else {
			$messages[] = "Le mot de passe que vous avez spécifié est incorrect.";
		}
	}
}

require "Pages/Login.php";