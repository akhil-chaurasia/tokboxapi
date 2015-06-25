## Setup the SDK ##

  * Get a Sandbox API key/secret pair (http://sandbox.tokbox.com/view/platformkeys)

  * Download the PHP\_v1 SDK from the TokBox Platform Google Code site:

  * http://code.google.com/p/tokboxapi/downloads

  * In API\_Config.php put in your API key/secret pair. As an example, let's say my API key were 1127 and my API secret were f92f49ece2ac828587da9266858578e4. Then my API\_Config.php file would look like:

```
		// Replace this value with your TokBox API Partner Key 
		const PARTNER_KEY = "1127";

		// Replace this value with your TokBox API Partner Secret 
		const PARTNER_SECRET = "f92f49ece2ac828587da9266858578e4";
```

Great! Now our SDK is all setup, and we're ready to setup a moderated call!

## Moderated Calls ##

First, we're going to create an open moderated call. In an open moderated call anyone
is allowed to join the call immediately, but if there is a moderator in the room,
then they can still be kicked out, silenced or even promoted. Only registered users
can generate moderated calls, and so before we dive into moderated calls, let's
setup a new user

### Register a user ###

To get moderated calls working, we're going to register a user, and save their
credentials to our API\_Config.php file.

  * To create a registered user, copy and paste the code below into a PHP file, and then we'll walk through the code. Make sure to substitute the email, last name and first name for your own.

```
<?php
	require_once('SDK/TokBoxUser.php');
	
	$apiObj = TokBoxUser::registerUser('example@tokbox.com', 'User', 'Moderation');

	echo "The user is ".$apiObj->getJabberId()."<br/>";
	echo "The access token is ".$apiObj->getSecret()."<br/>";
```

  * If you go to this file in your browser, then you should see a jabber ID and a user access token printed on the page. Mine were:

```
    The user is 547747@jabber.dev.tokbox.com
    The access token is a823adc03a4885080fbb8cba2f76a33a
```

  * Let's go ahead and copy these values into our API\_Config.php file like we did earlier. Don't forget to substitute my values for the values you generated.

```
     // User who generates moderated calls
     const MODERATION_USER = "547747@jabber.dev.tokbox.com";
		
     // User Access Token for moderated calls
     const MODERATION_USER_SECRET = "a823adc03a4885080fbb8cba2f76a33a";
```

### Creating a moderated call ###

  * To create a moderated call, first copy and paste the code below into a PHP file, and then we'll walk through the code.

```
<?php
	require_once('SDK/TokBoxCall.php');

	$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
	$apiObj->setJabberId(API_Config::MODERATION_USER);
	$apiObj->setSecret(API_Config::MODERATION_USER_SECRET);

	$callName = "My first moderated chat";
	$callType = "open";

	$user_parts = explode("@", API_Config::MODERATION_USER);
	$callOwnerId = $user_parts[0];

	$callDefId = 1;

	$callInstanceId = TokBoxCall::createModeratedCall($apiObj, $callName, $callType, $callOwnerId, $callDefId);
	$callId = TokBoxCall::startModeratedCall($apiObj, $callInstanceId);

	echo TokBoxCall::generateModerationEmbedCode($callId, API_Config::MODERATION_USER, API_Config::MODERATION_USER_SECRET);
```

Congratulations! You've just started a moderated call.