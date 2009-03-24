<?php
	require_once '../SDK/TokBoxApi.php';
	require_once '../Test_Config.php';	
	
	try {
	
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
		$apiObj->setJabberId(Test_Config::TEST_JABBERID);
		$apiObj->setSecret(Test_Config::TEST_ACCESS_SECRET);

		$result = $apiObj->joinCall(Test_Config::TEST_INVITEID);
	
		header("content-type: text/xml");
		echo $result;
	
	} catch(Exception $e) {
		echo $e->getMessage();
	}