<?php
	require_once '../SDK/TokBoxApi.php';
	require_once '../Test_Config.php';	
	
	try {
	
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
		
		$result = $apiObj->validateAccessToken(Test_Config::TEST_ACCESS_SECRET, Test_Config::TEST_JABBERID);
	
		header("content-type: text/xml");
		echo $result;
	
	} catch(Exception $e) {
		echo $e->getMessage();
	}