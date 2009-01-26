package com.tokbox.util;

import java.io.IOException;
import java.io.StringReader;
import java.util.Collection;
import java.util.Iterator;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

import com.tokbox.exception.TokBoxRuntimeException;

public class TokBoxUtils {
	public static String join(Collection<String> s, String delimiter) {
		StringBuffer buffer = new StringBuffer();
		Iterator<String> iter = s.iterator();

		while (iter.hasNext()) {
			buffer.append(iter.next());
			if (iter.hasNext()) {
				buffer.append(delimiter);
			}
		}

		return buffer.toString();
	}
	
	public static Node parseXML(String matchToken, NodeList nodelist) {
		Node token = null;
		int index = 0;
		while((token = nodelist.item(index)) != null) {
			if(token.getNodeName().equals(matchToken)) {
				break;
			}
			
			index++;
		}	
		
		return token;
	}

	public static Document setupDocument(String xmlResponse) throws ParserConfigurationException, SAXException, IOException {
		if(null == xmlResponse) {
			throw new TokBoxRuntimeException("There was an error in retrieving the response. Please make sure that you are pointing to the correct server");
		}

		DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
		DocumentBuilder builder;
		Document document;
		
		builder = dbFactory.newDocumentBuilder();
		document = builder.parse(new InputSource(new StringReader(xmlResponse)));

		Node errorNodes = TokBoxUtils.parseXML("error", document.getElementsByTagName("error"));

		if(null != errorNodes) {
			throw new TokBoxRuntimeException(errorNodes.getNodeValue());
		}

		return document;
	}
}