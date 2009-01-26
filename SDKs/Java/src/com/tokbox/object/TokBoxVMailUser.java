package com.tokbox.object;

public class TokBoxVMailUser {
	//Full Name of this user associated with this VMail
	private String fullName;

	//Defines whether this user is the sender of this VMail
	private boolean isSender;

	//Timestamp of the read time of the message
	private String timeRead;

	//User ID of this user associated with this VMail
	private String userId;
	
	public TokBoxVMailUser(String userId, String fullName, boolean isSender, String timeRead) {
		this.userId = userId;
		this.fullName = fullName;
		this.timeRead = timeRead;
		this.isSender = isSender;
	}
	
	public String getFullName() { return this.fullName; }
	public boolean getIsSender() { return this.isSender; }
	public String getTimeRead() { return this.timeRead; }
	public String getUserId() { return this.userId; }
}