<?php 
	session_start();
	$host=$_SERVER['SERVER_NAME'];
	include 'includes/style.php';
	require_once '../includes/koneksi2.php';

	if (isset($_GET['dbname'])) {
		$dbname = $_GET['dbname'];

		$sql = mysql_query("SELECT cs.*, c.*, COUNT(cs.order_id) AS jumlah_user, c.npwp_no, cs.taxpayer_id FROM cust_order cs JOIN company c on cs.company_id=c.id WHERE cs.dbname='$dbname' GROUP BY cs.dbname") or die(mysql_error());
		$info = mysql_fetch_array($sql);
		$date = date('d-M-Y', strtotime($info['end_date']));
        $order_id = $info['order_id'];
		$begin_date = $info['begin_date'];
		$end_date = $info['end_date'];
		$used = $info['used'];
		$npwp = $info['npwp_no'];
		$taxpayer_id = $info['taxpayer_id'];

		$action = 'simpanfakturpajak';

	}
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
			#tagihan{
				font-size: 16px;
				text-align: center;
				padding: 40px !important;
			}
			span.nominal{
				font-size: 30px;
				font-weight: bold;
			}
			span.keterangan{
				font-size: 12px;
				font-weight: lighter;
			}
			.container{
				margin-top: 30px;
			}
			.control-label{
				text-align:left !important;
			}
			.form-control{
				width: 70% !important;
			}
			.table tr,td{
				border:0 !important;
			}
			#voucher a{
				/*font-style: italic;*/
				font-weight: 600;
			}
			.navbar {
				background-color: #ffffff00;
				box-shadow:none !important;
			}
			.ace-nav>li.light-blue>a {
			    background-color: #62a8d130;
			}
		</style>

	</head>

	<body class="main-container">
		<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<a href="account.php"><img class="pos_img" src="../img/LOGO-SISCOM.png" style="max-height: 60px"></a>
						</small>
					</a>

					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>

						<img src="assets/images/avatars/avatar4.png" />
					</button>
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
		<!-- CONTENT -->

    	<form id="fakturpajak" enctype="multipart/form-data" method="post" action="action.php">
			<div class="container">
				<div class="row" id="kontak">
		            <div class="panel panel-primary" >
		                <div class="panel-heading">
		                    Informasi Kontak
		                </div>
		                <div class="panel-body">
		                    <div class="form-group">
		                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                            Nama
		                        </label>
		                        <input type="text" name="nama" id="nama" class="form-control" value="<?=$_SESSION['custName'];?>" readonly>
		                    </div>
		                    <div class="form-group">
		                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                            Nama Perusahaan
		                        </label>
		                        <input type="text" name="perusahaan" id="perusahaan" class="form-control" value="<?=$info['name'];?>" readonly>
		                    </div>
		                    <div class="form-group">
		                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                            Handphone
		                        </label>
		                        <input type="text" name="handphone" id="handphone" class="form-control" value="<?=$info['phone_no'];?>" readonly>
		                    </div>
		                    <div class="form-group">
		                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                            Email
		                        </label>
		                        <input type="text" name="email" id="email" class="form-control" value="<?=$_SESSION['custEmail'];?>" readonly>
		                    </div>
		                    <div class="form-group">
		                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                            Nama Database
		                        </label>
		                        <input type="text" name="dbname" id="dbname" class="form-control" value="<?=$dbname;?>" readonly>
		                    </div>
		                    <div class="form-group">
		                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                            Upload KTP
		                        </label>
		                        <input type="file" name="ktp" id="ktp" class="form-control" required>
		                    </div>
		                    <div class="form-group">
		                        <div class="col-lg-12 col-md-12 col-sm-12">
		                            <a class="pull-right" href="#" id="ubah" style="font-size: 14px"> Ubah</a>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="row">
		            <div class="panel panel-primary">
		                <div class="panel-heading">
		                    Faktur Pajak
		                </div>
		                <div class="panel-body">
		                    <div class="checkbox">
		                        <label class="control-label">Apakah Anda membutuhkan faktur pajak?&emsp;</label>
		                        <label><input type="checkbox" name="faktur" id="faktur" value="Y">Ya</label>	
		                    </div>
		                    <div id="form-faktur" style="display: none;">
		                        <div class="form-group">
		                            <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                                NPWP
		                            </label>
									<input type="text" name="npwp" id="npwp" class="form-control" placeholder="NPWP" value="<?=$npwp?>" required />
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                                Jenis Faktur
		                            </label>
		                        
		                            <select class="form-control" name="jenisfaktur" required>
		                                <option value="">-- Pilih Jenis Faktur --</option>
		                        <?php 
		                            $sql = mysql_query("select * from taxpayer_type") or die(mysql_error());
		                            while ($row = mysql_fetch_array($sql)) {
		                            	$selected = $row['id'] == $taxpayer_id ? 'selected' : '';
		                        ?>
		                                <option value="<?=$row['id'];?>" <?=$selected?>><?=$row['name'];?></option>
		                        <?php
		                            }
		                        ?>                     
		                            </select>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-3 col-lg-3 col-sm-3">
		                                Upload NPWP
		                            </label>
		                            <input type="file" name="npwp" id="npwp" class="form-control" required>
		                        </div>						
		                    </div>
		                </div>
		            </div>
		        </div>
        		
        		<input type="hidden" name="action" value="<?=$action;?>">
        		<input type="hidden" name="company_id" value="<?=$info['company_id'];;?>">


		        <div class="row">
	            	<div class="pull-right">
	                	<a href="info_tagihan.php?dbname=<?=$dbname;?>" class="btn btn-warning btn-lg" >Kembali</a>&emsp;
	                	<button class="btn btn-success btn-lg" id="simpan">Simpan</button><br><br><br>
	            	</div>
	            </div>
			</div>
		</form>

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

		<script type="text/javascript">
			
			$('#faktur').on('click', function(){
				var checked = $('#faktur').is(':checked');
				if (checked) {
					$('#form-faktur').show();
					$('#form-faktur input').prop('required', true);
				}else{
					$('#form-faktur').hide();
				}
			})

			$('#ubah').on('click', function(){
				if($(this).text()=='Ubah'){
					$(this).text('Batal')
					$('#kontak input').removeAttr('readonly');	
				}else{
					$(this).text('Ubah')
					$('#kontak input').attr('readonly', 'true');	
				}
				
			});
		</script>
	</body>
</html>