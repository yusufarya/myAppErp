<?php
  date_default_timezone_set('Asia/Jakarta');
	
  $hostname = 'localhost';
  $username = 'root';
  $password = 'Siscom3519';
  $database = 'erp_admin';

  $db = mysql_connect($hostname, $username, $password);
  mysql_select_db($database, $db) or die ("ERROR!");
	
	$abs_fin = "https://$_SERVER[SERVER_NAME]/sisfin";
  $doc_fin = $_SERVER['DOCUMENT_ROOT'].'/sisfin';
?>
