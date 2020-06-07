<?php
$routes = [
	"#^\/$#" => "Home.php",
	"#^\/account\/login$#" => "Login.php",
	"#^\/account\/register$#" => "Register.php",
	"#^\/account\/forgot-password$#" => "ForgotPassword.php",
	"#^\/account\/reset\/(.+)$#" => "ResetPassword.php",
	"#^\/account\/logout\?hash=(.+)$#" => "Logout.php",
	"#^\/forums\/([0-9]+)-([0-9]+)$#" => "Forum.php",
	"#^\/forums\/([0-9]+)-([0-9]+)-([0-9]+)-(.+)$#" => "Topic.php"
];