/*
*		*************************************
*		*            TokBox API             *
*		*************************************
*
*		Melih Onvural, October 2008
*
*	This class implements all of the methods exposed by the TokBox API.
*
*/

package com.tokbox.api;

import java.util.HashMap;

import java.util.Map;
public class TokBoxAPI extends BaseAPI {

	public TokBoxAPI(String partnerKey, String secret) {
		super(partnerKey, secret);
	}

	/**
	 *Creates a request token tied to the creating partner.
	 *This can be exchanged on the TokBox site for an access token, which is then passed to API calls as authentication on user actions.
	 *TODO:
	 *Pass the request token to ADD URL.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string callbackUrl URL to send the user to after they log in.
	 *
	 *@return Response string to API call
	*/
	public String getRequestToken(String callbackUrl) {
		String method = "POST";
		String url = "/auth/getRequestToken";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("callbackUrl", callbackUrl);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Retrieves an access token appropriate to the credentials of the user who is requesting the token.
	 *If the user is valid, and registered, they will receive an access token associated with their account. Otherwise they will receive a guest token.
	 *
	 *AuthLevel: require_trusted_partner
	 *
	 *@param string jabberId Jabber ID for the user who is attempting to retrieve the token.
	 *@param string password MD5 hashed password for the user who is attempting to retrieve the token.
	 *
	 *@return Response string to API call
	*/
	public String getAccessToken(String jabberId, String password) {
		String method = "POST";
		String url = "/auth/getAccessToken";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("password", password);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Ensures that the access token and the associated user are correlated by the TokBox system.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string jabberId Jabber ID of the user who is being validated
	 *@param string accessSecret Access token which is being validated
	 *
	 *@return Response string to API call
	*/
	public String validateAccessToken(String jabberId, String accessSecret) {
		String method = "POST";
		String url = "/auth/validateAccessToken";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("accessSecret", accessSecret);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Create a call and return the media server address and call id of the video chat.
	 *The Jabber ID and name are used for logging.
	 *After creating a call, create and send invites to any party you wish to join you.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid callerJabberId Jabber ID of the caller creating the video chat.
	 *@param string callerName Name of the caller creating the video chat.
	 *@param string features Advanced features.
	 *@param boolean persistent True if this callid should remain valid past the normal 4 day timeout
	 *
	 *@return Response string to API call
	*/
	public String createCall(String callerJabberId, String callerName, String features, String persistent) {
		String method = "POST";
		String url = "/calls/create";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("callerJabberId", callerJabberId);
		paramList.put("callerName", callerName);
		paramList.put("features", features);
		paramList.put("persistent", persistent);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Create an invite to a particular call. Returns an inviteId to be sent to call recipients
	 *The calleeJid is used for logging and missed call notifications
	 *Clients are expected to use inviteIds to join calls
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid callerJabberId Jabber ID of the inviter who has initiated the call.
	 *@param string calleeJabberId Jabber ID of the invitee who is being invited to the call
	 *@param string call_id CallId returned from /calls/create API call
	 *
	 *@return Response string to API call
	*/
	public String createInvite(String callerJabberId, String calleeJabberId, String call_id) {
		String method = "POST";
		String url = "/calls/invite";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("callerJabberId", callerJabberId);
		paramList.put("calleeJabberId", calleeJabberId);
		paramList.put("call_id", call_id);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns whether or not a call id still exists
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string callid Callid returned from /call/create.
	 *
	 *@return Response string to API call
	*/
	public String validateCallID(String callid) {
		String method = "POST";
		String url = "/calls/validate";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("callid", callid);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns whether or not a call id still exists
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string callid Callid returned from /call/create.
	 *
	 *@return Response string to API call
	*/
	public String getCallInfo(String callid) {
		String method = "POST";
		String url = "/calls/getCallInfo";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("callid", callid);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Used to get the information necessary to join a call based on the invite id provided. Returns the media server and callId of the call
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string invite_id Invite ID returned from /calls/invite and used to find the server and call id info necessary to connect to a call.
	 *
	 *@return Response string to API call
	*/
	public String joinCall(String invite_id) {
		String method = "POST";
		String url = "/calls/join";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("invite_id", invite_id);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Return a list of servers to run latency tests on. This should return a server from each region.
	 *
	 *AuthLevel: require_guest
	 *
	 *
	 *@return Response string to API call
	*/
	public String getTestServers() {
		String method = "POST";
		String url = "/calls/getTestServers";
		Map <String, String> paramList = new HashMap<String, String>();

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns the address of a server for recording vmails, and one for playback.
	 *
	 *AuthLevel: require_guest
	 *
	 *
	 *@return Response string to API call
	*/
	public String getvmailserver() {
		String method = "POST";
		String url = "/vmail/getVMailServer";
		Map <String, String> paramList = new HashMap<String, String>();

		return TB_Request(method, url, paramList);
	}

	/**
	 *Send a video mail to either TokBox contacts or a list of e-mail contacts.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string vmail_id VmailId of the recorded message that is being sent.
	 *@param string tokboxRecipients Comma separated list of TokBox Jabber IDs who will receive the VMail.
	 *@param string emailRecipients Comma separated list of valid email addresses who will receive the VMail.
	 *@param jid senderJabberId Jabber ID of the VMail sender.
	 *@param string text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public String sendVMail(String vmail_id, String tokboxRecipients, String emailRecipients, String senderJabberId, String text) {
		String method = "POST";
		String url = "/vmail/send";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("vmail_id", vmail_id);
		paramList.put("tokboxRecipients", tokboxRecipients);
		paramList.put("emailRecipients", emailRecipients);
		paramList.put("senderJabberId", senderJabberId);
		paramList.put("text", text);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Forward a video mail to either TokBox contacts or a list of e-mail contacts.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string vmail_id VmailId of the recorded message that is being sent.
	 *@param string tokboxRecipients Comma separated list of TokBox Jabber IDs who will receive the VMail.
	 *@param string emailRecipients Comma separated list of valid email addresses who will receive the VMail.
	 *@param jid senderJabberId Jabber ID of the VMail sender.
	 *@param string text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public String forwardVMail(String vmail_id, String tokboxRecipients, String emailRecipients, String senderJabberId, String text) {
		String method = "POST";
		String url = "/vmail/forward";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("vmail_id", vmail_id);
		paramList.put("tokboxRecipients", tokboxRecipients);
		paramList.put("emailRecipients", emailRecipients);
		paramList.put("senderJabberId", senderJabberId);
		paramList.put("text", text);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Removes a VMail from the feed/inbox. Returns the number of unread feed items
	 *
	 *AuthLevel: require_user
	 *
	 *@param string message_id Message ID of the VMail being removed from the feed.
	 *@param string type Type of message to delete from the feed. {'vmailRecv', 'vmailSent','callEvent', 'vmailPostRecv','vmailPostPublic', 'other'}
	 *
	 *@return Response string to API call
	*/
	public String deleteVMail(String message_id, String type) {
		String method = "POST";
		String url = "/vmail/delete";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("message_id", message_id);
		paramList.put("type", type);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Post a public VMail to the public feed portion of Tokbox
	 *
	 *AuthLevel: require_user
	 *
	 *@param text vmail_id Vmail ID of the recorded message that is being posted on the public feed.
	 *@param text scope Either {friends} or {public}. Defines the scope of who receives this public VMail post.
	 *@param jid senderJabberId Jabber ID of the message sender.
	 *@param text text Text of the vmail message.
	 *@param integer templateId A reference to the template to apply to this vmail
	 *
	 *@return Response string to API call
	*/
	public String postPublicVMail(String vmail_id, String scope, String senderJabberId, String text, String templateId) {
		String method = "POST";
		String url = "/vmail/postPublic";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("vmail_id", vmail_id);
		paramList.put("scope", scope);
		paramList.put("senderJabberId", senderJabberId);
		paramList.put("text", text);
		paramList.put("templateId", templateId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Mark a VMail read. This triggers a notice to the sender. Returns the number of unread feed items
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string message_id Message ID of the VMail being marked as read
	 *
	 *@return Response string to API call
	*/
	public String markVmailRead(String message_id) {
		String method = "POST";
		String url = "/vmail/markasviewed";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("message_id", message_id);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns information about a VMail which lets you access the message.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string message_id Message ID of the video mail being retrieved.
	 *
	 *@return Response string to API call
	*/
	public String getVMail(String message_id) {
		String method = "POST";
		String url = "/vmail/getVmail";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("message_id", message_id);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Return information about a video post including comments
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string message_id MessageId of the video post
	 *@param integer numcomments Number of comments to return
	 *
	 *@return Response string to API call
	*/
	public String getVideoPost(String message_id, String numcomments) {
		String method = "POST";
		String url = "/vmail/getPost";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("message_id", message_id);
		paramList.put("numcomments", numcomments);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Add a comment on the end of the specified post
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid posterjabberId The Jabber ID of the person making the post
	 *@param string vmailmessageid Message ID of the post that this comment refers to
	 *@param string vmailcontentid If this is a video post then this is the VMail ID of the video.
	 *@param string commenttext The text component of the comment
	 *@param integer templateId A reference to the template to apply to this vmail
	 *
	 *@return Response string to API call
	*/
	public String addComment(String posterjabberId, String vmailmessageid, String vmailcontentid, String commenttext, String templateId) {
		String method = "POST";
		String url = "/vmail/addcomment";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("posterjabberId", posterjabberId);
		paramList.put("vmailmessageid", vmailmessageid);
		paramList.put("vmailcontentid", vmailcontentid);
		paramList.put("commenttext", commenttext);
		paramList.put("templateId", templateId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns a list of all the comments associated with a given message_id
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string message_id The Message ID of the post against which comments are being collected.
	 *@param integer start The comment from which to start returning results.
	 *@param integer count The number of comments to return.
	 *
	 *@return Response string to API call
	*/
	public String getAllPostComments(String message_id, String start, String count) {
		String method = "POST";
		String url = "/vmail/getcomments";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("message_id", message_id);
		paramList.put("start", start);
		paramList.put("count", count);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Return the user's feed.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the user whose feed is being requested.
	 *@param text filter Options: {all[default], vmailSent, vmailRecv, callEvent, vmailPostPublic, vmailPostRecv, other}
	 *@param integer start Page number of the user feed from which to start the retrieval.
	 *@param integer count Items per page of the user feed which is being retrieved.
	 *@param text sort What to sort the feed by
	 *@param string locale The currently selected locale for the user
	 *@param text dateRange Date range to filter the feed on. Should be in the format of 'DATE - DATE' where DATE is either a unix timestamp or ISO date YYY-DD-MM HH:MM:SS. Leaving out either DATE leaves it unbounded so 'DATE - ', is all feed items after DATE.
	 *
	 *@return Response string to API call
	*/
	public String getFeed(String jabberId, String filter, String start, String count, String sort, String locale, String dateRange) {
		String method = "POST";
		String url = "/feed/getFeed";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("filter", filter);
		paramList.put("start", start);
		paramList.put("count", count);
		paramList.put("sort", sort);
		paramList.put("locale", locale);
		paramList.put("dateRange", dateRange);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns the number of unread feed items for the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the user whose feed is being requested.
	 *
	 *@return Response string to API call
	*/
	public String getFeedUnreadCount(String jabberId) {
		String method = "POST";
		String url = "/feed/unreadCount";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Delete a call event from the feed of the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the recipient of the call event upon which is being deleted.
	 *@param string invite_id Invite ID of the call upon which is being acted.
	 *
	 *@return Response string to API call
	*/
	public String deleteCallEvent(String jabberId, String invite_id) {
		String method = "POST";
		String url = "/callevent/delete";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("invite_id", invite_id);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Mark a call event as viewed from the feed of the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the recipient of the call event which is being marked viewed.
	 *@param string invite_id Invite ID of the call upon which is being acted.
	 *
	 *@return Response string to API call
	*/
	public String markCallEventViewed(String jabberId, String invite_id) {
		String method = "POST";
		String url = "/callevent/markasviewed";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("invite_id", invite_id);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Register a user with the system, a password will be emailed to them, and an access_secret is returned to the caller.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string firstname First Name of the user who is being registered. The length of the name must be greater than 2 characters.
	 *@param string lastname Last Name of the user who is being registered. The length of the name must be greater than 2 characters.
	 *@param string email Valid email address of the user who is being registered.
	 *@param boolean searchAllow Whether the registered user should be searchable within the TokBox environment or not. Default is true
	 *
	 *@return Response string to API call
	*/
	public String registerUser(String firstname, String lastname, String email, String searchAllow) {
		String method = "POST";
		String url = "/users/register";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("firstname", firstname);
		paramList.put("lastname", lastname);
		paramList.put("email", email);
		paramList.put("searchAllow", searchAllow);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Returns - name, image, online status - information about a user.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid jabberId Jabber ID of the profile that is being looked up.
	 *
	 *@return Response string to API call
	*/
	public String getUserProfile(String jabberId) {
		String method = "POST";
		String url = "/users/getProfile";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Create a guest user on the TokBox jabber server to make/receive calls.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string partnerKey Partner Key of the API Developer who is trying to create the guest account
	 *
	 *@return Response string to API call
	*/
	public String createGuestUser(String partnerKey) {
		String method = "POST";
		String url = "/users/createGuest";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("partnerKey", partnerKey);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Request a contact relation with this jabberid.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the user adding friends to their list.
	 *@param text remoteJabberId Comma separated list of Jabber IDs to add to the contact list of the adding user.
	 *
	 *@return Response string to API call
	*/
	public String addContact(String jabberId, String remoteJabberId) {
		String method = "POST";
		String url = "/contacts/request";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("remoteJabberId", remoteJabberId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Remove a user from the TokBox contact list of the given user whose ID is supplied.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the user removing a contact from their TokBox contact list.
	 *@param jid remoteJabberId Jabber ID of the user being removed from the contact list of the removing user.
	 *
	 *@return Response string to API call
	*/
	public String removeContact(String jabberId, String remoteJabberId) {
		String method = "POST";
		String url = "/contacts/remove";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("remoteJabberId", remoteJabberId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Rejects a pending request from this jabberid. Won't remove an accepted request.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the user removing a contact from their TokBox contact list.
	 *@param text remoteJabberId Comma separated list of Jabber IDs to ignore the friend request from.
	 *
	 *@return Response string to API call
	*/
	public String rejectContact(String jabberId, String remoteJabberId) {
		String method = "POST";
		String url = "/contacts/reject";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("remoteJabberId", remoteJabberId);

		return TB_Request(method, url, paramList);
	}

	/**
	 *Get the relation between two users.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid jabberId Jabber ID of the user initiating the relation check.
	 *@param jid remoteJabberId Jabber ID against which the relation is being tested.
	 *
	 *@return Response string to API call
	*/
	public String isFriend(String jabberId, String remoteJabberId) {
		String method = "POST";
		String url = "/contacts/getRelation";
		Map <String, String> paramList = new HashMap<String, String>();
		paramList.put("jabberId", jabberId);
		paramList.put("remoteJabberId", remoteJabberId);

		return TB_Request(method, url, paramList);
	}

}
