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

$is_logged = false;
$is_banned = false;
$is_admin = false;
$show_footer = false;

$only_message = false;


$is_more = false;
$is_more1 = '';

if(isset($_REQUEST['uid']) && isset($_REQUEST['accesstoken'])) {
	// A Genuine request

	$uid = $_REQUEST['uid'];
	$accesstoken = $_REQUEST['accesstoken'];

	$q1 = mysql_query("Select * from members where id='".$uid."' and accesstoken='".$accesstoken."'");

	if(mysql_num_rows($q1) > 0) {
		// UID and ACCESSTOKEN is valid.

		mysql_query("Update members set recent='".date("U")."' where id=".$uid."") or die(serror("Unable to update recent time"));

		$is_logged = true;
		$show_footer = true;
		
		$user = mysql_fetch_array($q1);
		
		update($user['id']);

		if($user['banned'] == '1') {
			$is_banned = true;
		}

		if($user['rank'] == 'owner') {
			$is_admin = true;
		}

		if(isset($_GET['onlymsg'])) {
			$only_message = true;
		}

		if(isset($_GET['read'])) {
			$back_btn = 'pm.php';
		}
		else {
			$back_btn = 'login.php';
		}

		if(!$only_message) {
			print '

			<div style="visibility: none;" class="sound_class">
				<audio>
					<source src="./audio/new_msg_normal.mp3"></source>
				</audio>
			</div>
			<div class="userdetailsforjson" uid="'.$user['id'].'" accesstoken="'.$user['accesstoken'].'"></div>
			<div class="title">
				<div class="back_button_div">
					<a href="'.$back_btn.'"><img class="back_button" src="./images/back_btn.png"></a>
				</div>
				<div class="pagetitle">
					<h1>Messages</h1>
				</div>';
				if(isset($_GET['read'])) { print '
					<div class="smiley_button_div">
						<img class="smiley_button" src="./images/smileybtn.png">
					</div>';
				}
				print '
			</div>';
			if(isset($_GET['read'])) {
				print '
				<div class="popup">
					<div class="close"><img src="./images/closebox.png"></div>
					<iframe src="./emoticons_old.html" width="100%" height="100%"></iframe>';//<div class="smiley-container"></div>
					print '
				</div>';
			}
		}
		
		if($is_banned == true) {
			?><script>gohome(); alert('You have beend banned!');</script><?php 
			die();
		}
		
		if($is_logged == false) {
			?><script>gohome(); alert('You must be logged in to view!');</script><?php
			die();
		}
	
	
		if(isset($_GET['read'])) {
		
			if(isset($_GET['more'])) { 
				
				$is_more = true;
				$more = getfilter(trim($_GET['more']));
				$start = $more*5;
			}
			else {
				
				$is_more1 = 'first';
				$start = 10;
			}
			
			$id = getfilter(trim($_GET['read']));
			$msg_with = $id;
			$user_id = $user['id'];
			$has_thread_id = false;
			$is_friends = false;
			$msg_send = null;
			
			$timenow = date("M d, H:i");

			/*check if thread id exist*/
			$msg_thread = mysql_query("select * from message_thread where type='1'") or die(mysql_error());
			while($message_thread = mysql_fetch_array($msg_thread)) {
				$users_v = $message_thread['users'];
				$pieces = explode(":",$users_v);
				if(($pieces[0] == $msg_with && $pieces[1] == $user_id) || ($pieces[0] == $user_id && $pieces[1] == $msg_with)) {
					$has_thread_id = true;
					$thread_id = $message_thread['id'];
					
				}
			}
			if(!$only_message) {
				print '
				<div class="msgform">
					<form method="post" action="pm.php?read='.$id.'" class="pm_form">
						<input class="msginput" type="text" name="message" placeholder="type message here..">
						';//<input class="btn_no_width" type="submit" name="send" value="Send">
						print '
					</form>
				</div>';
			}
			if(!$only_message) {
				print '
				<div class="pm pm_page">';
			}
			
			if(isset($_REQUEST['send'])) {
				$sender = $user['id'];
				$receiver = $msg_with;
				
				$message = commentsfilter(trim($_REQUEST['message']));
				if(strlen($message) > 0 ) {
					//print $message;
					if($has_thread_id == true) {

						$lmt = date('U');
					
						$remove_last = mysql_query("update pm set last='0' where last='1' and tid='".$thread_id."'");
					
						$send1 = mysql_query("insert into pm (sender,receiver,message,time,tid,last) values ('$sender','$receiver','$message','$timenow','$thread_id','1')");

						$up_lmt_t = mysql_query("update message_thread set last_message_time=".$lmt." where id=".$thread_id);
				
						if($send1) { $msg_send = true; } else { $msg_send = false; }
					}
					else {
						
						$users_thread = $sender.":".$receiver;
						
						$lmt = date('U');
						
						$create_thread = mysql_query("insert into message_thread (users,type,last_message_time) values('$users_thread','1','$lmt')");
						if($create_thread) {
						
							$select_thread = mysql_query("select * from message_thread where users='".$users_thread."'");
							$select_thread_new = mysql_fetch_array($select_thread);
							$tid_new = $select_thread_new['id'];

							$has_thread_id = true;
							$thread_id = $tid_new;
							
							$send1 = mysql_query("insert into pm (sender,receiver,message,time,tid,last) values ('$sender','$receiver','$message','$timenow','$tid_new','1')");
							if($send1) { $msg_send = true; } else { $msg_send = false; }
						
						}
					}
				}		
			}

			if($has_thread_id == true) {
				$pmget = mysql_query("select * from pm where tid='".$thread_id."' order by id desc limit 0,".$start."") or die(mysql_error());
				
				$get_msg_with = mysql_query("select * from members where id='".$msg_with."'") or die(mysql_error());
				$msg_with_user = mysql_fetch_array($get_msg_with);

				//print '
				//<div class="erow_small_fonts static">
				//	'.$msg_with_user['firstname'].' last seen at '.$msg_with_user['lastseen'].'
				//</div>
				//<div class="msgcontainerttt">';
				$lmi = null;

				while($pm = mysql_fetch_array($pmget)) {
					$sender11 = mysql_query("select * from members where id='".$pm['sender']."'") or die(mysql_query());
					$sender2 = mysql_fetch_array($sender11);

					$query = mysql_query("Update pm set viewed='1' where id='".$pm['id']."' and receiver='".$user['id']."'");
					
					if($sender2['id'] == $user['id']) {
						print '
						<li class="small_msg_box" style="text-align:right;" li-msg-id="'.$pm['id'].'">
							<div id="shouter"><a href="profile.php?id='.$sender2['id'].'"><span>'.$pm['time'].'</span> You</a></div>
							<div class="message">'.nl2br(makesmiley($pm['message'],"normal")).'</div>
						</li>';
						if($lmi == null) {
							$lmi = $pm['id'];
						}
					}
					else {
						print '
						<li class="small_msg_box" li-msg-id="'.$pm['id'].'">
							<div id="shouter"><a href="profile.php?id='.$sender2['id'].'">'.$sender2['username'].'</a><span>'.$pm['time'].'</span></div>
							<div class="message">'.nl2br(makesmiley($pm['message'],"normal")).'</div>
						</li>';
						if($lmi == null) {
							$lmi = $pm['id'];
						}
					}
				}

				print '<div class="lmi_selector" code="'.$lmi.'"></div><div class="tid_selector" code="'.$thread_id.'"></div>';
				//print '
				//</div>';
			}
			else {
				print '
				<div class="erow margin_erow">
					You have no messages
				</div>';
			}

			print '
			<div id="big_msg_box">';
			
			// mysql_query("update pm set viewed='1',viewtime='".$timenow."' where tid='".$thread_id."' and viewed='0' and receiver='".$user['id']."'") or die(mysql_error());

					
			print '
			<div class="normal_box">';
				if($is_more == true) {
					print '<a href="pm.php?read='.$id.'&more='.($more+1).'">Show old messages</a>';
				} else if($is_more1 == 'first') {
					print '<a href="pm.php?read='.$id.'&more=2">Show old messages</a>'; 
				}
				print '
			</div>
			
			</div>';				
		}
		else
		{
			$am_sender = null;
			$am_receiver = null;
		
			if(!$only_message) {
				print '
				<div class="shout_container pm_main pm_m_page">';
			}
			

			$a1 = mysql_query("select * from pm where last='1' order by id desc");
			
			if(mysql_num_rows($a1) >= 1) {
			
				while($a2 = mysql_fetch_array($a1)) {
					if(($a2['sender'] == $user['id']) || ($a2['receiver'] == $user['id'])) {
						
						$arrow = '';
						if($a2['sender'] == $user['id']) {
							$am_sender = true;
							$arrow = '<div class="arrow"><img src="./images/sender.png"></div>';
							$name_view1 = mysql_query("select * from members where id='".$a2['receiver']."'") or die(mysql_query());
							$name_view = mysql_fetch_array($name_view1);
						}
						else {
							$am_receiver = true;
							$arrow = '<div class="arrow"><img src="./images/receiver.png"></div>';
							$name_view1 = mysql_query("select * from members where id='".$a2['sender']."'") or die(mysql_query());
							$name_view = mysql_fetch_array($name_view1);
						}

						$readed = true;
						if($a2['viewed'] == '0') {
							if($a2['receiver'] == $user['id']) {
								$readed = false;
							}
						}
						
						print '
						<li class="small_msg_box_no_pad thread-block-'.$a2['tid'].'">
							<div id="shouter"><a href="pm.php?read='.$name_view['id'].'">'.$name_view['username'].'</a><span>'.$a2['time'].'</span></div>
							<div class="message '; if($readed == false) { print 'unread_om'; } print'">'.nl2br(makesmiley($a2['message'],"normal")).'</div>
						</li>';
					}
				}
			
			}
			else {
				print '
				<div class="erow">
					No messages
				</div>';
			}
				
			if(!$only_message) {
				print '
			</div>';
			}
		}
	}
}

mysql_close($connect);
?>

