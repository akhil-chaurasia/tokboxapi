from BaseAPI import BaseAPI

class TokBoxAPI(BaseAPI):
	def __init__(self, partnerkey, partnersecret):
		BaseAPI.__init__(self, partnerkey, partnersecret)

	def getRequestToken(self, callbackUrl):
		url = "/auth/getRequestToken"
		paramList = {}
		paramList['callbackUrl'] = callbackUrl
		return self.TB_Request(url, paramList)

	def createCall(self, callerName, callerJabberId, features=None, persistent=None):
		url = "/calls/create"
		paramList = {}
		paramList['callerJabberId'] = callerJabberId
		paramList['callerName'] = callerName
		if features != None:
			paramList['features'] = features
		if persistent != None:
			paramList['persistent'] = persistent
		return self.TB_Request(url, paramList)

	def createInvite(self, call_id, calleeJabberId, callerJabberId):
		url = "/calls/invite"
		paramList = {}
		paramList['callerJabberId'] = callerJabberId
		paramList['calleeJabberId'] = calleeJabberId
		paramList['callId'] = call_id
		return self.TB_Request(url, paramList)

	def joinCall(self, invite_id):
		url = "/calls/join"
		paramList = {}
		paramList['inviteId'] = invite_id
		return self.TB_Request(url, paramList)

	def sendVMail(self, senderJabberId, vmail_id, tokBoxRecipients=None, emailRecipients=None, text=None):
		url = "/vmail/send"
		paramList = {}
		paramList['vmailId'] = vmail_id
		if tokBoxRecipients != None:
			paramList['tokboxRecipients'] = tokBoxRecipients
		if emailRecipients != None:
			paramList['emailRecipients'] = emailRecipients
		paramList['senderJabberId'] = senderJabberId
		if text != None:
			paramList['text'] = text
		return self.TB_Request(url, paramList)

	def forwardVMail(self, senderJabberId, vmail_id, tokboxRecipients=None, emailRecipients=None, text=None):
		url = "/vmail/forward"
		paramList = {}
		paramList['vmailId'] = vmail_id
		if tokboxRecipients != None:
			paramList['tokboxRecipients'] = tokboxRecipients
		if emailRecipients != None:
			paramList['emailRecipients'] = emailRecipients
		paramList['senderJabberId'] = senderJabberId
		if text != None:
			paramList['text'] = text
		return self.TB_Request(url, paramList)

	def deleteVMail(self, type, messageId):
		url = "/vmail/delete"
		paramList = {}
		paramList['messageId'] = messageId
		paramList['type'] = type
		return self.TB_Request(url, paramList)
		
	def sendVMailToAllFriends(self, senderJabberId, vmail_id, text=None):	
		url = "/vmail/sendToFriends"
		paramList = {}
		paramList['vmailId'] = vmail_id
		paramList['senderJabberId'] = senderJabberId
		if text != None:
			paramList['text'] = text
		return self.TB_Request(url, paramList)

	def forwardVMailToAllFriends(self, senderJabberId, vmail_id, text=None):
		url = "/vmail/forwardToFriends"
		paramList = {}
		paramList['vmailId'] = vmail_id
		paramList['senderJabberId'] = senderJabberId
		if text != None:
			paramList['text'] = text
		return self.TB_Request(url, paramList)

	def markVmailRead(self, messageId):
		url = "/vmail/markasviewed"
		paramList = {}
		paramList['messageId'] = messageId
		return self.TB_Request(url, paramList)

	def getVMail(self, messageId):
		url = "/vmail/getVmail"
		paramList = {}
		paramList['messageId'] = messageId
		return self.TB_Request(url, paramList)

	def getVideoPost(self, numComments, messageId):
		url = "/vmail/getPost"
		paramList = {}
		paramList['messageId'] = messageId
		paramList['numcomments'] = numComments
		return self.TB_Request(url, paramList)

	def	getAllPostComments(self, messageId, start=None, count=None):
		url = "/vmail/getcomments"
		paramList = {}
		paramList['messageId'] = messageId
		if start != None:
			paramList['start'] = start
		if count != None:
			paramList['count'] = count
		return self.TB_Request(url, paramList)

	def getFeed(self, jabberId, filter=None, start=None, count=None, sort=None, dateRange=None):
		url = "/feed/getFeed"
		paramList = {}
		paramList['jabberId'] = jabberId
		if filter != None:
			paramList['filter'] = filter
		if start != None:
			paramList['start'] = start
		if count != None:
			paramList['count'] = count
		if sort != None:
			paramList['sort'] = sort
		if dateRange != None:
			paramList['dateRange'] = dateRange
		return self.TB_Request(url, paramList)

	def getFeedUnreadCount(self, jabberId):
		url = "/feed/unreadCount"
		paramList = {}
		paramList['jabberId'] = jabberId
		return self.TB_Request(url, paramList)

	def deleteCallEvent(self, inviteId, jabberId):
		url = "/callevent/delete"
		paramList = {}
		paramList['jabberId'] = jabberId
		paramList['inviteId'] = inviteId
		return self.TB_Request(url, paramList)

	def markCallEventViewed(self, inviteId, jabberId):
		url = "/callevent/markasviewed"
		paramList = {}
		paramList['jabberId'] = jabberId
		paramList['inviteId'] = inviteId
		return self.TB_Request(url, paramList)

	def registerUser(self, email, lastName, firstName):
		url = "/users/register"
		paramList = {}
		paramList['firstname'] = firstName
		paramList['lastname'] = lastName
		paramList['email'] = email
		return self.TB_Request(url, paramList)

	def getUserProfile(self, jabberId):
		url = "/users/getProfile"
		paramList = {}
		paramList['jabberId'] = jabberId
		return self.TB_Request(url, paramList)

	def createGuestUser(self, partnerKey):
		url = "/users/createGuest"
		paramList = {}
		paramList['partnerKey'] = partnerKey
		return self.TB_Request(url, paramList)

	def addContact(self, remoteJabberId, jabberId):
		url = "/contacts/request"
		paramList = {}
		paramList['jabberId'] = jabberId
		paramList['remoteJabberId'] = remoteJabberId
		return self.TB_Request(url, paramList)

	def removeContact(self, remoteJabberId, jabberId):
		url = "/contacts/remove"
		paramList = {}
		paramList['jabberId'] = jabberId
		paramList['remoteJabberId'] = remoteJabberId
		return self.TB_Request(url, paramList)

	def isFriend(self, remoteJabberId, jabberId):
		url = "/contacts/getRelation"
		paramList = {}
		paramList['jabberId'] = jabberId
		paramList['remoteJabberId'] = remoteJabberId
		return self.TB_Request(url, paramList)