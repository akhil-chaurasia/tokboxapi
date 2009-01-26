package com.tokbox.util {
	import flash.utils.ByteArray;
	import flash.utils.Endian;
	
	/**
	 * URL related utilities, such as encode/decode strings into URL or extract
	 * URL from a text. 
	 *
	 * The encode/decode functions are ported from the javascript code at:
	 * http://cass-hacks.com/articles/code/js_url_encode_decode/
	 */
	public class URLEncoding
	{
		//--------------------------------------
		//  CLASS VARIABLES
		//--------------------------------------

		// exclude regular expression in encode.
		private static var exclude:RegExp = /(^[a-zA-Z0-9_\.~-]*)/;
		
		// excape code's regular expression in decode
		private static var myregexp:RegExp = /(%[^%]{2})/;
		
		// regular expression to identify a URL pattern for replacing with clickable element.
		private static var urlPattern:RegExp = new RegExp("(((https?://)" + 											// Optional http
															"(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" +	// Optional user@
															"(([0-9]{1,3}[.]){3}[0-9]{1,3}|" + 							// IP Address or
															"([0-9a-z_!~*'()-]+[.])*)|" +								// Tertiary domain(s) - eg. www.
															"(www\.))" + 
															"([0-9a-z][0-9a-z-]{0,61})?[0-9a-z][.]" +					// second level domain
															"[a-z]{2,6}" +												// first level domain - eg. .com .net .museum
															"(:[0-9]{1,4})?" + 											// Optional port number
															"(/[0-9a-z_!~*'().;?:@&=+$,%#-]*)*)", 						// Whatever comes after the slash
															"gi"); 														// Ignore case and use global matching
		// filter regular expression
		private static var filterExpr:RegExp = /(https?:\/\/)|(www\.)|([&<>"'%])/;
		
		//--------------------------------------
		//  CONSTRUCTOR
		//--------------------------------------

		/*
		 * Dummy constructor is not needed.
		 */
		public function URLEncoding()
		{
		}

		//--------------------------------------
		//  CLASS METHODS
		//--------------------------------------

			private static var specialChar:Array = [
				0x5F, 0x2E, 0x7E, 0x2D
			]; // [a-zA-Z0-9_\.~-]
			
			public static function encode(input:String):String {
				var array:ByteArray = new ByteArray();
				array.endian = Endian.BIG_ENDIAN;
				array.writeUTFBytes(input);
				array.position = 0;
				var output:String = "";
				
				for (var i:int=0; i<array.length; ++i) {
					var code:uint = array.readUnsignedByte();
					if (code < 128 && (specialChar.indexOf(code) >= 0 ||
						code >= 0x30 && code <= 0x39 ||
						code >= 0x61 && code <= 0x7A ||
						code >= 0x41 && code <= 0x5A)) {
						output += String.fromCharCode(code);
					}
					else {
						var hex:String = code.toString(16);
						if (hex.length == 0) hex = "0" + hex;
						output += "%" + ( hex.length < 2 ? "0" : "" ) + hex.toUpperCase();						
//						output += "%" + hex;
					}
				}
				return output;
			}
			
			private static var hexDigits:String = "0123456789ABCDEF";
			
			public static function decode(encodedString:String):* {
				// change "+" to spaces
				var plusPattern:RegExp = /\+/gm;
				encodedString = encodedString.replace(plusPattern," ");
				
				// substitute %xx to byte value.
				var array:ByteArray = new ByteArray();
				array.endian = Endian.BIG_ENDIAN;
				
				for (var i:int=0; i<encodedString.length; ) {
					var ch:uint = encodedString.charCodeAt(i);
					if (ch == 0x25) {
						var x1:uint = hexDigits.indexOf(encodedString.charAt(i+1).toUpperCase());
						var x0:uint = hexDigits.indexOf(encodedString.charAt(i+2).toUpperCase());
						array.writeByte(((x1 << 4) | x0) & 0xff);	
						i += 3;
					}
					else {
						array.writeByte(ch);
						++i;
					}
				}
				
				array.position = 0;
				var output:String = array.readUTFBytes(array.length);
				
				return output;
			}


		/**
		 * Create a hyper-link with embedded http:// or www... strings in the text,
		 * and return the HTML formatted return text. Everything else (embedded <, >, &, etc)
		 * is HTML encoded. The HTML return text has URLs replaced by <a href=..> tags which
		 * throws event so that the result text can be used in Text field.
		 * 
		 * @param p_text the input text to be formatted as HTML.
		 * @param urlColor the color of the URL link, defaults to "#229ADF"
		 * @return the formatted HTML string which can be used in htmlText property of Text
		 * component.
		 */
		public static function filterHTML( p_text:String, urlColor:String="#229ADF" ) : String 
		{
			// a shortcircuit to not process rest of the code, if no URL or especial char found.
			if (p_text.search(filterExpr) < 0)
				return p_text;
				
			// Apply the regular expression and insert the href html
			// The event: part is used to make this link catchable by the 
			// Label link event
			var lt:RegExp = /</g;
			var rt:RegExp = />/g;
			var quote:RegExp = /\"/g; //"
			var singleQuote:RegExp = /\'/g; //'
			var textWithUrls:String = p_text.replace('&', '&amp;')
				.replace(lt, '&lt;').replace(rt,'&gt;')
				.replace(quote,'&quot;').replace(singleQuote, "&apos;")
				.replace(urlPattern, "<a href='event:$1' target='_blank'><font color='" + urlColor + "'>$1</font></a>")
				.replace('<<', '&lt;<').replace('>>', '>&gt;');
				
			// Loop through and check for missing http
			var curIndex:int = textWithUrls.indexOf("<a href='event:", 0);
			while (curIndex != -1) {
				if (textWithUrls.substr(curIndex, 22) != "<a href='event:http://" &&
					textWithUrls.substr(curIndex, 23) != "<a href='event:https://") {
					// If the href doesn't start with http:// we need to add the http:// and rebuild the string
					textWithUrls = textWithUrls.substring(0, curIndex) + "<a href='event:http://" + textWithUrls.substr(curIndex+15);
				}
				// continue searching after the current index
				curIndex = textWithUrls.indexOf("<a href='event:", curIndex+1);
			}
			return textWithUrls;
		}

	}
}