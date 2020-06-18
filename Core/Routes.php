<?php
$routes = [
	"#^\/$#" => "Home.php",
	"#^\/account\/login$#" => "Login.php",
	"#^\/account\/register$#" => "Register.php",
	"#^\/account\/forgot-password$#" => "ForgotPassword.php",
	"#^\/account\/reset\/(.+)$#" => "ResetPassword.php",
	"#^\/account\/logout\?hash=(.+)$#" => "Logout.php",
	"#^\/forums\/(.+)/([0-9]+)$#" => "Forum.php",
	"#^\/forums\/(.+)\/([0-9]+)\?mode=search&content=(.+)&type=(.+)$#" => "Forum.php",
	"#^\/forums/([0-9]+)-([0-9]+)-([0-9]+)-(.+)$#" => "Topic.php",
	"#^\/pm\/$#" => "Pm_list.php",
	"#^\/pm\/([0-9]+)$#" => "Pm_list.php",
	"#^\/pm\/([0-9]+)-([0-9]+)-(.+)$#" => "Pm.php",
	"#^\/api\/polls\/create$#" => "Api_poll_create.php"
];