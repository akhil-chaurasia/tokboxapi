<?php
	/*
	* This script handles the generation of a guest user who then generates a call id
	* for the party room which is being created. It then puts the relevant information
	* into the database to present in other pages.
	*
	* @author Melih Onvural melih@tokbox.com
	*/

	if(isset($_POST['submit'])) {
		require_once 'SDK/TokBoxCall.php';
		require_once 'SDK/TokBoxUser.php';
		require_once 'Site_Config.php';

		@$subject = trim($_POST['subject']);
		if (!isset($subject) || strlen($subject) == 0) {
			$subject = "Open Chat";
		}
		
		$inList = isset($_POST['inList']) ? 1 : 0;

		try {
	        	$userObj = TokBoxUser::createGuest(); //generates a guest user who is needed to generate a call
			$callid =  TokBoxCall::createCall($userObj, true); //generates a persistent (hence the true) call id

			$dbConn = @mysql_connect(Site_Config::DB_HOST, Site_Config::DB_USER, Site_Config::DB_PASSWORD) or die(mysql_error());
			mysql_select_db(Site_Config::DB_DATABASE, $dbConn) or die(mysql_error());
			$query = "INSERT INTO calls(callid, subject, created, list) VALUES('$callid', '$subject', NOW(), '$inList')";
			
			mysql_query($query, $dbConn) or die(mysql_error());
		} catch(Exception $e) {
			trigger_error($e->getMessage());
		}
	}
	
	header("Location:".Site_Config::VIDEO_CALL_ROOM."?callid=".$callid);
?>
