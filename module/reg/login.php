<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>SISCOM ONLINE</title>

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
		require_once 'includes/header.php';
		require_once 'includes/koneksi2.php';
		
		//session_start();
		$_SESSION['b1']     =rand(1,10);
		$_SESSION['b2']     =rand(10,15);
		$_SESSION['hasil']  =$_SESSION['b1']+$_SESSION['b2'];
		
		$_SESSION['b3']     =rand(1,10);
		$_SESSION['b4']     =rand(10,15);
		$_SESSION['hasil2'] =$_SESSION['b3']+$_SESSION['b4'];
		
		$act = (isset($_GET['id'])) ? $_GET['id'] : '';

		if($act == 'log') {
			$act_reg = '';
			$act_log = 'visible';
		} else if($act == '' or $act == 'reg') {
			$act_reg = 'visible';
			$act_log = '';
		} 
		$agn = (isset($_GET['agn'])) ? $_GET['agn'] : '';
		
		$qSal = mysql_query("SELECT name FROM salesman WHERE id = '$agn' AND stsrec = 'A'") or die (mysql_error());
		$rSal = mysql_fetch_array($qSal);
		$agn_name = $rSal['name'];
		if($agn_name == '') {
			$agn_name = 'Nama agen tidak ada';	
		}
	?>
    <?php
		$videoLink = 'https://www.youtube.com/watch?v=xVQOnAWjXvo?autoplay=1&controls=0&loop=1&playlist=111;showinfo=0';
		$videoLink = 'media/PT SHAN INFORMASI SISTEM (SISCOM).mp4';
	?>
	<body class="login-layout login-page">
            <!--<p class="judul1" align="center" style="padding: 0; color: #294d76; font-family: Arial">AKSES dari Manapun, BISNIS Jadi MUDAH</p>-->
            <div class="container">                
                <div align="center" style="margin-top: 20px;">
                    <a href="https://localhost/siscomweb"><img class="pos_img" src="img/LOGO-SISCOM.png" style="width: 220px; height: auto"></a>
                    <p class="judul1" align="center" style="padding: 0; color: #fff; font-family: Arial; font-size: 25px; font-style: italic;">#PastiLebihMudah</p>
                </div>
                <!-- bagian kiri -->
                <!--<div class="col-md-6" align="right">--
                       
                    <!--<iframe style="position: absolute; top: 0; left: 0;" src="<?=$videoLink?>" width="100%" height="350px" frameborder="0"></iframe>-->
                    <!--<video poster="" autoplay loop muted controls width="auto" height="350px">
                        <source src="<?=$videoLink?>" type="video/mp4">
                    </video>   
                </div>-->

                <!-- bagian kanan -->
                <!-- <div class="main-container"> -->
                	
                    <div class="col-md-12">
                        <div class="main-content">
                            <div class="row">
                                <div class="">
                                    <div class="login-container">
                                        <div class="center">

                                            <!--<div class="space-6"></div>-->
                
                                            <div class="position-relative">
                                                <div id="login-box" class="login-box <?=$act_log?> widget-box no-border">
                                                    <?php
                                                    if(isset($_SESSION['error'])){
                                                        echo "
                                                            <div class='callout callout-danger text-center'>
                                                            <p>".$_SESSION['error']."</p> 
                                                            </div>";
                                                        unset($_SESSION['error']);
                                                    }   
                                                    if(isset($_SESSION['success'])){
                                                        echo "
                                                            <div class='callout callout-success text-center'>
                                                            <p>".$_SESSION['success']."</p> 
                                                            </div>";
                                                        unset($_SESSION['success']);
                                                    }
                                                    ?>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                
                                                            <!-- <h4 class="header blue lighter bigger">
                                                                <i class="ace-icon fa fa-coffee green"></i>
                                                                Please Enter Your Information
                                                            </h4> -->
    
    
    														<!--<div class="space-6"></div>-->
                                                           <!-- login form -->

                                                            <form method="post" action="cc.php">
                                                                <fieldset>
                                                                    <h3 class="form-title">Login</h3>
                                                                    <label class="block clearfix">
                                                                        <span class="block input-icon input-icon-left form-input">
                                                                            <input type="email" class="form-control" id="email" name="email" style="border-radius: 10px!important; padding-left: 30px!important;" placeholder="Email" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789@._', this)" autofocus autocomplete="off" required />
                                                                            <!-- <i class="ace-icon fa fa-user"></i> -->
                                                                            <img src="assets/images/iconsax/sms.svg" class="ace-icon isax filter-gray" id="sms">
                                                                        </span>
                                                                    </label>
                
                                                                    <label class="block clearfix">
                                                                        <span class="block input-icon input-icon-left form-input">
                                                                            <input type="password" class="form-control" id="passwd" name="passwd" style="border-radius: 10px!important; padding-left: 30px!important;" placeholder="Password" autocomplete="off" minlength="6" required />
                                                                            <!-- <i class="ace-icon fa fa-lock"></i> -->
                                                                            <img src="assets/images/iconsax/lock.svg" class="ace-icon isax filter-gray" id="lock">

                                                                            <img src="assets/images/iconsax/eye.svg" class="ace-icon isax-eye filter-gray" id="eye">
                                                                            <input type="hidden" name="eye1" id="eye1" value="1">
                                                                        </span>
                                                                        <div class="toolbar">
                                                                            <span class="label-forgot"><a href="#" data-target="#forgot-box">Lupa Password ?</a></span>
                                                                        </div>
                                                                    </label>
                                                                    
                                                                    <!--<label class="block clearfix">
                                                                        <label class="width-45 pull-left" style="padding:0px 0px 0px 0px"><img src="captcha.php" id="captImg"><font style="font-family:Tahoma, Geneva, sans-serif; font-size:12px" align="left"></label>
                                                                        <label class="width-45 pull-right">
                                                                            <span class="block input-icon input-icon-right">
                                                                            <input type="text" class="form-control" id="captchaAnswer" name="captchaAnswer" placeholder="Captcha Code" autocomplete="off" required /><i class="ace-icon fa fa-refresh"></i>
                                                                            </span>
                                                                        </label>                                                    	
                                                                    </label>-->
                													
                                                                    <label class="block clearfix">
                                                                        <label class="width-45 pull-left" style="padding:0px 0px 0px 0px; font-family:Tahoma, Geneva, sans-serif; font-size:18px"><?=$_SESSION['b1']." + ".$_SESSION['b2']." = ?"?></label>
                                                                        <label class="width-45 pull-right">
                                                                            <span class="block input-icon input-icon-right">
                                                                            <input type="text" class="form-control" id="captchaAnswer" name="captchaAnswer" style="border-radius: 10px!important; padding-left: 15px!important; font-size: 13px;" placeholder="Kode Captcha" maxlength="5" onKeyPress="return goodchars(event, '1234567890', this)" autocomplete="off" required />
                                                                            </span>
                                                                            <input type="hidden" name="hasil" value="<?=$_SESSION['hasil'];?>">
                                                                        </label>                                                    	
                                                                    </label>
                                                                    
                                                                    <div class="space-4"></div>
                
                                                                    <div class="clearfix">
                                                                        <!--<label class="inline">
                                                                            <input type="checkbox" class="ace width-45 pull-left" />
                                                                            <span class="lbl"> Remember Me</span>
                                                                        </label>-->

                                                                        <!-- <button type="button" class="width-45 pull-left btn btn-sm btn-success" name="signOut" onclick="location.href = '<?=$abs?>/reg/login.php?id=reg';">
                                                                            <i class="ace-icon fa fa-pencil-square-o"></i>
                                                                            <span class="bigger-110">Daftar</span>
                                                                        </button> -->
                
                                                                        <button type="submit" class="btn btn-sm btn-block btn-submit" name="signIn">
                                                                            <span class="bigger-110">Masuk</span>
                                                                        </button>
                                                                    </div>

                                                                    <label class="label-reg">Tidak punya akun? <a href="#" onclick="location.href = '<?=$abs?>/reg/login.php?id=reg'">Daftar</a></label>
                
                                                                    <div class="space-4"></div>
                                                                </fieldset>
                                                            </form>

                                                        <!--<div class="social-or-login center">
                                                            <span class="bigger-80">Or Login Using</span>
                                                        </div>

    													<div class="space-4"></div>

    													<div class="social-login center">
                                                        <a class="btn btn-primary">
                                                            <i class="ace-icon fa fa-facebook"></i>
                                                        </a>
    													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
        
        
                                                        <!-- <a class="btn btn-info">
                                                            <i class="ace-icon fa fa-twitter"></i>
                                                        </a> -->
        

                                                        <!--<a class="btn btn-danger">
                                                            <i class="ace-icon fa fa-google-plus"></i>
                                                        </a>-->
                                                    </div>
                                                </div><!-- /.widget-main -->

                                                <!-- <div class="toolbar clearfix">
                                                    <div>
                                                        <a href="#" data-target="#forgot-box" class="forgot-password-link">
                                                            <i class="ace-icon fa fa-arrow-left"></i>
                                                            Lupa Password
                                                        </a>
                                                    </div>
        											<?php if($act != 'log') {?> 
                                                    <div>
                                                        <a href="#" data-target="#signup-box" class="user-signup-link">
                                                            Daftar
                                                            <i class="ace-icon fa fa-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                    <?php } ?>
                                                </div> -->
                                            </div><!-- /.widget-body -->
                                        </div><!-- /.login-box -->
                                        
                                        <!-- forgot form -->

                                        <div id="forgot-box" class="forgot-box widget-box no-border">
                                            <?php
                                            if(isset($_SESSION['error'])){
                                                echo "
                                                    <div class='callout callout-danger text-center'>
                                                    <p>".$_SESSION['error']."</p> 
                                                    </div>";
                                                unset($_SESSION['error']);
                                            }   
                                            if(isset($_SESSION['success'])){
                                                echo "
                                                    <div class='callout callout-success text-center'>
                                                    <p>".$_SESSION['success']."</p> 
                                                    </div>";
                                                unset($_SESSION['success']);
                                            }
                                            ?>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <h3 class="form-title" style="color: #B42929!important">
                                                        <!-- <i class="ace-icon fa fa-key"></i> -->
                                                        Lupa Password
                                                    </h3>
        
                                                    <div class="space-6"></div>
                                                    <p class="red" style="font-size: 12px;">
                                                        Ketik email Anda untuk menerima konfirmasi
                                                    </p>
        
                                                    <form method="post" action="cc.php">
                                                        <!-- <fieldset> -->
                                                            <label class="block clearfix">
                                                                <span class="block input-icon input-icon-left">
                                                                    <input type="email" class="form-control" name="emailForgot" id="emailForgot" style="border-radius:10px!important; padding-left: 30px!important" placeholder="Email" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789@._', this)" autocomplete="off" required/>
                                                                    <!-- <i class="ace-icon fa fa-envelope"></i> -->
                                                                    <img src="assets/images/iconsax/sms.svg" class="ace-icon isax filter-gray">
                                                                </span>
                                                            </label>
        
                                                            <div class="clearfix">
                                                                <button type="submit" name="forgotpass" class="btn btn-sm btn-block btn-reset">
                                                                    <!-- <i class="ace-icon fa fa-lightbulb-o"></i> -->
                                                                    <span class="bigger-110">Kirim</span>
                                                                </button>
                                                            </div>
                                                        <!-- </fieldset> -->
                                                    </form>
                                                    <div class="toolbar">
                                                        <a href="#" data-target="#login-box" style="float: left; margin-left: -6%;">
                                                            Kembali ke Halaman Login
                                                            <i class="ace-icon fa fa-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                </div><!-- /.widget-main -->
        
                                                <!-- <div class="toolbar center">
                                                    <a href="#" data-target="#login-box" class="back-to-login-link">
                                                        Kembali ke Halaman Login
                                                        <i class="ace-icon fa fa-arrow-right"></i>
                                                    </a>
                                                </div> -->
                                            </div><!-- /.widget-body -->
                                        </div><!-- /.forgot-box -->
                                        
                                        <!-- signup form -->
                                        
                                        <div id="signup-box" class="signup-box <?=$act_reg?> widget-box no-border">
                                            <?php               
                                            if(isset($_SESSION['errorReg'])){
                                                echo "
                                                    <div class='callout callout-danger text-center'>
                                                    <p>".$_SESSION['errorReg']."</p> 
                                                    </div>";
                                                unset($_SESSION['errorReg']);
                                            }   
                                            if(isset($_SESSION['successReg'])){
                                                echo "
                                                    <div class='callout callout-success text-center'>
                                                    <p>".$_SESSION['successReg']."</p> 
                                                    </div>";
                                                unset($_SESSION['successReg']);
                                            }
                                            ?>
                                            <div class="widget-body">
                                                <div class="widget-main">
    											
    											<!-- <h4 class="header green lighter bigger">
    												<i class="ace-icon fa fa-users blue"></i>
    												New User Registration
    											</h4> -->

    											<!--<div class="space-6"></div>-->
    											<!--<h5> Enter your details: </h5>-->
    										
    											<form id="myRegForm" method="post" action="term.php">
    												<fieldset>
                                                        <!-- <h3 class="form-title">Register</h3> -->
    													<label class="block clearfix">
    														<span class="block input-icon input-icon-left">
    															<input type="email" class="form-control input-reg" id="emailReg" name="emailReg" style="border-radius:10px!important;" placeholder="Email" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789@._', this)" maxlength="100" autofocus autocomplete="off" required />
    															<!-- <i class="ace-icon fa fa-envelope"></i> -->
                                                                <img src="assets/images/iconsax/sms.svg" class="ace-icon isax filter-gray">
    														</span>
    													</label>

    													<label class="block clearfix">
    														<span class="block input-icon input-icon-left">
    															<input type="text" class="form-control input-reg" id="nameReg" name="nameReg" style="border-radius:10px!important;" placeholder="Nama" maxlength="50" autocomplete="off" required />
    															<!-- <i class="ace-icon fa fa-user"></i> -->
                                                                <img src="assets/images/iconsax/profile-circle.svg" class="ace-icon isax filter-gray">
    														</span>
    													</label>

    													<label class="block clearfix">
    														<span class="block input-icon input-icon-left">
                                                                <!--<label class="width-20 pull-left">
                                                                    <input type="text" class="form-control" id="countryCode" name="countryCode" value="62" style="text-align: center;" readonly="" />
    															</label>-->
                                                                <!--<label class="width-80 pull-right">-->
                                                                <input type="text" class="form-control input-reg" id="hpReg" name="hpReg" style="border-radius:10px!important;" placeholder="08xxxxxxxxxx (mis. 085577889900)" maxlength="15" onKeyPress="return goodchars(event, '0123456789()', this)" autocomplete="off" required />
                                                                <!--</label>-->
                                                                <!-- <i class="ace-icon glyphicon glyphicon-phone"></i> -->
                                                                <img src="assets/images/iconsax/mobile.svg" class="ace-icon isax filter-gray">
    														</span>
    													</label>

    													<label class="block clearfix">
    														<span class="block input-icon input-icon-left">
    															<input type="password" class="form-control input-reg" id="passwdReg" name="passwdReg" style="border-radius:10px!important;" placeholder="Password (minimal 6 karakter)" autocomplete="off" minlength="6" onChange="form.passwd2Reg.pattern=this.value;" required />
    															<!-- <i class="ace-icon fa fa-lock"></i> -->
                                                                <img src="assets/images/iconsax/lock.svg" class="ace-icon isax filter-gray">

                                                                <img src="assets/images/iconsax/eye.svg" class="ace-icon isax-eye filter-gray" id="eye-reg">
                                                                <input type="hidden" name="eye-reg1" id="eye-reg1" value="1">
    															<!--<label style="color: #6A6767; font-size: 10px">*Please enter minimum  of 6 characters</label>-->
    														</span>
    													</label>

    													<label class="block clearfix">
    														<span class="block input-icon input-icon-left">
    															<input type="password" class="form-control input-reg" id="passwd2Reg" name="passwd2Reg" style="border-radius:10px!important;" placeholder="Konfirmasi Password" autocomplete="off" pattern="" required />
    															<!-- <i class="ace-icon fa fa-pencil-square-o"></i> -->
                                                                <img src="assets/images/iconsax/lock.svg" class="ace-icon isax filter-gray">

                                                                <img src="assets/images/iconsax/eye.svg" class="ace-icon isax-eye filter-gray" id="eye-regConf">
                                                                <input type="hidden" name="eye-regConf1" id="eye-regConf1" value="1">
    														</span>
    													</label>
                                                        
                                                        <?php if($agn != '') { ?>
                                                        <label class="block clearfix">
    														<span class="block input-icon input-icon-left">
    															<input type="text" class="form-control" id="salesman" name="salesman" style="border-radius:10px!important;" placeholder="Salesman" autocomplete="off" pattern="" value="<?=$agn_name?>" disabled />
    															<i class="ace-icon fa fa-group"></i>
    														</span>
    													</label>
                                                        <?php } ?>
                                                        <input type="hidden" id="salesid" name="salesid" value="<?=$agn?>" />
                                                        
    													<div class="clearfix" style="font-size: 14px">
                                                            <span>
                                                                <label class="width-45 pull-left">
                                                                    <input type="radio" id="gender" name="gender" value="M" checked>&nbsp;Pria</input>
                                                                </label>
                                                                <label class="width-45 pull-right">
                                                                    <input type="radio" id="gender" name="gender" value="F">&nbsp;Wanita</input>
                                                                </label>
                                                            </span>
                              							</div>
                                                        <!--<label class="block clearfix">
                                                        	<label class="width-45 pull-left" style="padding:0px 0px 0px 0px"><img src="captcha.php" id="captImgReg"><font style="font-family:Tahoma, Geneva, sans-serif; font-size:12px" align="left"></label>
                                                            <label class="width-45 pull-right">
                                                            	<span class="block input-icon input-icon-right">
                                                                <input type="text" class="form-control" id="captchaAnswerReg" name="captchaAnswerReg" placeholder="Captcha Code" autocomplete="off" on required /><i class="ace-icon fa fa-refresh"></i>
                                                                </span>
                                                            </label>                                                    	
                                                        </label>-->

                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-icon-left">
                                                                <label class="width-45 pull-left">
                                                                    <input type="text" class="form-control" id="otp" name="otp" style="border-radius:10px!important;" placeholder="Kode OTP" maxlength="6" onKeyPress="return isNumberKey(event)" onKeyUp="openRegister()" autocomplete="off" required />
                                                                </label>
                                                                <button type="button" name="getOTP" id="getOTP" class="width-45 pull-right" style="border-radius:5px!important;" onclick="minta_otp()">
                                                                    <span class="bigger-110">Minta OTP</span>
                                                                </button>
                                                            </span>
                                                        </label>

                                                        <label class="block clearfix">
                                                            <!--<label class="width-45 pull-left" style="padding:0px 0px 0px 0px; font-family:Tahoma, Geneva, sans-serif; font-size:18px"><?=$_SESSION['b3']." + ".$_SESSION['b4']." = ?"?></label>
                                                            <label class="width-45 pull-right">
                                                                <span class="block input-icon input-icon-right">
                                                                <input type="text" class="form-control" id="captchaAnswerReg" name="captchaAnswerReg" placeholder="Captcha Code" maxlength="5" onKeyPress="return goodchars(event, '1234567890', this)" autocomplete="off" required />
                                                                </span>
                                                                <input type="hidden" name="hasilReg" value="<?=$_SESSION['hasil2'];?>">
                                                            </label>-->
                                                            <label class="width-90 pull-center" style="font-style: italic; font-size: 12px; color : red; ">
                                                                [ Kode OTP akan dikirimkan ke Nomor WA Anda ]
                                                            </label>
                                                        </label>
                                                         
    													<div class="clearfix">
    														<button type="reset" class="width-45 pull-left btn btn-sm">
    															<i class="ace-icon fa fa-refresh"></i>
    															<span class="bigger-110">Reset</span>
    														</button>

    														<!--<button type="submit" name="register" id="register" class="width-45 pull-right btn btn-sm btn-success">-->
                                                            <button type="button" name="register" id="register" class="width-45 pull-right btn btn-sm btn-success" onclick="validasi_register()">
    															<span class="bigger-110">Daftar</span>
    															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
    														</button>
    													</div>
                                                        <label class="label-reg">Sudah punya akun? <a href="#" onclick="location.href = '<?=$abs?>/reg/login.php?id=log'">Login</a></label>
    												</fieldset>
    											</form>
    										</div>
											<!-- <?php if($act != 'reg' and $act != '') { ?>
    										<div class="toolbar center">
    											<a href="#" data-target="#login-box" class="back-to-login-link">
    												<i class="ace-icon fa fa-arrow-left"></i>
    												Back to login
    											</a>
    										</div>
                                            <?php } ?> -->
    									</div><!-- /.widget-body -->
    								</div><!-- /.signup-box -->
    							</div><!-- /.position-relative -->
    						</div>
    					</div><!-- /.col -->
    				</div><!-- /.row -->
    			<!-- </div>/.main-content -->
			<div class="col-md-1"></div>
		</div><!-- /.main-container -->
	</div><!-- container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="assets/js/jquery-2.1.4.min.js"></script>
    <script src="assets/sweetalert/sweetalert2.all.min.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
		<script src="assets/js/jquery-1.11.3.min.js"></script>
	<![endif]-->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function($) {
       		$(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			});

        });
    </script>

    <script type="text/javascript">

        var x = $('.form-input')
        
        for (var i = 0; i < x.length; i++) {

          x[i].addEventListener("click", function() {

          var id = this.children[i-1].id;
          var current = document.getElementsByClassName("filter-blue");
          if (current.length > 0) {

            current[0].className = current[0].className.replace(" filter-blue", " ");
            if(id == 'sms')
            {
                $('#lock').attr('src','assets/images/iconsax/lock.svg')
            } else if(id == 'lock'){
                $('#sms').attr('src','assets/images/iconsax/sms.svg')
            }
            
          }

          this.children[i-1].className += " filter-blue";

          if(id == 'sms')
          {
            this.children[i-1].src = "assets/images/iconsax/sms-bold.svg";
          } else if(id == 'lock'){
            this.children[i-1].src = "assets/images/iconsax/lock-bold.svg";
          }

          });

        }

        $('#eye').on('click', function(){
            var val = $('#eye1').val()
            var newVal;
            
            if(val == 0)
            {
                newVal = val + 1;
                $('#passwd').attr('type', 'password')
                $('#eye').attr('src', 'assets/images/iconsax/eye.svg')
            } else if(val == 1){
                newVal = val - 1;
                $('#passwd').attr('type', 'text')
                $('#eye').attr('src', 'assets/images/iconsax/eye-slash.svg')
            }

            $('#eye1').val(newVal)
        })

        $('#eye-reg').on('click', function(){
            var val = $('#eye-reg1').val()
            var newVal;
            
            if(val == 0)
            {
                newVal = val + 1;
                $('#passwdReg').attr('type', 'password')
                $('#eye-reg').attr('src', 'assets/images/iconsax/eye.svg')
            } else if(val == 1){
                newVal = val - 1;
                $('#passwdReg').attr('type', 'text')
                $('#eye-reg').attr('src', 'assets/images/iconsax/eye-slash.svg')
            }

            $('#eye-reg1').val(newVal)
        })

        $('#eye-regConf').on('click', function(){
            var val = $('#eye-regConf1').val()
            var newVal;
            
            if(val == 0)
            {
                newVal = val + 1;
                $('#passwd2Reg').attr('type', 'password')
                $('#eye-regConf').attr('src', 'assets/images/iconsax/eye.svg')
            } else if(val == 1){
                newVal = val - 1;
                $('#passwd2Reg').attr('type', 'text')
                $('#eye-regConf').attr('src', 'assets/images/iconsax/eye-slash.svg')
            }

            $('#eye-regConf1').val(newVal)
        })

        function isNumberKey(evt) 
        {
            var charCode = (evt.which) ? evt.which : event.keyCode
            // Added to allow decimal, period, or delete
            //if (charCode == 110 || charCode == 190 || charCode == 46)
            //  return true;
            
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            
            return true;
        }
    </script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#register').prop('disabled', true);
        });
    </script>

    <script type='text/javascript'>
		function getkey(e) {
			if (window.event)
				return window.event.keyCode;
			else if (e)
				return e.wich;
			else
				return null;
		}

		function goodchars(e, goods, field) {
			var key, keychar;
			key = getkey(e);
			if (key == null) return true;
		
			keychar = String.fromCharCode(key);
			keychar = keychar.toLowerCase();
			goods = goods.toLowerCase();
		
			//check goodkeys
			if(goods.indexOf(keychar) != -1)
				return true;
				// control keys
			if(key == null || key==0 || key==8 || key==9 || key==27)
				return true;
			if (key == 13) {
				var i;
				for (i=0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
				i = (i + 1) % field.form.elements.length;
				field.form.elements[i].focus();
				return false;
			}
			//else return false
			return false;
		}

        function openRegister(){
            var OTP = $('#otp').val();
            if(OTP.length > 3) {
                $('#register').prop('disabled', false);
            } else {
                $('#register').prop('disabled', true);
            }
        }

        function minta_otp() {
            var email = $('#emailReg').val();
            var telp = $('#hpReg').val();
            var phone = telp.substring(1,20).trim();
            var hp = '62'+phone;
            var waNumber = hp.replace(/\D/g, '');
            var name = $('#nameReg').val();
            var OTPText = '<h4>Send OTP to '+waNumber+'</h4>';
            
            if(email == '') {
                Swal.fire(
                    'Kesalahan Email',
                    'Email harus diisi!',
                    'warning'
                )
            } else if(email != '' && validateEmail(email) == false) {
                Swal.fire(
                    'Kesalahan Email',
                    'Format Email salah!',
                    'warning'
                )
            } else if(telp.length < 7) {
                Swal.fire(
                    'Kesalahan Nomor WA',
                    'Panjang Nomor WA minimal 7 digit!',
                    'warning'
                )
            } else {
                Swal.fire({
                    title : 'GET OTP',
                    html : OTPText,
                    type : 'warning',
                    position: 'top',
                    showCancelButton : true,
                    confirmButtonColor : '#3085d6',
                    cancelButtonColor : '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText :'Cancel' 
                }).then((result) => {
                    if(result.value){
                        //https://github.com/t4t5/sweetalert/issues/778
                         Swal.fire({
                            title: 'Silakan tunggu ...!',
                            text: 'Permintaan sedang diproses ...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            onOpen: () => {
                                Swal.showLoading()
                            }
                        })
                        $.ajax({
                            type:'post',
                            dataType:'json',
                            url:'action_login.php',
                            data:{action_login:'mintaOTP', emailReg: email, waNumber: waNumber, phone: telp, nameReg: name},
                            success: function(record){
                                if (record.status=='success') {
                                    //var token = record.token;
                                    Swal.fire(
                                        'Kode OTP sudah terkirim ke Nomor WA Anda',
                                        'Silakan periksa kiriman OTP di HP Anda',
                                        'success'
                                    )
                                    //window.location.reload(); 
                                } else if (record.status=='failed') {
                                    Swal.fire(
                                        'Gagal mengirimkan Kode OTP ke Nomor WA '+waNumber+'!',
                                        'Periksa kembali Nomor WA Anda, apakah sudah benar?',
                                        'warning'
                                    )
                                } else if (record.status=='email') {
                                    Swal.fire(
                                        'Email Anda sudah terdaftar!',
                                        'Silakan daftar dengan email lainnya',
                                        'warning'
                                    )
                                } else if (record.status=='otpIsValid') {
                                    Swal.fire(
                                        'Kode OTP Anda masih berlaku',
                                        'Silakan gunakan Kode OTP yang sudah diterima',
                                        'warning'
                                    )
                                 } else if (record.status=='wa') {
                                    Swal.fire(
                                        'Nomor WA Anda sudah terdaftar!',
                                        'Silakan daftar dengan nomor WA lainnya',
                                        'warning'
                                    )
                                } else if (record.status=='otp') {
                                    var otp = record.kode;
                                    Swal.fire(
                                        'Kode OTP Anda :'+otp,
                                        'Gunakan Kode OTP ini untuk registrasi',
                                        'warning'
                                    )
                                }
                                
                            }
                        })
                    }
                })
            }
        }

        function validasi_register() {
            var email = $('#emailReg').val();
            var name = $('#nameReg').val();
            var hp = $('#hpReg').val();
            var password = $('#passwdReg').val();
            var repassword = $('#passwd2Reg').val();
            var gender = $('#gender').val();
            var salesid = $('#salesid').val();
            var otp = $('#otp').val();

            if(email == '') {
                Swal.fire(
                    'Kesalahan Email',
                    'Email harus diisi!',
                    'warning'
                )
            } else if(email != '' && validateEmail(email) == false) {
                Swal.fire(
                    'Kesalahan Email',
                    'Format Email salah!',
                    'warning'
                )
            } else if(name == '') {
                Swal.fire(
                    'Kesalahan Nama',
                    'Nama harus diisi!',
                    'warning'
                )
            } else if(hp == '') {
                Swal.fire(
                    'Kesalahan Nomor WA',
                    'Nomor WA harus diisi!',
                    'warning'
                )
            } else if(hp.length < 7) {
                Swal.fire(
                    'Kesalahan Nomor WA',
                    'Panjang Nomor WA minimal 7 digit!',
                    'warning'
                )
            } else if(password == '') {
                Swal.fire(
                    'Kesalahan Password',
                    'Password harus diisi!',
                    'warning'
                )
            } else if(password.length < 6) {
                Swal.fire(
                    'Kesalahan Password',
                    'Panjang Password minimal 6 digit!',
                    'warning'
                )
            } else if(repassword == '') {
                Swal.fire(
                    'Kesalahan Konfirmasi Password',
                    'Konfirmasi Password harus diisi!',
                    'warning'
                )
            } else if(repassword.length < 6) {
                Swal.fire(
                    'Kesalahan Konfirmasi Password',
                    'Panjang Konfirmasi Password minimal 6 digit!',
                    'warning'
                )
            } else if(otp == '') {
                Swal.fire(
                    'Kesalahan Kode OTP',
                    'Kode OTP harus diisi!',
                    'warning'
                )
            } else {
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:'action_login.php',
                    data:{action_login:'validasiRegister', emailReg: email, nameReg: name, hpReg: hp, passwdReg: password, passwd2Reg: repassword, gender: gender, salesid: salesid, otp: otp},
                    success: function(record){
                        if (record.status=='success') {
                            //document.getElementById('myRegForm').submit(); 
                            Swal.fire({
                                title : 'Apakah Anda yakin untuk mendaftar?',
                                html : '<h4>Daftar Akun Baru</h4>',
                                type : 'warning',
                                showCancelButton : true,
                                confirmButtonColor : '#3085d6',
                                cancelButtonColor : '#d33',
                                confirmButtonText: 'Ya',
                                cancelButtonText :'Batal'
                            }).then((result) => {
                                if (result.value) {
                                    Swal.fire({
                                        title: 'Silakan tunggu ...!',
                                        text: 'Pendaftaran sedang diproses ...',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false,
                                        onOpen: () => {
                                            Swal.showLoading()
                                        }
                                    })
                                    document.getElementById('myRegForm').submit(); 
                                }
                            })

                        } else if (record.status=='emailReg') {
                            var errTitle = record.errorTitle;
                            var errMsg = record.errorReg;
                            Swal.fire(
                                errTitle,
                                errMsg,
                                'warning'
                            )
                        } else if (record.status=='otpBlank') {
                            var errTitle = record.errorTitle;
                            var errMsg = record.errorReg;
                            Swal.fire(
                                errTitle,
                                errMsg,
                                'warning'
                            )
                        } else if (record.status=='passwordReg') {
                            var errTitle = record.errorTitle;
                            var errMsg = record.errorReg;
                            Swal.fire(
                                errTitle,
                                errMsg,
                                'warning'
                            )
                        } else if (record.status=='otpIsNotValid') {
                            var errTitle = record.errorTitle;
                            var errMsg = record.errorReg;
                            Swal.fire(
                                errTitle,
                                errMsg,
                                'warning'
                            )
                        } else if (record.status=='otpIsUsed') {
                            var errTitle = record.errorTitle;
                            var errMsg = record.errorReg;
                            Swal.fire(
                                errTitle,
                                errMsg,
                                'warning'
                            )
                        } else if (record.status=='otpReg') {
                            var errTitle = record.errorTitle;
                            var errMsg = record.errorReg;
                            Swal.fire(
                                errTitle,
                                errMsg,
                                'warning'
                            )
                        } 
                    }
                })
            }
        }

	</script>

    <script>
        function formatPhone(obj) {
            var numbers = obj.value.replace(/\D/g, ''),
                char = {0:'(',3:') ',6:'-'};
            obj.value = '';
            for (var i = 0; i < numbers.length; i++) {
                obj.value += (char[i]||'') + numbers[i];
            }
        }

        function formatMobilePhone(obj) {
            var numbers = obj.value.replace(/\D/g, ''),
                char = {4:'-',6:'-'};
            obj.value = '';
            for (var i = 0; i < numbers.length; i++) {
                obj.value += (char[i]||'') + numbers[i];
            }
        }

        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    </script>

	
	</body>
</html>
