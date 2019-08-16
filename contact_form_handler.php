<?php
session_unset();
session_start();
if(!empty($_POST)){
	if(empty($_POST['firstName'])||empty($_POST['lastName'])||empty($_POST['email'])||empty($_POST['subject'])||empty($_POST['message'])) { 
		$_SESSION['errorEmptyData'] = true;
		header("Location: contact.php");
		exit();
	}
	$client =[
		'firstName' => "",
		'lastName' => "",
		'email' => "",
		'subject' => "",
		'message' => "",
		"IP" => $_SERVER['REMOTE_ADDR'],
	];
	$mailTo = "";
	$mailMessage = "";
	$mailHeader = "";
	if(isset($_POST['firstName'])){
		$client['firstName'] = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
	}
	if(isset($_POST['lastName'])){
		$client['lastName'] = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
	}
	if(isset($_POST['email'])){
		$client['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$client['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		if(!$client['email']){
			$_SESSION['errorWrongEmail'] = true;
			header("Location: contact.php");
			exit();
		}
	}
	if(isset($_POST['subject'])){
		$client['subject'] = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
	}
	if(isset($_POST['message'])){
		$client['message'] = htmlspecialchars($_POST['message']);
	}
	$mailTo .= "workshopcontact@workshop.com";
	$mailMessage.= "First name: {$client['firstName']} \r\n";
	$mailMessage.= "Last name: {$client['lastName']} \r\n";
	$mailMessage.=" {$client['message']}";
	$mailHeader .= "From: {$client['email']}\r\n"."ClientIP: {$client['IP']}";
	if(mail($mailTo, $client['subject'], $mailMessage,$mailHeader)){
		$_SESSION['success'] = true;
		header("Location: contact.php");
		exit();
	}else{
		$_SESSION['errorSendingEmail'] = true;
		header("Location: contact.php");
		exit();
	}
}else{
	$_SESSION['errorEmptyData'] = true;
	header("Location: contact.php");
	exit();
}