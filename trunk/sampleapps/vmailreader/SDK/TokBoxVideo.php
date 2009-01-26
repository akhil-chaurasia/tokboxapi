<?php

require_once 'API_Config.php';
require_once 'TokBoxApi.php';
require_once 'TokBoxVMail.php';

class TokBoxVideo {

	public static function getVMailSent($apiObj, $startPage = 0, $count = 10) {
		return self::getFeed($apiObj, "vmailSent", $startPage, $count);
	}
	
	public static function getVmailRecv($apiObj, $startPage = 0, $count = 10) {
		return self::getFeed($apiObj, "vmailRecv", $startPage, $count);
	}
	
	public static function getCallEvent($apiObj, $startPage = 0, $count = 10) {
		return self::getFeed($apiObj, "callEvent", $startPage, $count);
	}
	
	public static function getPublicPost($apiObj, $startPage = 0, $count = 10) {
		return self::getFeed($apiObj, "vmailPostPublic", $startPage, $count);;
	}
	
	public static function getPublicRecv($apiObj, $startPage = 0, $count = 10) {
		return self::getFeed($apiObj, "vmailPostRecv", $startPage, $count);
	}

	public static function generateRecorderEmbedCode($isGuest=false, $width="322", $height="321") {
		$htmlCode = "";
		
		if($isGuest) {
			$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
			$apiObj->updateToken($apiObj->getRequestToken(API_Config::CALLBACK_URL));
		
			$htmlCode .= "<script language=\"javascript\" src=\"SDK/js/TokBoxScript.js\"></script>\n";
			$htmlCode .= "<body onclick=\"setToken('".$apiObj->getAuthToken()."');\">\n";			
		}

		$htmlCode .= "<object  width=\"$width\" height=\"$height\">\n".
					"\t<param name=\"movie\" value=\"".API_CONFIG::API_SERVER.TokBoxApi::$API_SERVER_RECORDER_WIDGET."\"></param>\n".
					"\t<param name=\"allowFullScreen\" value=\"true\"></param>\n".
					"\t<param name=\"allowScriptAccess\" value=\"true\"></param>\n".
					"\t<embed id=\"tbx_recorder\" src=\"".API_CONFIG::API_SERVER.TokBoxApi::$API_SERVER_RECORDER_WIDGET."\"\n". 
					"\t\ttype=\"application/x-shockwave-flash\"\n".
					"\t\tallowfullscreen=\"true\"\n".
					"\t\tallowScriptAccess=\"always\"\n".
					"\t\twidth=\"$width\"\n". 
					"\t\theight=\"$height\"\n".
					"\t>\n".
					"\t</embed>\n".
					"</object>\n";
	
		return $htmlCode;
	}
	
	public static function generatePlayerEmbedCode($messageId, $width="425", $height="344", $autoPlay="true") {
		$htmlCode =	"<object  width=\"$width\" height=\"$height\">".
					"\t<param name=\"movie\" value=\"".API_CONFIG::API_SERVER.TokBoxApi::$API_SERVER_PLAYER_WIDGET."\"></param>".
					"\t<param name=\"allowFullScreen\" value=\"true\"></param>".
					"\t<param name=\"allowScriptAccess\" value=\"true\"></param>".
					"\t<param name=\"flashvars\" value=\"targetVmail=$messageId&autoPlay=$autoPlay\"></param>".
					"\t<embed id=\"tbx_player\" src=\"".API_CONFIG::API_SERVER.TokBoxApi::$API_SERVER_PLAYER_WIDGET."\"". 
					"\t\ttype=\"application/x-shockwave-flash\"". 
					"\t\tallowfullscreen=\"true\"".
					"\t\tallowScriptAccess=\"always\"".
					"\t\tflashvars=\"targetVmail=$messageId&autoPlay=$autoPlay\"".
					"\t\twidth=\"$width\"". 
					"\t\theight=\"$height\"".
					"\t>".
					"\t</embed>".
					"</object>";
					
		return $htmlCode;
	}

	private static function getFeed($apiObj, $type="all", $startPage = 0, $count = 10) {
		$feed = $apiObj->getFeed($apiObj->getJabberId(), $type, $startPage, $count);
		
		if(!$feed) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}
		
		$feedXml = simplexml_load_string($feed);
		
		if($feedXml->error) {
			throw new Exception($feedXml->error, (int)$feedXml->error['code']);			
		}
		
		$itemsArray = array();
		
		foreach($feedXml->feed->item as $item) {
			foreach($item->videoMail as $videoMail) {
				$vmail = new TokBoxVmail($videoMail['vmail_id']);
			
				$vmail->setVmailType($item['type']);
				$vmail->setVmailBatchId($videoMail->content['batch_id']);
				$vmail->setVmailImgUrl($videoMail->image);
				$vmail->setVmailFlvUrl($videoMail->content);
				$vmail->setVmailMessageId($videoMail->content['message_id']);
				
				$recipients = array();
				foreach($videoMail->recipients->recipient as $recipient) {
					$user = new TokBoxVmailUser($recipient['userid'], $recipient, false, $recipient['time_read']);
					$recipients[] = $user;
				}
		
				$vmail->setVmailRecipients($recipients);
	
				$senders = array();
				foreach($videoMail->senders->sender as $sender) {
					$user = new TokBoxVmailUser($sender['userid'], $sender, true);
					$senders[] = $user;
				}
	
				$vmail->setVmailSenders($senders);
				$vmail->setVmailText($videoMail->text);
				$vmail->setVmailTimeRead($videoMail['time_read']);
				$vmail->setVmailTimeSent($videoMail['time_sent']);
	
				$itemsArray[] = $vmail;
			}
		}
		
		return $itemsArray;
	}
}
