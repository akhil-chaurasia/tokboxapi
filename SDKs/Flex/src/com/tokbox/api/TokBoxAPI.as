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

package com.tokbox.api
{
	public class TokBoxAPI extends BaseAPI {

		public function TokBoxAPI(partnerKey:String, partnerSecret:String) {
			super(partnerKey, partnerSecret);
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
		 */
		public function getRequestToken(callbackUrl:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/auth/getRequestToken";
			var paramList:Object = {
				callbackUrl: callbackUrl
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Retrieves an access token appropriate to the credentials of the user who is requesting the token.
		 *If the user is valid, and registered, they will receive an access token associated with their account. Otherwise they will receive a guest token.
		 *
		 *AuthLevel: require_trusted_partner
		 *
		 *@param string jabberId Jabber ID for the user who is attempting to retrieve the token.
		 *@param string password MD5 hashed password for the user who is attempting to retrieve the token.
		 */
		public function getAccessToken(password:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/auth/getAccessToken";
			var paramList:Object = {
				jabberId: jabberId,
				password: password
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Ensures that the access token and the associated user are correlated by the TokBox system.
		 *
		 *AuthLevel: require_partner
		 *
		 *@param string jabberId Jabber ID of the user who is being validated
		 *@param string accessSecret Access token which is being validated
		 */
		public function validateAccessToken(accessSecret:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/auth/validateAccessToken";
			var paramList:Object = {
				jabberId: jabberId,
				accessSecret: accessSecret
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function createCall(callerName:String, callerJabberId:String, okFunc:Function, errFunc:Function,features:String = "",persistent:Boolean = false):void {
			var method:String = "POST";
			var url:String = "/calls/create";
			var paramList:Object = {
				callerJabberId: callerJabberId,
				callerName: callerName,
				features: features,
				persistent: persistent
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function createInvite(call_id:String, calleeJabberId:String, callerJabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/calls/invite";
			var paramList:Object = {
				callerJabberId: callerJabberId,
				calleeJabberId: calleeJabberId,
				call_id: call_id
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Returns whether or not a call id still exists
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string callid Callid returned from /call/create.
		 */
		public function validateCallID(callid:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/calls/validate";
			var paramList:Object = {
				callid: callid
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Returns whether or not a call id still exists
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string callid Callid returned from /call/create.
		 */
		public function getCallInfo(callid:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/calls/getCallInfo";
			var paramList:Object = {
				callid: callid
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Used to get the information necessary to join a call based on the invite id provided. Returns the media server and callId of the call
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string invite_id Invite ID returned from /calls/invite and used to find the server and call id info necessary to connect to a call.
		 */
		public function joinCall(invite_id:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/calls/join";
			var paramList:Object = {
				invite_id: invite_id
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function sendVMail(senderJabberId:String, vmail_id:String, okFunc:Function, errFunc:Function,tokboxRecipients:String = "",emailRecipients:String = "",text:String = ""):void {
			var method:String = "POST";
			var url:String = "/vmail/send";
			var paramList:Object = {
				vmail_id: vmail_id,
				tokboxRecipients: tokboxRecipients,
				emailRecipients: emailRecipients,
				senderJabberId: senderJabberId,
				text: text
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function forwardVMail(senderJabberId:String, vmail_id:String, okFunc:Function, errFunc:Function,tokboxRecipients:String = "",emailRecipients:String = "",text:String = ""):void {
			var method:String = "POST";
			var url:String = "/vmail/forward";
			var paramList:Object = {
				vmail_id: vmail_id,
				tokboxRecipients: tokboxRecipients,
				emailRecipients: emailRecipients,
				senderJabberId: senderJabberId,
				text: text
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Removes a VMail from the feed/inbox. Returns the number of unread feed items
		 *
		 *AuthLevel: require_user
		 *
		 *@param string message_id Message ID of the VMail being removed from the feed.
		 *@param string type Type of message to delete from the feed. {'vmailRecv', 'vmailSent','callEvent', 'vmailPostRecv','vmailPostPublic', 'other'}
		 */
		public function deleteVMail(type:String, message_id:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/vmail/delete";
			var paramList:Object = {
				message_id: message_id,
				type: type
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function postPublicVMail(text:String, senderJabberId:String, scope:String, vmail_id:String, okFunc:Function, errFunc:Function,templateId:Number = 0):void {
			var method:String = "POST";
			var url:String = "/vmail/postPublic";
			var paramList:Object = {
				vmail_id: vmail_id,
				scope: scope,
				senderJabberId: senderJabberId,
				text: text,
				templateId: templateId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Mark a VMail read. This triggers a notice to the sender. Returns the number of unread feed items
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string message_id Message ID of the VMail being marked as read
		 */
		public function markVmailRead(message_id:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/vmail/markasviewed";
			var paramList:Object = {
				message_id: message_id
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Returns information about a VMail which lets you access the message.
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string message_id Message ID of the video mail being retrieved.
		 */
		public function getVMail(message_id:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/vmail/getVmail";
			var paramList:Object = {
				message_id: message_id
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Return information about a video post including comments
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string message_id MessageId of the video post
		 *@param integer numcomments Number of comments to return
		 */
		public function getVideoPost(numcomments:Number, message_id:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/vmail/getPost";
			var paramList:Object = {
				message_id: message_id,
				numcomments: numcomments
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function addComment(commenttext:String, vmailmessageid:String, posterjabberId:String, okFunc:Function, errFunc:Function,vmailcontentid:String = "",templateId:Number = 0):void {
			var method:String = "POST";
			var url:String = "/vmail/addcomment";
			var paramList:Object = {
				posterjabberId: posterjabberId,
				vmailmessageid: vmailmessageid,
				vmailcontentid: vmailcontentid,
				commenttext: commenttext,
				templateId: templateId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Returns a list of all the comments associated with a given message_id
		 *
		 *AuthLevel: require_guest
		 *
		 *@param string message_id The Message ID of the post against which comments are being collected.
		 *@param integer start The comment from which to start returning results.
		 *@param integer count The number of comments to return.
		 */
		public function getAllPostComments(message_id:String, okFunc:Function, errFunc:Function,start:Number = 0,count:Number = 0):void {
			var method:String = "POST";
			var url:String = "/vmail/getcomments";
			var paramList:Object = {
				message_id: message_id,
				start: start,
				count: count
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function getFeed(jabberId:String, okFunc:Function, errFunc:Function,filter:String = "",start:Number = 0,count:Number = 0,sort:String = "",locale:String = "",dateRange:String = ""):void {
			var method:String = "POST";
			var url:String = "/feed/getFeed";
			var paramList:Object = {
				jabberId: jabberId,
				filter: filter,
				start: start,
				count: count,
				sort: sort,
				locale: locale,
				dateRange: dateRange
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Returns the number of unread feed items for the given user ID.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the user whose feed is being requested.
		 */
		public function getFeedUnreadCount(jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/feed/unreadCount";
			var paramList:Object = {
				jabberId: jabberId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Delete a call event from the feed of the given user ID.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the recipient of the call event upon which is being deleted.
		 *@param string invite_id Invite ID of the call upon which is being acted.
		 */
		public function deleteCallEvent(invite_id:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/callevent/delete";
			var paramList:Object = {
				jabberId: jabberId,
				invite_id: invite_id
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Mark a call event as viewed from the feed of the given user ID.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the recipient of the call event which is being marked viewed.
		 *@param string invite_id Invite ID of the call upon which is being acted.
		 */
		public function markCallEventViewed(invite_id:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/callevent/markasviewed";
			var paramList:Object = {
				jabberId: jabberId,
				invite_id: invite_id
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
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
		 */
		public function registerUser(email:String, lastname:String, firstname:String, okFunc:Function, errFunc:Function,searchAllow:Boolean = false):void {
			var method:String = "POST";
			var url:String = "/users/register";
			var paramList:Object = {
				firstname: firstname,
				lastname: lastname,
				email: email,
				searchAllow: searchAllow
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Returns - name, image, online status - information about a user.
		 *
		 *AuthLevel: require_guest
		 *
		 *@param jid jabberId Jabber ID of the profile that is being looked up.
		 */
		public function getUserProfile(jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/users/getProfile";
			var paramList:Object = {
				jabberId: jabberId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Create a guest user on the TokBox jabber server to make/receive calls.
		 *
		 *AuthLevel: require_partner
		 *
		 *@param string partnerKey Partner Key of the API Developer who is trying to create the guest account
		 */
		public function createGuestUser(partnerKey:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/users/createGuest";
			var paramList:Object = {
				partnerKey: partnerKey
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Request a contact relation with this jabberid.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the user adding friends to their list.
		 *@param text remoteJabberId Comma separated list of Jabber IDs to add to the contact list of the adding user.
		 */
		public function addContact(remoteJabberId:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/contacts/request";
			var paramList:Object = {
				jabberId: jabberId,
				remoteJabberId: remoteJabberId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Remove a user from the TokBox contact list of the given user whose ID is supplied.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the user removing a contact from their TokBox contact list.
		 *@param jid remoteJabberId Jabber ID of the user being removed from the contact list of the removing user.
		 */
		public function removeContact(remoteJabberId:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/contacts/remove";
			var paramList:Object = {
				jabberId: jabberId,
				remoteJabberId: remoteJabberId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Rejects a pending request from this jabberid. Won't remove an accepted request.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the user removing a contact from their TokBox contact list.
		 *@param text remoteJabberId Comma separated list of Jabber IDs to ignore the friend request from.
		 */
		public function rejectContact(remoteJabberId:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/contacts/reject";
			var paramList:Object = {
				jabberId: jabberId,
				remoteJabberId: remoteJabberId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

		/**
		 *Get the relation between two users.
		 *
		 *AuthLevel: require_user
		 *
		 *@param jid jabberId Jabber ID of the user initiating the relation check.
		 *@param jid remoteJabberId Jabber ID against which the relation is being tested.
		 */
		public function isFriend(remoteJabberId:String, jabberId:String, okFunc:Function, errFunc:Function):void {
			var method:String = "POST";
			var url:String = "/contacts/getRelation";
			var paramList:Object = {
				jabberId: jabberId,
				remoteJabberId: remoteJabberId
			};
			super.TBRequest(method, url, paramList, okFunc, errFunc);
		}

	}
}
