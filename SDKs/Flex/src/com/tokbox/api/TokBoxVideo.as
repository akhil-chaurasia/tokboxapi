package com.tokbox.api
{
	import mx.collections.ArrayCollection;
	import mx.rpc.events.ResultEvent;
	
	public class TokBoxVideo
	{
		public static function getVMailSent(apiObj:TokBoxAPI, ok:Function, err: Function, startPage:int = 0, count:int = 10) : void {
			TokBoxVideo.getFeed(apiObj, ok, err, "vmailSent", startPage, count);
		}

		public static function getVmailRecv(apiObj:TokBoxAPI, ok:Function, err: Function, startPage:int = 0, count:int = 10) : void {
			TokBoxVideo.getFeed(apiObj, ok, err, "vmailRecv", startPage, count);
		}

		public static function getCallEvent(apiObj:TokBoxAPI, ok:Function, err: Function, startPage:int = 0, count:int = 10) : void {
			TokBoxVideo.getFeed(apiObj, ok, err, "callEvent", startPage, count);
		}

		public static function getPublicPost(apiObj:TokBoxAPI, ok:Function, err: Function, startPage:int = 0, count:int = 10) : void{
			TokBoxVideo.getFeed(apiObj, ok, err, "vmailPostPublic", startPage, count);
		}

		public static function getPublicRecv(apiObj:TokBoxAPI, ok:Function, err: Function, startPage:int = 0, count:int = 10) : void {
			TokBoxVideo.getFeed(apiObj, ok, err, "vmailPostRecv", startPage, count);
		}

		private static function getFeed(apiObj:TokBoxAPI, ok:Function, err:Function, type:String="all", startPage:int = 0, count:int = 10) : void {
			var handleGetFeed:Function = function(event:ResultEvent):void {
				if(null == event || event.result..error != undefined) {
					throw new Error(event.message.toString());
				}

				var xmlData:XMLList = event.result.feed;
				var feedCollection:ArrayCollection = new ArrayCollection();

				for each (var item:XML in xmlData.item) {
					var feedItem:TokBoxVMail = new TokBoxVMail(item.@vmailId.toString());
					
					feedItem.vmailType = item.@type.toString();
					feedItem.vmailBatchId = item.batchId.toString();

					feedItem.vmailImgUrl = item.content.image.toString();
					feedItem.vmailFlvUrl = item.content.video.toString();
					feedItem.vmailMessageId = item.content.messageId.toString();

					for each(var recipient:XML in item.users.recipients) {
						var newRecipient:TokBoxVMailUser = new TokBoxVMailUser(recipient.recipientName.toString(), false, recipient.timeRead.toString(), recipient.@jabberId.toString());
						
						feedItem.addVmailRecipients(newRecipient); 
					}

					for each(var sender:XML in item.users.sender) {
						var newSender:TokBoxVMailUser = new TokBoxVMailUser(sender.senderName.toString(), true, 0, sender.@jabberId.toString());
						
						feedItem.addVmailSenders(newSender); 
					}

					feedItem.vmailText = item.content.text.toString();
					feedItem.vmailTimeRead = Number(item.timeRead);
					feedItem.vmailTimeSent = Number(item.timeSent);
					
					feedCollection.addItem(feedItem);
				}

				ok(feedCollection);
			}
			
			apiObj.getFeed(apiObj.jabberId, handleGetFeed, err, type, startPage, count);
		}
	}
}