<?php 
	// die();
	include 'includes/koneksi2.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'vendor/phpmailer/src/Exception.php';
	require 'vendor/phpmailer/src/PHPMailer.php';
	require 'vendor/phpmailer/src/SMTP.php';

	$abs = "http://$_SERVER[SERVER_NAME]/yusuf/siserp/module";

	// $email = 'yudhoaan@gmail.com';
	// $email = 'shan3519@gmail.com';
	$email = 'yusufaryadilla29@gmail.com';
	$hash = '091d584fced301b442654dd8c23b3fc9';
	// $salesid = 'S0001';
	$sales 		  = mysql_query("SELECT co.*, c.email, c.name
					FROM `cust_order` co
					JOIN cust c ON c.id = co.cust_id
					WHERE c.email = '$email'") or die (mysql_error());
	$dataS 		  = mysql_fetch_array($sales);

	// $email_cust  = $dataS['email_cust'];
	// $email_cust = 'yusufaryadilla29@gmail.com';
	$email_cust = 'rothman.pleats@gmail.com';
	// $email_sales = ($dataS!= NULL && $dataS['email_sales']!='') ? $dataS['email_sales'] : 'office@siscomonline.co.id';
	// $url = $abs."/reg/pages/template_confirm.php?email=".$email."&hash=".$hash;
	$url = $abs."/reg/pages/template_deldb.php?email=".$email_cust;
	// $url = 'https://localhost/yusuf/siserp/module/reg/pages/template_confirm.php?email=yudhoaan@gmail.com&hash=091d584fced301b442654dd8c23b3fc9';
	// $url = 'http://www.example.com/empty_file.txt';
	// var_dump($url); 

 	function getUrlContent($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    // execute and return string (this should be an empty string '')
	    $data = curl_exec($curl);
	    curl_close($curl);
	    var_dump($data); die();
	    return $data;
    }

	$html = getUrlContent($url);
	// var_dump($html);

	// $xml = simplexml_load_string($html);
	$json = json_encode($html);
	$pesan = json_decode($json,TRUE);

	$username = 'no-reply@siscomonline.co.id';
	$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));
	// var_dump($emailrec);
	$name = $emailrec['name'];
	// $name = 'No-reply';
	$password = $emailrec['password'];
	// $password = 'gF74KLZ/agw1t4tug4bSOukbmKz2tKjuAW3XK8kWyBeBNRHBD7IG6CImnffbuWZS';
	$passwordDecrypt = 'siscomnoplayoke515'; //decrypt($ENCRKEY, $password);
	$subject = 'Customer Registration';
	// sendEmail1($email, $pesan, $subject, $username, $passwordDecrypt, $name);
	sendEmail1('yusufaryadilla29@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);

	function sendEmail1($email, $message, $subject, $username, $password, $name, $lampiran=null){
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

			// $mail->send();
			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    echo 'Message has been sent';
			}
			$_SESSION['success'] = 'Akun Anda sudah dibuat, silakan konfirmasi email Anda.';
		  } catch (Exception $e) {
			  $_SESSION['error'] = 'Mailer error: '.$e->getMessage(); //'Mailer error:{$mail->ErrorInfo}';
		  }  
	}

?>

