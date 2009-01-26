package com.tokbox.api {
	import flash.net.URLRequest;
	import flash.net.navigateToURL;
	
	import mx.rpc.events.FaultEvent;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.http.HTTPService;
	
	import com.tokbox.util.MD5;
	import com.tokbox.util.URLEncoding;

	public class BaseAPI
	{
		private var _partnerKey:String;
		private var _partnerSecret:String;
		private var _requestToken:String;

		private var _jabberId:String;

		private var _version:String = "1.0.0";
		private var _sigMethod:String = "SIMPLE-MD5";

		public static var API_SERVER_LOGIN_URL:String = "view/oauth&";
		public static var API_SERVER_METHODS_URL:String = "a/v0";
		public static var API_SERVER_CALL_WIDGET:String = "vc/";
		public static var API_SERVER_RECORDER_WIDGET:String = "vr/";
		public static var API_SERVER_PLAYER_WIDGET:String = "vp/";

		public function BaseAPI(partnerKey:String, partnerSecret:String)
		{
			this._partnerKey = partnerKey;
			this._partnerSecret = partnerSecret;
			this._jabberId = "";
			this._requestToken =  null;
		}

		public function get partnerKey():String { return this._partnerKey; }
		public function get partnerSecret():String { return this._partnerSecret; }
		public function get requestToken():String { return this._requestToken; }
		public function get jabberId():String { return this._jabberId; }

		public function set jabberId(jabberId:String):void { 
			this._jabberId = jabberId;	
		}

		public function set partnerSecret(partnerSecret:String):void {
			this._partnerSecret = partnerSecret;
		}

		public function set requestToken(requestToken:String):void {
			this._requestToken = requestToken;			
		}

		public function updateToken(event:ResultEvent):void {
			if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
			}

			var xmlData:XMLList = event.result.requestToken;
			this.requestToken = xmlData[0].token.toString();
		}

		public function loginUser():void {
			if(null == this.requestToken) {
				throw new Error("You must first retrieve a Request Token before you can make this call", 401);
			}

			var url:String = API_Config.API_SERVER+BaseAPI.API_SERVER_LOGIN_URL+"oauth_token="+this.requestToken;
			var redirect:URLRequest = new URLRequest(url);

			navigateToURL(redirect);
		}

		protected function TBRequest(method:String, apiURL:String, paramsList:Object, ok:Function, err:Function):void {
			var reqString:String = API_Config.API_SERVER+BaseAPI.API_SERVER_METHODS_URL+apiURL;

			var nonce:String = this.generateNonce();
			var timestamp:String = new Date().getTime().toString();

			paramsList['oauth_version'] = this._version;
			paramsList['oauth_timestamp'] = timestamp;
			paramsList['oauth_nonce'] = nonce;
			paramsList['tokbox_jabberid'] = this.jabberId;
			paramsList['oauth_partner_key'] = this.partnerKey;
			paramsList['oauth_signature_method'] = this._sigMethod;

			paramsList['_AUTHORIZATION'] = this.buildAuthenticateHeader(paramsList, timestamp, nonce, reqString, method);

			var service:HTTPService = new HTTPService();

			service.url = reqString;
			service.method = method;
			service.resultFormat = "e4x";
			service.requestTimeout = 15;
			service.useProxy = false;

			var RequestSuccessHandler:Function = function (event:ResultEvent):void {				
				if (event.result == "" || !event.result){
					throw new Error("Response came back empty");
				}

				ok(event);
			};

			var faultHandler:Function = function (event:FaultEvent):void {
				err(event);
			};
		
			service.addEventListener(ResultEvent.RESULT, RequestSuccessHandler);
			service.addEventListener(FaultEvent.FAULT, faultHandler);

			for (var value:String in paramsList) {
				var temp:String = "paramList['"+value+"'] = "+paramsList[value];
			}

			service.send(paramsList);
		}

		private function buildSignedRequest(paramsList:Object, timestamp:String, nonce:String, uri:String, method:String):String {
			var params:Object = new Object();

			for (var value:String in paramsList) {
				params[value] = paramsList[value];
			}

			params['oauth_partner_key'] = this.partnerKey;
			params['oauth_signature_method'] = this._sigMethod;
			params['oauth_timestamp'] = timestamp;
			params['oauth_version'] = this._version;
			params['oauth_nonce'] = nonce;
			params['tokbox_jabberid'] = this.jabberId;
			
			var requestString:String = method + "&" + uri + "&" + this.generateRequestString(paramsList);
			switch(this._sigMethod) {
				case "SIMPLE-MD5":
					var signedString:String = MD5.encrypt(requestString + this.partnerSecret);
					break;
				default:
					throw new Error("Invalid Signing Method");
			}
			return signedString;
		} 

		private function buildAuthenticateHeader(paramsList:Object, timestamp:String, nonce:String, uri:String, method:String):String {
			var signedRequest:String = buildSignedRequest(paramsList, timestamp, nonce, uri, method);

			var authHeader:String = 'oauth realm="tokbox.com", oauth_partner_key="' + this.partnerKey +
									'", oauth_signature_method="' + this._sigMethod + 
									'", oauth_timestamp="' + timestamp +
									'", oauth_version="' + this._version + 
									'", oauth_signature="' + signedRequest + 
									'", oauth_nonce="' + nonce + 
									'", tokbox_jabberid="' + URLEncoding.encode(this.jabberId) + '"';
			return authHeader;
		}

		private function generateRequestString(paramList:Object):String {
			var encodedParamList:Array = new Array();
			
			for(var key:String in paramList) {
				if(key.charAt(0) == "_") {
					continue;
				}
				
				if(paramList[key] == null || paramList[key].toString() == "") {
					continue;
				}
				
				var encodedKey:String = URLEncoding.encode(key);
				var encodedValue:String = URLEncoding.encode(paramList[key]);
				encodedParamList.push(encodedKey + "=" + encodedValue);
			}
			
			encodedParamList.sort();
			
			return encodedParamList.join("&");
		}

		private function generateNonce():String {
			var randomString:String = "";
			var size:uint = 16;

			var seed:String = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			
			for(var i:uint = 0; i < size; i++) {
				var pos:uint = int(Math.random()*seed.length);
				randomString += seed.substr(pos, 1);
			}
		
			return randomString;
		}
	}
}