package com.tokbox.object;

import java.util.ArrayList;

public class TokBoxVMail {
	//ID of the specific message in which this instance was sent
	private String vmailBatchId;
	
	//ID of the VMail
	private String vmailId;
	
	//Location of the VMail Image URL
	private String vmailImgUrl;
	
	//Location of the VMail FLV URL
	private String vmailFlvUrl;
	
	//ID of the message in which this VMail was sent. 
	//Should be used for interacting with the recorder
	private String vmailMessageId;
	
	//Array of the recipients
	private ArrayList<TokBoxVMailUser> vmailRecipients;
	
	//Array of the senders of a VMail
	private TokBoxVMailUser vmailSender;
	
	//Text associated with the VMail
	private String vmailText;
	
	//Timestamp of when the VMail was read
	private String vmailTimeRead;
	
	//Timestamp of when the VMail was sent
	private String vmailTimeSent;
	
	//Type of message that the VMail is
	private String vmailType;

	public String getVmailBatchId() {
		return vmailBatchId;
	}

	public void setVmailBatchId(String vmailBatchId) {
		this.vmailBatchId = vmailBatchId;
	}

	public String getVmailId() {
		return vmailId;
	}

	public void setVmailId(String vmailId) {
		this.vmailId = vmailId;
	}

	public String getVmailImgUrl() {
		return vmailImgUrl;
	}

	public void setVmailImgUrl(String vmailImgUrl) {
		this.vmailImgUrl = vmailImgUrl;
	}

	public String getVmailFlvUrl() {
		return vmailFlvUrl;
	}

	public void setVmailFlvUrl(String vmailFlvUrl) {
		this.vmailFlvUrl = vmailFlvUrl;
	}

	public String getVmailMessageId() {
		return vmailMessageId;
	}

	public void setVmailMessageId(String vmailMessageId) {
		this.vmailMessageId = vmailMessageId;
	}

	public ArrayList<TokBoxVMailUser> getVmailRecipients() {
		return vmailRecipients;
	}

	public void setVmailRecipients(ArrayList<TokBoxVMailUser> vmailRecipients) {
		this.vmailRecipients = vmailRecipients;
	}

	public TokBoxVMailUser getVmailSenders() {
		return vmailSender;
	}

	public void setVmailSenders(TokBoxVMailUser vmailSenders) {
		this.vmailSender = vmailSenders;
	}

	public String getVmailText() {
		return vmailText;
	}

	public void setVmailText(String vmailText) {
		this.vmailText = vmailText;
	}

	public String getVmailTimeRead() {
		return vmailTimeRead;
	}

	public void setVmailTimeRead(String vmailTimeRead) {
		this.vmailTimeRead = vmailTimeRead;
	}

	public String getVmailTimeSent() {
		return vmailTimeSent;
	}

	public void setVmailTimeSent(String vmailTimeSent) {
		this.vmailTimeSent = vmailTimeSent;
	}

	public String getVmailType() {
		return vmailType;
	}

	public void setVmailType(String vmailType) {
		this.vmailType = vmailType;
	}
}