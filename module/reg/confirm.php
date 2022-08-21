<?php 
session_start();
require_once 'includes/koneksi2.php';
include 'includes/encrypt_decrypt.php';

/*$key = '5U7V9w19a21Tya15';
$GLOBALS['ENCRKEY'] = $key;

function encrypt($key, $payload)
{
	$IV_SIZE = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$iv = mcrypt_create_iv($IV_SIZE, MCRYPT_DEV_URANDOM);
	$crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $payload, MCRYPT_MODE_CBC, $iv);
	$combo = $iv . $crypt;
	$garble = base64_encode($iv . $crypt);
	return $garble;
}

function decrypt($key, $garble)
{
	$IV_SIZE = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$combo = base64_decode($garble);
	$iv = substr($combo, 0, $IV_SIZE);
	$crypt = substr($combo, $IV_SIZE, strlen($combo));
	$payload = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $crypt, MCRYPT_MODE_CBC, $iv);
	return $payload;
}*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

if (isset($_GET['hash']) && $_GET['hash'] != 'resetpassword') {
	
	$email = $_GET['email'];
	$hash = $_GET['hash'];

	$sql = mysql_query("SELECT * FROM `cust` WHERE email='$email' AND hash='$hash'") or die(mysql_error());
	$data = mysql_fetch_array($sql);
	if($data != null){
		$host = $_SERVER['SERVER_NAME'];
		mysql_query("UPDATE cust SET valid='Y' WHERE email='$email'") or die(mysql_error());

		//kirim email ke sales
		$salesman_id  = $data['salesman_id'];
		$sales 		  = mysql_query("SELECT c.id, c.email AS email_cust, c.name, c.salesman_id, 
										s.email AS email_sales, c.phone, s.name AS sales_name  
										FROM `cust` c
										LEFT JOIN salesman s ON s.id = c.salesman_id
										WHERE c.salesman_id = '$salesman_id'") or die (mysql_error());
		$dataS 		  = mysql_fetch_array($sales);

		$email_cust   = $email;
		$email_sales  = ($dataS!= NULL && $dataS['email_sales']!='') ? $dataS['email_sales'] : 'office@siscomonline.co.id';

		$host 		  = $_SERVER['HTTP_HOST'];
		$emailOwner   = $email_sales;
		$subject 	  = 'Customer Confirmation';
		//$pesan 		  = file_get_contents("https://$host/siserp/module/reg/pages/template_confirm_sales.php?email=$email_sales&emailcust=$email_cust");
		//$pesan 		  = file_get_contents("$abs/reg/pages/template_confirm_sales.php?email=$email_sales&emailcust=$email_cust");
		//$pesan 		  = url_get_content("$abs/reg/pages/template_confirm_sales.php?email=$email_sales&emailcust=$email_cust");
		
		$pesan =
		"
		<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<style type='text/css'>
				body{
					width: 100%;
					height: 100%;
					margin: 0px;
					background-color: #dcdcdc;
				}
				.container{
					padding: 30px;
					width: 70%;
					height: 100%;
					margin:0px auto;
				}
				.row{
					display: block;
					clear: both;
				}
				.header{
					border-bottom: 1px solid grey;
					padding-bottom: 50px;
				}
				.pull-right{
					float: right;
					display: inline-block;
				}
				.pull-left{
					float: left;
					display: inline-block;
				}
				.header p{
					font-size: 14.5px;
					font-weight: 600;
				}
				p{
					text-align: justify !important;
					color: grey;
					font-size: 14px;
				}
				strong{
					color:black;
				}
				.text{
					margin-right: 30px;
				}
				a.btn{
					text-decoration: none !important;
				}
				.btn{
					margin-top:300px;
					text-align: center;
					padding: 10px;
					height: 40px;
					background-color: #438EB9;
					color:white;
					border-radius: 5px;
				}
				.btn:hover{
					background-color: #53b2e8;
				}
				#border{
					position: absolute;
					top: 165px;
					right: 12%;
				}
				.border-black{
					width: 8px;
					height: 200px;
					background-color: #003b6a;
				}
				.border-blue{
					width: 8px;
					height: 100px;
					background-color: #75ddf4;
				}
				.border-blue:after{
					content: '';
					width: 0;
					height: 0;
					position: absolute;
					bottom: 50px;
					left: -10px;
					border-width: 10px;
					border-style: solid;
					border-color: transparent white transparent transparent;
				}
				.border-green{
					width: 8px;
					height: 60px;
					background-color: #96d666;
				}
				.border-green:after{
					content: '';
					width: 0;
					height: 0;
					position: absolute;
					bottom: 0px;
					left: -10px;
					border-width: 10px;
					border-style: solid;
					border-color: transparent transparent white transparent;
				}
				.text{
					text-align: center;
				}
				.sosmed {
	                margin-top: 15px !important;
	                margin: 0 auto;
	                text-align: center;
	            }
	            .sosmed a {
	                text-decoration: none;
	                color: #3c678f;
	                margin: 5px;
	            }
			</style>
		</head>
		<body style='background-color: #d2dbdd;'>
			<div class='container' style='padding: 23px 45px; background-color: white; border: 1px solid #dcdcdc; border-radius: 8px; box-shadow: 1px 2px 7px #abb7b8; margin: 0px auto;'>
				<div class='header'>
					<div class='row'>
						<div class='icon'>
							<img src='".$abs."/reg/img/LOGO-SISCOM.png' width='200px'>
						</div>
						<div class='official'>
						</div>	
					</div>
					<div class='row'>
						<div class='pull-right'>
							<p>Account&emsp;|&emsp;<strong>Konfirmasi Email Customer</strong></p>
						</div>
					</div>
				</div>
				<div class='body'>
					<div class='pull-left'>
						<h3>Pemberitahuan Konfirmasi Email Customer</h3>
						<p>Halo <strong>".$dataS['sales_name'].",</strong></p>
						<div class='text'>
		                	<p>Customer Anda : <strong>".strtoupper($data['name'])."</strong></p>
		                	<p>Email &emsp;&emsp;&emsp;&emsp;: <strong>".strtolower($data['email'])."</strong></p>
		                	<p>No. Telp&emsp;&emsp;&emsp;: <strong>".strtoupper($data['phone'])."</strong></p>
							<p>Telah melakukan konfirmasi email layanan SISCOM Online.  Terimakasih.</p>
							<br><br>
						</div>
					</div>
					<div class='row'>
						<div class='text'>
							<br><br><br>
							<strong>PT. Shan Informasi Sistem</strong><br>
							City Resort Rukan Malibu Blok J/75-77 <br>
							Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
							Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/''>SISCOM Online</a>
						</div>
					</div>
				</div>
				<div class='sosmed'>
	                <a href='https://www.facebook.com/siscomsoftware/' style='font-size: 14px;'>
	                    <i class='bi bi-facebook'></i> Facebook
	                </a>
	                <a href='https://www.instagram.com/siscomsoftware/' style='font-size: 14px;'>
	                    <i class='bi bi-instagram'></i> Instagram
	                </a>
	                <a href='https://api.whatsapp.com/send?phone=62811803519&text=Hai, Saya ingin konsultasi' style='font-size: 14px;'>
	                    <i class='bi bi-whatsapp'></i> Whastapp
	                </a>
	            </div>
			</div>
			
		</body>
		</html>
		";

		//sendEmail($emailOwner, $pesan, $subject);
		$username = 'no-reply@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'siscomnoplayoke515'; //decrypt($ENCRKEY, $password);
		sendEmail($emailOwner, $pesan, $subject, $username, $passwordDecrypt, $name);
		// sendEmail('yusufaryadilla29@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);
		

		// EMAIL WELCOME TO SISCOM ONLINE
		$content = "http://$_SERVER[SERVER_NAME]/yusuf/siserp/module";

		$url = $content."/reg/pages/template_notice.php?email=".$email;

	 	function getUrlContent($url) {
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_HEADER, false);
		    // execute and return string (this should be an empty string '')
		    $data = curl_exec($curl);
		    curl_close($curl);
		    // var_dump($data);
		    return $data;
	    }

		$html = getUrlContent($url);
		// $xml = simplexml_load_string($html);
		$json = json_encode($html);
		$pesan = json_decode($json, TRUE);
		$subject = "Welcome SISCOM Online";
		$username = 'no-reply@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = "siscomnoplayoke515"; // decrypt($ENCRKEY, $password);
		// var_dump($passwordDecrypt);
		// sendEmail('yusufaryadilla29@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);
		sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name);
		sendEmail('jsonhary@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);
		sendEmail('iwansetiadik@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);

		//header("location:https://$host/siserp/module/reg/login.php?id=log");
		header("location: $abs/reg/login.php?id=log");

	} else {
		echo "<h1 style='background-color: #F0E68C; width: 400px; border-radius: 8px; padding: 0 15px;'>Akun Anda tidak terdaftar</h1>";
	}

}
else{
	$host     = $_SERVER['HTTP_HOST'];
	$email    = $_GET['email'];
	$passAcak = $_GET['password'];
	$password = password_hash($email.$passAcak,PASSWORD_DEFAULT);

	$sql = mysql_query("UPDATE `cust` SET password='$password', password_app='$password' WHERE email='$email'") or die(mysql_error());
	if ($sql) {
		$sql = mysql_query("SELECT co.dbname FROM `cust` c JOIN cust_order co ON c.id=co.cust_id WHERE c.email='$email'") or die(mysql_error());
		while ($data = mysql_fetch_array($sql)) {
			$dbname = $data['dbname'];
			//masukkan username,email,pass owner kedalam tb sysuser
			$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());

			mysqli_query($koneksi4, "UPDATE SYSUSER SET PASSWORD='$password' WHERE EMAIL='$email'") or die(mysqli_error($koneksi4));		
		}
		//header("location:https://$host/siserp/module/reg/login.php?id=log");
		header("location:<?=$abs?>/reg/login.php?id=log");
	}
}

function sendEmail($email, $message, $subject, $username, $password, $name, $inv_id=null, $lampiran=null){
	$mail = new PHPMailer(true);		

	try {
				
		$mail->SMTPDebug = 0;
		//$mail->isSMTP();
		$mail->Host 		= 'siscomonline.co.id';
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $username; //'no-reply@siscomonline.co.id';
		$mail->Password 	= $password; //'siscomnoplayoke515';
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
		
		$_SESSION['success'] = 'Akun Anda sudah dibuat, silakan konfirmasi email Anda.';
	  } catch (Exception $e) {
		  $_SESSION['error'] = 'Mailer error: '.$e->getMessage(); //'Mailer error:{$mail->ErrorInfo}';
		  //$_SESSION['error'] = $e;
	  }  
}

function url_get_content($url) {
	if (!function_exists('curl_init')) {
		die('CURL is not installed! :(');
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
?>