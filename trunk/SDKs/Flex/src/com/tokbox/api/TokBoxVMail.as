package com.tokbox.api
{
	import mx.rpc.events.ResultEvent;
	
	public class TokBoxVMail
	{
		//ID of the specific message in which this instance was sent
		private var _vmailBatchId:String;

		//ID of the VMail
		private var _vmailId:String;

		//Location of the VMail Image URL
		private var _vmailImgUrl:String;

		//Location of the VMail FLV URL
		private var _vmailFlvUrl:String;

		//ID of the message in which this VMail was sent. 
		//Should be used for interacting with the recorder
		private var _vmailMessageId:String;

		//Array of the recipients
		private var _vmailRecipients:Array;

		//Array of the senders of a VMail
		private var _vmailSenders:Array;

		//Text associated with the VMail
		private var _vmailText:String;

		//Timestamp of when the VMail was read
		private var _vmailTimeRead:Number;

		//Timestamp of when the VMail was sent
		private var _vmailTimeSent:Number;

		//Type of message that the VMail is
		private var _vmailType:String;

		public function TokBoxVMail(vmailId:String)
		{
			this._vmailId = vmailId
		}

		public function get vmailBatchId():String { return this._vmailBatchId; }
		public function get vmailId():String { return this._vmailId; }
		public function get vmailImgUrl():String { return this._vmailImgUrl; }
		public function get vmailFlvUrl():String { return this._vmailFlvUrl; }
		public function get vmailMessageId():String { return this._vmailMessageId; }
		public function get vmailRecipients():Array { return this._vmailRecipients; }
		public function get vmailSenders():Array { return this._vmailSenders; }
		public function get vmailText():String { return this._vmailText; }
		public function get vmailTimeRead():Number { return this._vmailTimeRead; }
		public function get vmailTimeSent():Number { return this._vmailTimeSent; }
		public function get vmailType():String { return this._vmailType; }

		public function set vmailBatchId(vmailBatchId:String):void { this._vmailBatchId = vmailBatchId; }
		public function set vmailImgUrl(imgUrl:String):void { this._vmailImgUrl = imgUrl; }
		public function set vmailFlvUrl(flvUrl:String):void { this._vmailFlvUrl = flvUrl; }
		public function set vmailMessageId(messageId:String):void { this._vmailMessageId = messageId; }

		public function addVmailRecipients(recipient:TokBoxVMailUser):void {
			if(null == this.vmailRecipients) {
				this._vmailRecipients = new Array();
			}
		
			this.vmailRecipients.concat(recipient); 
		}

		public function addVmailSenders(sender:TokBoxVMailUser):void { 
			if(null == this.vmailSenders) {
				this._vmailSenders = new Array();
			}

			this.vmailSenders.concat(sender); 
		}

		public function set vmailText(text:String):void { this._vmailText = text; }
		public function set vmailTimeRead(timeRead:Number):void { this._vmailTimeRead = timeRead; }
		public function set vmailTimeSent(timeSent:Number):void { this._vmailTimeSent = timeSent; }
		public function set vmailType(type:String):void { this._vmailType = type; }

		public static function deserializeFeed(event:ResultEvent):Array {
			if(event == null || event.result..error) {
				throw new Error(event.message);
			}

			var feedItems:Array = new Array();
			var xmlData:XMLList = event.result.feed;
			
			
			return feedItems;
		}
	}
}