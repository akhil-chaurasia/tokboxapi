package com.tokbox.api
{
	import mx.rpc.events.ResultEvent;
	public class TokBoxUser
	{
		public static function createGuestUser(ok:Function, err:Function):void {
			var apiObj:TokBoxAPI = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);
			
			var handleGuestOk:Function = function(event:ResultEvent):void {
				if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
				}
				
				var xmlData:XMLList = event.result.createGuest;
				apiObj.jabberId = xmlData[0].jabberId.toString();
				apiObj.partnerSecret = xmlData[0].secret.toString();

				ok(apiObj);
			}
			
			apiObj.createGuestUser(API_Config.PARTNER_KEY, handleGuestOk, err);
		}

		public static function createUser(jabberId:String, accessSecret:String, ok:Function, err:Function):void {
			var apiObj:TokBoxAPI = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);

			var validUserOk:Function = function(event:ResultEvent):void {
				if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
				}

				var xmlData:XMLList = event.result.validateAccessToken;
				var isValid:String = xmlData[0].isValid.toString();
				
				if(isValid == "true") {
					apiObj.jabberId = jabberId;
					apiObj.partnerSecret = accessSecret;
					ok(apiObj);
				}
				else {
					throw new Error("Jabber ID/Access Secret Pair are not a valid user for Partner Key: "+API_Config.PARTNER_KEY);
				}
			}

			apiObj.validateAccessToken(jabberId, accessSecret, validUserOk, err);
		}

		public static function registerUser(email:String, lastname:String, firstname:String, ok:Function, err:Function, searchAllow:Boolean = true):void {
			var apiObj:TokBoxAPI = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);

			var handleRegisterOk:Function = function(event:ResultEvent):void {
				if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
				}

				var xmlData:XMLList = event.result.registerUser;
				apiObj.jabberId = xmlData[0].jabberId.toString();
				apiObj.partnerSecret = xmlData[0].secret.toString();
				
				ok(apiObj);
			}

			apiObj.registerUser(email, lastname, firstname, handleRegisterOk, err, searchAllow);
		}

		public static function loginUser(err:Function):void {
			var apiObj:TokBoxAPI = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);
			
			apiObj.getRequestToken(API_Config.CALLBACK_URL, apiObj.updateToken, err);
			apiObj.loginUser();
		}
	}
}