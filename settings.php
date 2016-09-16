<?php

/*	Author: Vipin Bathaw
	Email: gamesforvipin@gmail.com
	Year: 2013
*/

/*BASIC PAGE DETAILS
	/*Name of site*/
	$site_name = 'FunChat';

	/*Link of site ( without trailing slash )*/
	$site_link = 'http://localhost/work/funchat-final-github';
	
	/*Title for site*/
	$site_title = 'FunChat';

	/*Description of site*/
	$site_desc = 'Funchat is a chatting website with lots of gifs.';

	/*Keywords for site*/
	$site_keywords = 'chat, fun';
	
	/*Admin Email ( Contact Us Feedback will be sent to this email )*/
	$admin_email = 'gamesforvipin@gmail.com';
	
	$default_pic = '';

	/*Set it true if you want only email verified users*/
	$only_email_verified = false;
	

/*MYSQL CONNECTION DETAILS*/
	/*Host of Database ( Usually its localhost )*/
	$db_host = 'localhost';

	/*Username of Host*/
	$db_username = 'root';

	/*Password for User of Host*/
	$db_password = '';

	/*Database Name*/
	$db_database = 'funchat_final';
	
/*ALL THE FUNCTIONS HERE*/

function getfilter($text)
{
	$danger = "/[<\}\\\'\{\`\^\;\"\>]/i";
	$text = preg_replace($danger,"",$text);
	$text = strip_tags($text);
	$text = mysql_real_escape_string($text);
	return $text;
}

function commentsfilter($text)
{
	$text = htmlspecialchars($text);
	$text = strip_tags($text);
	$text = mysql_real_escape_string($text);
	return $text;
}

?>