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
	public $sresults = array();
}

if(isset($_POST['uid']) && isset($_POST['accesstoken'])) {
	//A GENUINE REQUEST.
	$uid = $_POST['uid'];
	$accesstoken = $_POST['accesstoken'];

	//CHK IF USERNAME N ACCESSTOKEN IS RIGHT.
	$qq = mysql_query("Select * from members where id='".$uid."' and accesstoken='".$accesstoken."'") or die(serror(mysql_error()));
	if(mysql_num_rows($qq)>0) {
		//VALID USER.
		//print 'valid user';
		$user = mysql_fetch_array($qq);
		//print $user;

		if($user['banned'] == '1') {
			// USER IS BANNED.
			serror("You have been banned");
			die();
		}
		else {
			//DO WORK AS PER PAGE.
			$k = date("U")-180;

			$active = mysql_query("select * from members where recent>'$k' and online=1") or die(mysql_error());
			$activerss = mysql_num_rows($active);
			if($activerss>0) {
				//print 'more than 1';

				$output = new rawdata();
				$output->result = "success";

				while($actives = mysql_fetch_array($active)) {
					$list_person = $actives['id'];

					if($actives['picture'] < 1) {
						$profimage = $site_link.'/images/default_profile.jpg';
					}
					else {
						$profimage = $actives['picture'];
					}
					
					$tte = mysql_query("select * from buddylist where receiver='".$user['id']."' and sender='".$list_person."' and accepted='1'") or die(mysql_error());
					$ttef = mysql_fetch_array($tte);
					$num = mysql_num_rows($tte);
					if($num == '0') {
						$tte2 = mysql_query("select * from buddylist where receiver='".$list_person."' and sender='".$user['id']."' and accepted='1'") or die(mysql_error());
						$tte2f = mysql_fetch_array($tte2);
						$num1 = mysql_num_rows($tte2);
						if($num1 == '0') {
							
						}
						else {
							$jsonRow = array(
								"uid"		=>	$actives['id'],
								"profimage"	=>	$profimage,
								"fullname"	=>	$actives['fullname']
							);
							array_push($output->sresults, $jsonRow);
							//print '<a class="pli" href="pm.php?read='.$actives['id'].'"><li>'.$actives['fullname'].'</li></a>';
						}
					}
					else {
						$jsonRow = array(
							"uid"		=>	$actives['id'],
							"profimage"	=>	$profimage,
							"fullname"	=>	$actives['fullname']
						);
						array_push($output->sresults, $jsonRow);
						//print '<a class="pli" href="pm.php?read='.$actives['id'].'"><li>'.$actives['fullname'].'</li></a>';
					}
				}

				print json_encode($output);
			}
		}
	}

	function serror($errcode) {
		$output = array("result"=>"error","message"=>"Error : ".$errcode."");
		print json_encode($output);
	}
}


/*
if(isset($_SESSION['user'])) {

	$is_logged = true;
	
	$user3 = $_SESSION['user'];
	$user2 = mysql_query("select * from members where username='".$user3."'") or die(mysql_error());
	$user = mysql_fetch_array($user2);
	
	update($user['id']);


	if($user['banned'] == '1') {
		$is_banned = true;
	}

	if($user['rank'] == 'owner') {
		$is_admin = true;
	}
}

print '
	<div style="visibility: none;" class="sound_class">
		<audio>
			<source src="./audio/new_msg_normal.mp3"></source>
		</audio>
	</div>
	<div class="title">
		<div class="back_button_div">
			<a href="login.php"><img class="back_button" src="./images/back_btn.png"></a>
		</div>
		<div class="pagetitle">
			<h1>chat</h1>
		</div>
	</div>
	<div data-role="content" class="home_container chat_page">';
	
	if($is_banned == true) {
		print'
		<p class="notice error">You have beend banned!</p>';
		die();
	}
	
	if($is_logged == false) {
		print'
		<p class="notice error">You must be logged in to view!</p>';
		die();
	}
	
	
	if($is_logged == true) {
		
		$k = date("U")-180;
		
		print '
		<div class="erow margin_erow">These buddies are online</div>';
		
		$active = mysql_query("select * from members where recent>'$k' and online='1'") or die(mysql_error());
		$activerss = mysql_num_rows($active);
		
		print '
		<div class="big_online_list">
			<ul class="main_list">';
				while($actives = mysql_fetch_array($active)) {
					$list_person = $actives['id'];
					
					
					$tte = mysql_query("select * from buddylist where receiver='".$user['id']."' and sender='".$list_person."' and accepted='1'") or die(mysql_error());
					$ttef = mysql_fetch_array($tte);
					$num = mysql_num_rows($tte);
					if($num == '0') {
						$tte2 = mysql_query("select * from buddylist where receiver='".$list_person."' and sender='".$user['id']."' and accepted='1'") or die(mysql_error());
						$tte2f = mysql_fetch_array($tte2);
						$num1 = mysql_num_rows($tte2);
						if($num1 == '0') {
							
						}
						else {
							print '<a class="pli" href="pm.php?read='.$actives['id'].'"><li>'.$actives['fullname'].'</li></a>';
						}
					}
					else {
						print '<a class="pli" href="pm.php?read='.$actives['id'].'"><li>'.$actives['fullname'].'</li></a>';
					}					
				}
				print '
			</ul>
		</div>';
		
		
		
	}*/
mysql_close($connect);
?>

