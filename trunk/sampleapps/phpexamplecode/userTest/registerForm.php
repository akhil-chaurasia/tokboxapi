<?php

	if(isset($_POST['submit'])) {
	
		require_once '../SDK/TokBoxApi.php';
		require_once '../Test_Config.php';	
	
		try {
			$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
		
			$result = $apiObj->registerUser($_POST['email'], $_POST['lastname'], $_POST['firstname']);
			$resultxml = simplexml_load_string($result);

			if($resultxml->error)
				echo "There was an error of type: ".$resultxml->error['type']." with message ".$resultxml->error;
			else {
				echo "User ".$resultxml->registerUser->firstname." ".$resultxml->registerUser->lastname." was created with:<br/>";
				echo "     Jabber ID: ".$resultxml->registerUser->jabberId."<br/>";
				echo "     Secret: ".$resultxml->registerUser->secret."<br/>";
			}
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Register User</title>
</head>
<body>
	<form name="registerForm" method="POST" action="registerForm.php">
		<table>
			<tr>
				<td><label>First Name:</label></td>
				<td><input type="text" name="firstname" /></td>
			</tr>
			<tr>
				<td><label>Last Name:</label></td>
				<td><input type="text" name="lastname" /></td>			
			</tr>
			<tr>
				<td><label>E-Mail:</label></td>
				<td><input type="text" name="email" /></td>			
			</tr>
			<tr>
				<td colspan="2" align="right"><input type="submit" name="submit" value="Submit" /></td>
			</tr>
		</table>
	</form>
</body>
</html>
