<?php
require "Core/Pm.class.php";

$pm = new Pm($match[1]);
if (!$pm->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

if (!$pm->isInPm($_SESSION["userId"])) {
	http_response_code(403);
	require "Handlers/Error.php";
}


$breadcrumb = ["Messages privés", "Message privé #{$match[1]}"];

require "Pages/Pm.php";