<?php
require_once 'Site_Config.php';

	if(!isset($_COOKIE['tokboxId']))
		header("Location: ".Site_Config::BASE_SITE."login.php");
	else
		header("Location: ".Site_Config::BASE_SITE."main.php");