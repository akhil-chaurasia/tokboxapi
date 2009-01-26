<?php
	$jabberId = $_GET['oauth_jabberId'];
	$userId = substr($jabberId, 0, strpos($jabberId, "@"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>TokBox - Who Am I</title>
</head>
<body>
	<h2>Personal Identifying Values</h2>
	<table>
		<tr>
			<td>Jabber ID:</td>
			<td><?php echo $jabberId; ?></td>
		</tr>
		<tr>
			<td>User ID:</td>
			<td><?php echo $userId; ?></td>
		</tr>
	</table>
</body>
</html>