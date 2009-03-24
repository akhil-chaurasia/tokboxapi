<?php
	require_once '../SDK/TokBoxApi.php';
	require_once '../Test_Config.php';	
	
	try {
	
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$apiObj->updateToken($apiObj->getRequestToken(API_Config::CALLBACK_URL));
		$apiObj->loginUser();

	} catch(Exception $e) {
		echo $e->getMessage();
	}