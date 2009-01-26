<?php
	/*
	* RecorderPopup demonstrates using the SDK for the Recorder widget functionality
	* as well as handling the response from a callback out of the widget, and handling an
	* action as a response to that callback
	*
	* @author Melih Onvural
	*/

        require_once 'SDK/TokBoxVideo.php';
        require_once 'Site_Config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
        <title>TokBox Developer API - Sample Application - Wedding Guest Book</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript">
		//This function demonstrates how to handle the callback action from the widget by redirecting back to the index page
		function vmailSent(isSent) {
			window.location = "http://sandbox.tokbox.com/sampleApp/guestbook/index.php";	
		}
	</script>

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
	<div class="recorder">
	<?php
		//HTML code for the recorder widget. This includes the specific flashvar which addresses the e-mail
		//to the given e-mail address
		$htmlCode = TokBoxVideo::generateRecorderEmbedCodeToMe(Site_Config::EMAIL_ADDRESS);

		echo $htmlCode;
	?>
	</div>
	<div class="footermenu">
		<a href="index.php">Back to VideoBook</a>
	</div>
</div>
</body>
</html>
