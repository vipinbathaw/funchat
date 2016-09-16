<?php
error_reporting(0);
session_start();

include('connect.php');
include('functions.php');

if(isset($_POST['uid']) && isset($_POST['accesstoken'])) {
	//A GENUINE REQUEST.
	$uid = $_POST['uid'];
	$accesstoken = $_POST['accesstoken'];

	function serror() {
		$output = array("result"=>"error","message"=>"Unable to log out");
		print json_encode($output);
	}

	//mysql_query("update members set online='0' where accesstoken='".$accesstoken."'") or die(serror());
	mysql_query("update members set accesstoken='',online=0 where accesstoken='".$accesstoken."'") or die(serror());

	session_destroy();

	$output = array("result"=>"success","message"=>"Logged out successfully");

	print json_encode($output);
}
	
mysql_close($connect);

?>

