<?php

	if ($_POST['submit']) {
		require_once '../SDK/TokBoxApi.php';
		require_once '../Test_Config.php';	
	
		try {
	
			$startDate = trim($_POST['startDate']);
			$endDate = trim($_POST['endDate']);
			
			$daterange = "$startDate - $endDate";
			
			$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
			$apiObj->setJabberId(Test_Config::TEST_JABBERID);
			$apiObj->setSecret(Test_Config::TEST_ACCESS_SECRET);		
		
			$result = $apiObj->getFeed($apiObj->getJabberId(), "all", 0, 10, "dt", $daterange);

			header("content-type: text/xml");
			echo $result;

		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}