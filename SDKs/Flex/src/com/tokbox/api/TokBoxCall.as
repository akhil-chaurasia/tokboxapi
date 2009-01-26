package com.tokbox.api
{
	import mx.rpc.events.ResultEvent;
	
	public class TokBoxCall
	{
		public static function createCall(apiObj:TokBoxAPI, ok:Function, err:Function, displayName:String = "A Guest", persistent:Boolean = false, features:String = "") : void {
			
			var handleCreateCall:Function = function(event:ResultEvent):void {
				if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
				}
				
				var xmlData:XMLList = event.result.createCall;
				
				ok(xmlData.callId.toString());
			}

			apiObj.createCall(displayName, apiObj.jabberId, handleCreateCall, err, features, persistent);
		}
		
		public static function generateInvite(apiObj:TokBoxAPI, callId:String, calleeJID:String, ok:Function, err:Function) : void {
		
			var handleCreateInvite:Function = function(event:ResultEvent):void {
				if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
				}
				
				var xmlData:XMLList = event.result.createInvite;
				
				ok(xmlData.inviteId.toString());
			}
			
			apiObj.createInvite(callId, calleeJID, apiObj.jabberId, handleCreateInvite, err);
		}
		
		public static function generateLink(callId:String) : String {
			return API_Config.API_SERVER+BaseAPI.API_SERVER_CALL_WIDGET+callId;
		}
	}
}