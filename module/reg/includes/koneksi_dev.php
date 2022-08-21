<?php
  date_default_timezone_set('Asia/Jakarta');
	
  $hostname = 'localhost';
  $username = 'root';
  $password = 'Siscom3519';
  $database = 'erp_admin';

  $db = mysql_connect($hostname, $username, $password);
  mysql_select_db($database, $db) or die ("ERROR!");
	
	$abs = "https://$_SERVER[SERVER_NAME]/module";
  $abs2 = "https://$_SERVER[SERVER_NAME]";
  $abs3 = "https://$_SERVER[SERVER_NAME]/module:".$_SERVER['REQUEST_URI'];
?>
