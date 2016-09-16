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


if(isset($_session['user'])) {

	$is_logged = true;
	
	$user3 = $_session['user'];
	$user2 = mysql_query("select * from members where username='".$user3."'") or die(mysql_error());
	$user = mysql_fetch_array($user2);


	if($user['banned'] == '1') {
		$is_banned = true;
	}

	if($user['rank'] == 'owner') {
		$is_admin = true;
	}
}

print '
<html lang="en">
<head>
	<title>'.$site_title.'</title>
	<meta name="description" content="'.$site_desc.'" />
	<meta name="keywords" content="'.$site_keywords.'" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
</head>
<body>
	<div class="header">
		<h1>'.$site_name.'</h1>
	</div>
	<div class="container">';
	
	if($is_banned == true) {
		print'
		<p class="notice">you have beend banned!</p>';
		die();
	}
	
	if($is_logged == false) {
		print'
		<p class="notice">you must be logged in to view!</p>';
		die();
	}
	
	
	print '
</body>
</html>';


mysql_close($connect);
?>

