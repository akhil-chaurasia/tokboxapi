//Invite User Functionality
var sendInvite = function(userId, name, jabberId, inviteId) {
	if(userId && name && jabberId && inviteId) {
		var inviteObj = new Object();
		
		inviteObj.userId = userId;
		inviteObj.name = name;
		inviteObj.jabberId = jabberId;		
		
		var inviteHash = new Object();
		inviteHash.inviteId = inviteObj;
		
		document.getElementById("tbx_call").inviteUser(inviteHash);
	}
	else {
		alert("missing info to invite a user");
	}
};