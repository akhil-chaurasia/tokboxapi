<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
        <title>Sample Application - TokBox Developer API - Call Generator</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<?php
		require_once 'SDK/TokBoxCall.php';
		
		if (isset($_GET['callid'])) {
			$callId = $_GET['callid'];
			$htmlCode = TokBoxCall::generateEmbedCode($callId, $width="800", $height="600"); //This will generate the code necessary to embed the call widget onto your site. The call id is pulled from the GET parameter, which is passed in from index.php

			echo $htmlCode;
		}
		else {
			echo "<h2>This video room is no longer active. You can create a new room <here></h2>";
		}
	?>
</body>
</html>
