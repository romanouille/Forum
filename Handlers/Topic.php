<?php
require "Core/Forum.class.php";
require "Core/Topic.class.php";

$forumId = $match[1];
$topicId = $match[2];
$page = $match[3];

$forum = new Forum($forumId);
if (!$forum->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}

$topic = new Topic($topicId);
if (!$topic->exists()) {
	http_response_code(404);
	require "Handlers/Error.php";
}


require "Pages/Topic.php";