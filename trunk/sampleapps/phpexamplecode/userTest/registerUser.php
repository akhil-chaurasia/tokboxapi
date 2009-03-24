<?php
	require_once '../SDK/TokBoxApi.php';
	require_once '../Test_Config.php';	
	
	try {
	
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
		
		$result = $apiObj->registerUser(Test_Config::getRegisterEmail(), Test_Config::getRegisterLastName(), Test_Config::getRegisterFirstName());

		header("content-type: text/xml");
		echo $result;
	
	} catch(Exception $e) {
		echo $e->getMessage();
	}
