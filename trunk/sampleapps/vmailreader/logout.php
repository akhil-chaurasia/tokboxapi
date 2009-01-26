<?php
require_once 'Site_Config.php';

if (isset($_COOKIE['tokboxId'])) {
	setcookie ("tokboxId", "", time()-60*60*24*100);
}
header("Location: ".Site_Config::BASE_SITE."index.php");