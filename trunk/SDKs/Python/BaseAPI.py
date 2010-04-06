from ConfigParser import ConfigParser
import TokBoxRuntimeException
import string 
from random import Random
import urllib
import hashlib
import time
import urllib2
import xml.dom.minidom as xmldom

def escape(s):
	return urllib.quote(s, safe='~')

class BaseAPI():

	def __init__(self, partnerkey, partnersecret):
		self.partnerkey = str(partnerkey)
		self.secret = str(partnersecret)
		self.jabberid = str("")
		#
		self.apiconstants = ConfigParser()
		self.apiconstants.read('API_Config.conf')
		#
		self.apiversion = str(self.apiconstants.get("Signature Constants", "version"))
		self.sigmethod = str(self.apiconstants.get("Signature Constants", "sigMethod"))

	def TB_Request(self, apiURL, paramlist, method = "POST"):
		reqString = self.apiconstants.get("API Constants", "api_server")+self.apiconstants.get("Site Constants", "api_server_methods_url")+apiURL
		#
		nonce = self.generateNonce()
		timestamp = int(time.time())
		#
		signedSig = self.buildSignedRequest(method, reqString, nonce, timestamp, paramlist)
		#
		dataList = []
		#
		for key, value in paramlist.iteritems():
			encodekey = escape(str(key))
			encodevalue = escape(str(value)).replace("+", "%20")
 			dataList.append(encodekey+"="+encodevalue)
		#
		dataList.append('oauth_version='+self.apiversion)
		dataList.append('oauth_timestamp='+str(timestamp))
		dataList.append('oauth_nonce='+str(nonce))
		dataList.append('tokbox_jabberid='+escape(self.jabberid))
		dataList.append('oauth_partner_key='+self.partnerkey)
		dataList.append('oauth_signature_method='+self.sigmethod)
		#
		authFields = []
		authFields.append('oauth_partner_key="'+self.partnerkey+'"')
		authFields.append('oauth_signature_method="'+self.sigmethod+'"')
		authFields.append('oauth_timestamp="'+str(timestamp)+'"')
		authFields.append('oauth_version="'+self.apiversion+'"')
		authFields.append('oauth_nonce="'+str(nonce)+'"')
		authFields.append('oauth_signature="'+signedSig+'"')
		authFields.append('tokbox_jabberid="'+escape(self.jabberid)+'"')
		#
		authString = "&_AUTHORIZATION="+",".join(authFields)
		#
		dataString = "&".join(dataList)+authString
		#
		context_source = [
			('method', 'POST'),
			('Content-Type', 'application-xml'),
			('Content-Length', len(dataString))
		]
		#
		opener = urllib2.build_opener()
		opener.addheaders = context_source
		request = urllib2.Request(url=reqString, data=dataString)
		response = opener.open(request)
		#
		dom = xmldom.parseString(response.read())
		response.close()
		#
		return dom

	def buildSignedRequest(self, method, uri, nonce, timestamp, paramlist):
		signfields = paramlist.copy()
		#
		signfields['oauth_partner_key'] = self.partnerkey
		signfields['oauth_signature_method'] = self.sigmethod
		signfields['oauth_timestamp'] = timestamp
		signfields['oauth_version'] = self.apiversion
		signfields['oauth_nonce'] = nonce
		signfields['tokbox_jabberid'] = self.jabberid
		#
		requestString = method + "&" + uri + "&" + self.generateRequestString(signfields)
		#
		return hashlib.md5(requestString + self.secret).hexdigest()

	def generateRequestString(self, paramlist):
		if len(paramlist) == 0:
			raise TokBoxRuntimeException("There are no valid parameters")
		#
		params = []
		#
		for key, value in paramlist.iteritems():
			if key.startswith("_"):
				continue
			elif len(str(value)) == 0 or value is None:
				continue
			else:
				encodekey = escape(str(key))
				encodevalue = escape(str(value)).replace("+", "%20")
				params.append(encodekey+"="+encodevalue)
		#
		params.sort()
		return '&'.join(params)

	def generateNonce(self):
		return ''.join( Random().sample( string.letters + string.digits, 16) )