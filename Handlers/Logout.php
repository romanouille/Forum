<?php
if (isset($_GET["hash"]) && is_string($_GET["hash"]) && $_GET["hash"] == $hash) {
	session_destroy();
	header("Location: /");
	exit;
}