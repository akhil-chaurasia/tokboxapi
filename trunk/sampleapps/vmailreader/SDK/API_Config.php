<?php

	/*
	 *  API Configuration file. Make sure to replace the values below with your 
	 * 	own API Key/Secret.
	 *
	 *	You can obtain your own TokBox API key at http://www.tokbox.com/view/developers
	 *
	 */
	
	class API_Config {
	
		// Replace this value with your TokBox API Partner Key
		const PARTNER_KEY = "1115"; 
		
		// Replace this value with your TokBox API Partner Secret
		const PARTNER_SECRET = "eb315ae9d63e6d59bc98a17ae15d8a21";
		
		// API Server (Test env: sandbox.tokbox.com  Production env: api.tokbox.com)
		const API_SERVER = "http://sandbox.tokbox.com/";
		
		// Callback URL for successful oauth logins
		const CALLBACK_URL = "http://melih.tokbox.com/sampleApp/Callback.php";
	}