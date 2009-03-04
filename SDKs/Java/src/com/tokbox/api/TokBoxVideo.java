package com.tokbox.api;

import java.io.IOException;
import java.util.ArrayList;
import java.util.logging.Logger;

import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.NamedNodeMap;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import com.tokbox.exception.TokBoxRuntimeException;
import com.tokbox.object.TokBoxVMail;
import com.tokbox.object.TokBoxVMailUser;
import com.tokbox.util.TokBoxUtils;

public class TokBoxVideo {

	public static final String VMAIL_SENT = "vmailSent";
	public static final String VMAIL_RECV = "vmailRecv";
	public static final String CALL_EVENT = "callEvent";
	public static final String VMAIL_POST_PUBLIC = "vmailPostPublic";
	public static final String VMAIL_POST_RECV = "vmailPostRecv";

	private static Logger logger;

	public static ArrayList<TokBoxVMail> getVMailSent(TokBoxAPI apiObj, int startPage, int count) {
		return TokBoxVideo.getFeed(apiObj, TokBoxVideo.VMAIL_SENT, startPage, count);
	}

	public static ArrayList<TokBoxVMail> getVMailRecv(TokBoxAPI apiObj, int startPage, int count) {
		return TokBoxVideo.getFeed(apiObj, TokBoxVideo.VMAIL_RECV, startPage, count);
	}

	public static ArrayList<TokBoxVMail> getPublicPost(TokBoxAPI apiObj, int startPage, int count) {
		return TokBoxVideo.getFeed(apiObj, TokBoxVideo.VMAIL_POST_PUBLIC, startPage, count);
	}

	public static ArrayList<TokBoxVMail> getPublicRecv(TokBoxAPI apiObj, int startPage, int count) {
		return TokBoxVideo.getFeed(apiObj, TokBoxVideo.VMAIL_POST_RECV, startPage, count);
	}

	private static ArrayList<TokBoxVMail> getFeed(TokBoxAPI apiObj, String type, int startPage, int count) {
		ArrayList<TokBoxVMail> feedItems = null;
		if(API_Config.DEBUG_MODE) {
			TokBoxVideo.logger = Logger.getLogger("com.tokbox.api.TokBoxVideo");
		}

		Document document = null;
		try {		
			if(TokBoxUser.isValid(apiObj, API_Config.PARTNER_SECRET)) {
				document = TokBoxUtils.setupDocument(apiObj.getFeed(apiObj.getJabberId(), type, Integer.toString(startPage), Integer.toString(count), "", "", ""));

				feedItems = new ArrayList<TokBoxVMail>();
				Node feedNode = TokBoxUtils.parseXML("feed", document.getElementsByTagName("feed"));
				NodeList itemNodes = feedNode.getChildNodes();
				int itemNodesLength = itemNodes.getLength();
				for(int i = 0; i < itemNodesLength; i++ ) {
					Node itemNode = itemNodes.item(i);

					if(itemNode.getNodeName().equals("item")) {
						TokBoxVMail newItem = new TokBoxVMail();

						NamedNodeMap itemAttributes = itemNode.getAttributes();
						int itemAttributesLength = itemAttributes.getLength();
						for(int j = 0; j < itemAttributesLength; j++) {
							Node attr = itemAttributes.item(j);
							
							if(attr.getNodeName().equals("type")) {
								newItem.setVmailType(attr.getTextContent());
								break;
							}
						}
						
						Node videomailNode = TokBoxUtils.parseXML("videoMail", itemNode.getChildNodes());
						NamedNodeMap videomailAttributes = videomailNode.getAttributes();
						int videomailAttributesLength = videomailAttributes.getLength();
						for(int j = 0; j < videomailAttributesLength; j++) {
							Node attr = itemAttributes.item(j);
							
							if(attr.getNodeName().equals("vmailId")) {
								newItem.setVmailId(attr.getTextContent());
								break;
							}
						}

						newItem.setVmailTimeRead(TokBoxUtils.parseXML("timeRead", videomailNode.getChildNodes()).getTextContent());
						newItem.setVmailTimeSent(TokBoxUtils.parseXML("timeSent", videomailNode.getChildNodes()).getTextContent());

						Node contentNode = TokBoxUtils.parseXML("content", videomailNode.getChildNodes());
						NamedNodeMap contentAttributes = contentNode.getAttributes();
						int contentAttributesLength = contentAttributes.getLength();
						for(int j = 0; j < contentAttributesLength; j++) {
							Node attr = itemAttributes.item(j);
							
							if(attr.getNodeName().equals("batchId")) {
								newItem.setVmailBatchId(attr.getTextContent());
								break;
							}
						}

						newItem.setVmailText(TokBoxUtils.parseXML("text", contentNode.getChildNodes()).getTextContent());
						newItem.setVmailMessageId(TokBoxUtils.parseXML("messageId", contentNode.getChildNodes()).getTextContent());
						newItem.setVmailFlvUrl(TokBoxUtils.parseXML("video", contentNode.getChildNodes()).getTextContent());
						newItem.setVmailImgUrl(TokBoxUtils.parseXML("image", contentNode.getChildNodes()).getTextContent());

						Node usersNode = TokBoxUtils.parseXML("users", videomailNode.getChildNodes());
						Node sendersNode = TokBoxUtils.parseXML("sender", usersNode.getChildNodes());

						NamedNodeMap sendersAttributes = sendersNode.getAttributes();
						int sendersAttributesLength = sendersAttributes.getLength();
						for(int j = 0; j < sendersAttributesLength; j++) {
							Node attr = sendersAttributes.item(j);

							if(attr.getNodeName().equals("jabberId")) {
								TokBoxVMailUser sender = new TokBoxVMailUser(attr.getTextContent(), TokBoxUtils.parseXML("senderName", sendersNode.getChildNodes()).getTextContent(), true, null);
								
								newItem.setVmailSenders(sender);
								break;
							}
						}

						ArrayList<TokBoxVMailUser> recipients = new ArrayList<TokBoxVMailUser>();

						Node recipientsNode = TokBoxUtils.parseXML("recipients", usersNode.getChildNodes());
						NodeList recipientsList = recipientsNode.getChildNodes();
						int recipientsListCount = recipientsList.getLength();
						for(int j = 0; j < recipientsListCount; j++) {
							Node recipientNode = recipientsList.item(j);
							
							if(recipientNode.getNodeName().equals("recipient")) {
								NamedNodeMap recipientAttributes = recipientNode.getAttributes();
								int recipientAttributesLength = recipientAttributes.getLength();
								for(int k = 0; k < recipientAttributesLength; k++) {
									Node attr = recipientAttributes.item(k);
									
									if(attr.getNodeName().equals("jabberId")) {
										TokBoxVMailUser recipient = new TokBoxVMailUser(attr.getTextContent(), TokBoxUtils.parseXML("recipientName", recipientNode.getChildNodes()).getTextContent(), false, TokBoxUtils.parseXML("timeRead", recipientNode.getChildNodes()).getTextContent());
										recipients.add(recipient);
									}
								}

							}
						}
						
						newItem.setVmailRecipients(recipients);
						
						feedItems.add(newItem);
					}
				}
			}
			else {
				throw new TokBoxRuntimeException("The user is not properly validated");
			}
		} catch(TokBoxRuntimeException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxVideo.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw e;
		} catch (ParserConfigurationException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxVideo.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Configuration Error", e);
		} catch (SAXException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxVideo.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Error parsing response XML", e);
		} catch (IOException e) {
			if(API_Config.DEBUG_MODE) {
				TokBoxVideo.logger.severe(e.getMessage());
				e.printStackTrace();
			}
			throw new TokBoxRuntimeException("Communications error", e);
		}

		return feedItems;
	}
}