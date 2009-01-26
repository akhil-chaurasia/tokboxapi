<?php
require_once('SDK/TokBoxUser.php');
require_once('SDK/TokBoxCall.php');

	try {
	        $userObj = TokBoxUser::createGuest();
		$callid =  TokBoxCall::createCall($userObj);//guest access to a call
		$jid = $userObj->getJabberId();
		$userid = substr($jid, 0, strpos($jid, '@'));

		$inviteid = TokBoxCall::generateInvite($userObj, $callid, "565954@jabber.dev.tokbox.com");
	} catch(Exception $e) {
		echo "Please report this error:".$e->getMessage()." to an administrator";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Proctor 1</title>
	<script type="text/javascript" src="js/TokBoxScript.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			setTimeout("sendInvite('565954','Student for Proctor 1','565954@jabber.dev.tokbox.com', '<?php echo $inviteid;?>');", 10000);
		});
	</script>
</head>
<body>
	<?php echo TokBoxCall::generateEmbedCode($callid, 600, 450, 'js/swfobject.js'); ?>
</body>
</html>
