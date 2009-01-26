<?php
/*
*		*************************************
*		*            TokBox API             *
*		*************************************
*
*		Jason Friedman, Melih Onvural, August 2008
*
*	This class implements all of the methods exposed by the TokBox API.
*
*/

require_once('BaseApi.php');

final class TokBoxApi extends BaseApi {

	function __construct($userKey, $userSecret) {
		parent::__construct($userKey, $userSecret);
	}

	/**
	 *Creates a request token tied to the creating partner.
	 *This can be exchanged on the TokBox site for an access token, which is then passed to API calls as authentication on user actions.
	 *TODO:
	 *Pass the request token to ADD URL.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $callbackUrl URL to send the user to after they log in.
	 *
	 *@return Response string to API call
	*/
	public function getRequestToken($callbackUrl) {
		$method = "POST";
		$url = "/auth/getRequestToken";
		$paramList = array();
		$paramList['callbackUrl'] = $callbackUrl;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Retrieves an access token appropriate to the credentials of the user who is requesting the token.
	 *If the user is valid, and registered, they will receive an access token associated with their account. Otherwise they will receive a guest token.
	 *
	 *AuthLevel: require_trusted_partner
	 *
	 *@param string $jabberId Jabber ID for the user who is attempting to retrieve the token.
	 *@param string $password MD5 hashed password for the user who is attempting to retrieve the token.
	 *
	 *@return Response string to API call
	*/
	public function getAccessToken($password, $jabberId) {
		$method = "POST";
		$url = "/auth/getAccessToken";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['password'] = $password;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Ensures that the access token and the associated user are correlated by the TokBox system.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $jabberId Jabber ID of the user who is being validated
	 *@param string $accessSecret Access token which is being validated
	 *
	 *@return Response string to API call
	*/
	public function validateAccessToken($accessSecret, $jabberId) {
		$method = "POST";
		$url = "/auth/validateAccessToken";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['accessSecret'] = $accessSecret;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Create a call and return the media server address and call id of the video chat.
	 *The Jabber ID and name are used for logging.
	 *After creating a call, create and send invites to any party you wish to join you.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $callerJabberId Jabber ID of the caller creating the video chat.
	 *@param string $callerName Name of the caller creating the video chat.
	 *@param boolean $persistent True if this callid should remain valid past the normal 4 day timeout
	 *
	 *@return Response string to API call
	*/
	public function createCall($callerName, $callerJabberId, $persistent = null) {
		$method = "POST";
		$url = "/calls/create";
		$paramList = array();
		$paramList['callerJabberId'] = $callerJabberId;
		$paramList['callerName'] = $callerName;
		if($persistent !== null) $paramList['persistent'] = $persistent;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Create an invite to a particular call. Returns an inviteId to be sent to call recipients
	 *The calleeJid is used for logging and missed call notifications
	 *Clients are expected to use inviteIds to join calls
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $callerJabberId Jabber ID of the inviter who has initiated the call.
	 *@param string $calleeJabberId Jabber ID of the invitee who is being invited to the call
	 *@param string $call_id CallId returned from /calls/create API call
	 *
	 *@return Response string to API call
	*/
	public function createInvite($call_id, $calleeJabberId, $callerJabberId) {
		$method = "POST";
		$url = "/calls/invite";
		$paramList = array();
		$paramList['callerJabberId'] = $callerJabberId;
		$paramList['calleeJabberId'] = $calleeJabberId;
		$paramList['call_id'] = $call_id;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Used to get the information necessary to join a call based on the invite id provided. Returns the media server and callId of the call
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $invite_id Invite ID returned from /calls/invite and used to find the server and call id info necessary to connect to a call.
	 *
	 *@return Response string to API call
	*/
	public function joinCall($invite_id) {
		$method = "POST";
		$url = "/calls/join";
		$paramList = array();
		$paramList['invite_id'] = $invite_id;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Send a video mail to either TokBox contacts or a list of e-mail contacts.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $vmail_id VmailId of the recorded message that is being sent.
	 *@param string $tokboxRecipients Comma separated list of TokBox Jabber IDs who will receive the VMail.
	 *@param string $emailRecipients Comma separated list of valid email addresses who will receive the VMail.
	 *@param jid $senderJabberId Jabber ID of the VMail sender.
	 *@param string $text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public function sendVMail($senderJabberId, $vmail_id, $tokboxRecipients = null, $emailRecipients = null, $text = null) {
		$method = "POST";
		$url = "/vmail/send";
		$paramList = array();
		$paramList['vmail_id'] = $vmail_id;
		if($tokboxRecipients !== null) $paramList['tokboxRecipients'] = $tokboxRecipients;
		if($emailRecipients !== null) $paramList['emailRecipients'] = $emailRecipients;
		$paramList['senderJabberId'] = $senderJabberId;
		if($text !== null) $paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Forward a video mail to either TokBox contacts or a list of e-mail contacts.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $vmail_id VmailId of the recorded message that is being sent.
	 *@param string $tokboxRecipients Comma separated list of TokBox Jabber IDs who will receive the VMail.
	 *@param string $emailRecipients Comma separated list of valid email addresses who will receive the VMail.
	 *@param jid $senderJabberId Jabber ID of the VMail sender.
	 *@param string $text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public function forwardVMail($senderJabberId, $vmail_id, $tokboxRecipients = null, $emailRecipients = null, $text = null) {
		$method = "POST";
		$url = "/vmail/forward";
		$paramList = array();
		$paramList['vmail_id'] = $vmail_id;
		if($tokboxRecipients !== null) $paramList['tokboxRecipients'] = $tokboxRecipients;
		if($emailRecipients !== null) $paramList['emailRecipients'] = $emailRecipients;
		$paramList['senderJabberId'] = $senderJabberId;
		if($text !== null) $paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Send a video mail to all of your TokBox friends.
	 *
	 *AuthLevel: require_user
	 *
	 *@param string $vmail_id VmailId of the recorded message that is being sent.
	 *@param jid $senderJabberId Jabber ID of the VMail sender.
	 *@param string $text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public function sendVMailToAllFriends($senderJabberId, $vmail_id, $text = null) {
		$method = "POST";
		$url = "/vmail/sendToFriends";
		$paramList = array();
		$paramList['vmail_id'] = $vmail_id;
		$paramList['senderJabberId'] = $senderJabberId;
		if($text !== null) $paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Forward a video mail to all of your TokBox friends.
	 *
	 *AuthLevel: require_user
	 *
	 *@param string $vmail_id VmailId of the recorded message that is being sent.
	 *@param jid $senderJabberId Jabber ID of the VMail sender.
	 *@param string $text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public function forwardVMailToAllFriends($senderJabberId, $vmail_id, $text = null) {
		$method = "POST";
		$url = "/vmail/forwardToFriends";
		$paramList = array();
		$paramList['vmail_id'] = $vmail_id;
		$paramList['senderJabberId'] = $senderJabberId;
		if($text !== null) $paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Post a public VMail to the public feed portion of Tokbox
	 *
	 *AuthLevel: require_user
	 *
	 *@param text $vmail_id Vmail ID of the recorded message that is being posted on the public feed.
	 *@param text $scope Either {friends} or {public}. Defines the scope of who receives this public VMail post.
	 *@param jid $senderJabberId Jabber ID of the message sender.
	 *@param text $text Text of the vmail message.
	 *
	 *@return Response string to API call
	*/
	public function postPublicVMail($text, $senderJabberId, $scope, $vmail_id) {
		$method = "POST";
		$url = "/vmail/postPublic";
		$paramList = array();
		$paramList['vmail_id'] = $vmail_id;
		$paramList['scope'] = $scope;
		$paramList['senderJabberId'] = $senderJabberId;
		$paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Mark a VMail read. This triggers a notice to the sender. Returns the number of unread feed items
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $message_id Message ID of the VMail being marked as read
	 *
	 *@return Response string to API call
	*/
	public function markVmailRead($message_id) {
		$method = "POST";
		$url = "/vmail/markasviewed";
		$paramList = array();
		$paramList['message_id'] = $message_id;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Removes a VMail from the feed/inbox. Returns the number of unread feed items
	 *
	 *AuthLevel: require_user
	 *
	 *@param string $message_id Message ID of the VMail being removed from the feed.
	 *@param string $type Type of message to delete from the feed. {'vmailRecv', 'vmailSent','callEvent', 'vmailPostRecv','vmailPostPublic', 'other'}
	 *
	 *@return Response string to API call
	*/
	public function deleteVMail($type, $message_id) {
		$method = "POST";
		$url = "/vmail/delete";
		$paramList = array();
		$paramList['message_id'] = $message_id;
		$paramList['type'] = $type;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns information about a VMail which lets you access the message.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $message_id Message ID of the video mail being retrieved.
	 *
	 *@return Response string to API call
	*/
	public function getVMail($message_id) {
		$method = "POST";
		$url = "/vmail/getVmail";
		$paramList = array();
		$paramList['message_id'] = $message_id;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Add a comment on the end of the specified post
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $posterjabberId The Jabber ID of the person making the post
	 *@param string $vmailmessageid Message ID of the post that this comment refers to
	 *@param string $vmailcontentid If this is a video post then this is the VMail ID of the video.
	 *@param string $commenttext The text component of the comment
	 *
	 *@return Response string to API call
	*/
	public function addComment($commenttext, $vmailmessageid, $posterjabberId, $vmailcontentid = null) {
		$method = "POST";
		$url = "/vmail/addcomment";
		$paramList = array();
		$paramList['posterjabberId'] = $posterjabberId;
		$paramList['vmailmessageid'] = $vmailmessageid;
		if($vmailcontentid !== null) $paramList['vmailcontentid'] = $vmailcontentid;
		$paramList['commenttext'] = $commenttext;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns a list of all the comments associated with a given message_id
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $message_id The Message ID of the post against which comments are being collected.
	 *@param integer $start The comment from which to start returning results.
	 *@param integer $count The number of comments to return.
	 *
	 *@return Response string to API call
	*/
	public function getAllPostComments($message_id, $start = null, $count = null) {
		$method = "POST";
		$url = "/vmail/getcomments";
		$paramList = array();
		$paramList['message_id'] = $message_id;
		if($start !== null) $paramList['start'] = $start;
		if($count !== null) $paramList['count'] = $count;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Return the user's feed.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user whose feed is being requested.
	 *@param text $filter Options: {any[default], vmailSent, vmailRecv, callEvent, vmailPostPublic, vmailPostRecv, other}
	 *@param integer $start Page number of the user feed from which to start the retrieval.
	 *@param integer $count Items per page of the user feed which is being retrieved.
	 *
	 *@return Response string to API call
	*/
	public function getFeed($jabberId, $filter = null, $start = null, $count = null) {
		$method = "POST";
		$url = "/feed/getFeed";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		if($filter !== null) $paramList['filter'] = $filter;
		if($start !== null) $paramList['start'] = $start;
		if($count !== null) $paramList['count'] = $count;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns the number of unread feed items for the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user whose feed is being requested.
	 *
	 *@return Response string to API call
	*/
	public function getFeedUnreadCount($jabberId) {
		$method = "POST";
		$url = "/feed/unreadCount";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Delete a call event from the feed of the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the recipient of the call event upon which is being deleted.
	 *@param string $invite_id Invite ID of the call upon which is being acted.
	 *
	 *@return Response string to API call
	*/
	public function deleteCallEvent($invite_id, $jabberId) {
		$method = "POST";
		$url = "/callevent/delete";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['invite_id'] = $invite_id;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Mark a call event as viewed from the feed of the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the recipient of the call event which is being marked viewed.
	 *@param string $invite_id Invite ID of the call upon which is being acted.
	 *
	 *@return Response string to API call
	*/
	public function markCallEventViewed($invite_id, $jabberId) {
		$method = "POST";
		$url = "/callevent/markasviewed";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['invite_id'] = $invite_id;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Register a user with the system, a password will be emailed to them, and an access_secret is returned to the caller.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $firstname First Name of the user who is being registered. The length of the name must be greater than 2 characters.
	 *@param string $lastname Last Name of the user who is being registered. The length of the name must be greater than 2 characters.
	 *@param string $email Valid email address of the user who is being registered.
	 *
	 *@return Response string to API call
	*/
	public function registerUser($email, $lastname, $firstname) {
		$method = "POST";
		$url = "/users/register";
		$paramList = array();
		$paramList['firstname'] = $firstname;
		$paramList['lastname'] = $lastname;
		$paramList['email'] = $email;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns - name, image, online status - information about a user.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $jabberId Jabber ID of the profile that is being looked up.
	 *
	 *@return Response string to API call
	*/
	public function getUserProfile($jabberId) {
		$method = "POST";
		$url = "/users/getProfile";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Create a guest user on the TokBox jabber server to make/receive calls.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $partnerKey Partner Key of the API Developer who is trying to create the guest account
	 *
	 *@return Response string to API call
	*/
	public function createGuestUser($partnerKey) {
		$method = "POST";
		$url = "/users/createGuest";
		$paramList = array();
		$paramList['partnerKey'] = $partnerKey;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Add users to the TokBox contact list of the given user whose ID is supplied.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user adding friends to their list.
	 *@param text $remoteJabberId Comma separated list of Jabber IDs to add to the contact list of the adding user.
	 *
	 *@return Response string to API call
	*/
	public function addContact($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/add";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Remove a user from the TokBox contact list of the given user whose ID is supplied.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user removing a contact from their TokBox contact list.
	 *@param jid $remoteJabberId Jabber ID of the user being removed from the contact list of the removing user.
	 *
	 *@return Response string to API call
	*/
	public function removeContact($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/remove";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Get the relation between two users.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user initiating the relation check.
	 *@param jid $remoteJabberId Jabber ID against which the relation is being tested.
	 *
	 *@return Response string to API call
	*/
	public function isFriend($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/getRelation";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


}
