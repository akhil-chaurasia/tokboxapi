<?php

require_once('TokBoxApi.php');
require_once('TokBoxExceptions.php');

class TokBoxCall {

	public function createCall(TokBoxApi $callApiObj, $persistent='false', $displayname='Guest') {
		
		$isValid = $callApiObj->validateAccessToken($secret, $jabberId);		
		
		if(!$isValid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$isValidXml = simplexml_load_string($isValid);

		if($isValidXml->isValid == 'false') {
			throw new NotLoggedInException("The user is not properly validated");
		}
		
		$createCall = $callApiObj->createCall($displayname, $callApiObj->getJabberId(), $persistent);

		if(!$createCall)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$createCallXml = simplexml_load_string($createCall);

		return $createCallXml->call['callid'];
	}
	
	public static function generateInvite(TokBoxApi $callApi, $calleeId) {
	
		
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

	public static function generateEmbedCode($callId, $width="425", $height="320", $inviteButton=true, $guestList=true, $observerMode=true) {
		$htmlCode = "<object  width=\"$width\" height=\"$height\">\n".
					"\t<param name=\"movie\" value=\"".API_Config::API_SERVER.TokBoxApi::$API_SERVER_CALL_WIDGET.$callId."\"></param>\n".
					"\t<param name=\"allowFullScreen\" value=\"true\"></param>\n".
					"\t<param name=\"allowScriptAccess\" value=\"true\"></param>\n".
					"\t<param name=\"flashVars\" value=\"inviteButton=$inviteButton&guestList=$guestList&observerMode=$observerMode\"></param>\n".
					"\t<embed id=\"tbx_call\" src=\"".API_Config::API_SERVER.TokBoxApi::$API_SERVER_CALL_WIDGET.$callId."\"\n".
					"\t\ttype=\"application/x-shockwave-flash\"\n".
					"\t\tallowfullscreen=\"true\"\n".
					"\t\tallowScriptAccess=\"always\"\n".
					"\t\twidth=\"$width\"\n". 
					"\t\theight=\"$height\"\n".
					"\t\tflashvars=\"inviteButton=$inviteButton&guestList=$guestList&observerMode=$observerMode\"\n".
					"\t>\n".
					"\t</embed>\n".
					"</object>\n";
	
		return $htmlCode;
	}
}
