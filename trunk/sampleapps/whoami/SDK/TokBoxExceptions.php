<?php

class NotLoggedInException extends Exception {
    public function __construct($message) {       
        parent::__construct($message, 401);
    }
}

class MalformedXmlException extends Exception {
	public function __construct($message) {
		parent::__construct($message);
	}
}