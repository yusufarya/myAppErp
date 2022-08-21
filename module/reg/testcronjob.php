<?php 
// if (!defined('BASEPATH')) exit('No direct script access allowed');
include 'includes/encrypt_decrypt.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;	

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

session_start();
//$host = $_SERVER['SERVER_NAME'];
  	
$hostname2 = 'localhost';
$username2 = 'root';
$password2 = 'Siscom3519';
$database2 = 'erp_admin';

$db2 = mysql_connect($hostname2, $username2, $password2);
mysql_select_db($database2, $db2) or die ("ERROR!");

$createdBy = 'admin';
$createdOn = date('Y-m-d H:i:s');

/* JOBS UNTUK MENGUPDATE USED DAN MENGHAPUS DATABASE YANG SUDAH KADALUARSA */
ini_set('max_execution_time', 300); // max execute 5 menit
// setting database
$host = 'localhost'; //$_SERVER['SERVER_ADDR'];
$hostname2 = $GLOBALS['hostname2'];
$username2 = $GLOBALS['username2'];
$password2 = $GLOBALS['password2'];

// koneksi database 
//$connDeleteDB = mysqli_connect($hostname2,$username2, $password2) or die(mysqli_connect_error());
//$now = date('Y-m-d');
//$datetime = date('Y-m-d H:i:s');

$strInv = "SELECT DISTINCT iv.*, ivd.end_date, c.email AS cust_email FROM `invoice` iv 
			LEFT JOIN `invoice_detail` ivd ON ivd.inv_id = iv.id 
			LEFT JOIN `cust` c ON c.id = iv.cust_id 
			WHERE iv.paid_off = 'N' AND iv.stsrec = 'A' 
			AND DATEDIFF(ivd.begin_date, CURRENT_DATE()) <= 3
			ORDER BY iv.due_date, iv.inv_no";
$qryInv = mysql_query($strInv) or die(mysql_error());
while ($rowInv = mysql_fetch_array($qryInv)) {
	$invoiceID = $rowInv['id'];
	$end_date = $rowInv['end_date'];
	$custEmail = $rowInv['cust_email'];
}

// die();
$inv_id = $invoiceID;
$dateEnd = $end_date;
$host = "http://$_SERVER[SERVER_NAME]/yusuf/Crontab";
//$host = "cronjob.siscom.id"; //$_SERVER['HTTP_HOST'];
// $email = $custEmail;
// $email = 'shan3519@gmail.com';
$email = "yusufaryadilla29@gmail.com";
$subject = 'Reminder SISCOM Online';
// $pesan = file_get_contents("https://$host/Crontab/tagihan_email.php?inv_id=$invoiceID");

$url = $host."/template_reminder.php?email=".$email;
var_dump($url);

function getUrlContent($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    // execute and return string (this should be an empty string '')
    $data = curl_exec($curl);
    curl_close($curl);
    // var_dump($data); die();
    return $data;
}

$html = getUrlContent($url);
$json = json_encode($html);
$pesan = json_decode($json, TRUE);

$username = 'finance@siscomonline.co.id';
$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));
$name = $emailrec['name'];
$password = $emailrec['password'];
$passwordDecrypt = "financeoke515"; // decrypt($ENCRKEY, $password);
// var_dump($passwordDecrypt);
sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name);
// sendEmail('jsonhary@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);
// sendEmail('iwansetiadik@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);

function sendEmail($email, $message, $subject, $username, $password, $name, $inv_id=null, $lampiran=null){
	$mail = new PHPMailer(true);

	try {

		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host 		= 'siscomonline.co.id';
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $username; //'finance@siscomonline.co.id';
		$mail->Password 	= $password; //'financeoke515';
		$mail->SMTPSecure   = 'ssl';
		$mail->Port 		= 465;

		$mail->setFrom($username, $name);
		$mail->addAddress($email);

		if ($lampiran != null) {
			$mail->addAttachment($lampiran);
		}
		
		$mail->Charset = 'utf-8';
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;
		// $mail->AltBody //Body Alternatif untuk nonHTML client

		$mail->send();

		// $mail->send();
		if(!$mail->send()) {
		    echo 'Message could not be sent to' . $email;
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    echo 'Message has been sent';
		}
		
		$_SESSION['success'] = 'Your account has been sent.';
	} catch (Exception $e) {
		$_SESSION['error'] = 'Mailer error:{$mail->ErrorInfo}';
	}  
}

?>