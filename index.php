<?php

	session_start();
	date_default_timezone_set('asia/kolkata');

	require_once('connect.php');
	require_once('functions.php');

	$isLogged = false;

	if(isset($_SESSION['userid'])) {

		$user_id = $_SESSION['userid'];

		$isLogged = true;

		$user = mysql_query("select * from members where id=".$user_id) or die(mysql_error());
		$user = mysql_fetch_array($user);
	}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php if($isLogged) { ?> <link rel="stylesheet" href="css/lstyle.css" /> <?php } else { ?> <link rel="stylesheet" href="css/style.css" /> <?php } ?>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="js/jquery.js"></script>
	<script src="js/flowtype.js"></script>
	<script src="js/spin.min.js"></script>
	<title>Funchat</title>
</head>
<body>

	<?php
	if(!$isLogged) { ?>

		<!-- HOMEPAGE WHEN NOT LOGIN -->
		<div class="home_no_login">

			<div data-role="header" class="header">
				<h1>FunChat</h1>
			</div>

			<div data-role="content" class="home_container_login_box">

				<form class="login form_box" method="post" action="login.php">
					
					<input type="text" name="username" id="username" placeholder="Username" style="padding: 4.565217391304348% 4.347826086956522%;">
					<input type="password" name="password" id="password" placeholder="Password" style="padding: 4.565217391304348% 4.347826086956522%;">
					
					<input class="btn" type="submit" name="submit" value="Login">
				</form>
				<p class='center_text'>
					<b><a href='#' fc-goto='register_page' fc-gofrom='home_no_login'>Don't have account ?</a></b>
				</p>
			</div>
		</div>
		<!-- HOME_NO_LOGIN END -->

		<!-- REGISTER PAGE -->
		<div class="register_page">

			<div class="title">
				<div class="back_button_div">
					<a href="#" fc-goto="home_no_login" fc-gofrom="register_page"><span class="back_laquo">&laquo;</span></a>
				</div>
				<div class="pagetitle"><h1>Register</h1></div>
			</div>
			<div data-role="content" class="home_container register_page">
				<div class="regbox">

					<form method="post" class="form_box register" action="register.php">
						<li class="label">Username</li>
						<li><input type="text" name="username" /></li>
						<li class="label">First Name</li>
						<li><input type="text" name="firstname" /></li>
						<li class="label">Last Name</li>
						<li><input type="text" name="lastname" /></li>
						<li class="label">Email</li>
						<li><input type="text" name="email" /></li>
						<li class="label">Password<br /></li>
						<li><input type="password" name="password" /></li>
						<li class="label">When was you born ?</li>
						<li class="label">
							<select name="date">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
							</select>
						
							<select name="month">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
							</select>
						
							<select name="year">
									<option value="2005">2005</option>
									<option value="2004">2004</option>
									<option value="2003">2003</option>
									<option value="2002">2002</option>
									<option value="2001">2001</option>
									<option value="2000">2000</option>
									<option value="1999">1999</option>
									<option value="1998">1998</option>
									<option value="1997">1997</option>
									<option value="1996">1996</option>
									<option value="1995">1995</option>
									<option value="1994" selected="selected">1994</option>
									<option value="1993">1993</option>
									<option value="1992">1992</option>
									<option value="1991">1991</option>
									<option value="1990">1990</option>
									<option value="1989">1989</option>
									<option value="1988">1988</option>
									<option value="1987">1987</option>
									<option value="1986">1986</option>
									<option value="1985">1985</option>
									<option value="1984">1984</option>
									<option value="1983">1983</option>
									<option value="1982">1982</option>
									<option value="1981">1981</option>
									<option value="1980">1980</option>
									<option value="1979">1979</option>
									<option value="1978">1978</option>
									<option value="1977">1977</option>
									<option value="1976">1976</option>
									<option value="1975">1975</option>
									<option value="1974">1974</option>
									<option value="1973">1973</option>
									<option value="1972">1972</option>
									<option value="1971">1971</option>
									<option value="1970">1970</option>
									<option value="1969">1969</option>
									<option value="1968">1968</option>
									<option value="1967">1967</option>
									<option value="1966">1966</option>
									<option value="1965">1965</option>
									<option value="1964">1964</option>
									<option value="1963">1963</option>
									<option value="1962">1962</option>
									<option value="1961">1961</option>
									<option value="1960">1960</option>
									<option value="1959">1959</option>
									<option value="1958">1958</option>
									<option value="1957">1957</option>
									<option value="1956">1956</option>
									<option value="1955">1955</option>
									<option value="1954">1954</option>
									<option value="1953">1953</option>
									<option value="1952">1952</option>
									<option value="1951">1951</option>
									<option value="1950">1950</option>
							</select>
						</li>
						<input class="reg btn" type="submit" name="submit" value="Register">
					</form>
				</div>
			</div>
		</div>
		<!-- REGISTER PAGE ENDS -->

		<?php
	}
	?>

	<!-- HOME WHEN LOGGED IN -->
	<div class="home_loggedin">

		<div class="title">
			<div class="pagetitle">
				<h1>Menu box</h1>
			</div>
		</div>
		<div class="loggedin_container">

		<div class="erow margin_erow fc-welcome-msg"><?php if($isLogged) { print "Welcome ".$user['firstname'];  } ?></div>

		<div data-role="content" class="home_container menu_page">
			<div class="userdetailsforjson" uid="<?php if($isLogged) { print $user_id; } else { print 'uid'; } ?>" accesstoken="<?php if($isLogged) { print $user['accesstoken']; } else { print 'accesstoken'; } ?>"></div>
			<p class="notice avg_margin success fc-buddy-requests" fc-buddy-requests-no="0"></p>
			<ul class="main_list">
				<a class="pli" href="shout.php" fc-goto="shoutbox_page" fc-gofrom="home_loggedin"><li>Shoutbox</li></a>
				<a class="pli" href="#" fc-goto="message_threads" fc-gofrom="home_loggedin"><li class="msgno_li">Messages <div class="float_right unread_msg_class" code="0">0</div></li></a>
				<a class="pli" href="#" fc-goto="online_buddies" fc-gofrom="home_loggedin"><li class="chatno_li">Chat <div class="float_right online_buddies_class" code="0">0</div></li></a>
				<a class="pli" href="#" fc-goto="searchpage" fc-gofrom="home_loggedin"><li>Search</li></a>
				<a class="pli" href="emoticons.php" fc-goto="emoticons_page" fc-gofrom="home_loggedin"><li>Emoji Codes</li></a>
				<a class="pli logout_exist" href="logout.php"><li>Logout</li></a>
			</ul>
		</div>
		</div>
	</div>
	<!-- HOME WHEN LOGGED IN END -->

	<!-- MESSAGE THREADS -->
	<div class="message_threads">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="message_threads"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1>Messages</h1>
			</div>
		</div>
		<div class="pm pm_page message_threads_container"></div>
	</div>
	<!-- MESSAGE THREADS END -->

	<!-- EMOTICONS PAGE -->
	<div class="emoticons_page">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="emoticons_page"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1>Emoticons</h1>
			</div>
		</div>
		<div class="emoticons_container"></div>
	</div>
	<!-- EMOTICONS PAGE END -->

	<!-- ONLINE BUDDY LIST -->
	<div class="online_buddies">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="online_buddies"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1>Chat</h1>
			</div>
		</div>
		<div data-role="content" class="home_container chat_page">
		<div class="erow margin_erow">These buddies are online</div>
			<div class="big_online_list">
				<ul class="main_list online_buddy_ul">
				</ul>
			</div>
		</div>
	</div>
	<!-- ONLINE BUDDY LIST END -->

	<!-- SEARCH PAGE -->
	<div class="searchpage">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="searchpage"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1>Search</h1>
			</div>
		</div>
		<div data-role="content" class="container search_page">
			<form class="search_form form_box" method="post" action="search.php">
				<input class="search_q_input" type="text" name="q" placeholder="username here">
				<input type="submit" name="submit" class="btn" value="SEARCH" style="padding: 3.91304347826087% 0;">
			</form>
			<div class="sresults_wrapper">
				<div class="sresults">
				</div>
			</div>
		</div>
	</div>
	<!-- SEARCH PAGE END -->

	<!-- PROFILE PAGE -->
	<div class="profilepage">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="notfixed" fc-gofrom="profilepage"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1 class="profile_page_username_h1"></h1>
			</div>
		</div>
		<div data-role="content" class="s_container profilepage_wrapper" style="margin-top:19%;">
			<div class="box_model rrrr-t-f" style="margin-top:25%;">
				<div class="pimg_wrapper profile_page_pimg" style="margin: 2% auto;float:none;width:50%;">
					
				</div>
				<div class="new show_if_forme">
					<li class="pp_bday"></li>
					<li class="pp_lo"></li>
				</div>
				<div class="new show_if_not_forme">
					<a href="" class="pp_bl">Add as buddy</a>
				</div>
			</div>
		</div>
	</div>
	<!-- PROFILE PAGE ENDS -->

	<!-- PM PAGE -->
	<div class="pmpage">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="pmpage"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1 class="pm_page_username_h1">Messages</h1>
			</div>
		</div>
		<div data-role="content" class="container pmpage_wrapper" style="margin-top:19%;">
			<form class="msg_form form_box sendmsg_form" method="post" action="">
				<input class="msg_input" type="text" name="message" placeholder="Type message here">
				<input type="submit" name="send" class="btn" value="SEND" style="padding: 3.91304347826087% 0;">
			</form>
			<div class="pmpage_list_wrapper"></div>
		</div>
	</div>
	<!-- PM PAGE ENDS -->

	<!-- SHOUTBOX PAGE -->
	<div class="shoutbox_page">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="shoutbox_page"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1 class="pm_page_username_h1">Shoutbox</h1>
			</div>
		</div>
		<div data-role="content" class="container shoutbox_page_wrapper" style="margin-top:19%;">
			<form class="msg_form form_box shout_form" method="post" action="shout.php">
				<input class="smsg_input" type="text" name="message" placeholder="Type message here">
				<input type="submit" name="send" class="btn" value="SEND" style="padding: 3.91304347826087% 0;">
			</form>
			<div class="shout_list_wrapper"></div>
		</div>
	</div>
	<!-- SHOUTBOX PAGE ENDS -->
	
	<!-- BUDDY REQUEST PAGE -->
	<div class="buddyrequestpage">

		<div class="title">
			<div class="back_button_div">
				<a href="#" fc-goto="home_loggedin" fc-gofrom="buddyrequestpage"><span class="back_laquo">&laquo;</span></a>
			</div>
			<div class="pagetitle">
				<h1>Buddy Requests</h1>
			</div>
		</div>
		<div data-role="content" class="container buddy_request_page">
			<div class="sresults_wrapper">
				<div class="sresults breqres">
				</div>
			</div>
		</div>
	</div>
	<!-- BUDDY REQUEST PAGE END -->

	<script src="js/manual.js"></script>
	<?php if($isLogged) { ?><div class="for_special_thing" style="display:none;">yes</div><?php } ?>
</body>
</html>