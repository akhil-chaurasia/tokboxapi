<?php
	require_once '../SDK/TokBoxCall.php';
	require_once '../SDK/TokBoxUser.php';

	try {
		$userObj = TokBoxUser::createGuest();
		$callid =  TokBoxCall::createCall($userObj);//guest access to a call
		$callUrl = TokBoxCall::generateLink($callid);//generate call URL
	} catch(Exception $e) {
		echo $e->getMessage();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Testing Call Embed as Guest</title>
	
</head>
<body>
	<?php
		//print out URL, and embed code
		echo "<a href=\"$callUrl\">$callUrl</a><br/>";
		echo TokBoxCall::generateEmbedCode($callid, "425", "320", "../SDK/js/swfobject.js");
	?>
</body>
</html>