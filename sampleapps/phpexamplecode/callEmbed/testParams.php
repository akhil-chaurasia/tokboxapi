<?php

	$textChat = (isset($_POST['textChat']) && (strlen($_POST['textChat']) > 0)) ? $_POST['textChat'] : "false";
	$inviteButton = (isset($_POST['inviteButton']) && (strlen($_POST['inviteButton']) > 0)) ? $_POST['inviteButton'] : "true";
	$guestList = (isset($_POST['guestList']) && (strlen($_POST['guestList']) > 0)) ? $_POST['guestList'] : "true";
	$observerMode = (isset($_POST['observerMode']) && (strlen($_POST['observerMode']) > 0)) ? $_POST['observerMode'] : "false";
?>
<html>
<head>
	<title>Test Embed Parameters</title>
</head>
<body>
	<form action="testParams.php" method="POST" name="paramsForm">
		<table cellpadding="2" cellspacing="5" border="1">
			<tr>
				<td><label>textChat: Currently <?php echo $_POST['textChat']; ?></label></td>
				<td><label>inviteButton: Currently <?php echo $_POST['inviteButton']; ?></label></td>
				<td><label>guestList: Currently <?php echo $_POST['guestList']; ?></label></td>
				<td><label>observerMode: Currently <?php echo $_POST['observerMode']; ?></label></td>
			</tr>
			<tr>
				<td>
					<select name="textChat">
						<option value="">--</option>
						<option value="true">true</option>
						<option value="false">false</option>
					</select>
				</td>
				<td>
					<select name="inviteButton">
						<option value="">--</option>
						<option value="true">true</option>
						<option value="false">false</option>
					</select>
				</td>
				<td>
					<select name="guestList">
						<option value="">--</option>						
						<option value="true">true</option>
						<option value="false">false</option>
					</select>
				</td>
				<td>
					<select name="observerMode">
						<option value="">--</option>					
						<option value="true">true</option>
						<option value="false">false</option>
					</select>
				</td>
			</tr>
		</table>

		<input type="submit" name="submit" value="Submit" />
	</form>
		
	<object  width="600" height="400">
		<param name="movie" value="http://qa.tokbox.com/vc/ztssmc3hcof1jhl6"></param>
		<param name="allowFullScreen" value="true"></param>
		<param name="allowScriptAccess" value="true"></param>
		<param name="flashVars" value="textChat=<?php echo $textChat ?>&inviteButton=<?php echo $inviteButton;?>&guestList=<?php echo $guestList;?>&observerMode=<?php echo $observerMode;?>"></param>
		<embed id="tbx_call" src="http://qa.tokbox.com/vc/ztssmc3hcof1jhl6"
			type="application/x-shockwave-flash"
			allowfullscreen="true"
			allowScriptAccess="always"
			width="600"
			height="400"
			flashvars="textChat=<?php echo $textChat ?>&inviteButton=<?php echo $inviteButton;?>&guestList=<?php echo $guestList;?>&observerMode=<?php echo $observerMode;?>"
		>
		</embed>
	</object>

	<br/><br/>
	
	<a href="http://qa.tokbox.com/vc/ztssmc3hcof1jhl6">http://qa.tokbox.com/vc/ztssmc3hcof1jhl6</a>

</body>
</html>
