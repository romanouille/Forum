<?php
function renderPage() {
	global $api, $result;
	
	if ($api) {
		echo json_encode($result);
		exit;
	}
	
	$page = ob_get_contents();
	ob_end_clean();
	
	$page = str_replace("  ", " ", str_replace("	", "", str_replace("\n", "", $page)));
	
	echo $page;
}

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

/**
 * Parse un message
 *
 * @param string $message Message à parser
 *
 * @return string Message parsé
 */
function parseMessage(string $message, int $messageId) : string {
	$message = htmlspecialchars($message);
	
	preg_match_all("`\[quote:([0-9]+)]`isU", $message, $quotes);
	
	foreach ($quotes[1] as $originalQuote=>$messageId) {
		$quote = new Message($messageId);
		if ($quote->exists() && !$quote->isDeleted()) {
			$data = $quote->load();
			$message = str_replace($quotes[0][$originalQuote], "<blockquote><b>Le ".date("d/m/Y à H:i:s").", {$data["username"]} a écrit :</b><br><br>{$data["content"]}</blockquote>", $message);
		} else {
			$message = str_replace($quotes[0][$originalQuote], "<blockquote><b>Cette citation ne peut pas être affichée</b></blockquote>", $message);
		}
	}
	
	preg_match_all("`\[sticker:(.+)]`isU", $message, $stickers);
	foreach ($stickers[1] as $nb=>$id) {
		$sticker = new Sticker($id);
		if (!$sticker->exists()) {
			continue;
		}
		
		$stickerExt = $sticker->getExt();
		$message = str_replace($stickers[0][$nb], "<img src=\"/img/stickers/$id.$stickerExt\">", $message);
	}
	
	$message = preg_replace("#\[b\](.+)\[\/b\]#iUs", '<b>$1</b>', $message);
	$message = preg_replace("#\[i\](.+)\[\/i\]#iUs", '<i>$1</i>', $message);
	$message = preg_replace("#\[s\](.+)\[\/s\]#iUs", '<s>$1</s>', $message);
	$message = preg_replace("#\[u\](.+)\[\/u\]#iUs", '<u>$1</u>', $message);
	
	$message = replace_links($message);
	
	preg_match_all("`>https:\/\/image.noelshack.com\/(.+)<`isU", $message, $stickers);
	foreach ($stickers[0] as $nb=>$sticker) {
		$message = str_replace($sticker, "><img src=\"https://image.noelshack.com/{$stickers[1][$nb]}\"><", $message);
	}
	
	preg_match_all("`>https:\/\/www.noelshack.com\/(.+)<`isU", $message, $stickers);
	foreach ($stickers[0] as $nb=>$sticker) {
		$realUrl = explode("-", $stickers[1][0], 4);
		$message = str_replace($sticker, "><img src=\"https://image.noelshack.com/fichiers/".implode("/", $realUrl)."\"><", $message);
	}
	
	preg_match_all("#\[spoilers\](.+)\[\/spoilers\]#iUs", $message, $quotes);
	foreach ($quotes[0] as $nb=>$quote) {
		$message = str_replace($quote, "<button type=\"button\" class=\"btn waves-effect waves-light\" onclick=\"document.getElementById('spoiler_$messageId').style.display='';this.style.display='none'\">Spoilers</button><span id=\"spoiler_$messageId\" style=\"display:none\">{$quotes[1][$nb]}</span>", $message);
	}
	
	$message = nl2br($message);
	
	return $message;
}

function replace_links( $text ) {    
	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);

	$ret = ' ' . $text;
	
	// Replace Links with http://
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $ret);
	
	// Replace Links without http://
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $ret);

	// Replace Email Addresses
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	$ret = substr($ret, 1);
	
	return $ret;
}