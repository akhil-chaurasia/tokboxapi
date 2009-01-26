package com.tokbox.exception;

public class TokBoxRuntimeException extends RuntimeException{

	private static final long serialVersionUID = -1665947194882517132L;
	
	public TokBoxRuntimeException(String message, Throwable rootCause) {
		super(message, rootCause);
	}

	public TokBoxRuntimeException() {
		super();
	}

	public TokBoxRuntimeException(String message) {
		super(message);
	}

	public TokBoxRuntimeException(Throwable cause) {
		super(cause);
	}
}