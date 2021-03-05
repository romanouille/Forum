<?php
$routes = [
	"#^\/$#" => "Home.php",
	"#^\/account\/login$#" => "Login.php",
	"#^\/account\/register$#" => "Register.php",
	"#^\/account\/forgot-password$#" => "Forgot_password.php",
	"#^\/account\/reset\/(.+)$#" => "Reset_password.php",
	"#^\/account\/logout\?hash=(.+)$#" => "Logout.php",
	"#^\/forums\/(.+)/([0-9]+)$#" => "Forum.php",
	"#^\/forums\/(.+)\/([0-9]+)\?mode=search&content=(.+)&type=(.+)$#" => "Forum.php",
	"#^\/forums\/(.+)\/([0-9]+)-([0-9]+)-(.+)\?poll_response=([0-9]+)&token=(.+)$#" => "Topic.php",
	"#^\/forums\/(.+)\/([0-9]+)-([0-9]+)-(.+)\?edit_message=([0-9]+)#" => "Topic_edit_message.php",
	"#^\/forums\/(.+)\/([0-9]+)-([0-9]+)-(.+)$#" => "Topic.php",
	"#^\/pm\/$#" => "Pm_list.php",
	"#^\/pm\/([0-9]+)$#" => "Pm_list.php",
	"#^\/pm\/([0-9]+)-([0-9]+)$#" => "Pm.php",
	"#^\/api\/stickers\/search$#" => "Api_stickers_search.php",
	"#^\/account\/extendedaccess$#" => "Auth_extended_access.php",
	"#^\/api\/moderation\/kick$#" => "Api_moderation_kick.php",
	"#^\/api\/moderation\/unkick$#" => "Api_moderation_unkick.php",
	"#^\/api\/moderation\/messages\/delete$#" => "Api_moderation_messages_delete.php",
	"#^\/api\/moderation\/messages\/restore$#" => "Api_moderation_messages_restore.php",
	"#^\/api\/moderation\/topics\/delete$#" => "Api_moderation_topics_delete.php",
	"#^\/api\/moderation\/topics\/restore$#" => "Api_moderation_topics_restore.php",
	"#^\/api\/moderation\/topics\/pin$#" => "Api_moderation_topics_pin.php",
	"#^\/api\/moderation\/topics\/unpin$#" => "Api_moderation_topics_unpin.php",
	"#^\/api\/moderation\/topics\/lock$#" => "Api_moderation_topics_lock.php",
	"#^\/api\/moderation\/topics\/unlock$#" => "Api_moderation_topics_unlock.php"
];