<?php
/*
 * @link http://phpform.net/math_captcha.php
 */
include 'includes/koneksi2.php';
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

session_start();
//Sessions in PHP are started by using the
//session_start() function.
//Like the setcookie( ) function,
//the session_start function must come before any HTML,
//including blank lines, on the page.session_start();
//Check if the security code and
//the session value are not blank
//and if the input text matches the stored text
if(isset($_POST['signIn'])){
	$email = $_POST['email'];
	$_email = strtolower($email);
	$password = $_email.$_POST['passwd'];

	$scEmail = mysql_query("SELECT c.id, c.email, c.name, c.password, c.password_app, c.ctype, c.billing_send, 
							c.salesman_id, s.name AS salesman_name   
							FROM cust c LEFT JOIN salesman s ON s.id = c.salesman_id 
							WHERE c.email = '$_email'") or die (mysql_error());
	$row = mysql_fetch_array($scEmail);

	$matchE = mysql_num_rows($scEmail);

	if($matchE == 0){
		
		$_SESSION['error'] = "Incorrect email.";
		echo '<script>
		 		window.location=history.go(-1);
		 	</script>';
			
	} else {
		//if($_POST['captchaAnswer'] == $_SESSION['check']) {
		if($_POST['captchaAnswer'] == $_POST['hasil']) {	
			
			$search = mysql_query("SELECT email, password, valid FROM cust WHERE email = '$_email'") or die (mysql_error());
			$match=mysql_fetch_array($search);
			
			if($match['valid'] == 'N') {
				$_SESSION['error'] = "Lakukan konfirmasi email terlebih dahulu.";
				header('location:login.php?id=log');
			} else if(password_verify($password, $match['password']) == FALSE){
				$_SESSION['error'] = "Incorrect password.";
				/*
				echo '<script>
						window.location=history.go(-1);
						</script>';
				*/
				header('location:login.php?id=log');
			}else {
				$_SESSION['custID'] = $row['id'];
				$_SESSION['custName'] = $row['name'];
				$_SESSION['custPasswordApp'] = $row['password_app'];
				$_SESSION['custEmail'] = $row['email'];
				$_SESSION['custLevel'] = $row['ctype'];
				$_SESSION['custBilling'] = $row['billing_send'];
				$_SESSION['custSalesID'] = $row['salesman_id'];
				$_SESSION['custSalesName'] = $row['salesman_name'];
				$IDcust = $row['id'];

				$custSet = mysql_query("SELECT cust_id, company_id, dbname FROM cust_order WHERE cust_id = '$IDcust'") or die (mysql_error());
				$rowCs = mysql_fetch_array($custSet);
				$_SESSION['cekCpn'] = $rowCs['company_id'];
				$_SESSION['dbname'] = $rowCs['dbname'];
				// echo $_SESSION['cekCpn'];

				/*Cek Database yang sudah berakhir masa aktif*/
				$custID = $row['id'];
		    	$co = mysql_query("SELECT * FROM cust_order WHERE cust_id = '$custID' AND used != 'D'") or die(mysql_error());	
		    	while ($rowj = mysql_fetch_array($co)) {
					$dbname = $rowj['dbname'];
		    		$end_date = $rowj['end_date'];
		    		$now = strtotime(date('Y-m-d'));
		    		if($now > strtotime($end_date)){
		    			$sqlUpdate = mysql_query("UPDATE cust_order SET used = 'D' WHERE dbname = '$dbname'") or die(mysql_error());
		    		}
					
					$iv = mysql_query("SELECT DISTINCT ivd.inv_id, iv.inv_no, iv.inv_date, ivd.begin_date, 
										ivd.end_date, co.dbname, iv.paid_off, iv.stsrec   
										FROM cust_order co 
										LEFT JOIN invoice_detail ivd ON ivd.order_id = co.order_id 
										LEFT JOIN invoice iv ON iv.id = ivd.inv_id 
										WHERE co.dbname = '$dbname' AND ivd.begin_date <= CURRENT_DATE() 
										AND iv.paid_off = 'N' AND iv.stsrec <> 'D' 
										ORDER BY co.dbname, ivd.begin_date, iv.id ") or die(mysql_error());	
		    		while ($rowi = mysql_fetch_array($iv)) {
						$begin_inv = $rowi['begin_date'];
						$invID1 = $rowi['inv_id'];
						if($now >= strtotime($begin_inv)){
							$sqlUpdate2 = mysql_query("UPDATE invoice SET stsrec = 'D' WHERE id = '$invID1'") or die(mysql_error());
						}
					}
					
		    	}
				
				/*echo '<script>alert("'.$_SESSION["custID"].'");</script>';*/
				header('location:pages/account.php');

			}
		} else {
			$_SESSION['error'] = 'Salah Kode Captcha, silakan dicoba kembali.';
			/*
			echo '<script>
					window.location=history.go(-1);
					</script>';
			*/
			header('location:login.php?id=log');
		}
	}
} 
elseif (isset($_POST['forgotpass'])) {
	$email = $_POST['emailForgot'];
	$_email = strtolower($email);

	$sql = mysql_query("SELECT * FROM `cust` WHERE email = '$_email'") or die(mysql_error());
	$cekEmail = mysql_num_rows($sql);
	if ($cekEmail >=1) {
		$passAcak = substr(str_shuffle(MD5(microtime())), 0,6);
		$password = password_hash($_email.$passAcak,PASSWORD_DEFAULT);
	
		$subject = 'Reset Password SISCOM Online';
		$host = $_SERVER['HTTP_HOST'];
		//$pesan = file_get_contents("https://$host/siserp/module/reg/template_reset_password.php?email=$email&password=$passAcak");
		// $pesan = file_get_contents("$abs/reg/template_reset_password.php?email=$email&password=$passAcak");
		// $pesan = url_get_content("$abs/reg/template_reset_password.php?email=$email&password=$passAcak");
		
		$pesan =  
		"
		<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css'>
			<style type='text/css'>
				body{
					width: 100%;
					height: 100%;
					margin: 0px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
				}
				.container{
                    margin-top: 30px !important;
					padding: 20px 40px;
					background-color: white;
					border: 1px solid #dcdcdc;
                    border-radius: 8px;
                    box-shadow: 10px 20px 10px #dcdcdc;
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
					font-size: 16px;
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
				a.btn{
					text-decoration: none !important;
				}
				.btn{
					margin-top: 90px;
					text-align: center;
					padding: 8px 10px;
					height: 40px;
					background-color: #438EB9;
					color:white !important;
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
				.text1{
					text-align: center;
				}
				.text2{
					text-align: center;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    font-size: 14px;
                    padding-top: 10px !important;
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
		<body style='background-color: #dcdcdc;'>
			<div>
			<br>
			</div>
			<div class='container' style='width: 55%; height: 50%; margin-top: 30px; margin-bottom: 30px;'>
				<div class='header'>
					<div class='row'>
						<div class='icon'>
							<img src='".$abs."/reg/img/LOGO-SISCOM1.png' width='200px'>
						</div>
						<div class='official'>	
						</div>
					</div>
					<div class='row'>
						<div class='pull-right'>
							<p>Account&emsp;|&emsp;<strong>Reset Password</strong></p>
						</div>
					</div>
				</div>
				<div class='body'>
					<div class='pull-left'>
						<h3>Instruksi Reset Password</h3>
						<p>Halo <strong>".$email.",</strong></p>
						<div class='text1'>
							<p style='font-size: 13px;'>Kami telah menerima permintaan untuk melakukan reset password terhadap akun anda. Untuk mereset password Anda, silakan klik tombol Reset dan masuk ke website dengan menggunakan password yang telah diberikan.</p>
							<br>
							<a style='font-weight: 500;' href='".$abs."/reg/confirm.php?email=".$email."&hash=resetpassword&password=".$passAcak."' class='btn'>Reset Password</a>
							<br><br>
							<p style='font-size: 13px;'>Untuk Masuk kedalam website, silahkan gunakan password : <strong>".$passAcak."</strong>.</p>
						</div>
					</div>
					<div class='row'>
						<div class='text2'>
							<strong>PT. Shan Informasi Sistem</strong><br>
							City Resort Rukan Malibu Blok J/75-77 <br>
							Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
							Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/''>SISCOM Online</a>
						</div>
					</div>
				</div>
                <div class='sosmed'>
                    <a href='https://www.facebook.com/siscomsoftware/'>
                        <i class='bi bi-facebook'></i> Facebook
                    </a>
                    <a href='https://www.instagram.com/siscomsoftware/'>
                         <i class='bi bi-instagram'></i> Instagram
                    </a>
                    <a href='https://api.whatsapp.com/send?phone=62811803519&text=Hai, Saya ingin konsultasi'>
                        <i class='bi bi-whatsapp'></i> WhatsApp
                    </a>
                </div>
			</div>
			<br>

		</body>
		</html>
		";

		//sendEmail($email, $pesan, $subject);
		$username = 'no-reply@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));	
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'siscomnoplayoke515'; //decrypt($ENCRKEY, $password);
		sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name);
		$_SESSION['success'] = 'Silakan Cek Email Untuk Instruksi Lebih Lanjut';	
	}
	else{
		
		$_SESSION['error'] = 'Email yang Anda masukkan salah! Cek kembali email Anda';
	}
	
	$host = $_SERVER['HTTP_HOST'];
	echo "<script type='text/javascript'>
			  window.location='$abs/reg/login.php?id=log';
	    </script>";
	//window.location='$abs/reg/login.php?id=log';
	///window.location='https://$host/siserp/module/reg/login.php?id=log';
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