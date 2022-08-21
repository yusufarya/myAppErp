<?php
  date_default_timezone_set("Asia/Jakarta");
  	
	$hostname2 = 'localhost';
  $username2 = 'pro';
  $password2 = 'Siscom3519';
  $database2 = 'erp_admin';

  $db2 = mysql_connect($hostname2, $username2, $password2);
  mysql_select_db($database2, $db2) or die ("ERROR!");
	
	$abs = "https://$_SERVER[SERVER_NAME]/module";
  $abs2 = "https://$_SERVER[SERVER_NAME]";
  $abs3 = "https://$_SERVER[SERVER_NAME]/module:".$_SERVER['REQUEST_URI'];
?>