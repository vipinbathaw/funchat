<?php

date_default_timezone_set('asia/kolkata');

require_once('connect.php');
require_once('functions.php');

$is_logged = false;
$is_banned = false;
$is_admin = false;
$is_more = false;

$is_friend = false;
$is_me = false;

class rawdata {
	public $result;
	public $profimage;
	public $friend_or_me;
	public $uid;
	public $in_req;
	public $in_req_id;
	public $username;
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

			$output = new rawdata();
			$output->result = "success";

			$id = getfilter(trim($_GET['id']));
			
			$getprofile3 = mysql_query("select * from members where id=".$id) or die(mysql_error());
			$getprofile = mysql_fetch_array($getprofile3);
			
			$tttt = mysql_query("SELECT * FROM buddylist WHERE receiver = $user[id] AND sender = $getprofile[id] UNION SELECT * FROM buddylist WHERE receiver = $getprofile[id] AND sender = $user[id]");
			if(mysql_num_rows($tttt) > 0 ) {

				$rtr = mysql_fetch_array($tttt);

				$output->in_req = "true";
				$output->in_req_id = $rtr['id'];

				if($rtr['accepted'] == 1) {
					$is_friend = true;
				}
			}
			else {
				$output->in_req = "no";
			}
			
			if($getprofile['id'] == $user['id']) {
				$is_me = true;
			}
			
			if($getprofile['picture'] < 1) {
				$profimage = $site_link.'/images/default_profile.jpg';
			}
			else {
				$profimage = $getprofile['picture'];
			}
			
			$output->profimage = $profimage;
			$output->uid = $id;
			$output->username = $getprofile['username'];

			if($is_friend == true || $is_me == true) {
			
				$output->friend_or_me = "true";
			}
			else {
				$output->friend_or_me = "false";
			}

			print json_encode($output);

		}
	}

	function serror($errcode) {
		$output = array("result"=>"error","message"=>"Error : ".$errcode."");
		print json_encode($output);
	}
}

mysql_close($connect);
?>

