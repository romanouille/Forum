<?php
error_reporting(-1);
ini_set("display_errors", true);
set_time_limit(10);
$devMode = $_SERVER["REMOTE_ADDR"] == "127.0.0.1";

setlocale(LC_ALL, "fr-fr");
set_include_path("../");

require "Core/Functions.php";
require "Core/Routes.php";
require "Core/User.class.php";
require "Core/Init.php";

foreach ($routes as $route=>$handlerName) {
	if (preg_match($route, $_SERVER["REQUEST_URI"], $match)) {
		unset($match[0]);
		
		require "Handlers/$handlerName";
		exit;
	}
}

http_response_code(404);
require "Handlers/Error.php";