<?php

/*	author: vipin bathaw
	year: 2012
	licence: gpl
	note: you are allowed to modify script, but selling is prohibited.
*/

die();

session_start();
date_default_timezone_set('asia/kolkata');

require_once('connect.php');
require_once('functions.php');

$only_shouts = false;
$show_footer = false;

if(isset($_get['onlyshouts'])) {
	$only_shouts = true;
}


if(!$only_shouts) {
	print '
	
	<div data-role="content" class="sboxcontainer shoutbox_page">';
}


		$is_banned = false;
		$is_admin = false;

		if(!isset($_session['user'])) {
			?><script>gohome(); alert('you are not logged in. please login to view this page');</script><?php 
			
			die();
		}

		$show_footer = true;
		
		$user2 = $_session['user'];
		$user3 = mysql_query("select * from members where username='".$user2."'") or die(mysql_error());
		$user = mysql_fetch_array($user3);
		
		update($user['id']);

		if($user['banned'] == '1') {
			?><script>gohome(); alert('you have been banned!');</script><?php 
			
			die();
		}

		if(!$only_shouts) {
			print'
			<div class="title">
				<div class="back_button_div">
					<a href="login.php"><img class="back_button" src="./images/back_btn.png"></a>
				</div>
				<div class="pagetitle">
					<h1 class="pagetitle_h1">shoutbox</h1>
				</div>
			</div>
			<div class="popup">
				<div class="close"><img src="./images/closebox.png"></div>
				<div class="smiley-container"></div>
			</div>
			<form class="shout_form form_box_model" method="post" action="shoutbox.php">
				<input class="shout_input" type="text" name="message" placeholder="your shout here..">
				<input class="btn" type="submit" name="submit" value="shout">
			</form>
			<div class="sa_wrapper">
			';
		}


		if(isset($_post['submit'])) {

			$message = $_post['message'];
			
			shout($user['id'],$message);
			
			update($user['id']);
			
			echo '
			<div id="shout_container">';
				getshouts();
				echo '
			</div>';
			
		}
		else {
			echo '
			<div id="shout_container">';
				getshouts();
				echo '
			</div>';
		
		}
		
		$k = date("u")-300;
		
		$active = mysql_query("select * from members where recent>'$k' and online='1'") or die(mysql_error());
		$activerss = mysql_num_rows($active);
		
		print '
		<div class="active_users">
			<span>active users ( '.$activerss.' )</span><br /><b>-';
			
			while($actives = mysql_fetch_array($active)) {
				print'
				<a href="profile.php?id='.$actives['id'].'">'.$actives['name'].'</a>-';
			}
			print '</b>
		</div>';
		if(!$only_shouts) { 
		print '
		</div>
	</div>'; }
	
	print '
</body>
</html>';

mysql_close($connect);

?>