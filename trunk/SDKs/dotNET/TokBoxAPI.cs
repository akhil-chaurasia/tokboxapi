/*
 * Copyright (c) 2008 Syedur Islam
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *		*************************************
 *		*            TokBox API             *
 *		*************************************
 *
 *		Original Java Code by Melih Onvural (melih@tokbox.com), November 2008
 *		Coverted to C# by Syedur Islam (i@syedur.com), November 2008
 *
 *	This class implements all of the methods exposed by the TokBox API.
 *
 */

using System;
using System.Collections;
using System.Collections.Generic;

namespace TokBox
{
    public class TokBoxAPI : BaseAPI
    {
        public TokBoxAPI(string partnerKey, string secret)
            : base(partnerKey, secret)
        {
        }

        /**
         *Creates a request token tied to the creating partner.
         *This can be exchanged on the TokBox site for an access token, which is then passed to API calls as authentication on user actions.
         *
         *AuthLevel: require_partner
         *
         *@param string callbackUrl URL to send the user to after they log in.
         *
         *@return Response string to API call
        */
        public string GetRequestToken(string callbackUrl)
        {
            string method = "POST";
            string url = "/auth/getRequestToken";
            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("callbackUrl", callbackUrl);

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

        public string ValidateAccessToken(string accessSecret, string jabberId)

        {

            string method = "POST";

            string url = "/auth/validateAccessToken";

            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);

            paramList.Add("accessSecret", accessSecret);



            return TB_Request(method, url, paramList);

        }



        /// <summary>

        /// Create a call and return the media server address and call id of the video chat.

        /// The Jabber ID and name are used for logging.

        /// After creating a call, create and send invites to any party you wish to join you.

        /// </summary>

        /// <param name="callerName">Name of the caller creating the video chat.</param>

        /// <param name="callerJabberId">Jabber ID of the caller creating the video chat.</param>

        /// <param name="persistent">True if this callid should remain valid past the normal 4 day timeout</param>

        /// <param name="features">Advanced features.</param>

        /// <returns>Response string to API call</returns>

        public string CreateCall(string callerName, string callerJabberId, string persistent, string features)

        {

            string method = "POST";

            string url = "/calls/create";

            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("callerJabberId", callerJabberId);

            paramList.Add("callerName", callerName);

            paramList.Add("persistent", persistent);

            paramList.Add("features", features);



            return TB_Request(method, url, paramList);

        }



        /**
         *Create an invite to a particular call. Returns an inviteId to be sent to call recipients
         *The calleeJid is used for logging and missed call notifications
         *Clients are expected to use inviteIds to join calls
         *
         *AuthLevel: require_guest
         *
         *@param JID callerJabberId Jabber ID of the inviter who has initiated the call.
         *@param string calleeJabberId Jabber ID of the invitee who is being invited to the call
         *@param string call_id CallId returned from /calls/create API call
         *
         *@return Response string to API call
        */

        public string CreateInvite(string call_id, string calleeJabberId, string callerJabberId)

        {

            string method = "POST";

            string url = "/calls/invite";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("callerJabberId", callerJabberId);

            paramList.Add("calleeJabberId'", calleeJabberId);

            paramList.Add("call_id", call_id);



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

        public string JoinCall(string invite_id)

        {

            string method = "POST";

            string url = "/calls/join";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("invite_id", invite_id);



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
         *@param JID senderJabberId Jabber ID of the VMail sender.
         *@param string text Text of the VMail message.
         *
         *@return Response string to API call
        */

        public string SendVMail(string senderJabberId, string vmail_id, string tokboxRecipients, string emailRecipients, string text)

        {

            string method = "POST";

            string url = "/vmail/send";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("vmail_id", vmail_id);

            if (tokboxRecipients != null) paramList.Add("tokboxRecipients", tokboxRecipients);

            if (emailRecipients != null) paramList.Add("emailRecipients", emailRecipients);

            paramList.Add("senderJabberId", senderJabberId);

            if (text != null) paramList.Add("text", text);



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
         *@param JID senderJabberId Jabber ID of the VMail sender.
         *@param string text Text of the VMail message.
         *
         *@return Response string to API call
        */

        public string ForwardVMail(string senderJabberId, string vmail_id, string tokboxRecipients, string emailRecipients, string text)

        {

            string method = "POST";

            string url = "/vmail/forward";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("vmail_id", vmail_id);

            if (tokboxRecipients != null) paramList.Add("tokboxRecipients", tokboxRecipients);

            if (emailRecipients != null) paramList.Add("emailRecipients", emailRecipients);

            paramList.Add("senderJabberId", senderJabberId);

            if (text != null) paramList.Add("text", text);



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

        public string DeleteVMail(string type, string message_id)

        {

            string method = "POST";

            string url = "/vmail/delete";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("message_id", message_id);

            paramList.Add("type", type);



            return TB_Request(method, url, paramList);

        }



        /**
         *Send a video mail to all of your TokBox friends.
         *
         *AuthLevel: require_user
         *
         *@param string vmail_id VmailId of the recorded message that is being sent.
         *@param JID senderJabberId Jabber ID of the VMail sender.
         *@param string text Text of the VMail message.
         *
         *@return Response string to API call
        */

        public string SendVMailToAllFriends(string senderJabberId, string vmail_id, string text)

        {

            string method = "POST";

            string url = "/vmail/sendToFriends";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("vmail_id", vmail_id);

            paramList.Add("senderJabberId", senderJabberId);

            if (text != null) paramList.Add("text", text);



            return TB_Request(method, url, paramList);

        }



        /**
         *Forward a video mail to all of your TokBox friends.
         *
         *AuthLevel: require_user
         *
         *@param string vmail_id VmailId of the recorded message that is being sent.
         *@param JID senderJabberId Jabber ID of the VMail sender.
         *@param string text Text of the VMail message.
         *
         *@return Response string to API call
        */

        public string ForwardVMailToAllFriends(string senderJabberId, string vmail_id, string text)

        {

            string method = "POST";

            string url = "/vmail/forwardToFriends";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("vmail_id", vmail_id);

            paramList.Add("senderJabberId", senderJabberId);

            if (text != null) paramList.Add("text", text);



            return TB_Request(method, url, paramList);

        }



        /**
         *Post a public VMail to the public feed portion of Tokbox
         *
         *AuthLevel: require_user
         *
         *@param string vmail_id Vmail ID of the recorded message that is being posted on the public feed.
         *@param string scope Either {friends} or {public}. Defines the scope of who receives this public VMail post.
         *@param JID senderJabberId Jabber ID of the message sender.
         *@param string text Text of the vmail message.
         *
         *@return Response string to API call
        */

        public string PostPublicVMail(string text, string senderJabberId, string scope, string vmail_id)

        {

            string method = "POST";

            string url = "/vmail/postPublic";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("vmail_id", vmail_id);

            paramList.Add("scope", scope);

            paramList.Add("senderJabberId", senderJabberId);

            paramList.Add("text", text);



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

        public string MarkVmailRead(string message_id)

        {

            string method = "POST";

            string url = "/vmail/markasviewed";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("message_id", message_id);



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

        public string GetVMail(string message_id)

        {

            string method = "POST";

            string url = "/vmail/getVmail";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("message_id", message_id);



            return TB_Request(method, url, paramList);

        }



        /**
         *Return information about a video post including comments
         *
         *AuthLevel: require_guest
         *
         *@param string message_id MessageId of the video post
         *@param string numcomments Number of comments to return
         *
         *@return Response string to API call
        */

        public string GetVideoPost(string numcomments, string message_id)

        {

            string method = "POST";

            string url = "/vmail/getPost";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("message_id", message_id);

            paramList.Add("numcomments", numcomments);



            return TB_Request(method, url, paramList);

        }



        /**
         *Add a comment on the end of the specified post
         *
         *AuthLevel: require_guest
         *
         *@param JID posterjabberId The Jabber ID of the person making the post
         *@param string vmailmessageid Message ID of the post that this comment refers to
         *@param string vmailcontentid If this is a video post then this is the VMail ID of the video.
         *@param string commenttext The text component of the comment
         *
         *@return Response string to API call
        */

        public string AddComment(string commenttext, string vmailmessageid, string posterjabberId, string vmailcontentid)

        {

            string method = "POST";

            string url = "/vmail/addcomment";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("posterjabberId", posterjabberId);

            paramList.Add("vmailmessageid", vmailmessageid);

            if (vmailcontentid != null) paramList.Add("vmailcontentid", vmailcontentid);

            paramList.Add("commenttext", commenttext);



            return TB_Request(method, url, paramList);

        }



        /**
         *Returns a list of all the comments associated with a given message_id
         *
         *AuthLevel: require_guest
         *
         *@param string message_id The Message ID of the post against which comments are being collected.
         *@param string start The comment from which to start returning results.
         *@param string count The number of comments to return.
         *
         *@return Response string to API call
        */

        public string GetAllPostComments(string message_id, string start, string count)

        {

            string method = "POST";

            string url = "/vmail/getcomments";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("message_id", message_id);

            if (start != null) paramList.Add("start", start);

            if (count != null) paramList.Add("count", count);



            return TB_Request(method, url, paramList);

        }



        /**
         *Return the user's feed.
         *
         *AuthLevel: require_user
         *
         *@param JID jabberId Jabber ID of the user whose feed is being requested.
         *@param string filter Options: {all[default], vmailSent, vmailRecv, callEvent, vmailPostPublic, vmailPostRecv, other}
         *@param string start Page number of the user feed from which to start the retrieval.
         *@param string count Items per page of the user feed which is being retrieved.
         *@param string sort What to sort the feed by
         *@param string dateRange Date range to filter the feed on. Should be in the format of 'DATE - DATE' where DATE is either a unix timestamp or ISO date YYY-DD-MM HH:MM:SS. Leaving out either DATE leaves it unbounded so 'DATE - ', is all feed items after DATE.
         *
         *@return Response string to API call
        */

        public string GetFeed(string jabberId, string filter, string start, string count, string sort, string dateRange)

        {

            string method = "POST";

            string url = "/feed/getFeed";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);

            if (filter != null) paramList.Add("filter", filter);

            if (start != null) paramList.Add("start", start);

            if (count != null) paramList.Add("count", count);

            if (sort != null) paramList.Add("sort", sort);

            if (dateRange != null) paramList.Add("dateRange", dateRange);



            return TB_Request(method, url, paramList);

        }



        /**
         *Returns the number of unread feed items for the given user ID.
         *
         *AuthLevel: require_user
         *
         *@param JID jabberId Jabber ID of the user whose feed is being requested.
         *
         *@return Response string to API call
        */

        public string GetFeedUnreadCount(string jabberId)

        {

            string method = "POST";

            string url = "/feed/unreadCount";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);



            return TB_Request(method, url, paramList);

        }



        /**
         *Delete a call event from the feed of the given user ID.
         *
         *AuthLevel: require_user
         *
         *@param JID jabberId Jabber ID of the recipient of the call event upon which is being deleted.
         *@param string invite_id Invite ID of the call upon which is being acted.
         *
         *@return Response string to API call
        */

        public string DeleteCallEvent(string invite_id, string jabberId)

        {

            string method = "POST";

            string url = "/callevent/delete";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);

            paramList.Add("invite_id", invite_id);



            return TB_Request(method, url, paramList);

        }



        /**
         *Mark a call event as viewed from the feed of the given user ID.
         *
         *AuthLevel: require_user
         *
         *@param JID $jabberId Jabber ID of the recipient of the call event which is being marked viewed.
         *@param string $invite_id Invite ID of the call upon which is being acted.
         *
         *@return Response string to API call
        */

        public string MarkCallEventViewed(string invite_id, string jabberId)

        {

            string method = "POST";

            string url = "/callevent/markasviewed";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);

            paramList.Add("invite_id", invite_id);



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
         *@param string searchAllow Whether the registered user should be searchable within the TokBox environment or not. Default is true
         *
         *@return Response string to API call
        */

        public string RegisterUser(string email, string lastname, string firstname, string searchAllow)

        {

            string method = "POST";

            string url = "/users/register";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("firstname", firstname);

            paramList.Add("lastname", lastname);

            paramList.Add("email", email);

            if (searchAllow != null) paramList.Add("searchAllow", searchAllow);



            return TB_Request(method, url, paramList);

        }





        /**
         *Returns - name, image, online status - information about a user.
         *
         *AuthLevel: require_guest
         *
         *@param JID jabberId Jabber ID of the profile that is being looked up.
         *
         *@return Response string to API call
        */

        public string GetUserProfile(string jabberId)

        {

            string method = "POST";

            string url = "/users/getProfile";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);



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

        public string CreateGuestUser(string partnerKey)

        {

            string method = "POST";

            string url = "/users/createGuest";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("partnerKey", partnerKey);



            return TB_Request(method, url, paramList);

        }



        /**
         *Request a contact relation with this jabberid.
         *
         *AuthLevel: require_user
         *
         *@param JID jabberId Jabber ID of the user adding friends to their list.
         *@param string remoteJabberId Comma separated list of Jabber IDs to add to the contact list of the adding user.
         *
         *@return Response string to API call
        */

        public string AddContact(string remoteJabberId, string jabberId)

        {

            string method = "POST";

            string url = "/contacts/request";



            Dictionary<string, string> paramList = new Dictionary<string, string>();

            paramList.Add("jabberId", jabberId);

            paramList.Add("remoteJabberId", remoteJabberId);



            return TB_Request(method, url, paramList);

        }

    }

}