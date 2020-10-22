<?php
require "Core/Sticker.class.php";

if (!isset($_POST["tags"]) || !is_string($_POST["tags"]) || empty(trim($_POST["tags"]))) {
	$result["message"] = "Le paramètre tags est manquant.";
	http_response_code(400);
	exit;
}

$result = Sticker::search(trim($_POST["tags"]));