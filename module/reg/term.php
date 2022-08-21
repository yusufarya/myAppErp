<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Terms & Conditions</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
		
	</head>
	<?php 
		include 'includes/header.php'; 
		include 'includes/koneksi2.php';

		/*
		if(isset($_POST['register'])){
			$email = $_POST['emailReg'];
			$name = $_POST['nameReg'];
			$telp = $_POST['hpReg'];
			$password = $_POST['passwdReg'];
			$repassword = $_POST['passwd2Reg'];
			$gender = $_POST['gender'];
			$salesid = $_POST['salesid'];

			$_SESSION['email'] = $email;
			$_SESSION['nameReg'] = $name;
			
			$qEmail = mysql_query("SELECT email FROM $database2.cust WHERE email = '$email'") or die (mysql_error());
			$row = mysql_fetch_array($qEmail);
			$cek = mysql_num_rows($qEmail);
			
			//$qPhone = mysql_query("SELECT phone FROM $database2.cust WHERE phone = '$telp'") or die (mysql_error());
			//$rowP = mysql_fetch_array($qPhone);
			//$cekP = mysql_num_rows($qPhone);
			
			if($_POST['captchaAnswerReg'] != $_POST['hasilReg']) {
			 	$_SESSION['errorReg'] = 'The CAPTCHA was incorrect, please try again.';		
				echo '<script>
		 				window.location=history.go(-1);
		 			</script>';
			}
			else if($cek != 0){
				$_SESSION['errorReg'] = 'Email already exists. Please login or enter a different email';
				echo '<script>
				 		window.location=history.go(-1);
				 	</script>';
			}
			//else if($cekP != 0){
			//	$_SESSION['errorReg'] = 'Phone already exists. Please login or enter a different phone';
			//	echo '<script>
			//	 		window.location=history.go(-1);
			//	 	</script>';
			//}
		} 
		*/

		$email = $_POST['emailReg'];
		$name = $_POST['nameReg'];
		$hp = $_POST['hpReg'];
		$password = $_POST['passwdReg'];
		$repassword = $_POST['passwd2Reg'];
		$gender = $_POST['gender'];
		$salesid = $_POST['salesid'];
		$otp = $_POST['otp'];
	?>
	
	<body class="login-layout login-page" style="margin-top: 6px">
	<!--
		<div align="center">
			<img class="pos_img" src="../../siscomweb/reg/img/SISCOM clouds.png"></div>
        <p class="judul1" align="center" style="padding: 0; color: #294d76; font-family: Arial">AKSES dari Manapun, BISNIS Jadi MUDAH</p>
	-->
    <div class="container" style="display: flex; align-items: center; justify-content: center;">
		
        	<div class="col-md-12" style="display: flex; align-self: center; margin-left: 16%;">
						
                <div class="position-relative">
                    <div id="login-box" class="login-box visible widget-box no-border" style="width: 100%">
                    
                        <div class="widget-body">
                            <div class="widget-main">
    
                                <h4 class="header blue lighter bigger" align="center">
                                    <i class="ace-icon fa fa-coffee green"></i>
                                    Terms and Conditions
                                </h4>

                                <form style="height: 350px">

                                  	<!-- /.isi-item -->
                                    <div class="bxsliderText scroll">
									<?php
										//$query2 = mysql_query("SELECT * FROM $database.t_konten_baris where menu_id='12' ORDER BY nourut ASC");
										$query2 = mysql_query("SELECT * FROM $database2.term WHERE form = 'R' AND status = 'P' AND stsrec = 'A' ORDER BY id ASC");
										while ($row = mysql_fetch_array($query2)) {
											//$isi = $row['description']; //$row['keterangan
											$isi = preg_replace('/[^(\20-\x7F)]*/','',$row['description']);
									?>
					 					<li class="isi-item" style="list-style: none"><?=$isi;?></li>
									<?php
										}
                                        ?>
                                    </div>
                              	</form>
    
                            </div><!-- /.widget-main -->
                            
                            <form method="post" action="register.php">
                            <input type="hidden" id="emailReg" name="emailReg" value="<?=$email?>">
                            <input type="hidden" id="nameReg" name="nameReg" value="<?=$name?>">
                            <input type="hidden" id="hpReg" name="hpReg" value="<?=$hp?>">
                            <input type="hidden" id="passwdReg" name="passwdReg" value="<?=$password?>">
                            <input type="hidden" id="gender" name="gender" value="<?=$gender?>">
                            <input type="hidden" id="salesid" name="salesid" value="<?=$salesid?>">
                            <input type="hidden" id="otp" name="otp" value="<?=$otp?>">
    
                            <div class="toolbar clearfix">
                                <div>
                                	<label>
                                    <input type="checkbox" name="ceklis" id="ceklis" style="margin-left: 10px" value="setuju" />
                                    <span style="color: black; padding: 0">&nbsp;&nbsp;I have read and agree</span></label>
                                </div>
    
                                <div>
                                    <!-- <a href="" style="color: white" id="agree" name="agree">
                                        I Agree
                                        <i class="ace-icon fa fa-arrow-right" style="color: white"></i>
                                    </a> -->
                                    <input type="submit" style="margin-right: 15px" name="agree" class="btn btn-danger btn-sm btn-sm" id="agree" value="Register" disabled="disabled">
                                </div>
                            </div>
                        	</form>
                        </div><!-- /.widget-body -->
                    </div><!-- /.login-box -->
                
                </div><!-- /.position-relative -->
					
			</div><!-- /.col -->
		<div class="col-md-3"></div>	
	</div><!-- container -->
    
    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="assets/js/jquery-2.1.4.min.js"></script>

    <script type='text/javascript'>
    	$('#ceklis').click(function() {
    		if ($(this).is(':checked')) {
    			$('#agree').removeAttr('disabled'); //enable input
    		} else{
    			$('#agree').attr('disabled', true); //disable input
    		}    		
    	});
    </script>
	
    </body>
</html>