<?php

session_start();
date_default_timezone_set('asia/kolkata');

require_once('connect.php');
require_once('functions.php');

$is_logged = false;
$is_banned = false;
$is_admin = false;
$wrong_login = false;
$logged_out = false;
$show_footer = false;

$_LOGGEDUSER = '';

class rawdata {
	public $result;
	public $username;
	public $userfirstname;
	public $uid;
	public $accesstoken;
	public $friendrequests;
	public $unreadmsg;
	public $onlinebuddy;
}

function serror($n) {
	if($n == '2') {
		$output = array("result"=>"error","message"=>"Error: Unable to set AT");
		print json_encode($output);
	}
}


if(isset($_POST['submit'])) {
	$username = getfilter(trim($_POST['username']));
	$password = md5(getfilter(trim($_POST['password'])));
	
	$fetch_members = mysql_query("select * from members where username='$username' and password='$password'") or die(mysql_error());
	$_members = mysql_fetch_array($fetch_members);

	$isAUTH = false;

	if($_members) {

		if($only_email_verified) {
			if($_members['vremail'] == '1') {
				$isAUTH = true;
			}
		}
		else {
			$isAUTH = true;
		}
	}

	if($isAUTH) {

		$_SESSION['user'] = $username;
		$_SESSION['userid'] = $_members['id'];

		$_LOGGEDUSER = $username;

		//GENERATE A ACCESSTOKEN AND UPDATE DB.

		/* Strings from which word will be selected.*/
		$word = "abcdefghjklmnopqrstuvwxyz123456789";
		/*Shuffle the strings*/
		$shuffled = str_shuffle($word);
		/*Split the shuffled strings in pair of 5 words*/
		$shuffled2 = str_split($shuffled,8);
		/*Select first pair from shuffled words group*/
		$accesstoken2 = $shuffled2[1];

		mysql_query("update members set accesstoken='".$accesstoken2."' where username='".$username."'") or die(serror('2'));
		
		$_SESSION['accesstoken'] = $accesstoken2;

		update($_members['id']);
		
		if($_members['banned']=='1') {
			$is_banned = true;
		}
		
		$is_logged = true;
		
		
		if($_members['rank']=='owner') {
			$is_admin = true;
		}
		
	}
	else {
		$wrong_login = true;
	}
}

if($is_logged) {
	$user3 = $_LOGGEDUSER;
	$user2 = mysql_query("select * from members where username='".$user3."'") or die(mysql_error());
	$user = mysql_fetch_array($user2);

	update($user['id']);
	$show_footer = true;

	if($user['banned'] == '1') {
		$is_banned = true;
	}

	if($user['rank'] == 'owner') {
		$is_admin = true;
	}
}
	
	if($is_banned == true) {
		$output = array("result"=>"error","message"=>"You have been banned");
		print json_encode($output);
		die();
	}
		
	if($is_logged == true) {
		/*check if any friend request*/
		$query2 = mysql_query("select * from buddylist where receiver='".$user['id']."' and accepted='0'");
		$friend_requests = mysql_num_rows($query2);
	
		$unread_msg = get_unread_pm($user['id']);
		$online_buddies = get_online_buddies($user['id']);


		$data_to_send = new rawdata();
		$data_to_send->result = "success";
		$data_to_send->username = $user['username'];
		$data_to_send->userfirstname = $user['firstname'];
		$data_to_send->uid = $user['id'];
		$data_to_send->accesstoken = $user['accesstoken'];
		$data_to_send->friendrequests = $friend_requests;
		$data_to_send->unreadmsg = $unread_msg;
		$data_to_send->onlinebuddy = $online_buddies;

		print json_encode($data_to_send);
	}
	else {
		$output = array("result"=>"error","message"=>"Username or Password is incorrect");
		print json_encode($output);
	}
	
mysql_close($connect);

// this is the end of index.php file.
?>