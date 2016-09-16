<?php

date_default_timezone_set('asia/kolkata');

require_once('connect.php');
require_once('functions.php');

$is_logged = false;
$is_banned = false;
$is_admin = false;
$is_more = false;

class rawdata {
	public $result;
	public $sresults;
}

if(isset($_GET['uid']) && isset($_GET['accesstoken'])) {
	//A GENUINE REQUEST.
	$uid = $_GET['uid'];
	$accesstoken = $_GET['accesstoken'];

	//CHK IF USERNAME N ACCESSTOKEN IS RIGHT.
	$qq = mysql_query("Select * from members where id='".$uid."' and accesstoken='".$accesstoken."'") or die(serror(mysql_error()));
	if(mysql_num_rows($qq)>0) {
		//VALID USER.
		$user = mysql_fetch_array($qq);

		if($user['banned'] == '1') {
			// USER IS BANNED.
			serror("You have been banned");
			die();
		}
		else {
			//DO WORK AS PER PAGE.
		}
	}

	function serror($errcode) {
		$output = array("result"=>"error","message"=>"Error : ".$errcode."");
		print json_encode($output);
	}
}
?>