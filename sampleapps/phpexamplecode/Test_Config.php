<?php

class Test_Config {

	//User Constants
	const TEST_JABBERID = "547935@jabber.dev.tokbox.com";
	const TEST_ACCESS_SECRET = "0bb2c65ddda623a2d90913a6eee7f0cc";
	
	//Call Constants
	const TEST_CALLID = "8u17nnalmsqve7bp";//"b8sfo3azkfsftcb9";
	const TEST_CALLEE_JABBERID = "547747@jabber.dev.tokbox.com";
	const TEST_INVITEID = "tbhmvhijb890aw7j";

	//Contact Constants
	const TEST_ADD_CONTACT = "547237@jabber.dev.tokbox.com";
	
	//Feed Constants
	const TEST_DELETE_VMAILID = "5y2m2a8yxrwq";
	const TEST_DELETE_VMAIL_TYPE = "vmailRecv";
	const TEST_COMMENTS_MESSAGEID = "urkbtth3sikg";
	const TEST_COMMENT_TEXT = "This is only a test";
	const TEST_MARKREAD_MESSAGEID = "urkbtth3sikg";

	//VMail Constants
	const TEST_VMAIL_VMAILID = "x7owmwgr2gp4";
	const TEST_VMAIL_TOJABBERID = "547237@jabber.dev.tokbox.com";
	const TEST_VMAIL_MESSAGEID = "urkbtth3sikg";
	const TEST_VMAIL_SCOPE = "public";
	const TEST_VMAIL_TEXT = "This is only a test";

	public static function getRegisterFirstName() {
		return self::generateRandomString(6);
	}
	
	public static function getRegisterLastName() {
		return self::generateRandomString(6);	
	}
	
	public static function getRegisterEmail() {
			return self::generateRandomString(6)."@tokbox.com";
	}
	
	private static function generateRandomString($length) {
		$feed = "abcdefghijklmnopqrstuvwxyz";
		
		$rand_str = "";
		$feed_length = strlen($feed)-1;
  
	  	for ($i=0; $i < $length; $i++){
      		$rand_str .= substr($feed, mt_rand(0, $feed_length), 1);
  		}
  
	  	return $rand_str;
	}
}
