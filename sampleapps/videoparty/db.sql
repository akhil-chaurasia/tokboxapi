#Script for generating the database and table neccessary for the TokBox party sample application
DROP DATABASE IF EXISTS sampleApp;
create database sampleApp;
use sampleApp;

DROP TABLE IF EXISTS calls;
create table calls(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	callid VARCHAR(32) NOT NULL,
	subject VARCHAR(50) DEFAULT "Open Chat",
	created TIMESTAMP NOT NULL,
	list BOOLEAN DEFAULT true
);
