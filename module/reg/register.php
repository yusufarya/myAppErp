<?php
include 'includes/koneksi2.php';
//include 'includes/encrypt_decrypt.php';

$key = '5U7V9w19a21Tya15';
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
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

session_start();

$username = 'no-reply@siscomonline.co.id';
$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));
$nameSysUser = $emailrec['name'];
$passwordSysUser = $emailrec['password'];
$passwordDecrypt = 'siscomnoplayoke515'; //decrypt($ENCRKEY, $passwordSysUser);

$email = strtolower($_POST['emailReg']);
$name = $_POST['nameReg'];
$telp = $_POST['hpReg'];
$password = password_hash($email.$_POST['passwdReg'],PASSWORD_DEFAULT);
$password_app = password_hash($email.$_POST['passwdReg'],PASSWORD_DEFAULT);
//$repassword = $_POST['passwd2Reg'];
$gender = $_POST['gender'];
$salesid = $_POST['salesid'];
if($salesid == ''){
	$salesid = 'S0001';
}
$otp = $_POST['otp'];

$_SESSION['email'] = $email;
$_SESSION['namaReg'] = $name;

$hash = md5(rand(0,1000));
$now = date('Y-m-d H:i:s');

$insert = mysql_query("INSERT INTO cust(id, email, name, password, password_app, gender, phone, hash, otp, billing_send, valid, ctype, salesman_id, created_on) values(NULL, '$email', '$name', '$password', '$password_app', '$gender', '$telp', '$hash', '$otp', 'Y', 'N', 'A', '$salesid', '$now')");

mysql_query("UPDATE cust_otp SET `email` = '$email', `name` = '$name', `stsrec` = 'R' WHERE `phone` = '$telp' AND `otp` = '$otp'");

if($insert){

	$mail = new PHPMailer(true);

	try {
		
		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host 		= 'siscomonline.co.id';
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $username; //''no-reply@siscomonline.co.id'; 
		$mail->Password 	= $passwordDecrypt; //'siscomnoplayoke515';
		$mail->SMTPSecure   = 'ssl';
		$mail->Port 		= 465; //465
		
		$mail->setFrom($username, $nameSysUser);
		$mail->addAddress($email);

		//$img =  "http://".$_SERVER['SERVER_NAME']."/module/reg/img/LOGO-SISCOM.png";
		$host = $_SERVER['HTTP_HOST'];
		//$pesan = file_get_contents("https://$host/module/reg/pages/template_confirm.php?email=$email&hash=$hash");
		//$pesan = file_get_contents("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash");
		//$pesan = url_get_content("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash");

		$content = "http://$_SERVER[SERVER_NAME]/yusuf/siserp/module";

		$url = $content."/reg/pages/template_confirm.php?email=".$email."&hash=".$hash;
		// var_dump($url);

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

		// $pesan =
		// "
		// <!DOCTYPE html>
		// <html>
		//     <head>
		//         <title></title>
		//         <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css'>
		//         <style type='text/css'>
		//             body{
		//                 width: 100%;
		//                 height: 100%;
		//                 margin: 0px;
		//                 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		//             }
		//             .container{
		//                 margin-top: 30px !important;
		//                 padding: 23px 40px;
		//                 background-color: white;
		//                 border: 1px solid #dcdcdc;
		//                 border-radius: 8px;
		//                 box-shadow: 1px 2px 7px #abb7b8;
		//                 margin:0px auto;
		//             }
		//             .row{
		//                 display: block;
		//                 clear: both;
		//             }
		//             .header{
		//                 border-bottom: 1px solid grey;
		//                 padding-bottom: 50px;
		//             }
		//             .pull-right{
		//                 float: right;
		//                 display: inline-block;
		//             }
		//             .pull-left{
		//                 float: left;
		//                 display: inline-block;
		//             }
		//             .header p{
		//                 font-size: 16px;
		//                 font-weight: 600;
		//             }
		//             p{
		//                 text-align: justify !important;
		//                 color: grey;
		//                 font-size: 14px;
		//             }
		//             strong{
		//                 color:black;
		//             }

		//             .text1 {
		//                 align-items: center;
		//             }
		//             .text1 a {
		//                 text-decoration: none;
		//             }
		//             .text1 .btn-confirm {
		//                 display: block;
		//                 margin: 1px auto 0px;
		//                 text-align: center;
		//                 padding: 8px 10px;
		//                 width: 18%;
		//                 height: 20px;
		//                 font-weight: 400;
		//                 font-size: 14px;
		//                 background-color: #4898c7;
		//                 color: white !important;
		//                 border-radius: 5px;
		//                 transition: .2s;
		//             }
		//             .text1 .btn-confirm:hover {
		//                 background-color: #3781ac;
		//                 color: #dcdcdc;
		//             }
		//             .text2 {
		//                 text-align: center;
		//                 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		//                 font-size: 14px;
		//                 padding-top: 10px !important;
		//             }
		//             .sosmed {
		//                 margin-top: 15px !important;
		//                 margin: 0 auto;
		//                 text-align: center;
		//             }
		//             .sosmed a {
		//                 text-decoration: none;
		//                 color: #3c678f;
		//                 margin: 5px;
		//             }
		//         </style>
		//     </head>
		//     <body style='background-color: #dcdcdc;'>
		//         <div>
		//         	<br>
		//         </div>
		//         <div class='container' style='width: 45%; height: 50%; margin-top: 30px; margin-bottom: 30px;'>
		//             <div class='header'>
		//                 <div class='row'>
		//                     <div class='icon'>
		//                         <img src='".$abs."/reg/img/LOGO-SISCOM1.png' width='200px'>
		//                     </div>
		//                     <div class='official'>	
		//                     </div>	
		//                 </div>
		//                 <div class='row'>
		//                     <div class='pull-right'>
		//                         <p>Account&emsp;|&emsp;<strong>Konfirmasi Email</strong></p>
		//                     </div>
		//                 </div>
		//             </div>
		//             <div class='body'>
		//                 <div class='pull-left'>
		//                     <h3>Konfirmasi email anda!</h3>
		//                     <p>Halo <strong>".$name.",</strong></p>
		//                     <div class='text1'>
		//                         <p style='font-size: 13.5px;'>Kami telah mendaftarkan email baru untuk layanan SISCOM Online. Demi keamanan mohon untuk mengkonfirmasi email anda dengan mengklik link dibawah ini</p>
		//                         <br><br>
		//                         <a href='".$abs."/module/reg/confirm.php?email=".$email."&hash=".$hash."' class='btn-confirm'>Konfirmasi Email</a>
		//                         <br><br>
		//                     </div>
		//                 </div>
		//                 <div class='row'>
		//                     <div class='text2'>
		//                         <strong>PT. Shan Informasi Sistem</strong><br>
		//                         City Resort Rukan Malibu Blok J/75-77 <br>
		//                         Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
		//                         Telp : +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
		//                     </div>
		//                 </div>
		//             </div>
		//             <div class='sosmed'>
		//                 <a href='https://www.facebook.com/siscomsoftware/' style='font-size: 14px;'>
		//                     <i class='bi bi-facebook'></i> Facebook
		//                 </a>
		//                 <a href='https://www.instagram.com/siscomsoftware/' style='font-size: 14px;'>
		//                     <i class='bi bi-instagram'></i> Instagram
		//                 </a>
		//                 <a href='https://api.whatsapp.com/send?phone=62811803519&text=Hai, Saya ingin konsultasi' style='font-size: 14px;'>
		//                     <i class='bi bi-whatsapp'></i> Whastapp
		//                 </a>
		//             </div>
		//         </div>
		//         <br>
		        
		//     </body>
		// </html>
		// ";

		$mail->isHTML(true);
		$mail->Subject 	= 'Konfirmasi Email SISCOM Online';
		$mail->Body 	= $pesan;
		// $mail->AltBody //Body Alternatif untuk nonHTML client
		
		$mail->send();

		$_SESSION['success'] = 'Akun Anda sudah dibuat, silakan konfirmasi email Anda.';
	} catch (Exception $e) {
		$_SESSION['error'] = 'Mailer error: '.$e->getMessage(); //'Mailer error:{$mail->ErrorInfo}';
	}

	//kirim ke sales
	if($salesid!=''){
		$sales 		= mysql_query("SELECT c.id, c.email AS email_cust, c.name, c.salesman_id, 
						s.email AS email_sales, c.phone, s.name AS sales_name  
						FROM `cust` c
						LEFT JOIN salesman s ON s.id = c.salesman_id
						WHERE c.salesman_id = '$salesid' 
						AND c.email = '$email'") or die (mysql_error());
		$dataS 		= mysql_fetch_array($sales);

		$email_cust = $dataS['email_cust'];

		$email_sales= ($dataS!= NULL && $dataS['email_sales']!='') ? $dataS['email_sales'] : 'office@siscomonline.co.id';

		$mail->SMTPDebug 	= 0;
		$mail->isSMTP();
		$mail->Host 		= 'siscomonline.co.id';
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $username; //'no-reply@siscomonline.co.id';
		$mail->Password 	= $passwordDecrypt; //'siscomnoplayoke515';
		$mail->SMTPSecure   = 'ssl';
		$mail->Port 		= 465; //465
		
		$mail->setFrom($username, $nameSysUser);
		//$mail->setFrom('no-reply@siscomonline.co.id', 'SISCOM Online');
		$mail->addAddress($email_sales);

		$host = $_SERVER['HTTP_HOST'];
		//$pesan1 		  = file_get_contents("http://$host/siserp/module/reg/pages/template_sales_custreg.php?email=$email_sales&emailcust=$email_cust");
		//$pesan1 		  = file_get_contents("$abs/reg/pages/template_sales_custreg.php?email=$email_sales&emailcust=$email_cust");
		//$pesan1 		  = url_get_content("$abs/reg/pages/template_sales_custreg.php?email=$email_sales&emailcust=$email_cust");
		$content = "http://$_SERVER[SERVER_NAME]/yusuf/siserp/module";

		$url = $content."/reg/pages/template_sales_custreg.php?email=".$email_sales."&emailcust=".$email_cust;

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
		$pesan1 = json_decode($json,TRUE);

		// $pesan1 =
		// "
		// <!DOCTYPE html>
		// <html>
		//     <head>
		//         <title></title>
		//         <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css'>
		//         <style type='text/css'>
		//             body{
		//                 width: 100%;
		//                 height: 100%;
		//                 margin: 0px;
		//                 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		//             }
		//             .container{
		//                 margin-top: 30px !important;
		//                 padding: 20px 40px;
		//                 background-color: white;
		//                 border: 1px solid #dcdcdc;
		//                 border-radius: 8px;
		//                 box-shadow: 1px 2px 7px #abb7b8;
		//                 margin:0px auto;
		//             }
		//             .row{
		//                 display: block;
		//                 clear: both;
		//             }
		//             .header{
		//                 border-bottom: 1px solid grey;
		//                 padding-bottom: 50px;
		//             }
		//             .pull-right{
		//                 float: right;
		//                 display: inline-block;
		//             }
		//             .pull-left{
		//                 float: left;
		//                 display: inline-block;
		//             }
		//             .header p{
		//                 font-size: 16px;
		//                 font-weight: 600;
		//             }
		//             p{
		//                 text-align: justify !important;
		//                 color: grey;
		//                 font-size: 14px;
		//             }
		//             strong{
		//                 color:black;
		//             }
		//             a.btn{
		//                 text-decoration: none !important;
		//             }
		//             .btn{
		//                 margin-top: 90px;
		//                 text-align: center;
		//                 padding: 8px 10px;
		//                 height: 40px;
		//                 background-color: #438EB9;
		//                 color:white !important;
		//                 border-radius: 5px;
		//             }
		//             .btn:hover{
		//                 background-color: #53b2e8;
		//             }

		//             #border{
		//                 position: absolute;
		//                 top: 165px;
		//                 right: 12%;
		//             }
		//             .border-black{
		//                 width: 8px;
		//                 height: 200px;
		//                 background-color: #003b6a;
		//             }
		//             .border-blue{
		//                 width: 8px;
		//                 height: 100px;
		//                 background-color: #75ddf4;
		//             }
		//             .border-blue:after{
		//                 content: '';
		//                 width: 0;
		//                 height: 0;
		//                 position: absolute;
		//                 bottom: 50px;
		//                 left: -10px;
		//                 border-width: 10px;
		//                 border-style: solid;
		//                 border-color: transparent white transparent transparent;
		//             }
		//             .border-green{
		//                 width: 8px;
		//                 height: 60px;
		//                 background-color: #96d666;
		//             }
		//             .border-green:after{
		//                 content: '';
		//                 width: 0;
		//                 height: 0;
		//                 position: absolute;
		//                 bottom: 0px;
		//                 left: -10px;
		//                 border-width: 10px;
		//                 border-style: solid;
		//                 border-color: transparent transparent white transparent;
		//             }
		//             .text2{
		//                 text-align: center;
		//                 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		//                 font-size: 14px;
		//                 padding-top: 10px !important;
		//             }
		//             .sosmed {
		//                 margin-top: 15px !important;
		//                 margin: 0 auto;
		//                 text-align: center;
		//             }
		//             .sosmed a {
		//                 text-decoration: none;
		//                 color: #3c678f;
		//                 margin: 5px;
		//             }
		//         </style>
		//     </head>
		//     <body style='background-color: #dcdcdc;'>
		//         <div>
		//         <br>
		//         </div>
		//         <div class='container' style='width: 55%; height: 50%; margin-top: 30px; margin-bottom: 30px;'>
		//             <div class='header'>
		//                 <div class='row'>
		//                     <div class='icon'>
		//                         <img src='".$abs."/reg/img/LOGO-SISCOM1.png' width='200px'>
		//                     </div>
		//                     <div class='official'>	
		//                     </div>	
		//                 </div>
		//                 <div class='row'>
		//                     <div class='pull-right'>
		//                         <p>Account&emsp;|&emsp;<strong>Customer Registration</strong></p>
		//                     </div>
		//                 </div>
		//             </div>
		//             <div class='body'>
		//                 <div class='pull-left'>
		//                     <h3>Pemberitahuan Registrasi Email Customer</h3>
		//                     <p>Halo <strong>".$datas['sales_name'].",</strong></p>
		//                     <div class='text1'>
		//                         <table style='font-size: 13px; color: #878787; margin-left: -3px;'>
		//                             <tr>
		//                                 <td>Customer Anda</td>
		//                                 <td> &emsp;: </td>
		//                                 <td><strong>".strtoupper($datas['name'])."</strong></td>
		//                             </tr>
		//                             <tr>
		//                                 <td>Email</td>
		//                                 <td> &emsp;: </td>
		//                                 <td><strong>".strtolower($datas['email'])."</strong></td>
		//                             </tr>
		//                             <tr>
		//                                 <td>No. Telp</td>
		//                                 <td> &emsp;: </td>
		//                                 <td><strong>".strtoupper($datas['phone'])."</strong></td>
		//                             </tr>
		//                         </table>
		//                         <p style='font-size: 13px;'>Telah melakukan registrasi email layanan SISCOM Online. Terimakasih.</p>
		//                         <br><br>
		//                     </div>
		//                 </div>
		//                 <div class='row'>
		//                     <div class='text2'>
		//                         <strong>PT. Shan Informasi Sistem</strong><br>
		//                         City Resort Rukan Malibu Blok J/75-77 <br>
		//                         Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
		//                         Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
		//                     </div>
		//                 </div>
		//             </div>
		//             <div class='sosmed'>
		//                 <a href='https://www.facebook.com/siscomsoftware/' style='font-size: 14px;'>
		//                     <i class='bi bi-facebook'></i> Facebook
		//                 </a>
		//                 <a href='https://www.instagram.com/siscomsoftware/' style='font-size: 14px;'>
		//                     <i class='bi bi-instagram'></i> Instagram
		//                 </a>
		//                 <a href='https://api.whatsapp.com/send?phone=62811803519&text=Hai, Saya ingin konsultasi' style='font-size: 14px;'>
		//                     <i class='bi bi-whatsapp'></i> Whastapp
		//                 </a>
		//             </div>
		//         </div>
		//         <br>
		        
		//     </body>
		// </html>
		// ";

		$mail->isHTML(true);
		$mail->Subject 	= 'Customer Registration';
		$mail->Body 	= $pesan1;
		
		//$mail->send();

		$username = 'no-reply@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));	
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'siscomnoplayoke515'; //decrypt($ENCRKEY, $password);
		$subject = 'Customer Registration';
		sendEmail('jsonhary@gmail.com', $pesan1, $subject, $username, $passwordDecrypt, $name);
		sendEmail('iwansetiadik@gmail.com', $pesan1, $subject, $username, $passwordDecrypt, $name);

		//}
		
		header('location:login.php?id=log');
	}else {
		$_SESSION['error'] = 'Input Database Error.';
	}

	function sendEmail($email, $message, $subject, $username, $password, $name, $inv_id=null, $lampiran=null){
		$mail = new PHPMailer(true);		

		try {
					
			$mail->SMTPDebug    = 0;
			$mail->isSMTP();
			$mail->Host 		= 'siscomonline.co.id'; //'mail.siscomonline.co.id';
			$mail->SMTPAuth 	= true;
			$mail->Username 	= $username; //'no-reply@siscomonline.co.id';
			$mail->Password 	= $password; //'siscomnoplayoke515';
			$mail->SMTPSecure   = 'ssl'; //'tls'
			$mail->Port 		= 465; //587
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