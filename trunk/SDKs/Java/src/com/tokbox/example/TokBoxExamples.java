package com.tokbox.example;

import com.tokbox.api.TokBoxAPI;
import com.tokbox.api.TokBoxCall;
import com.tokbox.api.TokBoxToken;
import com.tokbox.api.TokBoxUser;
import com.tokbox.exception.TokBoxRuntimeException;

public class TokBoxExamples {

	private static String testGenerateRequestToken() {
		try {
			return TokBoxToken.updateToken();
		} catch(TokBoxRuntimeException e) {
			return e.getMessage();
		}
	}
	
	private static TokBoxAPI testGenerateGuestUser() {
		try {
			return TokBoxUser.createGuest();
		}
		catch(TokBoxRuntimeException e) {
			e.printStackTrace();
		}
		
		return null;
	}
	
	private static String testGenerateCallId() {
		TokBoxAPI apiObj = TokBoxUser.createGuest();
		try {
			return TokBoxCall.createCall(apiObj, false, "Test User");
		} catch(TokBoxRuntimeException e) {
			return e.getMessage();
		}
	}
		
	public static void main(String args[]) {
		/////////////// Test - Generate a Request Token /////////////////
		//System.out.println("Request Token: "+testGenerateRequestToken());
		
		/////////////// Test - Generate a Guest User /////////////////
		//TokBoxAPI apiObj = testGenerateGuestUser();
		//System.out.println("My user/password is: "+apiObj.getJabberId()+"/"+apiObj.getSecret());

		/////////////// Test - Generate a Call ID /////////////////
		//System.out.println("Call ID: "+testGenerateCallId());
	}
}