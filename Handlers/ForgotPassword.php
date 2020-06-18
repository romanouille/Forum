<?php
require "Core/Captcha.class.php";
require "Core/Mail.class.php";

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
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$userId = User::getIdByUsername($_POST["username"]);
		$user = new User($userId);
		$hash = $user->generatePasswordResetHash();
		
		Mail::send($user->getEmail(), "Réinitialisation de votre mot de passe", "Bonjour,\n\nVotre lien de réinitialisation de mot de passe Avenoel est : https://avenoel.io/account/reset/$hash");
		$messages[] = "Un mail de réinitialisation de mot de passe vous a été envoyé.";
	}
}

$breadcrumb = ["Mon compte", "Mot de passe oublié"];

require "Pages/ForgotPassword.php";