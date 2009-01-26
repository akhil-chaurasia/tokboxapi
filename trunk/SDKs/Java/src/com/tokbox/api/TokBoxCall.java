package com.tokbox.api;

import java.io.IOException;
import java.util.logging.Logger;

import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.xml.sax.SAXException;

import com.tokbox.exception.TokBoxRuntimeException;
import com.tokbox.util.TokBoxUtils;

public class TokBoxCall {
	private static Logger logger;
	
	public static String createCall(TokBoxAPI apiObj, boolean persistent, String displayname) {
		String callId = null;

		if(API_Config.DEBUG_MODE) {
			TokBoxCall.logger = Logger.getLogger("com.tokbox.api.TokBoxCall");
		}
		
		if(null == displayname) {
			displayname = "Guest User";
		}

		try {		
			if(TokBoxUser.isValid(apiObj, API_Config.PARTNER_SECRET)) {
				Document document = TokBoxUtils.setupDocument(apiObj.createCall(displayname, apiObj.getJabberId(), Boolean.toString(persistent), ""));

				Node createCall = TokBoxUtils.parseXML("createCall", document.getElementsByTagName("createCall"));
				Node callIdNode = TokBoxUtils.parseXML("callId", createCall.getChildNodes());

				callId = callIdNode.getFirstChild().getTextContent();
			}
			else {
				throw new TokBoxRuntimeException("The user is not properly validated");
			}
		} catch(TokBoxRuntimeException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw e;
		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}

		return callId;
	}

	public static String generateInvite(TokBoxAPI apiObj, String callId, String calleeId) {
		String inviteId = null;
		if(API_Config.DEBUG_MODE) {
			TokBoxCall.logger = Logger.getLogger("com.tokbox.api.TokBoxCall");
		}

		try {		
			if(TokBoxUser.isValid(apiObj, API_Config.PARTNER_SECRET)) {
				Document document = TokBoxUtils.setupDocument(apiObj.createInvite(callId, calleeId, apiObj.getJabberId()));

				Node createInvite = TokBoxUtils.parseXML("createInvite", document.getElementsByTagName("createInvite"));
				Node inviteIdNode = TokBoxUtils.parseXML("inviteId", createInvite.getChildNodes());
			
				inviteId = inviteIdNode.getFirstChild().getTextContent();
			}
			else {
				throw new TokBoxRuntimeException("The user is not properly validated");
			}
		} catch(TokBoxRuntimeException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw e;
		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxCall.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}		
		return inviteId;
	}

    public static String joinCall(TokBoxAPI apiObj, String inviteId) {
        String callId = null;

        try {        
            if(TokBoxUser.isValid(apiObj, API_Config.PARTNER_SECRET)) {
                Document document = TokBoxUtils.setupDocument(apiObj.joinCall( inviteId ));

                Node joinCall = TokBoxUtils.parseXML("joinCall", document.getElementsByTagName("joinCall"));
                Node callIdNode = TokBoxUtils.parseXML("callId", joinCall.getChildNodes());
            
                callId = callIdNode.getFirstChild().getTextContent();
            }
            else {
                throw new TokBoxRuntimeException("The user is not properly validated");
            }
        } catch(TokBoxRuntimeException e) {
            if(API_Config.DEBUG_MODE) {
            	//TokBoxCall.logger.error(e.getMessage(), e );
            }
            throw e;
        } catch (ParserConfigurationException e) {
            if(API_Config.DEBUG_MODE) {
            	//TokBoxCall.logger.error("Error parsing msg", e );
            }
            throw new TokBoxRuntimeException("Configuration Error", e);
        } catch (SAXException e) {
            if(API_Config.DEBUG_MODE) {
            	//TokBoxCall.logger.error("Error parsing msg", e );
            }
            throw new TokBoxRuntimeException("Error parsing response XML", e);
        } catch (IOException e) {
            if(API_Config.DEBUG_MODE) {
            	//TokBoxCall.logger.error("Comm error", e );
            }
            throw new TokBoxRuntimeException("Communications error", e);
        }        
        return callId;
    }
}