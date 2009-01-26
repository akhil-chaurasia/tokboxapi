/*
*		*************************************
*		*       TokBox Base API             *
*		*************************************
*
*		Melih Onvural, August 2008
*
*
*/

package com.tokbox.api;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.math.BigInteger;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Iterator;
import java.util.Map;
import java.util.Random;
import java.util.logging.Logger;

import com.tokbox.exception.TokBoxRuntimeException;
import com.tokbox.util.TokBoxUtils;

public class BaseAPI {
	private String partnerKey;
	private String secret;
	
	private String jabberId;

	private Logger logger; 

	private final String version = "1.0.0";
	private final int sigMethod = BaseAPI.SIMPLE_MD5;
	
	public final static int SIMPLE_MD5 = 1;
	public final static int HMAC_SHA1 = 2;
	
	public final static String API_SERVER_LOGIN_URL = "view/oauth&";
	public final static String API_SERVER_METHODS_URL = "a/v0";
	public final static String API_SERVER_CALL_WIDGET = "vc/";
	public final static String API_SERVER_RECORDER_WIDGET = "vr/";
	public final static String API_SERVER_PLAYER_WIDGET = "vp/";
	
	public BaseAPI(String partnerKey, String secret) {
		this.partnerKey = partnerKey;
		this.secret = secret;
		this.jabberId = "";
		
		if(API_Config.DEBUG_MODE) {
			this.logger = Logger.getLogger("com.tokbox.api.BaseAPI");
		}
	}

	public String getPartnerKey() {
		return this.partnerKey;
	}

	public String getSecret() {
		return this.secret;
	}

	public String getJabberId() {
		return this.jabberId;
	}

	public void setJabberId(String jabberId) {
		this.jabberId = jabberId;
	}

	public void setSecret(String secret) {
		this.secret = secret;
	}

	public String sigMethodText() {
		return this.sigMethod == BaseAPI.SIMPLE_MD5 ? "SIMPLE-MD5" : "HMAC-SHA1";
	}

	protected String TB_Request(String method, String apiUrl, Map<String, String> paramList) {
		StringBuilder returnString = new StringBuilder();

		URL url = null;
		HttpURLConnection conn = null;
		BufferedReader br = null;
		OutputStreamWriter wr = null;
		BufferedWriter bufWriter = null;
		try {
			String reqString = API_Config.API_SERVER+BaseAPI.API_SERVER_METHODS_URL+apiUrl;
			String nonce = this.generateNonce();
			String timestamp = Long.toString(System.currentTimeMillis());

			StringBuilder dataString = new StringBuilder();

			for(Iterator<String> i = paramList.keySet().iterator(); i.hasNext(); ) {
				String key = i.next();
				String value = paramList.get(key);

				if(null != value) {
					value = URLEncoder.encode(paramList.get(key), "UTF-8").replaceAll("\\+", "%20");
					dataString.append(URLEncoder.encode(key, "UTF-8")).append("=").append(value).append("&");
				}
			}

			String signedSig = this.buildSignedRequest(method, reqString, nonce, timestamp, paramList);

			//Add oauth_version to parameter string
			dataString.append("oauth_version=").append(this.version).append("&");

			//Add timestamp to parameter string
			dataString.append("oauth_timestamp=").append(timestamp).append("&");

			//Add nonce to parameter string
			dataString.append("oauth_nonce=").append(nonce).append("&");

			//Add tokbox_jabber_id to parameter string
			dataString.append("tokbox_jabberid=").append(URLEncoder.encode(this.jabberId, "UTF-8")).append("&");

			//Add oauth_partner_key to parameter string
			dataString.append("oauth_partner_key=").append(this.partnerKey).append("&");

			//Add oauth_signature_method to parameter string
			dataString.append("oauth_signature_method=").append(this.sigMethodText()).append("&");

			//Add the _AUTHORIZATION to parameter string
			dataString.append("_AUTHORIZATION=");

			//Adding the oauth_partner_key to the _AUTHORIZATION 
			dataString.append("oauth_partner_key=\"").append(URLEncoder.encode(this.partnerKey, "UTF-8")).append("\",");

			//Adding the oauth_signature_method to the _AUTHORIZATION
			dataString.append("oauth_signature_method=\"").append(URLEncoder.encode(this.sigMethodText(), "UTF-8")).append("\",");

			//Adding the oauth_timestamp to the _AUTHORIZATION
			dataString.append("oauth_timestamp=\"").append(URLEncoder.encode(timestamp, "UTF-8")).append("\",");

			//Adding oauth_version to the _AUTHORIZATION
			dataString.append("oauth_version=\"").append(URLEncoder.encode(this.version, "UTF-8")).append("\",");

			//Adding oauth_nonce to the _AUTHORIZATION
			dataString.append("oauth_nonce=\"").append(URLEncoder.encode(nonce, "UTF-8")).append("\",");

			//Adding oauth_signature to the _AUTHORIZATION
			dataString.append("oauth_signature=\"").append(URLEncoder.encode(signedSig, "UTF-8")).append("\",");

			//Adding tokbox_jabberid to the _AUTHORIZATION
			dataString.append("tokbox_jabberid=\"").append(URLEncoder.encode(this.jabberId, "UTF-8")).append("\"");

			if(API_Config.DEBUG_MODE) {
				logger.info(dataString.toString());
			}

			url = new URL(reqString);
			conn = (HttpURLConnection) url.openConnection();

			conn.setDoOutput(true);
			conn.setDoInput(true);
			conn.setUseCaches(false);

			conn.setRequestMethod("GET");
			conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			conn.setRequestProperty("Content-Length", Integer.toString(dataString.toString().length()));
			conn.setRequestProperty("Accept-Charset", "utf-8");
			conn.setRequestProperty("Accept", "text/html, application/xhtml+xml,application/xml");

			wr = new OutputStreamWriter(conn.getOutputStream(), "UTF8");
            bufWriter = new BufferedWriter( wr );
            bufWriter.write(dataString.toString());
            bufWriter.flush();

            br = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF8"));

			String str;
			while(null != ((str = br.readLine())))
			{
				returnString.append(str);
				returnString.append("\n");
			}
			
			if(API_Config.DEBUG_MODE) {
				logger.info(returnString.toString());
			}
		} catch(IOException e) {
			e.printStackTrace();
		} finally {
			try {
				if(null != conn) {
					conn.disconnect();
				}
				
				if(null != wr) {
					wr.close();
				}
				
				if(null != bufWriter) {
					bufWriter.close();
				}
				
				if(null != br) {
					br.close();
				}
			}
			catch(IOException e) {
				e.printStackTrace();
			}
		}
		
		return returnString.toString();
	}

	private String buildSignedRequest(String method, String uri, String nonce, String timestamp, Map<String, String> paramList) throws UnsupportedEncodingException {
		String signedString = null;

		paramList.put("oauth_partner_key", this.partnerKey);
		paramList.put("oauth_signature_method", this.sigMethodText());
		paramList.put("oauth_timestamp", timestamp);
		paramList.put("oauth_version", this.version);
		paramList.put("oauth_nonce", nonce);
		paramList.put("tokbox_jabberid", this.jabberId);

		StringBuilder requestString = new StringBuilder();
		requestString.append(method).append("&").append(uri).append("&").append(this.generateRequestString(paramList)).append(this.secret);

		switch(this.sigMethod) {
		case BaseAPI.SIMPLE_MD5:
			signedString = this.md5Hash(requestString.toString());
			break;
		case BaseAPI.HMAC_SHA1:
			//not currently implemented
			break;
		default:
			throw new TokBoxRuntimeException("Desired Signature Method is not supported", new Throwable());
		}

		if(API_Config.DEBUG_MODE) {
			logger.info(requestString.toString());
			logger.info(signedString);
		}

		return signedString;
	}

	private String generateRequestString(Map<String, String> paramList) throws UnsupportedEncodingException {
		ArrayList<String> encodedParamList = new ArrayList<String>();

		for(Iterator<String> i = paramList.keySet().iterator(); i.hasNext(); ) {
			String key = i.next();
			
			if(key.charAt(0) == '_') {
				continue;
			}
			
			String value = paramList.get(key);
			
			if((null != value) && (value.length() > 0)) {
				String encodedKey = URLEncoder.encode(key, "UTF-8");
				String encodedValue = URLEncoder.encode(value, "UTF-8").replaceAll("\\+", "%20");

				StringBuilder encodedString = new StringBuilder();
				encodedString.append(encodedKey).append("=").append(encodedValue);

				encodedParamList.add(encodedString.toString());
			}
		}
		
		Collections.sort(encodedParamList);
		
		return TokBoxUtils.join(encodedParamList, "&");
	}
	
	private String generateNonce() {
		StringBuilder nonce = new StringBuilder(16);
		int length = 16;
		Random generator = new Random();
		
		String seed = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		int seedLength = seed.length() - 1;
		
		for(int i = 0; i < length; i++) {
			nonce.append(seed.charAt(generator.nextInt(seedLength)));
		}
		
		return nonce.toString();
	}
	
	private String md5Hash(String token) {
		String hashword = null;
		try {
			MessageDigest md5 = MessageDigest.getInstance("MD5");
			md5.update(token.getBytes());
			BigInteger hash = new BigInteger(1, md5.digest());
			hashword = hash.toString(16);
		} catch (NoSuchAlgorithmException nsae) {
			
		}
		
		return pad(hashword, 32, '0');
	}

	private String pad(String s, int length, char pad) {
		StringBuffer buffer = new StringBuffer(s);
		while (buffer.length() < length) {
			buffer.insert(0, pad);
		}

		return buffer.toString();
	}
}