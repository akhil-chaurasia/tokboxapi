<?php
	/*
	* The VideoBook sample application plays on the idea of a guestbook using the video recorder and video player
	* widgets in place of the traditional form and text comments. The API features present here include dyanmic
	* generation of the embed code for the video player, the video recorder being dynamically addressed to a user,
	* and handling Javascript messages delivered from the widget in one's application.
	*
	* @author Melih Onvural
	*/

	require_once 'SDK/TokBoxVideo.php';
	require_once 'Site_Config.php';
	require_once 'SDK/TokBoxUser.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>TokBox Developer API - Sample Application - Wedding Guest Book</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div class="disclaimer">
        <b>Note:</b> This is a TokBox API sample application. For more information on the TokBox Developer API please check out:
     	<ul>
                <li><a href="http://developers.tokbox.com/">Developer Wiki</a></li>
        	<li><a href="http://developers.tokbox.com/index.php/Sample_Applications">More Sample Applications</a></li>
	</ul>
</div>
<div class="container">
	<h1>Coco + Red Bunny's Wedding VideoBook</h1>
        <div class="couplesMessage">
	<?php
		//Given the message id: lxxqz554s57x, this generates the HTML code to embed the 
		//video player widget
		$htmlCode = TokBoxVideo::generatePlayerEmbedCode("lxxqz554s57x", "350", "290", false);

		echo $htmlCode;
	?> 
        </div>
	<div class="details">
		<p><b>Coco Crisp &amp; Red Bunny</b><br/>
		Happily announce their plans to marry, and invite you to leave them a video message</p>
		<p><b>Date:</b> Sunday, May 11 @ 5:00pm</p>
		<p><b>Location:</b>SF City Hall</p>
	</div>
	<div class="balloon">
		<a href="recorderPopup.php" target="_blank"><img src="images/balloon.png" border="0" alt="Leave a message" /></a>
	</div>
	<?php
		//Here we are instantiating the user's API object so that we can make requests on behalf
		//of the user JABBERID with secrect ACCESS_SECRET
		$apiObj = TokBoxUser::createUser(Site_Config::JABBERID, Site_Config::ACCESS_SECRET);
		$vmailRecvs = TokBoxVideo::getVmailRecv($apiObj); //grabs the feed of video mails received by the user
	
		ob_start();
		foreach($vmailRecvs as $vmailRecv) {
			$senders = $vmailRecv->getVmailSenders();					
			$sender = $senders[0]; //grabs the sender of the video mail

			echo "<div class=\"video\">";
			echo "<img src=\"".$vmailRecv->getVmailImgUrl()."\">"; //grabs the image URL of the video  mail
			echo "Message from ".$sender->getFullName(); //grabs the full 
			echo "<br/>";
			echo '<a href="playerPopup.php?mid='.$vmailRecv->getVmailMessageId().'">Click to play message</a>'; //gets the message ID which is used by the player widget to play the video
			echo "</div>";
		}
		ob_end_flush();
	?>
</div>
</body>
</html>
