<?php

$buddylist = "CREATE TABLE IF NOT EXISTS buddylist (
  id int(11) NOT NULL AUTO_INCREMENT,
  sender int(11) NOT NULL,
  receiver int(11) NOT NULL,
  accepted int(11) NOT NULL,
  time varchar(255) NOT NULL,
  accept_time varchar(255) NOT NULL,
  PRIMARY KEY (id)
)";

$groups = "CREATE TABLE IF NOT EXISTS groups (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  PRIMARY KEY (id)
)";

$group_admins = "CREATE TABLE IF NOT EXISTS group_admins (
  id int(11) NOT NULL AUTO_INCREMENT,
  member_id int(11) NOT NULL,
  group_id int(11) NOT NULL,
  PRIMARY KEY (id)
)";


$group_members = "CREATE TABLE IF NOT EXISTS group_members (
  id int(11) NOT NULL AUTO_INCREMENT,
  member_id int(11) NOT NULL,
  group_id int(11) NOT NULL,
  PRIMARY KEY (id)
)";


$info = "CREATE TABLE IF NOT EXISTS info (
  sitecounter bigint(255) NOT NULL DEFAULT '1'
)";


$members = "CREATE TABLE IF NOT EXISTS members (
  id int(11) NOT NULL AUTO_INCREMENT,
  firstname varchar(255) NOT NULL,
  lastname varchar(255) NOT NULL,
  fullname varchar(255) NOT NULL,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  vremail varchar(255) NOT NULL,
  isreal varchar(255) NOT NULL,
  rank varchar(255) NOT NULL,
  dob varchar(255) NOT NULL,
  banned varchar(255) NOT NULL,
  picture varchar(255) NOT NULL,
  lastonline varchar(255) NOT NULL,
  validcode varchar(255) NOT NULL,
  accesstoken varchar(255) NOT NULL,
  recent varchar(255) NOT NULL,
  browser varchar(255) NOT NULL,
  scode varchar(255) NOT NULL,
  joined varchar(255) NOT NULL,
  online int(11) NOT NULL,
  PRIMARY KEY (id)
)";


$message_thread = "CREATE TABLE IF NOT EXISTS message_thread (
  id int(11) NOT NULL AUTO_INCREMENT,
  users varchar(255) NOT NULL,
  type int(11) NOT NULL,
  last_message_time varchar(255) NOT NULL,
  PRIMARY KEY (id)
)";


$notifications = "CREATE TABLE IF NOT EXISTS notifications (
  id int(11) NOT NULL AUTO_INCREMENT,
  message varchar(255) NOT NULL,
  checked int(11) NOT NULL DEFAULT '0',
  nfor int(11) NOT NULL,
  PRIMARY KEY (id)
)";

$pm = "CREATE TABLE IF NOT EXISTS pm (
  id int(11) NOT NULL AUTO_INCREMENT,
  sender int(11) NOT NULL,
  receiver int(11) NOT NULL,
  message longtext NOT NULL,
  time varchar(255) NOT NULL,
  delivered int(11) NOT NULL DEFAULT '0',
  viewed int(11) NOT NULL DEFAULT '0',
  viewtime varchar(255) NOT NULL,
  last int(11) NOT NULL,
  bli int(11) NOT NULL,
  tid int(11) NOT NULL,
  PRIMARY KEY (id)
)";


$shouts = "CREATE TABLE IF NOT EXISTS shouts (
  id int(11) NOT NULL AUTO_INCREMENT,
  shouter varchar(255) NOT NULL,
  message longtext NOT NULL,
  time varchar(255) NOT NULL,
  PRIMARY KEY (id)
)";

require_once('connect.php');

mysql_query($buddylist) or die(mysql_error());
mysql_query($groups) or die(mysql_error());
mysql_query($group_admins) or die(mysql_error());
mysql_query($group_members) or die(mysql_error());
mysql_query($info) or die(mysql_error());
mysql_query($members) or die(mysql_error());
mysql_query($message_thread) or die(mysql_error());
mysql_query($notifications) or die(mysql_error());
mysql_query($pm) or die(mysql_error());
mysql_query($shouts) or die(mysql_error());

print "Installed";