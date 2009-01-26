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
		const PARTNER_KEY = ""; 
		
		// Replace this value with your TokBox API Partner Secret
		const PARTNER_SECRET = "";
		
		// API Server (Test env: sandbox.tokbox.com  Production env: api.tokbox.com)
		const API_SERVER = "http://sandbox.tokbox.com/";
		
		// Callback URL for successful oauth logins
		const CALLBACK_URL = "";
	}
