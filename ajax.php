<?php
//error_reporting(0);

function serror($errcode) {
	$output = array("result"=>"error","message"=>"Error : ".$errcode."");
	print json_encode($output);
}

include('connect.php');
include('functions.php');

$doreply = false;

class shout {
	public $sid;
	public $senderid;
	public $message;
	public $sendername;
	public $time;
}

class notification {
	public $nid;
	public $message;
}

class online_now {
	public $uid;
	public $username;
	public $picture;
}

class pm {
	public $tid;
	public $pmid;
	public $senderid;
	public $username;
	public $message;
	public $time;
}

class rawdata {

	public $mem_on;
	public $unread_msg;
	public $friend_requests;
	public $notifications;

	public $has_new_msg;
	public $messages;

	public $has_new_smsg;
	public $smessages;
}


if(isset($_REQUEST['uid']) && isset($_REQUEST['accesstoken'])) {
	// A Genuine request

	$uid = $_REQUEST['uid'];
	$accesstoken = $_REQUEST['accesstoken'];

	$q1 = mysql_query("Select * from members where id='$uid' and accesstoken='$accesstoken'");

	if(mysql_num_rows($q1) > 0) {
		// UID and ACCESSTOKEN is valid.

		mysql_query("Update members set recent='".date("U")."' where id=".$uid."") or die(serror("Unable to update recent time"));

		if(isset($_REQUEST['update'])) {
			// Update request.
			$req = $_REQUEST['update'];
			$received_data = json_decode($req, true);

			$tt = $received_data['datas'];
			foreach($tt as $row) {
				if($row['delivered'] == 'yes') {
					$qu = mysql_query("Update pm set delivered='1' where tid='".$row['tid']."'");
					//echo 'updated delivered no. '.$row['tid'];
				}
				if($row['read'] == 'yes') {
					$qu = mysql_query("Update pm set viewed='1' where tid='".$row['tid']."'");
					//echo 'updated read no. '.$row['tid'];
				}		
			}
		}
		else if(isset($_REQUEST['nup'])) {

			$up = mysql_query("update notifications set checked='1' where id=".getfilter($_REQUEST['nup'])) or serror(mysql_error());
		}
		else if(isset($_REQUEST['threads'])) {

			$data_to_send = new rawdata();
			$data_to_send->pm = array();

			$get_threads = mysql_query("select * from message_thread where users like '%".$uid.":%' UNION select * from message_thread where users like '%:".$uid."%' order by last_message_time asc");
			while($theThread = mysql_fetch_array($get_threads)) {

				$pmget = mysql_query("select * from pm where tid=".$theThread['id']." order by id desc limit 0,1") or serror('1 - '.mysql_error());
					
				//while($pm = mysql_fetch_array($pmget)) {

					$pm = mysql_fetch_array($pmget);

					if($pm['sender'] == $uid) {
						$toGET = $pm['receiver'];
					}
					else {
						$toGET = $pm['sender'];
					}

					$getsender = mysql_query("select id,username,picture from members where id='".$toGET."'");
					$sender = mysql_fetch_array($getsender);

					if($sender['picture'] < 1) {
						$sender['picture'] = $site_link.'/images/default_profile.jpg';
					}

					$current_data = new pm();

					$current_data->tid = $pm['tid'];
					$current_data->pmid = $sender['id'];
					$current_data->username = $sender['username'];
					$current_data->picture = $sender['picture'];
					$current_data->message = nl2br(makesmiley($pm['message'],'normal'));
					$current_data->time = $pm['time'];

					$data_to_send->pm[] = $current_data;
				//}
			}

			print $encoded_data = json_encode($data_to_send);
		}
		else if(isset($_REQUEST['online'])) {

			$data_to_send = new rawdata();
			$data_to_send->online_now = array();

			$buddyList = mysql_query("select * from buddylist where sender=".$uid." or receiver=".$uid) or serror(mysql_error());
			while ($bl = mysql_fetch_array($buddyList)) {
				if($bl['accepted'] == 1) {
					if($bl['sender'] == $uid) {
						$toCheck = $bl['receiver'];
					}
					else { $toCheck = $bl['sender']; }

					$k = date("U")-180;
					$sq = mysql_query("select id,username,picture from members where id=".$toCheck." and recent > '$k' and online='1'") or serror(mysql_error());
					if(mysql_num_rows($sq)>0) {

						$theUser = mysql_fetch_array($sq);

						if($theUser['picture'] < 1) {
							$theUser['picture'] = $site_link.'/images/default_profile.jpg';
						}

						$onn = new online_now();

						$onn->uid = $theUser['id'];
						$onn->username = $theUser['username'];
						$onn->picture = $theUser['picture'];

						$data_to_send->online_now[] = $onn;
					}
				}
			}

			print $encoded_data = json_encode($data_to_send);
		}
		else {

			$data_to_send = new rawdata();

			$data_to_send->friend_request = array();
			$data_to_send->notifications = array();
			$data_to_send->unread_msg = get_unread_pm($uid);

			$frc = 0;
			$mem_on = 0;


			if(isset($_REQUEST['pm_lmi'])) {
				$lmi = $_REQUEST['pm_lmi'];

				$chi = mysql_query("select * from pm where id=".$lmi) or die(mysql_error());
				$thePM = mysql_fetch_array($chi);

				$theThread = $thePM['tid'];

				$chin = mysql_query("select * from pm where tid=".$theThread." and id > ".$lmi." order by id desc") or die(mysql_error());
				if(mysql_num_rows($chin)) {

					$data_to_send->has_new_msg = 1;
					$data_to_send->messages = array();
					
					while ($thePM = mysql_fetch_array($chin)) {
						
						$current_pm = new pm();

						$current_pm->tid = $theThread;
						$current_pm->pmid = $thePM['id'];
						$current_pm->message = nl2br(makesmiley($thePM['message'],"normal"));
						$current_pm->time = $thePM['time'];

						$theSender = mysql_query("select * from members where id=".$thePM['sender']) or die(mysql_error());
						$theSender = mysql_fetch_array($theSender);

						$current_pm->senderid = $theSender['id'];
						$current_pm->username = $theSender['username'];

						$data_to_send->messages[] = $current_pm;
					}
				}
			}

			if(isset($_REQUEST['s_lmi'])) {
				$lmi = $_REQUEST['s_lmi'];

				$chin = mysql_query("select * from shouts where id > ".$lmi." order by id desc") or die(mysql_error());
				if(mysql_num_rows($chin)) {

					$data_to_send->has_new_smsg = 1;
					$data_to_send->smessages = array();
					
					while ($thePM = mysql_fetch_array($chin)) {
						
						$current_pm = new shout();

						$current_pm->sid = $thePM['id'];
						$current_pm->senderid = $thePM['shouter'];
						$current_pm->message = nl2br(makesmiley($thePM['message'],"normal"));
						$current_pm->time = $thePM['time'];

						$theSender = mysql_query("select * from members where id=".$thePM['shouter']) or die(mysql_error());
						$theSender = mysql_fetch_array($theSender);

						$current_pm->sendername = $theSender['username'];

						$data_to_send->smessages[] = $current_pm;
					}
				}
			}

			$buddyList = mysql_query("select * from buddylist where sender=".$uid." or receiver=".$uid) or serror(mysql_error());
			while ($bl = mysql_fetch_array($buddyList)) {

				if($bl['accepted'] == 1) {
					
					if($bl['sender'] == $uid) {
						$toCheck = $bl['receiver'];
					}
					else { $toCheck = $bl['sender']; }

					$k = date("U")-180;
					$sq = mysql_query("select id,username,picture from members where id=".$toCheck." and recent > '$k' and online='1'") or serror(mysql_error());
					if(mysql_num_rows($sq)>0) {

						$mem_on++;
					}
				}
				else {
					if($bl['receiver'] == $uid) {
						$frc++;	
					}
				}
			}

			$data_to_send->mem_on = $mem_on;
			$data_to_send->friend_requests = $frc;

			$notifications = mysql_query("select * from `notifications` where `nfor`=".intval($uid)." and `checked`=0") or serror(mysql_error());
			while($notification = mysql_fetch_array($notifications)) {

				$current_notification = new notification();

				$current_notification->nid = $notification['id'];
				$current_notification->message = $notification['message'];

				$data_to_send->notifications[] = $current_notification;
			}

			print $encoded_data = json_encode($data_to_send);
		}
	}
	else {
		print '{ "error" : "wrong accesstoken" }';
	}
}
else {
	print '{ "error" : "no arguments" }';
}