<?php
	if(isset($_POST['submit'])) {
		require_once 'SDK/TokBoxUser.php';
		require_once 'Callback.php';
	
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
	
		$apiObj = TokBoxUser::registerUser($email, $lastname, $firstname);
	
		Callback::generateCookie($apiObj->getJabberId(), $apiObj->getSecret());
	}
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
		<div id="registerForm">
			<form name="registerForm" action="register.php" method="POST">
				<table cellpadding="2" cellspacing="5">
					<tr>
						<td>First Name:</td>
						<td><input type="text" name="firstname" /></td>
					</tr>
					<tr>
						<td>Last Name:</td>
						<td><input type="text" name="lastname" /></td>					
					</tr>
					<tr>
						<td>E-Mail:</td>
						<td><input type="text" name="email" /></td>					
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Submit!" name="submit" /></td>					
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>