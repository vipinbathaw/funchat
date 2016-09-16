<?php

/*	author: vipin bathaw
	year: 2012
	licence: gpl
	note: you are allowed to modify script, but selling is prohibited.
*/

session_start();
date_default_timezone_set('asia/kolkata');

require_once('connect.php');
require_once('functions.php');

$hasError = false;
$theError = "";

$er_user_or_email_taken = false;
$user_or_email_is_small = false;
$pass_is_small = false;
$invalid_char_in_user = false;
$show_footer = true;

$show_form = true;

if(isset($_POST['submit'])) {
	
	$firstname = getfilter(trim($_POST['firstname']));
	$lastname = getfilter(trim($_POST['lastname']));
	$name = $firstname.' '.$lastname;
	$password = md5(trim($_POST['password']));
	$username = getfilter(trim($_POST['username']));
	$email = trim($_POST['email']);
	$dob = getfilter(trim($_POST['date'])).'-'.getfilter(trim($_POST['month'])).'-'.getfilter(trim($_POST['year'])).'';
	
	function shuffle_words() {
		$word = "abcdefghjklmnopqrstuvwxyz123456789";
		$shuffled = str_shuffle($word);
		$shuffled2 = str_split($shuffled,5);
		$shuffled3 = $shuffled2[1];
		return $shuffled3;
	}
	
	$validcode = shuffle_words();
	$scode = shuffle_words();
	$browser = mysql_real_escape_string(trim($_SERVER['HTTP_USER_AGENT']));
	$joined = date("Y-m-d H:i:s");
	
	$check3 = mysql_query("select * from members where username='".$username."' or email='".$email."'") or die(mysql_error());
	$check = mysql_fetch_array($check3);
	
	if($check) {	
		$hasError = true;
		$theError = "Username or Email is already in use";
	}

	if(strlen($username) < 4) {
		$hasError = true;
		$theError = "Username is less than 4 characters";
	}
	
	if(strlen($email) < 8) {
		$hasError = true;
		$theError = "Email is not valid";
	}
	
	if(!preg_match("^[a-za-z0-9]+$^", "$username")) {
		$hasError = true;
		$theError = "Invalid characters in username";
	}
	
	if($hasError) {
		print json_encode(array("result"=>"error","message"=>$theError));
	}
	else {
	
		$query = mysql_query("insert into members (
			firstname,
			lastname,
			fullname,
			username,
			password,
			email,
			vremail,
			isreal,
			rank,
			dob,
			banned,
			picture,
			lastonline,
			validcode,
			recent,
			scode,
			browser,
			joined
		) values ('$firstname','$lastname','$name','$username','$password','$email','0','1','normal','$dob','0','$default_pic','nil','$validcode','nil','$scode','$browser','$joined')") or die(mysql_error());

		if($only_email_verified) {
			@mail($mail, $site_name. ' Account Verification', "Hello ".$firstname.", to verify your account click <a href=\"".$site_link."/verify.php?id=".$validcode."\">here</a>");
		}
		
		if($query) { 
			print json_encode(array("result"=>"success","message"=>"You have been registered successfully")); 
		}
		else {
			print json_encode(array("result"=>"error","message"=>"Internal error, register after sometime"));
		}
	}

	//print json_encode($output);
}
else {
	print json_encode(array("result"=>"error","message"=>"Invalid Request"));
}

mysql_close($connect);
	
?>