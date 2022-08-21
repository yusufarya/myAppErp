<?php 
	session_start();
	$host=$_SERVER['SERVER_NAME'];
	include 'includes/style.php';
	require_once '../includes/koneksi2.php'

	/* Jobs untuk menghapus database */
	ini_set('max_execution_time', 300); // max execute 5 menit
	//buat database;
	$host = $_SERVER['SERVER_ADDR'];
	$hostname2 = $GLOBALS['hostname2'];
	$username2 = $GLOBALS['username2'];
	$password2 = $GLOBALS['password2'];

	/* koneksi untuk hapus database*/
	$connDeleteDB = mysqli_connect($hostname2,$username2, $password2) or die(mysqli_connect_error());
	$now = date('Y-m-d');

	$sql1 = mysql_query("SELECT * FROM `cust_order` WHERE expired_date >= '$now' and used='D'");
	while ($row = mysql_fetch_array($sql1)) {
		$dbname = $row['dbname'];

		$sql2 = mysql_query("SELECT DISTINCT TABLE_SCHEMA FROM information_schema.tables WHERE table_schema LIKE '%$dbname%'") or die(mysql_error());
		while ($row2=mysql_fetch_array($sql2)) {
			$dbName = $row2['TABLE_SCHEMA'];

			// hapus database
			mysqli_query($connDeleteDB,"DROP DATABASE $dbName") or die(mysqli_error($connDeleteDB));

		}

	}
	/*Akhir jobs untuk menghapus database*/


	

?>