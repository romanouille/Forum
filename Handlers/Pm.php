<?php
require "Core/Captcha.class.php";
require "Core/Message.class.php";
require "Core/Pm.class.php";

if (!$userLogged) {
	header("Location: /forums/blabla-general/1");
	exit;
}

$pm = new Pm($match[1]);
if (!$pm->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if (!$pm->isInPm($sessionData["user_id"])) {
	http_response_code(403);
	require "Handlers/Error.php";
}

$pmId = $match[1];
$page = $match[2];
$totalPages = $pm->getPagesNb();

if ($page > $totalPages) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$pmMessages = $pm->getMessages($page);
$pmTitle = $pm->getTitle();

$breadcrumb = ["Messages privés", "$pmTitle"];

require "Pages/Pm.php";