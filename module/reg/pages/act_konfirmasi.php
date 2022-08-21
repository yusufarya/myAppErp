<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	require_once '../includes/koneksi_finance.php';
	include '../includes/encrypt_decrypt.php';

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

	require '../vendor/phpmailer/src/Exception.php';
	require '../vendor/phpmailer/src/PHPMailer.php';
	require '../vendor/phpmailer/src/SMTP.php';

	$host = $_SERVER['HTTP_HOST'];
	
	if (isset($_POST)) {
		$nama 			   = $_POST['nama'];
		$dbname 		   = $_POST['namaDb'];
		$no_invoice 	   = $_POST['inv_no'];
		$paid_date 		   = date('Y-m-d',strtotime($_POST['tgl_transfer']));
		$keterangan 	   = $_POST['keterangan'];
		$metode_pembayaran = $_POST['metode_pembayaran'];
		$gambar     	   = $_FILES['bukti']['tmp_name'];
		$receipt_bank	   = $_POST['bank'];
		// $createBy			= $_SESSION['custID'];
		// $createOn			= date('Y-m-d');
		$modifiedBy 	   = $_SESSION['custID'];
		$modifiedOn		   = date('Y-m-d');
		$type 			   = explode('.', $_FILES['bukti']['name']);
		$type 			   = $type[count($type)-1];
		$typeGambar 		= ['jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF'];
		$namagambar        = '';
		
		if(in_array($type, $typeGambar) || $gambar ==''){

			if ($gambar !='') {
				$acak1=rand(000000,999999);
			    $acak2=rand(000000,999999);
			    $acak3=rand(000000,999999);
			    $acak=$acak1."_".$acak2."_".$acak3;
	
		        $namagambar = "siscom-".$acak.".".$type;
		        $upload = $doc_fin."/images/payment/".$namagambar;
		 
			  	move_uploaded_file($gambar,$upload);	        	
			}

			// $sql = mysql_query("insert into invoice_confirm (inv_no, paid_name, paid_date,payment,remark, attach_file, created_by, created_on, modified_by, modified_on) values('$no_invoice', '$nama','$paid_date', '$metode_pembayaran','$keterangan', '$namagambar', '$createBy', '$createOn', '$modifiedBy', '$modifiedOn')") or die(mysql_error());

			foreach ($no_invoice as $key => $value) {
				$inv_no = $value;
				// echo($inv_no);
				
				$sql = mysql_query("UPDATE invoice SET paid_name='$nama', paid_date='$paid_date', payment_id='$metode_pembayaran', receipt_bank='$receipt_bank', paid_remark='$keterangan', attach_file='$namagambar', modified_by='$modifiedBy', modified_on='$modifiedOn', paid_off='C', stsrec='A' WHERE inv_no='$inv_no'") or die(mysql_error());
			}

			// kirim email konfirm pembayaran ke finance
			// $email_tagihan	= "tagihan@siscomonline.co.id";
			// $subject 		= "Konfirmasi Pembayaran";
			// $pesan			= "<h3 style='text-align:center;'>Konfirmasi Pembayaran</h3><br>
			// 				<p>Halo, saya ingin mengkonfirmasi pembayaran tagihan:<p>
			// 				<p>Nama&emsp; : $nama</p>
			// 				<p>No. Tagihan&emsp; : $no_invoice</p>
			// 				<p>Tgl. Transfer&emsp; : $paid_date</p>
			// 				<p>Metode Pembayaran&emsp; : $metode_pembayaran</p>
			// 				<p>Keterangan&emsp; : $keterangan</p>
			// 				";
			//
			// sendEmail($email_tagihan, $pesan, $subject);
			// $username = 'finance@siscomonline.co.id';
			// $password = decrypt($ENCRKEY, $username);
			// $emailrec = mysql_fetch_array(mysql_query("SELECT name FROM sysemail WHERE email = '$username'"));
			// $name = $emailrec['name'];
			// sendEmail($email_tagihan, $pesan, $subject, $username, $password, $name);

	        // kirim email ke customer
	  		// $email 			= $_POST['email'];
			//
			// $subject1 		= 'Customer Payment Confirmation';
			// $pesan1 		  	= url_get_content("$abs/reg/pages/confirm_tagihan.php?email=$email&inv_no=$no_invoice");
			// sendEmail($email, $pesan1, $subject1);
			// $username = 'finance@siscomonline.co.id';
			// $password = decrypt($ENCRKEY, $username);
			// $emailrec = mysql_fetch_array(mysql_query("SELECT name FROM sysemail WHERE email = '$username'"));
			// $name = $emailrec['name'];
			// sendEmail($email, $pesan1, $subject1, $username, $password, $name);
		
			echo "<script type='text/javascript'>
	          alert('Data successfully added');
	          window.location='info_tagihan.php?dbname=$dbname';
	        </script>";
        }
        else{
        	echo "<script type='text/javascript'>
	          alert('Gagal upload!. Format gambar yang didukung: `.jpg`,  `.jpeg`,  `.png`,  `.gif`');
	          history.go(-1);
	        </script>";
        	
        }
	}

	function sendEmail($email, $message, $subject, $username, $password, $name, $inv_id=null, $lampiran=null){
		$mail = new PHPMailer(true);		

		try {
					
			$mail->SMTPDebug = 0;
			//$mail->isSMTP();
			$mail->Host 		= 'siscomonline.co.id';
			$mail->SMTPAuth 	= true;
			$mail->Username 	= $username; //'finance@siscomonline.co.id';
			$mail->Password 	= $password; //'financeoke515';
			$mail->SMTPSecure   = 'ssl';
			$mail->Port 		= 465;

			$mail->setFrom($username, $name);
			//$mail->addAddress($email);
			if (is_array($email)) {
	            foreach ($email as $key => $value) {
	                $mail->addAddress($value);
	            }
	        }
	        else{
	            $mail->addAddress($email);    
	        }
			
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