<?php
$base_url 		= "http://localhost/SCSElection/";
$secret_key 	= "!HdT9Ham$3";

$smtp_host 		= "smtp.gmail.com";
$smtp_email 	= "p146011@nu.edu.pk";
$smtp_password 	= "PASSWORDHERE";
$smtp_name 		= "Owais (SCS Election Script)";

$email_db = "db/email/";
$candidates_files = "db/.candidates.csv";
$result_file = "db/.results.csv";
$allowed_emails = explode("\n", file_get_contents("db/.allowed_emails.txt"));
$sent = null;

$positions = [
	'Vice President',
	'Assistant Vice President',  
	'General Secretary',
	'Assistant General Secretary (Only CS15)',
	'Treasurer',
	'Assistant Treasurer (Only CS15)',
	'Social Media Activist/Promotion/Designer',
	'Event Coordinator'
];

//Load composer's autoloader
require 'vendor/autoload.php';