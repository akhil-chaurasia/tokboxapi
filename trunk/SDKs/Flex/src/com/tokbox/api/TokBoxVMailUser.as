package com.tokbox.api
{
	public class TokBoxVMailUser
	{
		private var _fullName:String;
		private var _isSender:Boolean;
		private var _timeRead:Number;
		private var _jabberId:String;

		public function TokBoxVMailUser(fullName:String, isSender:Boolean, timeRead:Number, jabberId:String) {
			this._fullName = fullName;
			this._isSender = isSender;
			this._timeRead = timeRead;
			this._jabberId = jabberId;
		}

		public function get fullName():String{ return this._fullName; }
		public function get isSender():Boolean { return this._isSender; }
		public function get timeRead():Number { return this._timeRead; }
		public function get jabberId():String { return this._jabberId; }
	}
}