<?php

require_once('TokBoxApi.php');
require_once('API_Config.php');

class TokBoxUser {
	public static function createGuest() {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$createGuest = $apiObj->createGuestUser(API_Config::PARTNER_KEY);

		if(!$createGuest) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}

		$createGuestXml = simplexml_load_string($createGuest, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($createGuestXml->error) {
			throw new Exception($createGuestXml->error, (int)$createGuestXml->error['code']);
		}

		$apiObj->setJabberId($createGuestXml->createGuest->jabberId);
		$apiObj->setSecret($createGuestXml->createGuest->secret);

		return $apiObj;
	}

	public static function createUser($jabberId, $accessSecret) {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$valid = $apiObj->validateAccessToken($accessSecret, $jabberId);

		if(!$valid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$validXml = simplexml_load_string($valid, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($validXml->validateAccessToken->isValid == 'false')
			throw new Exception("The Jabber ID and Access Secret combination you passed in are not valid");

		$apiObj->setJabberId($jabberId);
		$apiObj->setSecret($accessSecret);

		return $apiObj;	
	}

	public static function loginUser() {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$apiObj->updateToken($apiObj->getRequestToken(API_Config::CALLBACK_URL));
		$apiObj->loginUser();
	}

	public static function registerUser($email, $lastname, $firstname) {
		$apiObj = new TokBoxAPI(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$register = $apiObj->registerUser($email, $lastname, $firstname);

		if(!$register) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}

		$registerXml = simplexml_load_string($register, 'SimpleXMLElement', LIBXML_NOCDATA);

		if(isset($registerXml->error)) {
			throw new Exception($registerXml->error, (int)$registerXml->error['code']);
		}

		$jabberId = $registerXml->registerUser->jabberId;
		$accessSecret = $registerXml->registerUser->secret;

		return self::createUser($jabberId, $accessSecret);
	}
}
