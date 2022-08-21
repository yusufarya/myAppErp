<?php 
	session_start();
	$host=$_SERVER['SERVER_NAME'];
	include 'includes/style.php';
	require_once '../includes/koneksi2.php';

	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>SISCOM ONLINE</title>

<!-- form element -->
		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

<!-- form widget -->
		<meta name="description" content="and Validation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="assets/css/select2.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="assets/js/ace-extra.min.js"></script>
		<!-- <script src="js/jquery.js"></script> -->

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			.panel-heading{
				font-size: 18px;
				text-align: center;
				padding: 10px auto;

			}
			button{
				width: 100px !important;
				border-radius: 5px !important;
			}
			.input-group-addon:hover{
				cursor:pointer;
			}
		</style>


	</head>

	<body>
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<a href="account.php"><img class="pos_img" src="<?=$abs?>/reg/img/LOGO-SISCOM.png" style="max-height: 60px"></a>
						</small>
					</a>

					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>

						<img src="assets/images/avatars/avatar4.png" />
					</button>

					<!-- <button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>
					</button> -->
				</div>

				<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">
						<?php include 'notification.php'; ?>

						<li class="light-blue dropdown-modal" style="height: 60px">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="height: 60px">
								<img class="nav-user-photo" src="assets/images/avatars/avatar4.png" alt="Jason's Photo" style="margin-top: 10px" />
								<span class="user-info">
									<small style="margin-top: 10px">Welcome,</small>
									<?php echo $_SESSION['custName'];  ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!--<li>
									<a href="#">
										<i class="ace-icon fa  fa-credit-card"></i>
										Penagihan
									</a>
								</li>-->

								<li>
									<a href="ubah_profil.php">
										<i class="ace-icon fa fa-user"></i>
										Profil
									</a>
								</li>
								
								<li>
									<a href="ubah_password.php">
										<i class="ace-icon fa fa-key"></i>
										Ubah Password
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="../logout.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<div class="container">
			<div class="row col-md-12 col-lg-12 col-sm-12" style="display: flex; justify-content: center; margin-top:30px ">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="panel panel-primary" id="kontak">
						<div class="panel-heading">
							Ubah Password
						</div>
						<div class="panel-body">
							<form id="form-password">
								<div class="form-group">
									<label>
										Password Lama
									</label>
									<div class="input-group" >
										<input type="password" name="password_lama" id="password_lama" class="form-control" value="" >
										<span class="input-group-addon" onclick="showpassword('password_lama')"><i class="fa fa-eye"></i></span>	
									</div>		
								</div>
								<div class="form-group">
									<label>
										Password Baru
									</label>
									<div class="input-group">
										<input type="password" name="password_baru" id="password_baru" class="form-control" value="" >
										<span class="input-group-addon" onclick="showpassword('password_baru')"><i class="fa fa-eye"></i></span>	
									</div>						
								</div>
								<div class="form-group">
									<label>
										Konfirmasi Password Baru
									</label>
									<div class="input-group">
										<input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" value="" >
										<span class="input-group-addon" onclick="showpassword('konfirmasi_password')"><i class="fa fa-eye"></i></span>
									</div>
										<p style="font-weight: 500" id="message"></p><br>
									
								</div>
							</form>
								<div class="form-group">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<button class="btn btn-default" id="batal">Batal</button>
										<button class="btn btn-primary pull-right" id="simpan" disabled><i class="fa fa-save"></i> Simpan</button>
									</div>
								</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script src="assets/js/jquery-2.1.4.min.js"></script>
		
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<script src="assets/js/wizard.min.js"></script>
		<script src="assets/js/jquery.validate.min.js"></script>
		<script src="assets/js/jquery-additional-methods.min.js"></script>
		<script src="assets/js/bootbox.js"></script>
		<script src="assets/js/jquery.maskedinput.min.js"></script>
		<script src="assets/js/select2.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			
			$('#simpan').on('click', function(evt){
				evt.preventDefault;
				var data = $('#form-password').serialize()+'&action=ubahpassword';

				$.ajax({
					type	: 'post',
					url	 	: 'action.php',
					data 	: data,
					dataType: 'json',
					success : function(record){
						if (record.status == 'success') {
							alert('Password Berhasil Diubah');
							window.location="account.php";
						}else{
							html='<div class="alert alert-danger alert-dismissible" role="alert">'+
										'<button type="button" class="close" style="font-size: 25px; font-weight:bold" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times</span></button>'+
										'<strong>Warning!!&emsp;</strong>Password yang anda masukkan salah... &emsp;&emsp;&emsp;'+
									'</div>';

							$('.panel-body').prepend(html);
						}
					}
				});

			});

			$('#konfirmasi_password').on('keyup', function(){
				var konfir = $(this).val();
				var password_baru = $('#password_baru').val();

				if (password_baru === konfir) {
					$('#simpan').prop('disabled', false);
					$('#message').text('Password Cocok');
					$('#message').css('color', 'green');
				}else{
					$('#simpan').prop('disabled', true);
					$('#message').text('Password Tidak Cocok')
					$('#message').css('color', 'red');
				}
			})
			
			$('#batal').on('click', function(){
				$('#form-password').trigger('reset');
				window.location="account.php";
			})
			
			function showpassword(str){
				if ($('#'+str).attr('type') == 'password') {
					$('#'+str).attr('type', 'text');
					$('#'+str).siblings().find('i').removeClass('fa-eye').addClass('fa-eye-slash')
				}else{
					$('#'+str).attr('type', 'password');
					$('#'+str).siblings().find('i').removeClass('fa-eye-slash').addClass('fa-eye')
				}
				
			}
		</script>
	</body>
</html>
