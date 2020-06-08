<?php
/**
 * Génère le slug d'une chaîne
 *
 * @param string $text Chaîne
 *
 * @return string Slug
 */
function slug(string $text) : string {
	$chars = str_split("abcdefghijklmnopqrstuvwxyz0123456789-");
	$text = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $text);
	$text = str_replace(" ", "-", strtolower($text));

	$text = str_split($text);
	
	foreach ($text as $id=>$char) {
		if (!in_array($char, $chars)) {
			unset($text[$id]);
		}
	}

	$text = implode("", $text);

	return $text;
}

function parseMessage(string $message) : string {
	preg_match_all("`\[quote:(.+)]`isU", $message, $quotes);
	
	foreach ($quotes[1] as $originalQuote=>$messageId) {
		$quote = new Message($messageId);
		if ($quote->exists() && !$quote->isDeleted()) {
			$data = $quote->load();
			$message = str_replace($quotes[0][$originalQuote], "<blockquote><b>Le ".date("d/m/Y à H:i:s").", {$data["username"]} a écrit :</b><br><br>{$data["message"]}</blockquote>", $message);
		} else {
			$message = str_replace($quotes[0][$originalQuote], "<blockquote><b>Message supprimé</b></blockquote>", $message);
		}
	}
	
	return $message;
}