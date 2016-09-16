<?php

/*	Author: Vipin Bathaw
	Email: gamesforvipin@gmail.com
	Year: 2013
	If you have any problem you can contact me.
*/

include ('settings.php');

$connect = mysql_connect($db_host,$db_username,$db_password) or die ('Error in database settings.');
if(!mysql_select_db($db_database,$connect))
{
	die('Wrong database setings, kindly check your database name');
}
?>