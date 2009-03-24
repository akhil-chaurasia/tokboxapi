<?php
	require_once '../SDK/TokBoxCall.php';
	require_once '../SDK/TokBoxUser.php';
	require_once '../Test_Config.php';
 
	try {
		$apiObj = TokBoxUser::createUser(Test_Config::TEST_JABBERID, Test_Config::TEST_ACCESS_SECRET);
 
		$callid =  TokBoxCall::createCall($apiObj); //guest access to a call
		$callUrl = TokBoxCall::generateLink($callid); //generate call URL
	} catch(Exception $e) {
		echo $e->getMessage();
	}
 
	//print out URL, and embed code
	echo "<a href=\"$callUrl\">$callUrl</a><br/>";
	echo TokBoxCall::generateEmbedCode($callid, "425", "320", "../SDK/js/swfobject.js");