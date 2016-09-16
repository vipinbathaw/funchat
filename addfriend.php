<?php

date_default_timezone_set('asia/kolkata');

function serror($errcode) {
	$output = array("result"=>"error","message"=>"Error : ".$errcode."");
	print json_encode($output);
}

require_once('connect.php');
require_once('functions.php');

$is_logged = false;
$is_banned = false;
$is_admin = false;
$is_more = false;

class rawdata {
	public $result;
	public $sresults = array();
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

			if(isset($_GET['id'])) {
				
				$cansend = false;
				
				$id = getfilter(trim($_GET['id']));
				
				$sendingto2 = mysql_query("select * from members where id='".$id."'") or die(mysql_error());
				$sendingto = mysql_fetch_array($sendingto2);
				
				$sender = $user['id']; $receiver = $sendingto['id'];
				
				$sr = mysql_query("select * from buddylist where sender='".$sender."' and receiver='".$receiver."'") or die(mysql_error());
				if(mysql_num_rows($sr) == 0) {
					$rs = mysql_query("select * from buddylist where sender='".$receiver."' and receiver='".$sender."'") or die(mysql_error());
					if(mysql_num_rows($rs) == 0) {
						$cansend = true;
					}
				}
				
				$date = date("d m d, y h:i:s");
				
				if($cansend == true) {
					mysql_query("insert into buddylist(sender,receiver,accepted,time) values ('$sender','$receiver','0','$date')") or die(mysql_error());
					print '{"result":"success","message":"Request Sent Successfully"}';
				}
				else {
					print '{"result":"error","message":"Cannot send request"}';
				}
			}
			else if(isset($_GET['check'])) {

				$output = new rawdata();
				$output->result = "success";

				$query2 = mysql_query("select * from buddylist where receiver='".$user['id']."' and accepted='0'");
				while($friend_request = mysql_fetch_array($query2)) {
				
					$frid = $friend_request['id'];
					$get_friend_request_sender = mysql_query("select * from members where id='".$friend_request['sender']."'");
					$frs = mysql_fetch_array($get_friend_request_sender);

					if($frs['picture'] == '') {
						$frs['picture'] = $site_link .'/images/default_profile.jpg';
					}

					$jsonRow = array(
						"frid" => $frid,
						"sid" => $frs['id'],
						"sfn" => $frs['username'],
						"spp" => $frs['picture']
					);
					
					array_push($output->sresults, $jsonRow);
					//print '
					//<li class="avg_padding fr"><a href="profile.php?id='.$frs['id'].'&ref=addfriend.php?check">'.$frs['fullname'].'</a> <span>[ <a href="addfriend.php?accept='.$frid.'">accept</a> - <a href="addfriend.php?ignore='.$frid.'">cancel</a> ]</span><li>';
				}
				//print '
				//</ul>';
				print json_encode($output);
			}
			else if(isset($_GET['accept'])) {
				$id = getfilter(trim($_GET['accept']));
				$get_request = mysql_query("select * from buddylist where id='".$id."'");
				$gr = mysql_fetch_array($get_request);
		
				if($gr['receiver'] == $user['id']) {
					$update_req = mysql_query("update buddylist set accepted='1' where id='".$id."'");
					if($update_req) {

						$notification = mysql_query("insert into notifications(`message`,`nfor`) values('".$user['username']." has accepted your buddy request','".$gr['sender']."')") or serror(mysql_error());
						print '{"result":"success","message":"Buddy Request Accepted"}';
					}
					else {
						print '{"result":"error","message":"Error Occured"}';
					}
				}
				else {
					print '{"result":"error","message":"This cannot happen"}';
				}
			}
			else if(isset($_GET['ignore'])) {
				$id = getfilter(trim($_GET['ignore']));
				$get_request = mysql_query("select * from buddylist where id='".$id."'");
				$gr = mysql_fetch_array($get_request);
		
				if($gr['receiver'] == $user['id']) {
					$update_req = mysql_query("delete from buddylist where id='".$id."'");
					if($update_req) {
						print '{"result":"success","message":"Buddy Request Canceled"}';
					}
					else {
						print '{"result":"error","message":"Error Occured"}';
					}
				}
				else {
					print '{"result":"error","message":"This cannot happen"}';
				}
			}
		}
	}
}

mysql_close($connect);
?>