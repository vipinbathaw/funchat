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

if(isset($_REQUEST['uid']) && isset($_REQUEST['accesstoken'])) {
	// A Genuine request

	$uid = $_REQUEST['uid'];
	$accesstoken = $_REQUEST['accesstoken'];

	$q1 = mysql_query("Select * from members where id='".$uid."' and accesstoken='".$accesstoken."'");

	if(mysql_num_rows($q1) > 0) {
		// UID and ACCESSTOKEN is valid.

		mysql_query("Update members set recent='".date("U")."' where id=".$uid."") or die(serror("Unable to update recent time"));
		
		$user = mysql_fetch_array($q1);
		
		update($user['id']);	

		$start = 5;
		$timenow = date("M d, H:i");

		if(isset($_REQUEST['send'])) {

			$sender = $user['id'];
			
			$message = commentsfilter(trim($_REQUEST['message']));

			if(strlen($message) > 0 ) {

				$lmt = date('U');
			
				$send1 = mysql_query("insert into shouts (shouter,message,time) values ('$sender','$message','$lmt')");
		
				//if($send1) { $msg_send = true; } else { $msg_send = false; }
			}
		}

		$pmget = mysql_query("select * from shouts order by id desc limit 0,15") or die(mysql_error());

		while($pm = mysql_fetch_array($pmget)) {



			$sender11 = mysql_query("select * from members where id='".$pm['shouter']."'") or die(mysql_query());
			$sender2 = mysql_fetch_array($sender11);
			
			if($sender2['id'] == $user['id']) {
				print '
				<li class="small_msg_box" style="text-align:right;" li-smsg-id="'.$pm['id'].'">
					<div id="shouter"><a href="profile.php?id='.$sender2['id'].'">You</a></div>
					<div class="message">'.nl2br(makesmiley($pm['message'],"normal")).'</div>
				</li>';
			}
			else {
				print '
				<li class="small_msg_box" li-smsg-id="'.$pm['id'].'">
					<div id="shouter"><a href="profile.php?id='.$sender2['id'].'">'.$sender2['fullname'].'</a></div>
					<div class="message">'.nl2br(makesmiley($pm['message'],"normal")).'</div>
				</li>';
			}
		}
	}
}

mysql_close($connect);
?>

