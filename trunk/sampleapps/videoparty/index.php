<?php
	/*
	* This sample application is a demonstration of the dynamic call generation functionality built into the 
	* TokBox Developer API. The user has the choice of creating their own room or simply joining a room which
	* has already been created. The video chat is then embedded into videochat.php, where the user will be
	* sent.
	*
	* @author Melih Onvural melih@tokbox.com
	*/

		require_once 'Site_Config.php';
		require_once 'Utils.php';

		$dbConn = mysql_connect(Site_Config::DB_HOST, Site_Config::DB_USER, Site_Config::DB_PASSWORD) or die(mysql_error());
		mysql_select_db(Site_Config::DB_DATABASE, $dbConn) or die (mysql_error());
		$query = "SELECT callid, subject, created FROM calls WHERE list=1 order by created DESC LIMIT 0,10";
		
		$result = mysql_query($query, $dbConn) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Sample Application - TokBox Developer API - Call Generator</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="container">
		<div class="header">&nbsp;
			<div class="title">Video Party</div>
			<div class="disclaimer">
				<b>Note:</b> This is a TokBox Developer API sample application<br/>
				For more information on the TokBox Developer API please check out:
				<ul>
					<li><a href="http://developers.tokbox.com/">Developer Wiki</a></li>
					<li><a href="http://developers.tokbox.com/index.php/Sample_Applications">More Sample Applications</a></li>
				</ul>
			</div>
		</div>
		<div class="main">
	                <div id="rightcontent">
                                <p>Create your own video room and invite your friends to chat</p>
				<form action="addCall.php" method="POST">
                                        <table>
                                                <tr>
                                                        <td>Topic:</td>
                                                        <td><input type="text" name="subject" maxlength="50"/></td>
							<td>Show in list?</td>
							<td><input type="checkbox" checked="checked" name="inList" /></td>
                                                </tr>
                                                <tr>
                                                        <td colspan="4" align="right"><input type="submit" name="submit" value="create call" /></td>
                                                </tr>
                                        </table>
                                </form>
                        </div>
			<p>or join an existing video chat room:</p>
			<div id="leftcontent">
				<table border="0" cellpadding="5" cellspacing="2">
					<tr>
						<td align="center" width="200px">Conversation topic</td>
						<td align="center" width="200px">date created</td>
						<td>&nbsp;</td>
					</tr>
			<?php
				if (isset($result) && mysql_num_rows($result) > 0){	
					ob_start();
					while($row = mysql_fetch_array($result)) 
					{
						echo "<tr>\n";
						echo "\t<td align=\"center\">".$row['subject']."</td>";
						echo "\t<td align=\"center\">".Utils::getHowLongAgo($row['created'])."</td>";
						echo "\t<td align=\"center\"><a href=\"videochat.php?callid=".$row['callid']."\">Join Chat</a></td>";
						echo "</tr>\n";	
					}
					ob_flush();
				}
				else 
					echo "<tr><td align=\"center\" colspan=\"3\"> No active rooms found, please create a new room</td></tr>"
			?>
				</table>
			</div>
		</div>
		<div class="footer">
		
		</div>
	</div>
</body>
</html>
