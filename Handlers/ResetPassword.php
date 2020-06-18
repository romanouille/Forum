<?php
require "Core/Captcha.class.php";

if ($_SESSION["logged"]) {
	header("Location: /");
	exit;
}

$hash = explode("/", $_SERVER["REQUEST_URI"]);
$hash = end($hash);

$userId = User::getUserIdByResetHash($hash);
if ($userId == 0) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if (count($_POST) > 0) {
	if (!isset($_POST["password"]) || !is_string($_POST["password"]) || empty($_POST["password"])) {
		$messages[] = "Vous devez spécifier votre mot de passe.";
	} elseif (strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 72) {
		$messages[] = "Votre mot de passe doit se composer d'au minimlum 8 caractères et d'au maximum 72 caractères.";
	}
	
	if (!isset($_POST["password2"]) || !is_string($_POST["password2"]) || empty($_POST["password2"])) {
		$messages[] = "Vous devez confirmer votre mot de passe.";
	} elseif (empty($messages) && $_POST["password"] != $_POST["password2"]) {
		$messages[] = "Les deux mots de passe ne correspondent pas.";
	}
	
	if (empty($messages)) {
		$_SESSION = [
			"logged" => true,
			"userId" => $userId,
			"username" => User::getUsernameById($userId)
		];
		header("Location: /");
		exit;
	}
}



$breadcrumb = ["Mon compte", "Réinitialiser mon mot de passe"];

require "Pages/ResetPassword.php";