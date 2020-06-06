<?php
class Captcha {
	private static $publicKey = "6LcB-AAVAAAAAHbASxkzohCi209I_Voy7fQ2SouZ", $privateKey = "6LcB-AAVAAAAAMMrPhZxxHxmeIxQZZogobc3J3Ek";

	public static function generate(bool $center = false) {
		echo "<div class=\"g-recaptcha".($center ? " captcha-center" : "")."\" data-sitekey=\"".self::$publicKey."\"></div>\n";
	}

	public static function check() : bool {		
		if (!isset($_POST["g-recaptcha-response"]) || empty($_POST["g-recaptcha-response"])) {
			return false;
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($curl, CURLOPT_ENCODING, "gzip");
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_USERAGENT, "");
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["secret" => self::$privateKey, "response" => $_POST["g-recaptcha-response"], "remoteip" => $_SERVER["REMOTE_ADDR"]]));
		curl_setopt($curl, CURLOPT_HTTPHEADER, ["X-Forwarded-For: {$_SERVER["REMOTE_ADDR"]}"]);
		curl_setopt($curl, CURLOPT_TIMEOUT, 3);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec($curl);
		curl_close($curl);

		$page = @json_decode($page, true);
		
		if (empty($page) || !isset($page["success"]) || !is_bool($page["success"])) {
			return true;
		}

		return $page["success"];
	}
}