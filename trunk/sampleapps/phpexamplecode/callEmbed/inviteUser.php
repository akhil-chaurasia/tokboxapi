<?php
	require_once '../SDK/TokBoxCall.php';
	require_once '../SDK/TokBoxUser.php';

	try {
		$userObj = TokBoxUser::createGuest();
		$callid =  TokBoxCall::createCall($userObj);//guest access to a call
		$inviteId = TokBoxCall::generateInvite($userObj, '547747@jabber.dev.tokbox.com');
	} catch(Exception $e) {
		echo $e->getMessage();
	}
?>
<html>
	<head>
		<title>TokBox: Talk to the World</title>
		<script language="javascript" src="../SDK/js/TokBoxScript.js"></script>
	</head>
	<body>
		<div style="width:100px;height:30px;background-color:gold;color:blue;line-height:30px;text-align:center;margin:10px;" onclick="sendInvite('547227', 'QA 101', '547227@jabber.dev.tokbox.com', '<?php echo $inviteId; ?>')">
			Invite User
		</div>

		<?php
			echo TokBoxCall::generateEmbedCode($callid, "425", "320", "../SDK/js/swfobject.js");		
		?>
	</body>
</html>
