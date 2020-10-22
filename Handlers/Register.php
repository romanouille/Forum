<?php
require "Core/Captcha.class.php";

if ($userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

if (count($_POST) > 0) {
	$messages = [];
	$_POST = array_map(function($a) { return is_string($a) ? trim($a) : $a; }, $_POST);
	
	if (!isset($_POST["username"]) || !is_string($_POST["username"]) || empty($_POST["username"])) {
		$messages[] = "Vous devez spécifier votre pseudo.";
	} elseif (!preg_match("#^([A-Za-z0-9-_\[\]]{3,20}+)$#", $_POST["username"])) {
		$messages[] = "Votre pseudo doit se composer d'au minimum 3 caractères et d'au maximum 20 caractères.";
	} elseif (User::exists($_POST["username"])) {
		$messages[] = "Ce pseudo existe déjà, veuillez en choisir un autre.";
	}
	
	if (!isset($_POST["email"]) || !is_string($_POST["email"]) || empty($_POST["email"])) {
		$messages[] = "Vous devez spécifier votre adresse e-mail.";
	} elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$messages[] = "L'adresse e-mail que vous avez spécifié est incorrecte.";
	} elseif (User::emailExists($_POST["email"])) {
		$messages[] = "Il existe déjà un compte avec cette adresse e-mail, veuillez en choisir une autre.";
	}
	
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
	
	if (!Captcha::check()) {
		$messages[] = "Vous devez prouver que vous n'êtes pas un robot.";
	}
	
	if (empty($messages)) {
		$userId = User::create($_POST["username"], $_POST["email"], $_POST["password"]);
		Session::create($userId);
		
		header("Location: /forums/blabla-general/1");
		exit;
	}
}


$breadcrumb = ["Mon compte", "Créer un compte"];

require "Pages/Register.php";