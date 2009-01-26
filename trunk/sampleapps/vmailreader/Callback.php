<?php

require_once 'Site_Config.php';

class Callback {

	public static function generateCookie($jabberId, $secret) {
		$id = trim($jabberId.":".$secret);
		$cookie = setCookie("tokboxId", $id, 0);
		$url = Site_Config::BASE_SITE."index.php";

		header("Location: $url");
	}
}

if (isset($_GET['oauth_jabberId'])) {
	Callback::generateCookie(trim($_GET['oauth_jabberId']), trim($_GET['oauth_secret']));
}