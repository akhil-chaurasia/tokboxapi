<?php

require_once('TokBoxApi.php');
require_once('TokBoxExceptions.php');

class TokBoxCall {

	public function createCall(TokBoxApi $callApiObj, $persistent='false', $displayname='Guest') {
		
		$isValid = $callApiObj->validateAccessToken($secret, $jabberId);		
		
		if(!$isValid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$isValidXml = simplexml_load_string($isValid);

		if($isValidXml->validateAccessToken->isValid == 'false') {
			throw new NotLoggedInException("The user is not properly validated");
		}
		
		$createCall = $callApiObj->createCall($displayname, $callApiObj->getJabberId(), "", $persistent);

		if(!$createCall)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$createCallXml = simplexml_load_string($createCall);

		return $createCallXml->createCall->callId;
	}
	
	public static function generateInvite(TokBoxApi $callApiObj, $calleeId) {
		$isValid = $callApiObj->validateAccessToken($callApiObj->getSecret(), $callApiObj->getJabberId());

		if(!$isValid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$isValidXml = simplexml_load_string($isValid);

		if($isValidXml->validateAccessToken->isValid == 'false') {
			throw new NotLoggedInException("The user is not properly validated");
		}

		$createInvite = $callApiObj->createInvite($callId, $calleeJabberId, $callApiObj->getJabberId());

		if(!createInvite)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$createInviteXml = simplexml_load_string($createInvite);

		return $createInviteXml->createInvite->inviteId;
	}

	public static function allowJoin(TokBoxApi $callApi) {
	
	}

	public static function generateLink($callId) {
		return API_Config::API_SERVER.TokBoxApi::$API_SERVER_CALL_WIDGET.$callId;
	}

	public static function generateInviteButton() {
		$htmlCode = "<script language=\"javascript\" src=\"SDK/js/TokBoxScript.js\"></script>\n".
					"<div style=\"width:100px;height:30px;background-color:gold;color:blue;line-height:30px;text-align:center;margin:10px;\" onclick=\"inviteUser()\">\n".
					"Invite User\n".
					"</div>\n";

		return $htmlCode;
	}
	
	public static function generateEmbedCode($callId, $width="425", $height="320", $swfobjectPath="SDK/js/swfobject.js", $inviteButton="1", $guestList="1", $observerMode="0") {
		$bodyCode = "<script type=\"text/javascript\" src=\"$swfobjectPath\"></script>\n".
					"<script type=\"text/javascript\">\n".
						"\tvar flashvars = {};\n".
						"\tflashvars.inviteButton = \"$inviteButton\";\n".
						"\tflashvars.guestList = \"$guestList\";\n".
						"\tflashvars.observerMode = \"$observerMode\";\n".
						"\tvar params = {};\n".
						"\tparams.allowfullscreen = \"true\";\n".
						"\tparams.allowscriptaccess = \"always\";\n".
						"\tvar attributes = {};\n".
						"\tattributes.id = \"tbx_call\";\n".
						"\tswfobject.embedSWF(\"".API_Config::API_SERVER.TokBoxApi::$API_SERVER_CALL_WIDGET.$callId."\", \"widgetDiv\", \"$width\", \"$height\", \"9.0.115\", false, flashvars, params, attributes);\n".
					"</script>\n".
					"<div id=\"widgetDiv\">\n".
						"\t<a href=\"http://www.adobe.com/go/getflashplayer\">\n".
							"\t\t<img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" />\n".
						"\t</a>\n".
					"</div>\n";

		return $bodyCode;
	}
}