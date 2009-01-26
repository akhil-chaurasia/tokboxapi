package com.tokbox.api;

import java.io.IOException;
import java.util.logging.Logger;

import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.xml.sax.SAXException;

import com.tokbox.exception.TokBoxRuntimeException;
import com.tokbox.util.TokBoxUtils;

public class TokBoxUser {
	private static Logger logger;

	public static TokBoxAPI createGuest() {
		TokBoxAPI apiObj = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);
		if(API_Config.DEBUG_MODE) {
			TokBoxUser.logger = Logger.getLogger("com.tokbox.api.TokBoxUser");
		}

		Document document;
		try {
			document = TokBoxUtils.setupDocument(apiObj.createGuestUser(API_Config.PARTNER_KEY));

			Node createGuestNode = TokBoxUtils.parseXML("createGuest", document.getElementsByTagName("createGuest"));
			Node jabberIdNode = TokBoxUtils.parseXML("jabberId", createGuestNode.getChildNodes());
			Node secretNode = TokBoxUtils.parseXML("secret", createGuestNode.getChildNodes());

			apiObj.setJabberId(jabberIdNode.getFirstChild().getTextContent());
			apiObj.setSecret(secretNode.getFirstChild().getTextContent());

		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}

		return apiObj;
	}

	public static TokBoxAPI createUser(String jabberId, String accessSecret) {
		TokBoxAPI apiObj = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);

		apiObj.setJabberId(jabberId);
		apiObj.setSecret(accessSecret);

		if(!TokBoxUser.isValid(apiObj, API_Config.PARTNER_SECRET)) {
			apiObj = null;
		}

		return apiObj;
	}

	public static TokBoxAPI registerUser(String firstname, String lastname, String email, boolean searchAllow) {
		TokBoxAPI apiObj = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);
		if(API_Config.DEBUG_MODE) {
			TokBoxUser.logger = Logger.getLogger("com.tokbox.api.TokBoxUser");
		}

		Document document;
		try {
			document = TokBoxUtils.setupDocument(apiObj.registerUser(email, lastname, firstname, Boolean.toString(searchAllow)));

			Node registerUserNode = TokBoxUtils.parseXML("registerUser", document.getElementsByTagName("registerUser"));
			Node jabberIdNode = TokBoxUtils.parseXML("jabberId", registerUserNode.getChildNodes());
			Node secretNode = TokBoxUtils.parseXML("secret", registerUserNode.getChildNodes());

			apiObj.setJabberId(jabberIdNode.getFirstChild().getTextContent());
			apiObj.setSecret(secretNode.getFirstChild().getTextContent());

		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}

		return apiObj;
	}

	protected static boolean isValid(TokBoxAPI apiObj, String partnerSecret) {
		boolean validUser = false;
		String accessSecret = apiObj.getSecret();
		apiObj.setSecret(partnerSecret);
		if(API_Config.DEBUG_MODE) {
			TokBoxUser.logger = Logger.getLogger("com.tokbox.api.TokBoxUser");
		}

		Document document;
		try {
			document = TokBoxUtils.setupDocument(apiObj.validateAccessToken(accessSecret, apiObj.getJabberId()));
			
			Node validateAccessToken = TokBoxUtils.parseXML("validateAccessToken", document.getElementsByTagName("validateAccessToken"));
			Node isValid = TokBoxUtils.parseXML("isValid", validateAccessToken.getChildNodes());
			
			if("true".equals(isValid.getFirstChild().getTextContent())) {
				validUser = true;
				apiObj.setSecret(accessSecret);
			}
		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxUser.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}
		
		return validUser;
	}	
}