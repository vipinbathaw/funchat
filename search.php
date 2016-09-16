<?php

date_default_timezone_set('asia/kolkata');

require_once('connect.php');
require_once('functions.php');

$is_logged = false;
$is_banned = false;
$is_admin = false;
$is_more = false;

function serror($errcode) {
	$output = array("result"=>"error","message"=>"Error : ".$errcode."");
	print json_encode($output);
}

class rawdata {
	public $result;
	public $sresults = array();
	public $moreLink;
}


if(isset($_GET['uid']) && isset($_GET['accesstoken'])) {
	//A GENUINE REQUEST.
	$uid = $_GET['uid'];
	$accesstoken = $_GET['accesstoken'];

	//print 'got : '.$uid.$accesstoken;

	//CHK IF USERNAME N ACCESSTOKEN IS RIGHT.
	$qq = mysql_query("Select * from members where id='".$uid."' and accesstoken='".$accesstoken."'") or die(serror("unable to get user details from server db"));
	if(mysql_num_rows($qq)>0) {
		//VALID USER.
		$user = mysql_fetch_array($qq);

		//print 'valid user:'.$user;

		if($user['banned'] == '1') {
			// USER IS BANNED.
			serror("You have been banned");
			die();
		}
		else {
			//DO WORK AS PER PAGE.
			if(isset($_POST['q'])) {
				//GOT A SEARCH QUERY.
				$query = getfilter(trim($_POST['q']));

				//print 'got query: '.$query;
		
				if(isset($_POST['more'])) {
					$is_more = true;
					$more = getfilter(trim($_POST['more']));
					$start = $more*5;
				}
				else {
					$more = 0;
					$is_more1 = 'first';
					$start = 0;
				}
				if(strlen($query) < 1) {
					//EMPTY REQUEST
					serror("Do you think we allow empty usernames? -_-");
					die();
				}

				$output = new rawdata();
				$output->result = "success";
				$output->moreLink = $more+1;

				$sqlq = mysql_query("select * from members where username like '%$query%' limit $start,5") or die(serror("unable to get details from server db"));
				while($result = mysql_fetch_array($sqlq)) {
					if($result['picture'] < 1) {
						$profimage = $site_link.'/images/default_profile.jpg';
					}
					else {
						$profimage = $result['picture'];
					}
					$jsonRow = array(
						"uid"		=> $result['id'],
						"username"	=> $result['username'],
						"profimage"	=> $profimage,
					);
					array_push($output->sresults, $jsonRow);
				}
				print json_encode($output);
			}
			else {
				//NO SEARCH QUERY.
				serror("No search query");
				die();
			}
		}
	}
}
/*

if(isset($_SESSION['user'])) {

	$is_logged = true;
	
	$user3 = $_SESSION['user'];
	$user2 = mysql_query("select * from members where username='".$user3."'") or die(mysql_error());
	$user = mysql_fetch_array($user2);


	
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
			<h1>search</h1>
		</div>
	</div>
	<div data-role="content" class="container search_page">';
	
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
	
	if(isset($_GET['q'])) {
	
		$query = getfilter(trim($_GET['q']));
		
		if(isset($_GET['more'])) {
			
			$is_more = true;
			$more = getfilter(trim($_GET['more']));
			$start = $more*5;
		}
		else {
			
			$is_more1 = 'first';
			$start = 0;
		
		}
		
		if(strlen($query) < 1) {
			print '<p class="notice">No result found</p>';
			display_form();
		}
		else {
		
			print '
			<p class="simple_padding"><b>Showing result for "'.$query.'"</b></p>
			<div class="new">';
			
			$sqlq = mysql_query("select * from members where fullname like '%$query%' limit $start,5") or die(mysql_error());
			while($result = mysql_fetch_array($sqlq)) {
				print '<li class="small_profile_box">';
				
				if($result['picture'] < 1) {
					$profimage = $site_link.'/images/default_profile_pic.gif';
				}
				else {
					$profimage = $result['picture'];
				}
				
				print '
				<div class="float_left"><a href="profile.php?id='.$result['id'].'">
					<img class="spbfl" src="'.$profimage.'"></img>
				</a></div>
				<div class="middle_">
					<a class="big" href="profile.php?id='.$result['id'].'&ref=search.php?q='.$query.'&submit=submit">'.$result['fullname'].'</a><br>
					<a href="addfriend.php?id='.$result['id'].'">Make friend</a>
				</div></li>';
				
			}
			
			if($is_more == true) {
				print '<br><div style="text-align:center;"><a href="search.php?q='.$query.'&more='.($more+1).'">Show more</a></div>';
			}
			else {
				print '<br><div style="text-align:center;"><a href="search.php?q='.$query.'&more=2">Show more</a></div>';
			}
			
			print '		
			</div>';
		}
	}
	else {
		display_form();
		
	}*/
mysql_close($connect);

?>

