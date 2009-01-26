package com.tokbox.api;

import java.io.IOException;
import java.util.logging.Logger;

import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.xml.sax.SAXException;

import com.tokbox.exception.TokBoxRuntimeException;
import com.tokbox.util.TokBoxUtils;

public class TokBoxToken {
	private static Logger logger;

	public static String updateToken() {
		String requestToken = null;
		if(API_Config.DEBUG_MODE) {
			TokBoxToken.logger = Logger.getLogger("com.tokbox.api.TokBoxToken");
		}

		TokBoxAPI apiObj = new TokBoxAPI(API_Config.PARTNER_KEY, API_Config.PARTNER_SECRET);

		Document document;
		try {
			document = TokBoxUtils.setupDocument(apiObj.getRequestToken(API_Config.CALLBACK_URL));

			Node requestTokenNode = TokBoxUtils.parseXML("requestToken", document.getElementsByTagName("requestToken"));
			Node token = TokBoxUtils.parseXML("token", requestTokenNode.getChildNodes());

			requestToken = token.getFirstChild().getTextContent();

		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxToken.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxToken.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxToken.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}

		return requestToken;
	}
}