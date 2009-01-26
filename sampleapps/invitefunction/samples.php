<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>TokBox API | Call Widget Sample Application</title>
	<script src="SDK/js/TokBoxScript.js"></script>
</head>
<body>
	<div id="callbox">
<?php
		require_once 'Site_Config.php';
		require_once 'SDK/TokBoxCall.php';
		require_once 'SDK/TokBoxUser.php';

		try {
	        $userObj = TokBoxUser::createGuest();
	        $jabberId = $userObj->getJabberId();
			$secret = $userObj->getSecret();

			$callId = TokBoxCall::createCall($userObj);//guest access to a call
			$inviteId = TokBoxCall::generateInvite($userObj, Site_Config::CALLEE_JABBERID, $callId);		
		} catch(Exception $e) {
			echo $e->getMessage();
		}
?>
<object type="application/x-shockwave-flash"
        data="http://sandbox.tokbox.com/vc/<?php echo $callId; ?>"
        width="500" height="350" id="tbx_call">
        <param name="movie" value="http://sandbox.tokbox.com/vc/<?php echo $callId; ?>" />
        <param name="allowFullScreen" value="true" />
        <param name="allowScriptAccess" value="always" />
</object>



<object width="425" height="368">
	<param name="movie" value="http://sandbox.tokbox.com/vc/<?php echo $callId; ?>"></param>				 
	<param name="allowFullScreen" value="true" />
	<embed src="http://sandbox.tokbox.com/vc/<?php echo $callId; ?>" type="application/x-shockwave-flash" 
		allowfullscreen="true" width="500" height="350" id="tbx_call">
	</embed>
</object>

	</div>
	<div id="inviteRow">
		<form>
<?php echo "\t\t<input type=\"button\" name=\"inviteUser\" value=\"".Site_Config::CALLEE_DISPLAYNAME."\" onClick=\"sendInvite('".Site_Config::CALLEE_USERID."','".Site_Config::CALLEE_DISPLAYNAME."','".Site_Config::CALLEE_JABBERID."','".$inviteId."');\">\n";?>
		</form>
	</div>
</body>
</html>