# Video Call Widget #

## About ##

The TokBox call widget allows web developers to add TokBox call functionality to their web site, blog or application. The TokBox call widget can be customized to fit the needs of the site on which it is embedded, and those options are describe on this site.


## Quick Start ##
  * Click [here](http://me.tokbox.com/#embed=call) to get started building your own custom call widget embed

## Customization ##

The video chat embed can be customized by setting specific values to a set of flashvars.  In addition, the embed exposes an external JavaScript interface that allows the application developer to interact with the video call in real time.

### Flash Var Parameters ###
##### addVideoButton #####
  * True if the Add Video option should be available on the call widget. Through this interface, users can add YouTube, SlideShare, or TokBox VMails into the call widget.

##### displayName #####
  * The name of the user who has joined the call. This will replace the default "Guest 0" to be the value which is passed in by the developer


##### guestList #####
  * True if the call widget should have a list of participants displayed.
  * _Note that this will add vertical height to the dimensions of your call widget, and this should be taken into account when setting the height and width of the widget._

##### inviteButton #####
  * False if the inviteButton in the chrome and in the guest list should be hidden

##### invitingUser #####
  * True if the call widget should mimic inviting a user. This should be used if another user is being invited, but may not join the call using an invite ID

##### observerMode #####
  * True if individuals should be allowed to observe the call without explicitly being a participant in the call. The user who is observing will still be present in the guest list if it is active.

##### showAddFriends #####
  * False if the call widget should hide the Add Friends pane in the video portion of the call widget

##### showExpandButton #####
  * True if the expand button should be in the chrome of the call widget. The expand functionality allows you to send users to TokBox.com where they can register to identify themselves as well as experience a larger widget size than what may be possible on a given website. Note that if showExpandButton is true, then showFullscreenButton is always false

##### showFullscreenButton #####
  * True if the full screen button should be in the chrome of the call widget.

##### showSocialInvite #####
  * True if the social invite screen in the video window section of the video chat widget should be shown

##### showInviteButton #####
  * True if the Invite button should be available on the call widget. This will allow users to tweet the URL of the conference so that other users can join them. The user must have a Twitter account for this functionality to work.

##### textChat #####
  * True if the call widget should have a text chat component.
  * _Note that this will add vertical height to the dimensions of your call widget, and this should be taken into account when setting the height and width of the widget._

### JavaScript Interface ###
##### userCount #####
If you want to count the number of users in a call, then simply use the following code:
```
	document.getElementById("tbx_call").userCount();	

	var userCountHandler = function(userCount) {
		alert("The number of users in the call is " + userCount);
	}
```

##### widgetConnected #####
If you want to execute an action, such as sending an invitation when the widget is finished loading, you can use the ''widgetConnected'' function which is called when the call widget finishes loading.

Example code:
```
     function widgetConnected() {
          sendInvite(...);
     }
```

##### invite #####
The invite Javascript method should create an object that has the userid, jabberid, and name of the individual being invited, and pass in an object that is indexed by the invite ID. This will notify the call widget that an invite has been sent out, and create a visual indication that an invite has been sent to the user with the name that was passed into the widget. Below is an example of how to implement the invite external interface:

```
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
```

This functionality only works when one of the users is logged into their TokBox account.

##### hangup #####
If you want the ability to hang up a user, then take advantage of the ''hangup'' function. By passing in a Jabber ID, you can hang up a user, and their video box will be removed from the call, and their call will be disconnected. However, the call will continue for the other participants minus the user who was disconnected.

Example code for this function:
```
     function hangup() {
          var user = {};
          user.jabberId = "547935@jabber.dev.tokbox.com";

          document.getElementById("tbx_call").hangupUser(user);
     } 
```

##### addContent #####
If you want to be able to programmatically add third party content such as a YouTube video or a SlideShare presentation or a TokBox Video Mail to a call, then use the ''addContent'' function.

Example code for this function:
```
     function addVideo() {
          document.getElementById('tbx_call').addContent("http://www.youtube.com/watch?v=-59TlpmQE1c");
     }
```

##### participantLeftCall #####
If you want to be notified when a participant leaves the chat, then use this function.

Example code for this function:
```
     function participantLeftCall() {
          alert('User just left chat');
     }
```

##### participantJoinedCall #####
If you want to be notified when a participant joins the chat, then use this function.

Example code for this function:
```
     function participantJoinedCall() {
          alert('User just joined chat');
     }
```

##### toggleMute #####
To toggle the mute of the local participant in the video chat, then use this function. The user will see that they are muted in their video view, and can unmute themselves.

Example code for this function:
```
     function toggleMute() {
          //assuming that your widget id is tbx_call
          document.getElementById('tbx_call').toggleMute();
     }
```

##### hangupCall #####
To close a call for a local participant, then use this function.

Example code for this function
```
     function toggleMute() {
          //assuming that your widget id is tbx_call
          document.getElementById('tbx_call').hangupCall();
     }
```