package com.tokbox.api;

public class API_Config {

	/*
	 * Partner Key provided by TokBox
	 */
	public static final String PARTNER_KEY = "";
	
	/*
	 * Partner Secret provided by TokBox
	 */
	public static final String PARTNER_SECRET = "";
	
	/*
	 * Environment to which you are pointing. Include trailing slash
	 * Sandbox: http://sandbox.tokbox.com/
	 * Production: http://api.tokbox.com/
	 */
	public static final String API_SERVER = "http://sandbox.tokbox.com/";
	
	/*
	 * Callback URL for redirecting successful OAuth based login
	 */
	public static final String CALLBACK_URL = "";

	public static final boolean DEBUG_MODE = Boolean.getBoolean(System.getProperty("tokbox.debug", "false"));
}
