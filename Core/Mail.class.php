<?php
class Mail {
	const mailDomain = "mail.skymote.io", websiteName = "Skymote", mailSource = "ne-pas-repondre@mail.skymote.io", mailPrivateApiKey = "key-b28e6f0e03ad376bfe000e261c2dbd9f";
	
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
		global $websiteName;
		
		$post = [
			"from" => self::websiteName." <".self::mailSource.">",
			"to" => $to,
			"subject" => $subject,
			"text" => $body
		];
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.eu.mailgun.net/v3/".self::mailDomain."/messages");
		curl_setopt($curl, CURLOPT_USERAGENT, "");
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_USERPWD, "api:".self::mailPrivateApiKey);
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