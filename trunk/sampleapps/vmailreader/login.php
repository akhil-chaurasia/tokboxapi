<?php
require_once 'Site_Config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>TokBox VMail Sample Application</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="container">
		<div id="header"><h1>TokBox VMail</h1></div>
		<div id="loginNeeded">
			<p><h2>You are not logged in.</h2></p>
			<p>Please either <a href="sendOauth.php">login</a> or <a href="register.php">register</a>.</p>
		</div>
	</div>
</body>
</html>