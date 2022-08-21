<?php
  date_default_timezone_set('Asia/Jakarta');
	
  $hostname = '10.148.0.8';
  $username = 'pro';
  $password = 'Siscom3519';
  $database = 'erp_admin';

  $db = mysql_connect($hostname, $username, $password);
  mysql_select_db($database, $db) or die ("ERROR!");
	
	$abs_fin = "https://finance.siscom.id";
  $doc_fin = substr($_SERVER['DOCUMENT_ROOT'],0,strlen($_SERVER['DOCUMENT_ROOT'])-12).'/2F201908001';
?>
