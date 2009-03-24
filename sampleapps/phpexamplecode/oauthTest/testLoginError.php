<?php
	require_once '../SDK/TokBoxApi.php';

	$url = API_Config::API_SERVER."view/oauth";
	header("Location: $url");
