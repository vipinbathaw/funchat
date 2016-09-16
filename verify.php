<?php

/*	author: vipin bathaw
	year: 2012
	licence: gpl
	note: you are allowed to modify script, but selling is prohibited.
*/


date_default_timezone_set('asia/kolkata');

require_once('settings.php');

if(isset($_GET['id'])) {
	
	$input = getfilter(trim($_GET['id']));
	
	$query = mysql_query("select * from members where validcode='".$input."'") or die(mysql_error());
	$result = mysql_fetch_array($query);
	
	if($result) {
		
		$userid = $result['id'];
		
		$update = mysql_query("update members set vremail='1' where id='".$userid."'") or die(mysql_error());
		
		print '<p class="notice">your account has been verified successfully.</p>';
	}
	
	else {
		
		print '<b style="color: red;">invalid code</b>';
		
	}
	
}

else {

	header("http/1.0 404 not found");
	
}	

?>