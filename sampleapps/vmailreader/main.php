<?php

require_once 'SDK/TokBoxVideo.php';
require_once 'SDK/TokBoxUser.php';

	$tokboxId = explode(":", $_COOKIE['tokboxId']);

	$jabberId = $tokboxId[0];
	$accessSecret = $tokboxId[1];

	$apiObj = TokBoxUser::createUser($jabberId, $accessSecret);

	$vmailRecvs = TokBoxVideo::getVmailRecv($apiObj);
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
		<div id="nav"><a href="logout.php">Log Out</a></div>
		<div id="main">
		<?php
			if(isset($_GET['msgId'])) {
				echo TokBoxVideo::generatePlayerEmbedCode($_GET['msgId']);
			}
			else {
				echo "Select a video from the right to view it here";
			}
		?>
		</div>
		<div id="sidebar">
			<?php
				ob_start();
				foreach($vmailRecvs as $vmailRecv) {
					$img = strlen($vmailRecv->getVMailImgUrl()) > 0 ? $vmailRecv->getVMailImgUrl() : "images/default.jpg";

					echo "<div class=\"vmailRecvItem\">\n";
					echo "<a href=\"main.php?msgId=".$vmailRecv->getVmailMessageId()."\"><img src=\"".$vmailRecv->getVMailImgUrl()."\" border=\"0\"></a><br/>\n";
					echo "Sent By: ";
					
					foreach($vmailRecv->getVmailSenders() as $sender) {
						echo $sender->getFullName()."<br/>\n";
					}
					
					$date = (int)$vmailRecv->getVmailTimeSent();
					
					echo "Sent on: ".date("Y-m-d", $date)."\n";
					echo "</div>\n";
				}
				ob_end_flush();
			?>
		</div>
	</div>
</body>
</html>