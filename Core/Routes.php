<?php
$routes = [
	"#^\/$#" => "Home.php",
	"#^\/account\/login$#" => "Login.php",
	"#^\/account\/register$#" => "Register.php",
	"#^\/account\/forgot-password$#" => "ForgotPassword.php",
	"#^\/account\/reset\/(.+)$#" => "ResetPassword.php"
];