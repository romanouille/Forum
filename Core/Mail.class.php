<?php
class Mail {
	/**
	 * Envoie un mail
	 *
	 * @param string $to Destinataire
	 * @param string $subject Sujet du mail
	 * @param string $body Contenu du mail
	 *
	 * @return bool Résultat
	 */
	public static function send(string $to, string $subject, string $body) : bool {
		global $config;
		
		$post = [
			"from" => "{$config["mail"]["website_name"]} <{$config["mail"]["mail_source"]}>",
			"to" => $to,
			"subject" => $subject,
			"text" => $body
		];
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.eu.mailgun.net/v3/".self::mailDomain."/messages");
		curl_setopt($curl, CURLOPT_USERAGENT, "");
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_USERPWD, "api:{$config["mail"]["private_key"]}");
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		return $code == 200;
	}
}