<?php 	
session_start();

include 'includes/style.php';
require_once '../includes/koneksi2.php';

	$host=$_SERVER['SERVER_NAME'];
	$datenow = date('d-m-Y');

// echo ();
if(!isset($_SESSION['custEmail']))
{
	echo "<script language='javascript'>alert('Your session has expired, please log in again.');</script>";	
	echo '<script language="javascript">window.location = "../login.php?id=log"</script>';
	exit;
} 

$email = $_SESSION['custEmail'];

if(!isset($prov)) {
	$prov = 31;
}

$qSal = mysql_query("SELECT c.salesman_id, s.name FROM cust c LEFT JOIN salesman s ON s.id = c.salesman_id WHERE c.email = '".$_SESSION['custEmail']."' AND s.stsrec = 'A'") or die (mysql_error());
$rSal = mysql_fetch_array($qSal);
$salesid = $rSal['salesman_id'];
$salesname = $rSal['name'];

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

		<!-- Date Picker -->
		<!-- <link rel="stylesheet" href="assets/datepicker/datepicker3.css"/>
		<link rel="stylesheet" href="assets/datepicker/bootstrap-datepicker.js"/> -->

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->

		<!-- GOOGLE FONT -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

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
			#dbname-error{
				color: red !important;
			}
			#selectAgen-error{
				color: red !important;
			}
			#inputan-error{
				color: red !important;
			}
			#combobox-error{
				color: red !important;
			}
			.lds-dual-ring{
				display: none;
				top: 0;
				left: 0;
				position: absolute;
				z-index: 2000;
				height: 100%;
				width: 100%;
				padding-top: 20%;
				background: rgba(255,255,255,0.3);
			}
			.lds-dual-ring:after{
				content: '';
				display: block;
				position: relative;
				z-index: 2001;
				width:46px;
				height: 46px;
				margin: auto;
				border-radius: 50%;
				border:5px solid #003b6a;
				border-color: #003b6a transparent #003b6a transparent;
				animation: lds-dual-ring 1.2s linear infinite;
			}
			.bg-color{
				background-color:#FFB752;
				color:white;
				font-weight:bold;
			}
			.modal-dialog{
				width: 70% !important;
			}
			tr:focus{
				background-color: yellow;
				color: white;
			}
			@keyframes lds-dual-ring{
				0%{
					transform: rotate(0deg);
				}
				100%{
					transform: rotate(360deg);
				}
			}

			.pos_img img{
				max-height: 45px;
				margin: 8px;
			}

			.myfont{
				font-size: 16px;
			}

			#judul1 h2, #judul1 h3{
				font-family: Montserrat;
				color: #fff; 
				font-weight: 600;
			}

			#judul1 p {
				color: #fff;
				font-family: Montserrat;
				font-style: normal;
				font-weight: normal;
				font-size: 24px;
				line-height: 30px;
				/* or 125% */

				text-align: center;

				color: #FFFFFF;

				margin: 5% 0%;
			}

			#judul1 a {
				color: #092F59!important;
				background: #fff!important;
				border: 0!important;
				width: 300px;
				font-weight: 600;

				box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1), 0px 0px 0px 1px #F7F9FC;
				border-radius: 6px;
			}

			#judul1 a:hover {
				background: #F6F6F6!important;
			}

			.dropdown-menu {
				margin: 5px 5px!important;
			}

			.dropdown-menu a{
				color: #5A6376!important;
			}

			.dropdown-menu a:hover{
				background: #E9EDF5!important;
				color: #5A6376!important;
			}

			@media (max-width: 768px)
			{

				#modal-table .modal-dialog{
					width: 95%!important;
				}

			}

		</style>

	</head>
	<body onload="load()" class="main-container">
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<!--<a href="account.php"><img class="pos_img" src="../img/LOGO-SISCOM.png" style="max-height: 60px"></a>-->
							<a href="account.php" class="pos_img"><img src="<?=$abs?>/reg/img/LOGO-SISCOM.png"></a>
						</small>
					</a>

					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>

						<img src="assets/images/avatars/avatar4.png" />
					</button>
				</div>

				<div class="navbar-buttons navbar-header pull-right collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">
						<?php include 'notification.php'; ?>

						<li class="dropdown-modal" style="height: 60px;">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<span class="user-info" style="padding: 0 10px;">
									<small style="text-align: right;">Hello</small>
									<?php echo $_SESSION['custName'];  ?>
								</span>
								<img class="nav-user-photo" src="assets/images/avatars/avatar4.png" alt="Jason's Photo" />

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-caret dropdown-close">
								<!-- <li>
									<a href="#">
										<i class="ace-icon fa fa-credit-card"></i>
										Penagihan
									</a>
								</li> -->
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
<!-- _____________________________________________Main Content____________________________________________ -->
		<div class="ace-save-state">
			<div class="main-content"  style="margin-top: 5%">
				<div class="main-content-inner">
					<?php
					$idcust = $_SESSION['custID'];
					$qdb = mysql_query("SELECT dbname FROM cust_order WHERE cust_id = $idcust AND stsrec = 'A'") or die (mysql_error());
					$datadb = mysql_fetch_array($qdb);
					//echo($datadb['dbname']);
						if ($datadb == 0){
					?>
					<div align="center" id="buatdb">
						<form id="judul1">
							<h2> Hi, <?php echo $_SESSION['custEmail'];  ?></h3>
							<h3>Selamat Datang di SISCOM Online</h3>
							<p>
								<!--Silakan buat database Anda sebelum memulai <br>pengalaman bisnis Anda Menjadi lebih Mudah-->
								Silakan membuat database Anda terlebih dahulu
							</p>
							<a href="#modal-table" role="button" class="btn" data-toggle="modal">+ Buat Database </a>
						</form>
					</div>
					<?php 
					} else{
					?>
						<!-- utk menampilkan halaman manage -->

						<div class="tampildata">
						</div>
					<?php
					}?>

					<div class="lds-dual-ring"></div>
					</div><!-- /.main-content-inner -->
				</div> <!-- /.main-content -->
				<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
					<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
				</a>
			</div> <!-- /.main-container -->
<!-- _____________________________________________./Main Content____________________________________________ -->
			<div id="modal-table" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-dialog-scrollable center-modal" style="margin-top: 12px;">
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">&times;</span>
								</button>
								Buat Database
							</div>
						</div>

<!-- _____________ISI WIDGET_______________ -->		
						<div class="modal-body">
						<!-- <div class="row"> -->
							<!-- <div class="col-xs-12"> -->
								<div class="widget-box">
									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<div class="row">
													<div class="widget-header widget-header-blue widget-header-flat">
														<ul class="steps">
															<li data-step="1" class="active">
																<span class="step">1</span>
																<span class="title">Info Perusahaan</span>
															</li>

															<li data-step="2">
																<span class="step">2</span>
																<span class="title">Pengaturan Awal</span>
															</li>

															<li data-step="3" id="step3">
																<span class="step">3</span>
																<span class="title">Akun Perkiraan</span>
															</li>
														</ul>
													</div>
												</div>
									
												<div class="step-content pos-rel row" style="padding:10px;" id="isiStep">
<!-- step 1 -->
													<div class="step-pane active" data-step="1">
														<form class="form-horizontal" id="validation-form1" method="get">
														<!-- <div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12"> -->
															<div class="col-sm-6">
																<div class="form-group">
																	<label class="control-label no-padding-right myfont" for="name">Nama<span style="color: red">*</span></label> 
																	<input type="text" class="form-control" id="nameU" name="nameU" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789. ', this)" placeholder="contoh: PT. SISCOM" autofocus autocomplete="off" required />
																</div>
																<div class="form-group">
																	<label class="control-label no-padding-right myfont">Bidang Usaha</label>
																	<select class="form-control myfont" id="selectBis" name="selectBis">
																		<option value="">--Pilih Bidang Usaha--</option>
																			<?php 
																				$qBis = "SELECT * FROM business WHERE stsrec = 'A' ORDER BY id DESC";
																				$rmB = mysql_query($qBis) or die(mysql_error());
																				while ($rowB = mysql_fetch_array($rmB)){
																					if ($rowB['id']==''?$selB="selected":$selB="");
																			?>
																						<option value="<?php echo $rowB['id'] ?>" <?php echo $selB ?>> <?php echo $rowB['name'] ?> </option>
																			<?php } ?>
																	</select>
																</div>
																<div class="form-group">
																	<label class="control-label no-padding-right myfont">No. Telp/Hp</label> 
																	<input type="text" id="hp" name="hp" class="form-control" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="15" placeholder="Nomor Handphone" autocomplete="off" />
																</div>
																<div class="form-group">
																	<label class="control-label no-padding-right myfont">No. WA <span style="color: red">*</span>
																	</label> 
																	<input type="text" id="wa" name="wa" class="form-control" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="15" placeholder="Nomor Whatsapp" autofocus autocomplete="off" required />
																</div>

																<div class="form-group">
																	<label class="control-label no-padding-right myfont"> NPWP </label> 
																	<input type="text" id="npwp" name="npwp" class="form-control" maxlength="20" onKeyPress="return goodchars(event, '0123456789.-', this)" placeholder="NPWP" autocomplete="off" />
																</div>
															</div>
															<div class="col-sm-1"></div>
															<div class="col-sm-5">
																<div class="form-group">
																	<label class="control-label no-padding-right myfont"> Periode Akuntansi</label>
																	<select id="selectperiod" name="selectperiod" class="form-control myfont">
																		<option value="01|12">Januari - Desember</option>
																		<option value="02|01">Februari - Januari</option>
																		<option value="03|02">Maret - Februari</option>
																		<option value="04|03">April - Maret</option>
																		<option value="05|04">Mei - April</option>
																		<option value="06|05">Juni - Mei</option>
																		<option value="07|06">Juli - Juni</option>
																		<option value="08|07">Agustus - Juli</option>
																		<option value="09|08">September - Agustus</option>
																		<option value="10|09">Oktober - September</option>
																		<option value="11|10">November - Oktober</option>
																		<option value="12|11">Desember - November</option>
																	</select>
																</div>
																<div class="form-group">
																	<label class="control-label no-padding-right myfont"> Negara </label>
																	<div>
																		<select id="selectNeg" name="selectNeg" class="form-control myfont">
																			<?php 
																				$qNeg = "SELECT * FROM country WHERE stsrec = 'A' ORDER BY code DESC";
																				$rmN = mysql_query($qNeg) or die(mysql_error());
																				while ($rowN = mysql_fetch_array($rmN)){
																					if ($rowN['code']=='1'?$selN="selected":$selN="");
																			?>
																						<option value="<?php echo $rowN['code'] ?>" <?php echo $selN ?>> <?php echo $rowN['name'] ?> </option>
																			<?php } ?>
																		</select> 
																	</div>
																</div>
																<div class="form-group">
																	<div class="row">
																		<div class="col-sm-7">
																			<label class="control-label no-padding-right myfont"> Provinsi </label>
																			<select class="form-control myfont" id="selectProv" name="selectProv">
																				<option value="">--Provinsi--</option>
																					<?php 
																						$qprov = "SELECT * FROM prov WHERE stsrec = 'A' ORDER BY name ASC";
																						$rmp = mysql_query($qprov) or die(mysql_error());
																						while ($rowp = mysql_fetch_array($rmp)){
																							if ($rowp['code']==$prov?$selP="selected":$selP="");
																					?>
																						<option value="<?php echo $rowp['code'] ?>" <?php echo $selP ?>> <?php echo $rowp['name'] ?> </option>
																						<?php } ?>
																			</select>
																		</div>
																		<div class="col-sm-5">
																			<label class="control-label no-padding-right myfont">Kode Pos</label>
																			<input type="text" id="kodepos" name="kodepos" class="form-control" placeholder="K. Pos" onKeyPress="return goodchars(event, '0123456789', this)" autocomplete="off" />
																		</div>
																	</div> 
																</div>
																<div class="form-group">
																	<label class="control-label no-padding-right myfont"> Kota </label>
																	<input type="text" id="kota" name="kota" class="form-control" placeholder="Kota/Kabupaten" autocomplete="off" />
																</div>
																<div class="form-group">
																	<label class="control-label no-padding-right myfont"> Alamat</label> 
																	<textarea class="form-control" id="address" name="address" rows="3" cols="22"></textarea>
																</div>  
															</div>
														<!-- </form> --> <!-- /.form-user -->
														<!-- </div> --><!-- /.div container -->
														</form>
													</div>
													
<!-- step 2 -->
													<div class="step-pane row" style="padding: 20px;" data-step="2">
														<form class="form-horizontal" id="validation-form2" method="get">
															<fieldset>
															<!-- <div class="container col-xs-12"> -->
																<div class="col-sm-6">
																	<?php
																		$selCheckAgent = "";
																		$selCheckCombo = "";
																		$selCheckR = "";
																		if ($salesid != '' and substr($salesid,0,1) != 'S')  
																		{
																			$selCheckAgent = "checked";
																		} else {
																			$selCheckAgent = "";
																		} 
																	?>
																	<!-- <form> -->
																	<div class="form-group myfont">
																		<label class="control-label no-padding-right" for="name">Nama Database&nbsp;<span style="color: red">*</span></label>
																		<input class="form-control" type="text" name="dbname" id="dbname" style="text-transform: lowercase;" placeholder="Nama Alias" maxlength="10" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789', this)" onFocus="cari(); selectFocus()" autofocus autocomplete="off" required />
																	</div>
																	<!-- </form> -->
																	<div class="form-group myfont" style="margin-top: 22px!important;">
																		<span style="font-size: 14px">Anda mengetahui SISCOM Online melalui:</span>
																			<div class="clearfix" style="font-size: 12px">
																				<div class="col-xs-12">
																					<div>
																						<label class="line-height-1 blue">
																							<input name="dari" id="agen" value="agen" type="radio" class="detail ace form-control" <?=$selCheckAgent?> />
																							<span class="lbl"> Agen penjualan (Marketing/Agent/Reseller)</span>
																							<br>
																							<div style="padding-left: 20px" >
																								<input style="text-transform: uppercase !important" type="text" class="form-control" id="selectAgen" name="agen" value="<?=$salesid?>" placeholder="Input kode agen" autocomplete="off" onkeyup="cari()" required>
																								<label id="infoAgn" style="color: #ec6f6f; font-size: 12px">*Hubungi agen penjualan untuk mengetahui kode agen</label>
																							</div>
																						</label>
																					</div>
																					<?php  
																						$query = mysql_query("SELECT s.id, s.name, s.remark, (SELECT COUNT(id) FROM `salesman` WHERE stsrec = 'A' AND parent_id = s.id) AS cnt FROM salesman s WHERE s.parent_id = '0' AND s.type = '3' AND s.stsrec = 'A' ORDER BY s.name") or die(mysql_error());
																						while ($row = mysql_fetch_array($query)) {
																							$parent_id = $row['id'];
																							$combo = $row['cnt'];
																							$remark = $row['remark'];
																							$names = strtolower(str_replace(' ', '', $row['name']));	
																					?>
																					<div>
																						<label class="line-height-1 blue">
																							<?php 
																								//id website siscom =  S0001
																								if ($combo > 0 && $row['id'] != 'S0001') {
																									if ($parent_id==$salesid?$selCheckCombo="checked":$selCheckCombo="");
																							?>
																								<div id="combo">
																									<input name="dari" id="<?=$names;?>" type="radio" class="ace form-control" value="<?=$row['id'];?>" <?=$selCheckCombo?> />
																									<span class="lbl"> <?=$row['name'];?> </span>
																									<br>
																									<div style="padding-left: 20px" >
																										<select style="width: 180px" id="combobox" name="combobox">
																											<option value=""> --Pilih <?=$row['name'];?>--</option>
																										<?php  
																											$query2 = mysql_query("SELECT * FROM salesman
																																WHERE parent_id = '$parent_id' 
																																AND LEFT(id,1) <> 'E'");
																											while ($row2 = mysql_fetch_array($query2)) {
																												//if ($row2['id']==$salesid?$selA="selected":$selA="");
																										?>
																											<option value="<?=$row2['id'];?>"><?=$row2['name'];?></option>
																										<?php } ?>
																										</select>
																									</div>
																								</div>
																							<?php 
																								} 
																								else if ($remark == 1) {
																									if ($parent_id==$salesid?$selCheckR="checked":$selCheckR="");
																								?>
																								<div id="remarks">
																									<input name="dari" id="<?=$names;?>" type="radio" class="ace form-control" value="<?=$row['id'];?>" <?=$selCheckR?> />
																									<span class="lbl"> <?=$row['name'];?> </span>
																									<br>
																									<div style="padding-left: 20px" >
																										<input type="text" class="form-control" id="inputan" name="remark" placeholder="Masukkan nama teman anda" autocomplete="off">
																									</div>
																								</div>
																							<?php } 
																								else{

																									$selChecks = $salesid == $row['id'] ? "checked" : "";
																							?>
																								<input name="dari" id="<?=$names;?>" type="radio" class="ace form-control" value="<?=$row['id'];?>"  <?=$selChecks?> />
																								<span class="lbl"> <?=$row['name'];?> </span>
																								<div style="display: none;"></div>
																							<?php } ?>
																						</label>
																					</div>
																					<?php } ?>
																				</div>
								                              				</div>
						                              				</div>
						                              			</div>
						                              			<div class="col-sm-1"></div>
						                              			<div class="col-sm-5">
						                              				<div class="form-group">
																		<label class="control-label no-padding-right" for="name" > Tanggal Mulai DB <span style="color: red">*</span>
																		</label> 
																		<div class="input-group">
																			<input class="form-control date-picker" id="beginDate" name="beginDate" type="text" autocomplete="off" data-date-format="dd-mm-yyyy" value="<?=$datenow?>" required/>
																			<span class="input-group-addon">
																				<i class="fa fa-calendar bigger-110"></i>
																			</span>
																		</div>
																	</div>
																	<div class="form-group" style="display: none;">
																		<label class="control-label no-padding-right" for="name" > Versi <span style="color: red">*</span>
																		</label> 
																		<select id="selectVer" name="selectVer" class="form-control" autofocus required>
																			<!-- <option value="">--Pilih Versi--</option> -->
																			<?php $qver = "SELECT * FROM version WHERE stsrec = 'A' AND id = '3' ORDER BY id";
																				$rmver = mysql_query($qver) or die(mysql_error());
																				while ($rowV = mysql_fetch_array($rmver)){
																					if ($rowV['id']=='3'?$selV="selected":$selV="");
																			?>
																			<option value="<?php echo $rowV['id'] ?>" <?php echo $selV ?>> <?php echo $rowV['name'] ?> </option>
																			<?php } ?>
																		</select>
																	</div>
																	
						                              			</div> <!-- /.col-md-5 -->
						                              		<!-- </form> --> <!-- form-user2 -->
					                              			<!-- </div> --><!-- /.div container -->
														</fieldset>
														</form>
													</div>
<!-- step 3 -->
													<div class="step-pane" data-step="3" id="dataStep3">
														<!-- <div class="center"> -->
														<div class="container col-xs-12">
															<div class="row">
																<div class="col-xs-12" style="padding-left: 10px">
																	<h4 class="lighter block green">Akun Perkiraan</h4>
																		<div class="col-sm-12 justify">
																		<span>
																			SISCOM membutuhkan akun perkiraan sebagai penampung nilai-nilai kas, pendapatan, persediaan, biaya, beban, dll. Jika anda kurang memahami bagaimana mendefinisikan daftar akun perkiraan untuk perusahaan anda, SISCOM dapat membuatkan secara otomatis daftar akun perkiraan otomatis daftar akun perkiraan yang umum digunakan oleh kebanyakan perusahaan.
																		</span><br><br>
																		</div> 
																		<div class="col-xs-10" style="padding-left: 10px">
																			<span>
																				Apakah anda ingin SISCOM otomatis membuatkan daftar akun perkiraan?
																			</span>
																		</div>
																		<div class="col-xs-2">
																			<label>
											                                    <input type="checkbox" name="auto" id="auto" checked/>
											                                    <span style="padding: 0">Ya</span></label>
																		</div>
																</div>
															</div> 
														</div><!-- /.container -->
													</div>
<!-- Step 4 -->
												</div> <!-- /.step-content pos-rel -->
											</div> <!-- /.fuelux-wizard-container -->
											<!-- <hr /> -->

											<div class="wizard-actions">
												<button class="btn btn-prev">
													<i class="ace-icon fa fa-arrow-left"></i>
													Kembali
												</button>

												<button class="btn btn-success btn-next" data-last="Finish" id="next">
													Lanjut
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>
											</div>

										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							<!-- </div> --> <!-- /.col-xs-12 -->
						<!-- </div> --><!-- .div-row -->
						</div><!-- .modal-body -->
					<!-- </div> --><!-- /.page-content -->
				</div> <!-- /.modal-content -->
			</div> <!-- /.modal-dialog -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.modal-table -->

<!-- ______________modal Edit Database___________ -->

		<div id="modal-edit" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">&times;</span>
							</button>
							Ubah Database
						</div>
					</div>

<!-- _____________ISI WIDGET_______________ -->		
		
						<div class="row">
							<div class="col-xs-12">
								<div class="widget-box">
									<div class="widget-body">
										<div class="widget-main">
											<div id="fuelux-wizard-container">
												<div class="widget-header widget-header-blue widget-header-flat">
													<div>
														<ul class="steps">
															<li data-step="1" class="active">
																<span class="step">1</span>
																<span class="title">Info Perusahaan</span>
															</li>

															<li data-step="2">
																<span class="step">2</span>
																<span class="title">Pengaturan Awal</span>
															</li>

															<li data-step="3" id="editStep3">
																<span class="step">3</span>
																<span class="title">Selesai</span>
															</li>
														</ul>
													</div>
												</div>
									
												<div class="step-content pos-rel">
<!-- EDIT step 1 -->
													<div class="step-pane active" data-step="1">
														<br>
														<form class="form-horizontal" id="validation-form1" method="get">
														<div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12">
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="float: left;">
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right" for="name" > Nama <label style="color: red">*</label>
																	</label> 
																	<div class="col-xs-8">
																		<input type="text" id="nameU" name="nameU" style="width: 180px; " onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.', this)" placeholder="contoh: PT. SISCOM" autofocus autocomplete="off" required />
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right">Bidang Usaha</label>
																	<div class="col-xs-8">
																		<select style="width: 180px; height: 33px" id="selectBis" name="selectBis">
																			<option value="">--Pilih Bidang Usaha--</option>
																				<?php 
																					$qBis = "SELECT * FROM business WHERE stsrec = 'A' ORDER BY id DESC";
																					$rmB = mysql_query($qBis) or die(mysql_error());
																					while ($rowB = mysql_fetch_array($rmB)){
																				?>
																							<option value="<?php echo $rowB['id'] ?>" > <?php echo $rowB['name'] ?> </option>
																				<?php } ?>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right">No. Telp/Hp</label> 
																	<div class="col-xs-8">
																		<input type="text" id="hp" name="hp" style="width: 180px" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="15" placeholder="Nomor Handphone" />
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right">No. WA <label style="color: red">*</label>
																	</label> 
																	<div class="col-xs-8">
																		<input type="text" id="wa" name="wa" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="15" placeholder="Nomor Whatsapp" autofocus required />
																	</div>
																</div>

																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right"> NPWP </label> 
																	<div class="col-xs-8">
																		<input type="text" id="npwp" name="npwp" style="width: 180px" maxlength="20" onKeyPress="return goodchars(event, '0123456789.-', this)" placeholder="NPWP" autocomplete="off" />
																	</div>
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style=" padding-left: 40px">
																
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> Periode Akuntansi</label> 
																	<div class="col-xs-7">
																		<select id="selectperiod" name="selectperiod" style="width: 180px; height: 34px">
																			<option value="01|12">Januari - Desember</option>
																			<option value="02|01">Februari - Januari</option>
																			<option value="03|02">Maret - Februari</option>
																			<option value="04|03">April - Maret</option>
																			<option value="05|04">Mei - April</option>
																			<option value="06|05">Juni - Mei</option>
																			<option value="07|06">Juli - Juni</option>
																			<option value="08|07">Agustus - Juli</option>
																			<option value="09|08">September - Agustus</option>
																			<option value="10|09">Oktober - September</option>
																			<option value="11|10">November - Oktober</option>
																			<option value="12|11">Desember - November</option>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> Alamat</label> 
																	<div class="col-xs-7">
																		<textarea id="address" name="address" rows="3" cols="22"></textarea>
																	</div>
																</div> 
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> </label>
																	<div class="col-xs-7">
																		<input type="text" id="kota" name="kota" style="width: 180px" placeholder="Kota/Kabupaten" />
																	</div>
																</div> 
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> </label>
																	<div class="col-xs-7">
																		<select id="selectProv" name="selectProv" style="width: 122px; height: 33px">
																				<option value="">--Provinsi--</option>
																					<?php $qprov = "SELECT * FROM prov WHERE stsrec = 'A' ORDER BY name ASC";
																						$rmp = mysql_query($qprov) or die(mysql_error());
																						while ($rowp = mysql_fetch_array($rmp)){
																							if ($rowp['code']==''?$selP="selected":$selP="");
																					?>
																						<option value="<?php echo $rowp['code'] ?>" <?php echo $selP ?>> <?php echo $rowp['name'] ?> </option>
																						<?php } ?>
																		</select> 
																		<input type="text" id="kodepos" name="kodepos" style="width: 50px" placeholder="K. Pos" onKeyPress="return goodchars(event, '0123456789', this)" />
																	</div>
																</div>  
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> </label>
																	<div class="col-xs-7">
																		<select id="selectNeg" name="selectNeg" style="width: 180px; height: 34px">
																			<?php $qNeg = "SELECT * FROM country WHERE stsrec = 'A' ORDER BY code DESC";
																					$rmN = mysql_query($qNeg) or die(mysql_error());
																					while ($rowN = mysql_fetch_array($rmN)){
																						if ($rowN['code']=='1'?$selN="selected":$selN="");
																			?>
																			<option value="<?php echo $rowN['code'] ?>" <?php echo $selN ?>> <?php echo $rowN['name'] ?> </option>
																			<?php } ?>
																		</select> 
																	</div>
																</div>  
															</div>
														<!-- </form> --> <!-- /.form-user -->
														</div><!-- /.div container -->

														</form>
													</div>
													
<!-- EDIT step 2 -->
													<div class="step-pane" data-step="2">
                                                    	<br>
														<!--<h4 class="lighter block green" style="padding-left: 10px">Masukkan Nama Database</h4>-->
														<form class="form-horizontal" id="validation-form2" method="get">
															<input type="hidden" name="salesmanID" id="salesmanID"> 
															<fieldset>
															<div class="container col-xs-12">
															<!-- <form method="post" class="form-user2" id="form-user2"> -->

																<div class="col-xs-6" style="float: left; padding-left: 10px">
																	<form>
																		<label for="name">Nama Database&nbsp;<label style="color: red">*</label>
																		
																		<input type="text" name="dbname" id="dbname" style="text-transform: lowercase;" placeholder="Nama Alias" maxlength="10" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789', this)" autofocus autocomplete="off" required />
																	</form>
																	<div><br>
																		<span style="font-size: 14px">Anda mengetahui SISCOM Online melalui:</span>
																			<div class="clearfix" style="font-size: 12px">
																				<div class="col-xs-12">
																					<div>
																						<label class="line-height-1 blue">
																							<input name="dari" id="dari" value="agen" type="radio" class="detail ace" disabled="disabled" />
																							<span class="lbl"> Agen penjualan (Marketing/Agent/Reseller)</span>
																							<br>
																							<div style="padding-left: 20px">
																								<input style="text-transform: uppercase !important" type="text" id="selectAgen" name="dari" placeholder="Input kode agen" autofocus autocomplete="off" onkeyup="cari()" required>
																								<label id="infoAgn" style="color: #ec6f6f; font-size: 12px">*Hubungi agen penjualan untuk mengetahui kode agen</label>
																							</div>
																						</label>
																						
																					</div>
																					<?php  
																						$query = mysql_query("SELECT s.id, s.name, s.remark, (SELECT COUNT(id) FROM `salesman` WHERE stsrec = 'A' AND parent_id = s.id) AS cnt FROM salesman s WHERE s.parent_id = '0' AND s.type = '3' AND s.stsrec = 'A' ORDER BY s.name");
																						while ($row = mysql_fetch_array($query)) {
																							$parent_id = $row['id'];
																							$combo = $row['cnt'];
																							$remark = $row['remark'];
																							$names = strtolower(str_replace(' ', '', $row['name']));
																					?>
																					<div>
																						<label class="line-height-1 blue">
																							<?php 
																								if ($combo > 0) {
																							 ?>
																							 	<input name="dari" id="<?=$names;?>" type="radio" class="ace" value="combo" disabled="disabled" />
																								<span class="lbl"> <?=$row['name'];?> </span>
																								<br>
																								<div style="padding-left: 20px">
																									<select style="width: 180px" id="combobox" name="combobox" disabled="disabled" >
																										<option value=""> --Pilih <?=$row['name'];?>--</option>
																									<?php  
																										$query2 = mysql_query("SELECT * FROM salesman
																															WHERE parent_id = '$parent_id'");
																										while ($row2 = mysql_fetch_array($query2)) {
																											//if ($row2['id']==$salesid?$selA="selected":$selA="");
																									?>
																										<option value="<?=$row2['id'];?>"><?=$row2['name'];?> </option>
																									<?php } ?>
																									</select>
																								</div>
																							<?php 
																								} 
																								else if ($remark == 1) {
																								?>
																								<input name="dari" id="<?=$names;?>" type="radio" class="ace" value="referensi" disabled="disabled" />
																								<span class="lbl"> <?=$row['name'];?> </span>
																								<br>
																								<div style="padding-left: 20px">
																									<input type="text" id="inputan" name="remark" placeholder="Masukkan nama teman anda" disabled="disabled" >
																								</div>
																							<?php } 
																								else{
																							?>
																								<input name="dari" id="<?=$names;?>" type="radio" class="ace" value="<?=$row['id'];?>" disabled="disabled" />
																								<span class="lbl"> <?=$row['name'];?> </span>
																							<?php } ?>
																						</label>
																					</div>
																					<?php } ?>
																				</div>
								                              				</div>
						                              				</div>
						                              			</div>
						                              			<div class="col-xs-6" style="padding: 0px">
						                              				<div class="form-group">
																		<label class="col-xs-3 control-label no-padding-right" for="name" > Tanggal Mulai DB <label style="color: red">*</label>
																		</label> 
																		<div class="col-xs-9">
																			<input type="date" id="beginDate" name="beginDate" style="width: 180px" autofocus autocomplete="off" required />
																		</div>
																	</div>
																	<div class="form-group" style="display: none;">
																		<label class="col-xs-3 control-label no-padding-right" for="name"> Versi <label style="color: red">*</label>
																		</label> 
																		<div style="padding-left: 20px" >
																			<select id="selectVer" name="selectVer" style="width: 180px; height: 34px" autofocus required>
																				<!-- <option value="">--Pilih Versi--</option> -->
																				<?php 
																					$qver = "SELECT * FROM version WHERE stsrec = 'A' AND id = '3' ORDER BY id";
																					$rmver = mysql_query($qver) or die(mysql_error());
																					while ($rowV = mysql_fetch_array($rmver)){
																						if ($rowV['id']=='1'?$selV="selected":$selV="");
																				?>
																				<option value="<?php echo $rowV['id'] ?>" <?php echo $selV ?>> <?php echo $rowV['name'] ?> </option>
																				<?php } ?>
																			</select>
																		</div>
																	</div>
																	
						                              			</div> <!-- /.col-md-5 -->
						                              		<!-- </form> --> <!-- form-user2 -->
					                              			</div><!-- /.div container -->
														</fieldset>

														</form>
													</div>
<!-- EDIT step 3 -->
													<div class="step-pane" data-step="3" id="editDataStep3">
														<!-- <div class="center"> -->
														<div class="container col-xs-12">
															<div class="row">
																<div class="col-xs-12" style="padding-left: 10px">
																	<h4 class="lighter block green">Selesai</h4>
																		<div class="col-sm-12 justify">
																		<span>
																			
																		</span><br><br>
																		</div> 
																		<div class="col-xs-10" style="padding-left: 10px">
																			<!-- <span>
																				Apakah anda ingin SISCOM otomatis membuatkan daftar akun perkiraan?
																			</span> -->
																		</div>
																		<div class="col-xs-2">
																			<!-- <label>
											                                    <input type="checkbox" name="auto" id="auto" value="Y" checked disabled/>
											                                    <span style="padding: 0">Ya</span></label> -->
																		</div>
																</div>
															</div> 
														</div><!-- /.container -->
													</div>
												</div> <!-- /.step-content pos-rel -->
											</div> <!-- /.fuelux-wizard-container -->
											<!-- <hr /> -->

											<div class="wizard-actions">
												<button class="btn btn-prev">
													<i class="ace-icon fa fa-arrow-left"></i>
													Kembali
												</button>

												<button class="btn btn-success btn-next" data-last="Finish">
													Lanjut
													<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
												</button>
											</div>

										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.widget-box -->
							</div> <!-- /.col-xs-12 -->
						</div> /<!-- .div-row -->
					<!-- </div> --><!-- /.page-content -->
				</div> <!-- /.modal-content -->
			</div> <!-- /.modal-dialog -->
			
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.modal-edit -->


		<!-- modal copy master -->
		<div id="modal-copy-master" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header orange-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">&times;</span>
							</button>
							Copy Master
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="widget-box">
								<div class="widget-body">
									<div class="widget-main">
										<div id="fuelux-wizard-container">
											<div class="widget-header widget-header-blue widget-header-flat">
												<div>
													<ul class="steps copy-master">
														<li data-step="1" class="active">
															<span class="step">1</span>
															<span class="title">Info Perusahaan</span>
														</li>

														<li data-step="2">
															<span class="step">2</span>
															<span class="title">Pengaturan Awal</span>
														</li>
													</ul>
												</div>
											</div>
								
											<div class="step-content pos-rel">
												<!-- step 1 -->
												<div class="step-pane active" data-step="1">
													<br>
													<form class="form-horizontal" id="validation-form1" method="get">
														<input type="hidden" name="dbMaster" id="dbMaster">
														<input type="hidden" name="active" value="D">
														<div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12">
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="float: left;">
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right" for="name" >
																		Copy Master dari :
																	</label> 
																	<div class="col-xs-8" style="padding-top: 8px">
																		<strong>
																			Database&nbsp;<span id="judulModal"></span>
																		</strong>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right" for="name" > Nama <label style="color: red">*</label>
																	</label> 
																	<div class="col-xs-8">
																		<input type="text" id="nameU" name="nameU" style="width: 180px; " onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789. ', this)" placeholder="contoh: PT. SISCOM" autofocus autocomplete="off" required />
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right">Bidang Usaha</label>
																	<div class="col-xs-8">
																		<select style="width: 180px; height: 33px" id="selectBis" name="selectBis">
																			<option value="">--Pilih Bidang Usaha--</option>
																				<?php 
																					$qBis = "SELECT * FROM business WHERE stsrec = 'A' ORDER BY id DESC";
																					$rmB = mysql_query($qBis) or die(mysql_error());
																					while ($rowB = mysql_fetch_array($rmB)){
																				?>
																							<option value="<?php echo $rowB['id'] ?>" > <?php echo $rowB['name'] ?> </option>
																				<?php } ?>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right">No. Telp/Hp</label> 
																	<div class="col-xs-8">
																		<input type="text" id="hp" name="hp" style="width: 180px" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="15" placeholder="Nomor Handphone" autocomplete="off" />
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right">No. WA <label style="color: red">*</label>
																	</label> 
																	<div class="col-xs-8">
																		<input type="text" id="wa" name="wa" style="width: 180px" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="15" placeholder="Nomor Whatsapp" autofocus autocomplete="off" required />
																	</div>
																</div>

																<div class="form-group">
																	<label class="col-xs-4 control-label no-padding-right"> NPWP </label> 
																	<div class="col-xs-8">
																		<input type="text" id="npwp" name="npwp" style="width: 180px" maxlength="20" onKeyPress="return goodchars(event, '0123456789.-', this)" placeholder="NPWP" autocomplete="off" />
																	</div>
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style=" padding-left: 40px">
																
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> Periode Akuntansi</label> 
																	<div class="col-xs-7">
																		<select id="selectperiod" name="selectperiod" style="width: 180px; height: 34px">
																			<option value="01|12">Januari - Desember</option>
																			<option value="02|01">Februari - Januari</option>
																			<option value="03|02">Maret - Februari</option>
																			<option value="04|03">April - Maret</option>
																			<option value="05|04">Mei - April</option>
																			<option value="06|05">Juni - Mei</option>
																			<option value="07|06">Juli - Juni</option>
																			<option value="08|07">Agustus - Juli</option>
																			<option value="09|08">September - Agustus</option>
																			<option value="10|09">Oktober - September</option>
																			<option value="11|10">November - Oktober</option>
																			<option value="12|11">Desember - November</option>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> Alamat</label> 
																	<div class="col-xs-7">
																		<textarea id="address" name="address" rows="3" cols="22"></textarea>
																	</div>
																</div> 
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> </label>
																	<div class="col-xs-7">
																		<input type="text" id="kota" name="kota" style="width: 180px" placeholder="Kota/Kabupaten" autocomplete="off" />
																	</div>
																</div> 
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> </label>
																	<div class="col-xs-7">
																		<select id="selectProv" name="selectProv" style="width: 122px; height: 33px">
																				<option value="">--Provinsi--</option>
																					<?php 
																						$qprov = "SELECT * FROM prov WHERE stsrec = 'A' ORDER BY name ASC";
																						$rmp = mysql_query($qprov) or die(mysql_error());
																						while ($rowp = mysql_fetch_array($rmp)){
																					?>
																						<option value="<?php echo $rowp['code'] ?>" > <?php echo $rowp['name'] ?> </option>
																						<?php } ?>
																		</select> 
																		<input type="text" id="kodepos" name="kodepos" style="width: 50px" placeholder="K. Pos" onKeyPress="return goodchars(event, '0123456789', this)" autocomplete="off" />
																	</div>
																</div>  
																<div class="form-group">
																	<label class="col-xs-5 control-label no-padding-right"> </label>
																	<div class="col-xs-7">
																		<select id="selectNeg" name="selectNeg" style="width: 180px; height: 34px">
																			<?php 
																				$qNeg = "SELECT * FROM country WHERE stsrec = 'A' ORDER BY code DESC";
																				$rmN = mysql_query($qNeg) or die(mysql_error());
																				while ($rowN = mysql_fetch_array($rmN)){
																			?>
																						<option value="<?php echo $rowN['code'] ?>"> <?php echo $rowN['name'] ?> </option>
																			<?php } ?>
																		</select> 
																	</div>
																</div>  
															</div>
														</div>
													</form>
												</div>
												
												<!-- step 2 -->
												<div class="step-pane" data-step="2">
                                                	<br>
													<form class="form-horizontal" id="validation-form2" method="get">
														<fieldset>
															<div class="container col-xs-12">
																<div class="col-xs-6" style="float: left; padding-left: 10px">
																	<form>
																		<label for="name">Nama Database&nbsp;<label style="color: red">*</label>
																		
																		<input type="text" name="dbname" id="dbname" style="text-transform: lowercase;" placeholder="Nama Alias" maxlength="10" onKeyPress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789', this)" onFocus="cari2()" autofocus autocomplete="off" required />
																	</form>
																	<div><br>
																		<span style="font-size: 14px">Anda mengetahui SISCOM Online melalui:</span>
																			<div class="clearfix" style="font-size: 12px">
																				<div class="col-xs-12">
																					<div>
																						<label class="line-height-1 blue">
																							<input name="dari" id="agen" value="" type="radio" class="detail ace" checked />
																							<span class="lbl"> Agen penjualan (Marketing/Agent/Reseller)</span>
																							<br>
																							<div style="padding-left: 20px" >
																								<input style="text-transform: uppercase !important" type="text" id="selectAgen" name="agen" value="<?=$salesid?>" placeholder="Input kode agen" autocomplete="off" onkeyup="cari2()" required>
																								<label id="infoAgn" style="color: #ec6f6f; font-size: 12px">*Hubungi agen penjualan untuk mengetahui kode agen</label>
																							</div>
																						</label>
																						
																					</div>
																					<?php  
																						$query = mysql_query("SELECT s.id, s.name, s.remark, (SELECT COUNT(id) FROM `salesman` WHERE stsrec = 'A' AND parent_id = s.id) AS cnt FROM salesman s WHERE s.parent_id = '0' AND s.type = '3' AND s.stsrec = 'A' ORDER BY s.name");
																						while ($row = mysql_fetch_array($query)) {
																							$parent_id = $row['id'];
																							$combo = $row['cnt'];
																							$remark = $row['remark'];
																							$names = strtolower(str_replace(' ', '', $row['name']));
																					?>
																					<div>
																						<label class="line-height-1 blue">
																							<?php 
																							//id website siscom =  S0001
																								if ($combo > 0 && $row['id'] != 'S0001') {
																							 ?>
																								<div id="combo">
																									<input name="dari" id="<?=$names;?>" type="radio" class="ace" value="" />
																									<span class="lbl"> <?=$row['name'];?> </span>
																									<br>
																									<div style="padding-left: 20px" >
																										<select style="width: 180px" id="combobox" name="combobox">
																											<option value=""> --Pilih <?=$row['name'];?>--
																											</option>
																										<?php  
																											$query2 = mysql_query("SELECT * FROM salesman
																																WHERE parent_id = '$parent_id'");
																											while ($row2 = mysql_fetch_array($query2)) {
																												// if ($row2['id']>'0'?$selA="selected":$selA="");
																										?>
																											<option value="<?=$row2['id'];?>"><?=$row2['name'];?> 
																											</option>
																										<?php } ?>
																										</select>
																									</div>
																								</div>
																							<?php 
																								} 
																								else if ($remark == 1) {
																								?>
																								<div id="remarks">
																									<input name="dari" id="<?=$names;?>" type="radio" class="ace" value="<?=$row['id'];?>" />
																									<span class="lbl"> <?=$row['name'];?> </span>
																									<br>
																									<div style="padding-left: 20px" >
																										<input type="text" id="inputan" name="remark" placeholder="Masukkan nama teman anda" autocomplete="off">
																									</div>
																								</div>
																							<?php } 
																								else{

																									$selChecks = $salesid == $row['id'] ? "checked" : "";
																							?>
																								<input name="dari" id="<?=$names;?>" type="radio" class="ace" value="<?=$row['id'];?>" <?=$selChecks?> />
																								<span class="lbl"> <?=$row['name'];?> </span>
																								<div style="display: none;"></div>
																							<?php } ?>
																						</label>
																					</div>
																					<?php } ?>
																				</div>
								                              				</div>
						                              				</div>
						                              			</div>
						                              			<div class="col-xs-6" style="padding: 0px">
						                              				<div class="form-group">
																		<label class="col-xs-3 control-label no-padding-right" for="name" > Tanggal Mulai DB <label style="color: red">*</label>
																		</label> 
																		<div class="col-xs-9">
																			<div class="input-group">
																				<input class="form-control date-picker" id="beginDate" name="beginDate" type="text" autocomplete="off" data-date-format="dd-mm-yyyy" value="<?=$datenow?>" required/>
																				<span class="input-group-addon">
																					<i class="fa fa-calendar bigger-110"></i>
																				</span>
																			</div>
																		</div>
																	</div>
																	<div class="form-group" style="display: none;">
																		<label class="col-xs-3 control-label no-padding-right" for="name" > Versi <label style="color: red">*</label>
																		</label> 
																		<div class="col-xs-9">
																			<select id="selectVer" name="selectVer" style="width: 100%; height: 34px" autofocus required>
																				<!-- <option value="">--Pilih Versi--</option> -->
																				<?php $qver = "SELECT * FROM version WHERE stsrec = 'A' AND id = '3' ORDER BY id";
																					$rmver = mysql_query($qver) or die(mysql_error());
																					while ($rowV = mysql_fetch_array($rmver)){
																						
																				?>
																				<option value="<?php echo $rowV['id'] ?>" > <?php echo $rowV['name'] ?> </option>
																				<?php  }?>
																			</select>
																		</div>
																	</div>
																	
						                              			</div>
					                              			</div>
														</fieldset>
													</form>
												</div>
											</div>
										</div> 
										<div class="wizard-actions">
											<button class="btn btn-prev">
												<i class="ace-icon fa fa-arrow-left"></i>
												Kembali
											</button>

											<button class="btn btn-success btn-next" data-last="Finish" id="next">
												Lanjut
												<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
											</button>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script src="assets/js/jquery-2.1.4.min.js"></script>
		
		<script type="text/javascript">
			// if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
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

		<!-- datepicker -->
		<script src="assets/sweetalert/sweetalert2.all.min.js"></script>
		<script src="assets/js/bootstrap-datepicker.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			
			$('#modal-table #dbname').on('keyup', function(){
				var dbname = $('#modal-table #dbname').val()
				// console.log('dbname')
				// console.log(dbname+' -> '+dbname.length)
				var radioBtn = $('#modal-table input[type=radio')

				if (dbname != '' && radioBtn.is(':checked')) {
					$('#modal-table #next').attr('disabled', false)
				}

				if (dbname.length <= 0 ) {
					$('#modal-table #next').attr('disabled', true)
					// console.log('kosong')
				} else {
					$('#modal-table #next').attr('disabled', false)
				}
			})

			$('#modal-copy-master #dbname').on('blur', function(){
				var dbname = $('#modal-copy-master #dbname').val()
				var radioBtn = $('#modal-copy-master input[type=radio')

				if (dbname != '' && radioBtn.is(':checked')) {
					$('#modal-copy-master #next').attr('disabled', false)
				}
			})
		</script>
		<script type="text/javascript">

			jQuery(function($) {
				//jika modal-add versi starter & pro diselect
				
				var step3 		= $('#step3')[0];
				var dataStep3 	= $('#dataStep3')[0];

				var iStep3 		= 	'<li data-step="3" id="iStep3">'+
										'<span class="step">3</span>'+
										'<span class="title">Selesai</span>'+
									'</li>';
				var iDataStep3 	=	'<div class="step-pane" data-step="3" id="iDataStep3">'+
										'<div class="container col-xs-12">'+
											'<div class="row">'+
												'<div class="col-xs-12" style="padding-left: 10px">'+
													'<h4 class="lighter block green">Selesai</h4>'+
														'<div class="col-sm-12 justify">'+
														'<span>'+
														'</span><br><br>'+
														'</div> '+
														'<div class="col-xs-10" style="padding-left: 10px">'+
															'<span>'+
																
															'</span>'+
														'</div>'+
												'</div>'+
											'</div> '+
										'</div>'+
									'</div>';

				$('#modal-table #selectVer').on('change', function(){
					
					if($('#modal-table #selectVer option:selected').val() != 3){
						
						$('#modal-table #step3').remove();
						$('#modal-table #dataStep3').remove();

						// $('#modal-table .steps').append(iStep3);
						// $('#modal-table #isiStep').append(iDataStep3);
						$('#modal-table #fuelux-wizard-container').addClass('complete');

						if($('.steps').find('#iStep3').length==0 && $('#isiStep').find('#iDataStep3').length==0){
							$('#modal-table .steps').append(iStep3);
							$('#modal-table #isiStep').append(iDataStep3);
						}
					}
					else{
						$('#modal-table .steps').append(step3);
						$('#modal-table #isiStep').append(dataStep3);

						if($('.steps').find('#iStep3').length!=0 && $('#isiStep').find('#iDataStep3').length!=0){
							$('#modal-table #iStep3').remove();
							$('#modal-table #iDataStep3').remove();
						}
						$('#modal-table #fuelux-wizard-container').removeClass('complete');
					}
				})
			
				$('[data-rel=tooltip]').tooltip();
			
				$('.select2').css('width','200px').select2({allowClear:true})
				.on('change', function(){
					$(this).closest('form').validate().element($(this));
				})
						
				var $validation = true;
				$('#modal-table #fuelux-wizard-container')
				.ace_wizard({
					//step: 2 //optional argument. wizard will jump to step "2" at first
					//buttons: '.wizard-actions:eq(0)'
				})
				.on('actionclicked.fu.wizard' , function(e, info){
					if(info.step == 1 && $validation) {
						if(!$('#modal-table #validation-form1').valid()) e.preventDefault();
					}
					else if(info.step == 2 && $validation) {
						if(!$('#modal-table #validation-form2').valid()) e.preventDefault();
					}
				})
				.on('finished.fu.wizard', function(e) {
					event.preventDefault();
					var auto = $('#auto').prop('checked') == true ? 'Y' : 'N';
					var data = $('#modal-table #validation-form1').serialize() + '&' +  $('#modal-table #validation-form2').serialize()+'&auto='+auto;

					// tanda
					$('.lds-dual-ring').css('display', 'block');

					$.ajax({
						type: 'POST',
						url: "action.php",
						dataType: 'json',
						data: data,
						success:function(record) {
							if (record.status == 'success') {
								// alert(record.status);
								$('.tampildata').load("manage.php");
								$('#judul1').remove();	
								bootbox.dialog({
									message: "Terimakasih! Data anda berhasil disimpan.", 
									buttons: {
										"success" : {
											"label" : "OK",
											"className" : "btn-sm btn-primary"

										}
									}
								});
								$("#modal-table").modal('hide');
								setTimeout(function() {
									location.reload();
								},5000)
								// $("#modal-table").modal('hide');
								// location.reload();

							}
							// else if (record.status == 'gagal'){
							// 	// alert(record.status);
							// 	bootbox.dialog({
							// 		message: "Maaf! Perusahaan anda sudah terdaftar sebelumnya.", 
							// 		buttons: {
							// 			"fail" : {
							// 				"label" : "OK",
							// 				"className" : "btn-sm btn-danger"

							// 			}
							// 		}
							// 	});

							// 	var wizard = $('#modal-table #fuelux-wizard-container').data('fu.wizard')
							// 	wizard.currentStep = 1;
							// 	wizard.setState();
							// }
							else if (record.status == 'gagal1'){
								// alert(record.status);
								bootbox.dialog({
									message: "Maaf! Nama database sudah ada.", 
									buttons: {
										"fail" : {
											"label" : "OK",
											"className" : "btn-sm btn-danger"

										}
									}
								});

								var wizard = $('#modal-table #fuelux-wizard-container').data('fu.wizard')
								wizard.currentStep = 2;
								wizard.setState();
							}
						}

					})

					.done(function(){
						$('.lds-dual-ring').css('display', 'none');
					})

					var cust_id = '<?=$_SESSION['custID']?>';
					var email = '<?=$_SESSION['custEmail']?>';
					var order_id = $('#modal-data #order_id').val();

					console.log(cust_id)
					console.log(email)

					$.ajax({
						type : 'POST',
						url : 'action.php',
						// dataType: 'json',
						data : {action: 'checkTrial', cust_id: cust_id, email: email},
						success:function (res) {
							console.log(res)
							$('.tampildata').load("manage.php");
							$('#judul1').remove();	
							bootbox.dialog({
								message: "Data anda berhasil disimpan.", 
							});
							$("#modal-table").modal('hide');
							setTimeout(function() {
								location.reload();
							},5000)
						}
					})


				})
				.on('stepclick.fu.wizard', function(e){
					//e.preventDefault();//this will prevent clicking and selecting steps
				});

				$('#modal-table').on('hidden.bs.modal', function(){
					location.reload();
					$('label.error').remove();
					$('input[type=text]').val('');
					$('input[type=number]').val('');
					$('input[type=email]').val('');
					$('input[type=radio]').attr('selected', false);
					// $('#agen').attr('selected', true);
					$('select[value=""]').prop('selected', true);
					$('textarea').val('');
					// $('#modal-table #agen').attr('checked', true);
					$('#modal-table #infoAgn').text('*Hubungi agen penjualan untuk mengetahui kode agen').css('color','#ec6f6f');
					var wizard = $('#modal-table #fuelux-wizard-container').data('fu.wizard')
					wizard.currentStep = 1;
					wizard.setState();
				})
			
				//jump to a step
				/**
				var wizard = $('#fuelux-wizard-container').data('fu.wizard')
				wizard.currentStep = 3;
				wizard.setState();
				*/
			
				//determine selected step
				//wizard.selectedItem().step
			
			
			
				//hide or show the other form which requires validation
				//this is for demo only, you usullay want just one form in your application
				// $('#skip-validation').removeAttr('checked').on('click', function(){
				// 	$validation = this.checked;
				// 	if(this.checked) {
				// 		$('#sample-form').hide();
				// 		$('#validation-form1').removeClass('hide');
				// 	}
				// 	else {
				// 		$('#validation-form1').addClass('hide');
				// 		$('#sample-form').show();
				// 	}
				// })
			
			
			
				//documentation : http://docs.jquery.com/Plugins/Validation/validate
			
			
				$.mask.definitions['~']='[+-]';
				$('#phone').mask('(999) 999-9999');
			
				jQuery.validator.addMethod("phone", function (value, element) {
					return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
				}, "Enter a valid phone number.");
			
				$('#modal-table #validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						email: {
							required: true,
							email:true
						},
						password: {
							required: true,
							minlength: 5
						},
						password2: {
							required: true,
							minlength: 5,
							equalTo: "#password"
						},
						name: {
							required: true,
							text: 'required'
						},
						phone: {
							required: true,
							phone: 'required'
						},
						url: {
							required: true,
							url: true
						},
						comment: {
							required: true
						},
						state: {
							required: true
						},
						platform: {
							required: true
						},
						subscription: {
							required: true
						},
						gender: {
							required: true,
						},
						agree: {
							required: true,
						}
					},
			
					messages: {
						email: {
							required: "Please provide a valid email.",
							email: "Please provide a valid email."
						},
						password: {
							required: "Please specify a password.",
							minlength: "Please specify a secure password."
						},
						name: {
							required: "Nama tidak boleh kosong."
						},
						state: "Please choose state",
						subscription: "Please choose at least one option",
						gender: "Please choose gender",
						agree: "Please accept our policy"
					},
					
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});
			
				$('#modal-table #modal-wizard-container').ace_wizard();
				$('#modal-table #modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
				
				/**
				$('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
					$(this).closest('form').validate().element($(this));
				});
				
				$('#mychosen').chosen().on('change', function(ev) {
					$(this).closest('form').validate().element($(this));
				});
				*/
				
				$(document).one('ajaxloadstart.page', function(e) {
					//in ajax mode, remove remaining elements before leaving page
					$('[class*=select2]').remove();
				});

	// ________script modal edit___________

				var $validation = true;
				$('#modal-edit #fuelux-wizard-container')
				.ace_wizard({
					//step: 2 //optional argument. wizard will jump to step "2" at first
					//buttons: '.wizard-actions:eq(0)'
				})
				.on('actionclicked.fu.wizard' , function(e, info){
					if(info.step == 1 && $validation) {
						if(!$('#modal-edit #validation-form1').valid()) e.preventDefault();
					}else if(info.step == 2 && $validation) { 
						if(!$('#modal-edit #validation-form2').valid())e.preventDefault();
					}	
				})
				.on('finished.fu.wizard', function(e) {
					event.preventDefault();
					var data = $('#modal-edit #validation-form1').serialize() + '&' +  $('#modal-edit #validation-form2').serialize() + '&action=edit'; 
					$.ajax({
						type: 'POST',
						url: "action.php",
						dataType: 'json',
						data: data,
						success:function(record) {
							if (record.status == 'successedit') {
								// alert(record.status);
								$('.tampildata').load("manage.php");
								$('#modal-edit #judul1').hide();	
								bootbox.dialog({
									message: "Terimakasih! Data anda berhasil diupdate.", 
									buttons: {
										"success" : {
											"label" : "OK",
											"className" : "btn-sm btn-primary"
										}
									}
								});

								$("#modal-edit").modal('hide');
							}
							else if (record.status == 'gagaledit'){
								// alert(record.status);
								bootbox.dialog({
									message: "Update data gagal.", 
									buttons: {
										"fail" : {
											"label" : "OK",
											"className" : "btn-sm btn-danger"

										}
									}
								});

								var wizard = $('#modal-edit #fuelux-wizard-container').data('fu.wizard')
								wizard.currentStep = 1;
								wizard.setState();
							}
						}

					})
				})
				.on('stepclick.fu.wizard', function(e){
					//e.preventDefault();//this will prevent clicking and selecting steps
				});

				$('#modal-edit').on('hidden.bs.modal', function(){
					location.reload();
					
					$('input[type=text]').val('');
					$('input[type=number]').val('');
					$('input[type=email]').val('');
					$('select[value=""]').prop('selected', true);
					$('textarea').val('');
					$('#modal-table #agen').attr('checked', true);
					$('#modal-table #infoAgn').text('*Hubungi agen penjualan untuk mengetahui kode agen').css('color','#ec6f6f');
					var wizard = $('#modal-edit #fuelux-wizard-container').data('fu.wizard')
					wizard.currentStep = 1;
					wizard.setState();
				})
				
			
				//jump to a step
				/**
				var wizard = $('#fuelux-wizard-container').data('fu.wizard')
				wizard.currentStep = 3;
				wizard.setState();
				*/
			
				//determine selected step
				//wizard.selectedItem().step
			
			
			
				//hide or show the other form which requires validation
				//this is for demo only, you usullay want just one form in your application
				// $('#skip-validation').removeAttr('checked').on('click', function(){
				// 	$validation = this.checked;
				// 	if(this.checked) {
				// 		$('#sample-form').hide();
				// 		$('#validation-form1').removeClass('hide');
				// 	}
				// 	else {
				// 		$('#validation-form1').addClass('hide');
				// 		$('#sample-form').show();
				// 	}
				// })
			
			
			
				//documentation : http://docs.jquery.com/Plugins/Validation/validate
			
			
				$.mask.definitions['~']='[+-]';
				$('#phone').mask('(999) 999-9999');
			
				jQuery.validator.addMethod("phone", function (value, element) {
					return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
				}, "Enter a valid phone number.");
			
				$('#modal-edit #validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						email: {
							required: true,
							email:true
						},
						password: {
							required: true,
							minlength: 5
						},
						password2: {
							required: true,
							minlength: 5,
							equalTo: "#password"
						},
						name: {
							required: true,
							text: 'required'
						},
						phone: {
							required: true,
							phone: 'required'
						},
						url: {
							required: true,
							url: true
						},
						comment: {
							required: true
						},
						state: {
							required: true
						},
						platform: {
							required: true
						},
						subscription: {
							required: true
						},
						gender: {
							required: true,
						},
						agree: {
							required: true,
						}
					},
			
					messages: {
						email: {
							required: "Please provide a valid email.",
							email: "Please provide a valid email."
						},
						password: {
							required: "Please specify a password.",
							minlength: "Please specify a secure password."
						},
						name: {
							required: "Nama tidak boleh kosong."
						},
						state: "Please choose state",
						subscription: "Please choose at least one option",
						gender: "Please choose gender",
						agree: "Please accept our policy"
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});

				$('#modal-edit #modal-wizard-container').ace_wizard();
				$('#modal-edit #modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
				
				
				/**
				$('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
					$(this).closest('form').validate().element($(this));
				});
				
				$('#mychosen').chosen().on('change', function(ev) {
					$(this).closest('form').validate().element($(this));
				});
				*/			
				
				$(document).one('ajaxloadstart.page', function(e) {
					//in ajax mode, remove remaining elements before leaving page
					$('[class*=select2]').remove();
				});


			// scipt Modal Copy Master
				var $validation = true;
				$('#modal-copy-master #fuelux-wizard-container')
				.ace_wizard({
					//step: 2 //optional argument. wizard will jump to step "2" at first
					//buttons: '.wizard-actions:eq(0)'
				})
				.on('actionclicked.fu.wizard' , function(e, info){
					if(info.step == 1 && $validation) {
						if(!$('#modal-copy-master #validation-form1').valid()) e.preventDefault();
					}
					else if(info.step == 2 && $validation) {
						if(!$('#modal-copy-master #validation-form2').valid()) e.preventDefault();
					}
				})
				.on('finished.fu.wizard', function(e) { //saat tombol finish di klik
					event.preventDefault();
					var data = $('#modal-copy-master #validation-form1').serialize() + '&' +  $('#modal-copy-master #validation-form2').serialize() +'&post=copy-master'; 

					$('.lds-dual-ring').css('display', 'block');

					$.ajax({
						type: 'POST',
						url: "action.php",
						dataType: 'json',
						data: data,
						success:function(record) {
							if (record.status == 'success') {
								// alert(record.status);
								$('.tampildata').load("manage.php");
								$('#judul1').remove();	
								bootbox.dialog({
									message: "Terimakasih! Data anda berhasil disimpan.", 
									buttons: {
										"success" : {
											"label" : "OK",
											"className" : "btn-sm btn-primary"

										}
									}
								});
								$("#modal-copy-master").modal('hide');
								setTimeout(function() {
									location.reload();
								},5000)

							}
							else if (record.status == 'gagal'){
								// alert(record.status);
								bootbox.dialog({
									message: "Maaf! Perusahaan anda sudah terdaftar sebelumnya.", 
									buttons: {
										"fail" : {
											"label" : "OK",
											"className" : "btn-sm btn-danger"

										}
									}
								});

								var wizard = $('#modal-copy-master #fuelux-wizard-container').data('fu.wizard')
								wizard.currentStep = 1;
								wizard.setState();
							}
							else if (record.status == 'gagal1'){
								// alert(record.status);
								bootbox.dialog({
									message: "Maaf! Nama database sudah ada.", 
									buttons: {
										"fail" : {
											"label" : "OK",
											"className" : "btn-sm btn-danger"

										}
									}
								});

								var wizard = $('#modal-copy-master #fuelux-wizard-container').data('fu.wizard')
								wizard.currentStep = 2;
								wizard.setState();
							}
						}

					}).done(function(){
						$('.lds-dual-ring').css('display', 'none');
					})
				})
				.on('stepclick.fu.wizard', function(e){
					//e.preventDefault();//this will prevent clicking and selecting steps
				});

				$('#modal-copy-master').on('hidden.bs.modal', function(){
					location.reload();
					$('#modal-copy-master #infoAgn').text('*Hubungi agen penjualan untuk mengetahui kode agen').css('color','#ec6f6f');
					var wizard = $('#modal-copy-master #fuelux-wizard-container').data('fu.wizard')
					wizard.currentStep = 1;
					wizard.setState();
				})

				$.mask.definitions['~']='[+-]';
				$('#phone').mask('(999) 999-9999');
			
				jQuery.validator.addMethod("phone", function (value, element) {
					return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
				}, "Enter a valid phone number.");
			
				$('#modal-copy-master #validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						email: {
							required: true,
							email:true
						},
						password: {
							required: true,
							minlength: 5
						},
						password2: {
							required: true,
							minlength: 5,
							equalTo: "#password"
						},
						name: {
							required: true,
							text: 'required'
						},
						phone: {
							required: true,
							phone: 'required'
						},
						url: {
							required: true,
							url: true
						},
						comment: {
							required: true
						},
						state: {
							required: true
						},
						platform: {
							required: true
						},
						subscription: {
							required: true
						},
						gender: {
							required: true,
						},
						agree: {
							required: true,
						}
					},
			
					messages: {
						email: {
							required: "Please provide a valid email.",
							email: "Please provide a valid email."
						},
						password: {
							required: "Please specify a password.",
							minlength: "Please specify a secure password."
						},
						name: {
							required: "Nama tidak boleh kosong."
						},
						state: "Please choose state",
						subscription: "Please choose at least one option",
						gender: "Please choose gender",
						agree: "Please accept our policy"
					},
					
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});
			
				$('#modal-copy-master #modal-wizard-container').ace_wizard();
				$('#modal-copy-master #modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
				
				$(document).one('ajaxloadstart.page', function(e) {
					//in ajax mode, remove remaining elements before leaving page
					$('[class*=select2]').remove();
				});
			})
		</script>
<!-- _________script radio button kode agen______ -->
		<script type='text/javascript'>
			
			$('#modal-table #combo #combobox').attr('readonly', true).hide();
			$('#modal-table #remarks #inputan').attr('readonly', true).hide();

			$('input[type=radio]').on('click', function(){
				let agen = $('#modal-table #agen');
	    			if (agen.is(':checked')){
						$('#modal-table #selectAgen').removeAttr('readonly');//enable input
						$('#modal-table #selectAgen').slideDown("fast");
						$('#modal-table #selectAgen').attr('required', true);
						$('#modal-table #infoAgn').removeAttr('disabled').slideDown("fast"); //enable alert*
	    			} else{
	    				$('#modal-table #selectAgen').attr('required', false).slideUp("fast"); //disable required
	    				$('#modal-table #selectAgen').attr('readonly', true);
	    				$('#modal-table #selectAgen-error').hide();
	    				$('#modal-table #infoAgn').slideUp("fast"); //disable alert*
	    				$('#modal-table #selectAgen').val('');
						$('#modal-table #infoAgn').text('*Hubungi agen penjualan untuk mengetahui kode agen').css('color','#ec6f6f');
						$('#modal-table #next').prop('disabled', false); // disable btn next
	    			}
    			
				let combobox = $('#modal-table #combo .ace');
    				if (combobox.is(':checked')){
						$('#modal-table #combo #combobox').removeAttr('readonly').slideDown("fast"); //enable input
						$('#modal-table #combo #combobox').attr('required', true);
					} else {
						$('#modal-table #combo #combobox').attr('readonly', true).slideUp("fast"); //disable input	
						$('#modal-table #combo #combobox').removeAttr('required');
						$('#modal-table #combo #combobox-error').hide();
	    			}

	    		let inputan = $('#modal-table #remarks .ace');
    				if (inputan.is(':checked')){
						$('#modal-table #remarks #inputan').removeAttr('readonly').slideDown("fast"); //enable input
						$('#modal-table #remarks #inputan').attr('required', true);
					} else {
						$('#modal-table #remarks #inputan').attr('readonly', true).slideUp("fast"); //disable input
						$('#modal-table #remarks #inputan').removeAttr('required');
						$('#modal-table #remarks #inputan-error').hide();
	    			}	
			});

		//Modal copy master radio button kode agen 
			$('#modal-copy-master #combo #combobox').attr('readonly', true).hide();
			$('#modal-copy-master #remarks #inputan').attr('readonly', true).hide();

			$('input[type=radio]').on('click', function(){
				let agen = $('#modal-copy-master #agen');
	    			if (agen.is(':checked')){
						$('#modal-copy-master #selectAgen').removeAttr('readonly');//enable input
						$('#modal-copy-master #selectAgen').slideDown("fast");
						$('#modal-copy-master #selectAgen').attr('required', true);
						$('#modal-copy-master #infoAgn').removeAttr('disabled').slideDown("fast"); //enable alert*
	    			} else{
	    				$('#modal-copy-master #selectAgen').attr('required', false).slideUp("fast"); //disable required
	    				$('#modal-copy-master #selectAgen').attr('readonly', true);
	    				$('#modal-copy-master #selectAgen-error').hide();
	    				$('#modal-copy-master #infoAgn').slideUp("fast"); //disable alert*
	    				$('#modal-copy-master #selectAgen').val('');
						$('#modal-copy-master #infoAgn').text('*Hubungi agen penjualan untuk mengetahui kode agen').css('color','#ec6f6f');
						$('#modal-copy-master #next').prop('disabled', false); // disable btn next
	    			}
    			
				let combobox = $('#modal-copy-master #combo .ace');
    				if (combobox.is(':checked')){
						$('#modal-copy-master #combo #combobox').removeAttr('readonly').slideDown("fast"); //enable input
						$('#modal-copy-master #combo #combobox').attr('required', true);
					} else {
						$('#modal-copy-master #combo #combobox').attr('readonly', true).slideUp("fast"); //disable input	
						$('#modal-copy-master #combo #combobox').removeAttr('required');
						$('#modal-copy-master #combo #combobox-error').hide();
	    			}

	    		let inputan = $('#modal-copy-master #remarks .ace');
    				if (inputan.is(':checked')){
						$('#modal-copy-master #remarks #inputan').removeAttr('readonly').slideDown("fast"); //enable input
						$('#modal-copy-master #remarks #inputan').attr('required', true);
					} else {
						$('#modal-copy-master #remarks #inputan').attr('readonly', true).slideUp("fast"); //disable input
						$('#modal-copy-master #remarks #inputan').removeAttr('required');
						$('#modal-copy-master #remarks #inputan-error').hide();
	    			}	
			});


			function tampil(dbname, cust_id, used){
				$('#modal-data #title').text('Info Database');
				$('#modal-data .modal-footer').empty();
				$.ajax({
					url:'action.php',
					type:'post',
					data: {dbname: dbname, custID:cust_id, action:'tampil'},
					dataType: 'json',
					success: function(record){
						if (record.status == 'successTampil') {
							var end_date = record.data[0]['end_date'];
							var dateNow = Date.now();
							var versi = record.data[0]['ver_id'];
							
							$('#modal-data #simple-table tbody').empty();
							$('#modal-data #simple-table thead').empty();

							if (record.used == 'tenggang') {
								var hapus = '<th><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>Tanggal Hapus Database'+
										'</th>';
							}

							var header = '<tr>'+
										'<th width="30px">No</th>'+
										'<th>Email Pengguna</th>'+
										'<th>Level User</th>'+
										'<th>Nama Database</th>'+
										'<th>Nama Perusahaan</th>'+
										'<th><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>Tanggal Berakhir'+
										'</th>'+hapus+
									'</tr>';

							var html = '', i=1,dbname;
							record.data.forEach(function(index){
								/*if (index['billing_send']=='Y') {
									level = 'Owner';
								}else{
									level = (index['level_user']=='O') ? 'Operator' : 'Administrator';
								}*/
								if(index['ctype'] == 'C') {
									level = 'Owner';
								} else if(index['ctype'] == 'A') {
									level = 'Administrator';
								} else if(index['ctype'] == 'O') {
									level = 'Operator';
								}

								html+= '<tr>'+
										'<td style="vertical-align:middle">'+i+'</td>'+
										'<td style="vertical-align:middle">'+index['email']+'</td>'+
										'<td style="vertical-align:middle">'+level+'</td>'+
										'<td style="vertical-align:middle">'+index['dbname']+'</td>'+
										'<td style="vertical-align:middle">'+index['companyname']+'</td>';
								if (record.used == 'tenggang') {
									html += '<td style="vertical-align:middle"><span class="btn btn-warning btn-sm">'+record.end_date+'</span></td>'+
										'<td style="vertical-align:middle"><span class="btn btn-danger btn-sm">'+record.expired_date+'</span></td>'+
										'</tr>';
								}
								else{
									html+=  '<td style="vertical-align:middle">'+record.end_date+'</td></tr>';
								}
								i++;
								dbname = index['dbname'];
							})

							$('#modal-data #simple-table thead').append(header);
							$('#modal-data #simple-table tbody').append(html);

							$('#message').empty();
							//menampilkan pesan apabila sudah dalam masa tenggang
							if (record.used == 'tenggang') {

								html='<div class="alert alert-danger alert-dismissible" role="alert">'+
										'<button type="button" class="close" style="font-size: 25px; font-weight:bold" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times</span></button>'+
										'<strong>Warning!!&emsp;</strong>Masa pemakaian Anda sudah berakhir. Anda sedang berada dalam <strong>masa tenggang</strong>. Jika ingin melanjutkan, mohon selesaikan tagihan Anda terlebih dahulu .... &emsp;&emsp;&emsp;'+
									'</div>';
								$('#message').append(html);
								/*$('#modal-data .modal-footer').append('<a href="https://<?=$host;?>/siserp/module/reg/pages/info_tagihan.php?dbname='+dbname+'&versi='+versi+'" class="btn btn-lg btn-success" id="bayar">Info tagihan</a>');*/
								$('#modal-data .modal-footer').append('<a href="<?=$abs;?>/reg/pages/info_tagihan.php?dbname='+dbname+'&versi='+versi+'" class="btn btn-lg btn-success" id="bayar">Info tagihan</a>');
							}
						}
					}
				})
			}

			function tampilcabang(dbname, cust_id,versi, enddate, used, order_id){
				// alert(versi)
				$('#modal-data #dbname').val(dbname);
				$('#modal-data #order_id').val(order_id);
				$('#modal-data #versi').val(versi);
				$('#modal-data #enddate').val(enddate);
				$('#modal-data #used').val(used); // T,D,R
				$('#modal-data #title').text('Info Cabang');
				$('#modal-data .modal-footer').empty();
				$.ajax({
					url:'action.php',
					type:'post',
					data: {dbname: dbname, custID:cust_id, versi: versi, used : used, order_id : order_id, action:'tampilcabang'},
					dataType: 'json',
					success: function(record){
						if (record.status == 'success') {

							$('#modal-data #simple-table tbody').empty();
							$('#modal-data #simple-table thead').empty();

							var cabangPending = record.data.pending_branch ? record.data.pending_branch : 0;
							var message = cabangPending != 0 ? "  (Silakan melakukan pembayaran)" : "";
							var colspan = cabangPending != 0 ? "3" : "5";

							var header = '<tr style="background: transparent;">'+    
    										'<th colspan="'+colspan+'" style="border-left-color:transparent;border-right-color:transparent;">Total Maks. Cabang : '+record.data.maxCabang+'</th>';
							if(cabangPending != 0){ 
    							header += '<th colspan="2" style="border-left-color:transparent;border-right-color:transparent; text-align: right; color: #a94442;">Cabang Pending  :  '+cabangPending + message+'</th>'+
											'</tr>';
							}
								header += '<tr>'+
											'<th width="30px">No</th>'+
											'<th width="50px">Kode Cabang</th>'+
											'<th>Cabang</th>'+
											'<th width="50px">Kode Gudang</th>'+
											'<th>Gudang</th>'+
										'</tr>';

							var html = '', i=1, kodecb, namacb, kodegd, namagd;
							record.data.cabangInfo.forEach(function(index){
								kodecb = index['kodecb']
								namacb = index['namacb']
								kodegd = index['kodegd'] == null ? '' : index['kodegd']
								namagd = index['namagd'] == null ? '' : index['namagd']
								html+= '<tr>'+
										'<td style="vertical-align:middle">'+i+'</td>'+
										'<td style="vertical-align:middle">'+kodecb+'</td>'+
										'<td style="vertical-align:middle">'+namacb+'</td>'+
										'<td style="vertical-align:middle">'+kodegd+'</td>'+
										'<td style="vertical-align:middle">'+namagd+'</td>';
								i += 1;
							})

							var footer = '<button class="btn btn-sm btn-primary" id="addBranch" onclick="tambahCabang()">Tambah Cabang</button>';

							$('#modal-data #simple-table thead').append(header);
							$('#modal-data #simple-table tbody').append(html);
							$('#modal-data .modal-footer').append(footer);

							$('#message').empty();
							var price = parseFloat(record.data.price);
							var ppn = 10;
							var totprice = price + (price * ppn / 100);
								totprice = parseFloat(totprice).toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")
							message='<div class="alert alert-danger alert-dismissible" role="alert">'+
										'<button type="button" class="close" style="font-size: 25px; font-weight:bold" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times</span></button>'+
										'<strong>Info&emsp;</strong><small>Penambahan cabang dikenakan biaya administrasi sebesar Rp. '+totprice+' / bulan (sudah termasuk PPN '+ppn+'%)</small>'+
									'</div>';
							$('#message').append(message);
							
						}
					}
				})
			}

			function tambahCabang(){
				// swal.fire(
				// 	'Oops!',
				// 	'This feature under construction.',
				// 	'warning'
				// )
				var used = $('#modal-data #used').val()
				if(used == 'T'){
					swal.fire(
						'Info!',
						'Menu ini tidak tersedia untuk versi Trial.',
						'warning'
					)
				}
				else{
					$('#modal-data #tambahCabang').focus()
					$('#addBranch').attr('disabled',true)
					$('#branchContainer').show();
				}
			}

			function cancelBranch(){
				$('#addBranch').attr('disabled',false)
				$('#branchContainer').hide();
			}

			function submitBranch(){
				var nilai = $('#tambahCabang').val()
				var dbname = $('#modal-data #dbname').val()
				var enddate = $('#modal-data #enddate').val()
				var versi = $('#modal-data #versi').val()
				var used = $('#modal-data #used').val()
				var cust_id = <?=$_SESSION['custID']?>;
				var order_id = $('#modal-data #order_id').val()

				if(nilai == 0 || !nilai){
					swal.fire(
						'Warning!',
						'Jumlah cabang wajib diisi.',
						'warning'
					)
				}
				else{
					Swal.fire({
						title : 'Apakah Anda yakin?',
						html : '<h4>Penambahan cabang akan dikenakan biaya administrasi.</h4>',
						type : 'warning',
						position: 'top',
						showCancelButton : true,
						confirmButtonColor : '#3085d6',
						cancelButtonColor : '#d33',
						confirmButtonText: 'Ya',
						cancelButtonText :'Batal' 
					}).then((result) => {
						if(result.value){
							$('.lds-dual-ring').css('display', 'block');
						
							$.ajax({
								type:'post',
								dataType:'json',
								url: 'action.php',
								data: {nilai : nilai, dbname : dbname, versi : versi, enddate: enddate, used: used, action : 'tambahCabang'},
								success: function(record){
									if (record.status=='success') {
										swal.fire(
											'Success!',
											'Cabang berhasil ditambahkan. Total cabang akan ditambahkan setelah dilakukan pembayaran tagihan. Silahkan cek email "<?=$_SESSION['custEmail']?>" untuk info tagihan.',
											'success'
										)

										tampilcabang(dbname, cust_id,versi, enddate, used, order_id)
										$('#addBranch').attr('disabled',false)
										$('#branchContainer').hide();
										$('#modal-data input').val('');
									}
									else{
										swal.fire(
											'Gagal!',
											'Cabang gagal ditambahkan. Silahkan ulangi kembali.',
											'warning'
										)
									}
								}
							}).done(function(){
								$('.lds-dual-ring').css('display', 'none');
							})
						}
					});
				}
			}


			
			// script radio button edit database
			// $('#modal-edit #selectSosmed').hide();
			// $('#modal-edit #selectTmn').hide();
			// $('input[type=radio]').on('click', function(){
			// 	let agen = $('#modal-edit #dari');
	  //   			if (agen.is(':checked')){
			// 			$('#modal-edit #selectAgen').removeAttr('disabled').slideDown("fast"); //enable input
			// 			$('#modal-edit #infoAgn').removeAttr('disabled').slideDown("fast"); //enable input
	  //   			} else{
	  //   				$('#modal-edit #selectAgen').attr('required', false).slideUp("fast"); //disable input
	  //   				$('#modal-edit #infoAgn').attr('disabled', true).slideUp("fast"); //disable input
	  //   			}
    			
			// 	let sosmed = $('#modal-edit #sosmed');
   //  				if (sosmed.is(':checked')){
			// 			$('#modal-edit #selectSosmed').removeAttr('disabled').slideDown("fast"); //enable input
			// 		} else {
			// 			$('#modal-edit #selectSosmed').attr('disabled', true).slideUp("fast"); //disable input	
	  //   			}

	  //   		let teman = $('#modal-edit #teman');
   //  				if (teman.is(':checked')){
			// 			$('#modal-edit #selectTmn').removeAttr('disabled').slideDown("fast"); //enable input
			// 		} else {
			// 			$('#modal-edit #selectTmn').attr('disabled', true).slideUp("fast"); //disable input	
	  //   			}	
			// });

			function edit(dbname, cust_id){

				// $(window).load(function) {
				$('#modal-edit').modal('show');
				$.ajax({
					url:'action.php',
					type:'post',
					data: {dbname:dbname, custID:cust_id, action:'tampil'},
					dataType: 'json',
					success: function(record){
						if (record.status == 'successTampil') {
							$('#modal-data #simple-table tbody').empty();
							var html = '';

							$('#modal-edit #company_id').val(record.data[0]['companyid']);
							$('#modal-edit #nameU').val(record.data[0]['companyname']).attr('readonly', 'true');
							$('#modal-edit #selectBis option[value="'+record.data[0]['business_id']+'"]').prop('selected', true);
							$('#modal-edit #hp').val(record.data[0]['phone_no']);
							$('#modal-edit #wa').val(record.data[0]['wa_no']);
							$('#modal-edit #emailU').val(record.data[0]['email']).attr('readonly', 'true');
							$('#modal-edit #npwp').val(record.data[0]['npwp_no']);
							$('#modal-edit #selectperiod').attr('disabled', 'true');
							$('#modal-edit #address').val(record.data[0]['address']);
							$('#modal-edit #kota').val(record.data[0]['city']);
							$('#modal-edit #selectProv option[value="'+record.data[0]['prov_code']+'"]').prop('selected', true);
							$('#modal-edit #kodepos').val(record.data[0]['zip_code']);
							$('#modal-edit #selectNeg option[value="'+record.data[0]['country_code']+'"]').prop('selected', true);
							$('#modal-edit #dbname').val(record.data[0]['dbname']).attr('readonly', 'true');
							$('#modal-edit #beginDate').val(record.data[0]['db_date']).attr('readonly', 'true');
							$('#modal-edit #selectVer option[value="'+record.data[0]['ver_id']+'"]').prop('selected', true);
							$('#modal-edit #selectVer').attr('disabled', 'true');
							// $('#modal-edit #1 option[value="'+record.data[0]['']+'"]').prop('selected', true);
							// $('#modal-edit #1').attr('disabled', 'true');
							$('#modal-edit #selectAgen').val(record.sales_code).attr('disabled', 'true');
							$('#modal-edit #remarks #inputan').val(record.data[0]['salesman_remark']).attr('readonly', 'true');
							$('#modal-edit #combo #combobox').val(record.data[0]['salesman_id']).attr('disabled', 'true');
							$('#modal-edit #salesmanID').val(record.data[0]['salesman_id']);

							$("#modal-edit input[name=dari][value="+record.data[0]['salesman_id']+"]").prop('checked', true);
							$('#modal-edit #infoAgn').text(record.sales_name);
							
							if(record.data[0]['salesman_id'].substring(0,1) != 'S') {
								$("#modal-edit input[name=dari][value=agen]").attr('checked', 'checked');
								$('#modal-edit #selectAgen').val(record.data[0]['salesman_id']).attr('style', 'display: text');
								$('#modal-edit #selectAgen').val(record.data[0]['salesman_id']).attr('disabled', 'true');
								$('#modal-edit #combo #combobox').val(record.data[0]['salesman_id']).attr('disabled', 'true');
								$("#modal-edit input[name=dari][value=referensi]").attr('style', 'display: none');
								$('#modal-edit #inputan').attr('style', 'display: none');
								$('#modal-edit #combobox').attr('style', 'display: none');
							} else {
								$('#modal-edit #selectAgen').attr('style', 'display: none');
								$('#modal-edit #infoAgn').attr('style', 'display: none');
								if(record.data[0]['salesman_remark'] == '') {
									if(record.data[0]['parent_id'] == '0') {
										$('#modal-edit #combobox').attr('style', 'display: none');
										$('#modal-edit #inputan').attr('style', 'display: none');
										$("#modal-edit input[name=dari][value=record.sales_name]").attr('checked', 'checked');
									} else {
										$("#modal-edit input[name=dari][value=combo]").attr('checked', 'checked');
										$('#modal-edit #combobox').val(record.data[0]['salesman_id']).attr('disabled', 'true');
										$('#modal-edit #inputan').attr('style', 'display: none');
									}
								} else {
									$('#modal-edit #combobox').attr('style', 'display: none');
									$("#modal-edit input[name=dari][value=referensi]").attr('checked', 'checked');
									$('#modal-edit #inputan').val(record.data[0]['salesman_remark']).attr('style', 'display: text');
								}
							}
							
							console.log(record)
							// $('#modal-edit #2').attr('disabled', 'true');
							// $('#modal-edit #3 option[value="'+record.data[0]['']+'"]').prop('selected', true);
							// $('#modal-edit #3').attr('disabled', 'true');
							// $('#modal-edit #selectSosmed').val(record.data[0]['']).attr('disabled', 'true');

							// $('#modal-edit #4 option[value="'+record.data[0]['']+'"]').prop('selected', true);
							// $('#modal-edit #4').attr('disabled', 'true');
							// $('#modal-edit #5 option[value="'+record.data[0]['']+'"]').prop('selected', true);
							// $('#modal-edit #5').attr('disabled', 'true');
							// $('#modal-edit #selectTmn').val(record.data[0]['']).attr('disabled', 'true');
							$('#modal-edit #auto option[value="'+record.data[0]['']+'"]').prop('selected', true);
							$('#modal-edit #auto').attr('disabled', 'true');
						}
					}
				})
			}

			// function disable(dbname){
			// 	if (dbname!='') {
			// 		$('#dbname').val(dbname);
			// 	}else{
			// 		var dbname = $('#dbname').val();

			// 		$.ajax({
			// 			url:'action.php',
			// 			type:'post',
			// 			data: {dbname: dbname, action:'disable'},
			// 			dataType: 'json',
			// 			success: function(record){
			// 				if (record.status == 'success') {
			// 					window.location.href='';
			// 				}
			// 			}
			// 		})
			// 	}
			// }
			function cari(){
				var id = $('#modal-table #selectAgen').val();
				$.ajax({
					type: 'POST',
					url: "action.php",
					dataType: 'json',
					data: {id:id, action:'cari'},
					success:function(record) {
						if (record.status == 'gagal') {
							$('#modal-table #infoAgn').text('Kode agen tidak ditemukan!');
							$('#modal-table #infoAgn').css('color','#ec6f6f');
							$('#modal-table #next').prop('disabled', true);
						}
						else{
							//$('#modal-table #infoAgn').text('Kode agen ditemukan.');
							//$('#modal-table #infoAgn').css('color','#87ba22');
							$('#modal-table #infoAgn').text(record.status);
							$('#modal-table #infoAgn').css('color','#a36c1d');								
							$('#modal-table #next').prop('disabled', false);
							$('#modal-table #selectAgen').prop('readonly', true);		
							$('#modal-table input[name=dari]').prop('disabled', true);		
						}
					}

				})
			}

			//cari agen pada form copy master
			function cari2(){
				var id = $('#modal-copy-master #selectAgen').val();
				$.ajax({
					type: 'POST',
					url: "action.php",
					dataType: 'json',
					data: {id:id, action:'cari'},
					success:function(record) {
						if (record.status == 'gagal') {
							$('#modal-copy-master #infoAgn').text('Kode agen tidak ditemukan!');
							$('#modal-copy-master #infoAgn').css('color','#ec6f6f');
							$('#modal-copy-master #next').prop('disabled', true);
						}
						else{
							$('#modal-copy-master #infoAgn').text(record.status);
							$('#modal-copy-master #infoAgn').css('color','#a36c1d');								
							$('#modal-copy-master #next').prop('disabled', false);
							$('#modal-copy-master #selectAgen').prop('readonly', true);		
							$('#modal-copy-master input[name=dari]').prop('disabled', true);		
						}
					}

				})
			}

			function load(){
				//var data= "<?=$_SESSION['cekCpn']?>";
				var data= "<?=$datadb['dbname']?>";

				//alert(data)
				if (data != 0) {
					$('.tampildata').load("manage.php");
					$('#judul1').remove();
				}
				// alert('yest'+data)
			}

			// $('#show-modal-edit').on('click', function(e){
			// 	e.preventDefault();
			// 	alert('1');
			// 	// $('#modal-edit').modal('show');
			// });
    	
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

    		function tambahuser(dbname, cust_id, ver_id, enddate, addUser_used){
				$('#modal-adduser #dbname').val(dbname);
    			$('#modal-adduser #versi').val(ver_id);
    			$('#modal-adduser #enddate').val(enddate);
    			$('#modal-adduser #addUser_used').val(addUser_used);
    			$('#modal-adduser #form-user').attr('action', 'simpan');
    			ambiluser(dbname);
				$('#modal-adduser').modal('show');
			}

			function simpanUser() {
				// alert(ambiluser(dbname));
				var cekEmail =$('#form-user #email').val().toLowerCase();
				var emails = $('#tbl-user td:nth-child(1)');
				
				getEmail = [];
				Object.keys(emails).forEach(function(index, item) {

					if (!isNaN(index) && index >0) {
						var email = $(emails[index]).text().toLowerCase()
						getEmail.push(email)
					}
				})

				if(cekEmail != ''){
					if(getEmail.includes(cekEmail)){
						alert('Email sudah terdaftar');
					}
					else{
						$('.lds-dual-ring').css('display', 'block');
					
					var method = $('#form-user').attr('action');
					var data = $('#form-user').serialize()+'&action='+method+'user';
					var dbname = $('#form-user #dbname').val();
					$.ajax({
						type:'post',
						dataType:'json',
						url:'action.php',
						data:data,
						success: function(record){
							if (record.status=='success') {
								ambiluser(dbname);
							} else {
								alert('Email gagal terdaftar');
							}
						}
					}).done(function(){
							$('.lds-dual-ring').css('display', 'none');
						})
					}
				}
			}

			function simpangantiUser(){
				var cekEmail =$('#form-user #email').val().toLowerCase();
				var emails = $('#tbl-user td:nth-child(1)');
				var emailLama = $('#emailLama').val();
				
				getEmail = [];
				Object.keys(emails).forEach(function(index, item) {

					if (!isNaN(index) && index >0) {
						var email = $(emails[index]).text().toLowerCase()
						getEmail.push(email)
					}
				})

				if(cekEmail != ''){
					if(getEmail.includes(cekEmail)){
						alert('Email sudah terdaftar');
					}
					else{
						// return alert('Under Construction');

						Swal.fire({
							title : 'Apakah Anda yakin?',
							html : '<h4>Ganti email : "'+emailLama+'"</h4> <h4>dengan email : "'+cekEmail+'" ?</h4>',
							type : 'warning',
							position: 'top',
							showCancelButton : true,
							confirmButtonColor : '#3085d6',
							cancelButtonColor : '#d33',
							confirmButtonText: 'Ya',
							cancelButtonText :'Batal' 
						}).then((result) => {
							if(result.value){
								$('.lds-dual-ring').css('display', 'block');
							
								var method = $('#form-user').attr('action');
								var data = $('#form-user').serialize()+'&action=gantiuser';
								var dbname = $('#form-user #dbname').val();
								$.ajax({
									type:'post',
									dataType:'json',
									url:'action.php',
									data:data,
									success: function(record){
										if (record.status=='success') {
											ambiluser(dbname);
											swal.fire(
												'Success!',
												'User berhasil di ganti. Silakan cek Email '+cekEmail+' untuk konfirmasi password. Segera lakukan pembayaran tagihan ganti user.',
												'success'
											)
										}
									}
								}).done(function(){
									$('.lds-dual-ring').css('display', 'none');
								})
							}
						});
					}
				}
			}

			function ambiluser(dbname){
				reset();
				$.ajax({
					type:'post',
					dataType:'json',
					url:'action.php',
					data:{action:'ambiluser', dbname:dbname},
					success: function(record){
						if (record.status=='success') {
							var html='';
							$('#tbl-user tbody').empty();
							record.data.forEach(function(index){
								//level = (index['level']=='A') ? 'Administrator' : 'Operator';
								//level = (level=='Administrator' && index['billing_send']=='Y') ? 'Owner' : level;
								setUslevel = '';
								if(index['level'] == 'C') {
									level = 'Owner';
								} else if(index['level'] == 'A') {
									level = 'Administrator';
									setCustLevel = 'O';
									setUslevel = '2';
								} else if(index['level'] == 'O') {
									level = 'Operator';
									setCustLevel = 'A';
									setUslevel = '1';
								} else {
									level = '-';
								}
								textColor = '';
								textColor2 = '';
								textColor3 = '';
								inv = '';
								adminBilling = index['billing_admin'];

								used = index['used'];
								active = index['active'];
								
								if(used == 'R'){
									used = 'Register';
								}
								else if(used == 'D'){
									used = 'Disabled';
									textColor = 'style="color: #d33"';
								}
								else if(used == 'T'){
									used = 'Trial';
								}
								else if(used = 'N'){
									used = 'Invalid';
									textColor = 'style="color: #d33"';
								}
								
								if(active == 'D'){
									status = 'Disabled';
									textColor2 = 'style="color: #d33"';
								} 
								else if(active == 'A'){
									status = 'Active';
								}
								
								valid = index['valid'];
								if(valid == 'N'){
									textColor3 = 'style="color: #d33"';
								} 
								
								cntinv = index['cntinv'];
								if(cntinv == 0){
									inv = 'N/A';
								} else {
									inv = 'Ada';
								}

								var style = adminBilling == 'N' ? 'style="width: 30px;text-align: center;color: #fff;background-color: red;border: 0px;"' : 'style="width: 30px;text-align: center;color: #fff;background-color: green;border: 0px;"';
								var styleLevel = 'style="width: 100%;text-align: center;color: #393939;background-color: #fff;border: 1px solid;"';
								
								if(level != 'Owner'){
									html += '<tr onclick="baris(this)" style="cursor: pointer">'+
													'<td>'+index['email']+'</td>'+
													// '<td>'+level+'</td>'+
													'<td align="center" onclick="setLevel(`'+index['cust_id']+'`,`'+index['email']+'`,`'+setCustLevel+'`,`'+setUslevel+'`)">'+
														'<button '+styleLevel+' title="ubah level">'+level+'</button>'+
													'</td>'+
													'<td '+textColor+'>'+used+'</td>'+
													'<td '+textColor2+'>'+status+'</td>'+
													'<td '+textColor3+' align="center">'+valid+'</td>'+
													'<td>'+inv+'</td>'+
													'<td align="center" onclick="setAdmBill(`'+index['cust_id']+'`,`'+index['email']+'`,`'+adminBilling+'`)">'+
														'<button '+style+' title="ubah admin billing">'+adminBilling+'</button>'+
													'</td>'+
												'</tr>';
								}
								else{
									html += '<tr style="cursor: not-allowed">'+
													'<td>'+index['email']+'</td>'+
													'<td align="center">'+level+'</td>'+
													'<td>'+used+'</td>'+
													'<td>'+status+'</td>'+
													'<td align="center">'+valid+'</td>'+
													'<td>'+inv+'</td>'+
													'<td></td>'+
												'</tr>';
								}
								
							});

							$('#tbl-user tbody').append(html);

						}
					}
				})
			}

			function baris(param){
				reset();
				var tr = $(param);
				var td  = tr[0].children;
				var email = td[0].innerText;
				var level = (td[1].innerText=='Operator') ? 'O' : 'A';
				var idused = (td[2].innerText=='Register') ? 'hapustagihan' : 'tambahtagihan';
				var idstatus = (td[3].innerText=='Disabled') ? 'aktif' : 'nonaktif';
				var valid = td[4].innerText;
				var inv = td[5].innerText;
				
				$(tr).addClass('bg-color');
				$('#email').prop('readonly', true);
				$('input[name=level][value='+level+']').prop('checked', true);
				$('#email').val(email);
				
				if(inv == 'N/A') {
					if(idused == 'tambahtagihan') {
						if(idstatus == 'aktif') {
							if(td[2].innerText == 'Trial') {
								$('#'+idused).removeClass('hidden');
								$('#'+idstatus).removeClass('hidden');
							} else {
								$('#'+idused).removeClass('hidden disabled');
								$('#'+idstatus).removeClass('hidden');
								// $('#gantiUser').addClass('hidden');
							}
						}
					} else {
						$('#'+idused).removeClass('hidden disabled');
						$('#'+idstatus).removeClass('hidden disabled');
						$('#gantiUser').removeClass('hidden');
					}
				} else {
					$('#'+idused).removeClass('hidden disabled');
					$('#'+idstatus).removeClass('hidden disabled');
					if (idstatus == 'nonaktif') {
						$('#gantiUser').removeClass('hidden');
					}
				}					
				$('#batal').removeClass('hidden');
				$('#simpan').addClass('hidden');
			}

			function gantiUser(){
				var emailLama = $('#email').val();
				$('#emailLama').val(emailLama);
				$('#email-lama').removeClass('hidden');
				$('#simpangantiUser').removeClass('hidden');
				$('#email').attr('readonly',false);
				$('#email').val('');
				$('#modal-adduser #email').focus();
				$('#gantiUser').addClass('hidden');
				$('#nonaktif').addClass('hidden');
				$('#hapustagihan').addClass('hidden');
				// $('#modal-adduser input[type=radio').attr('disabled',true)
			}
			
			function reset(){
				$('#form-user').trigger('reset');
				$('#email').removeAttr('readonly');

				$('#tbl-user tr').removeClass('bg-color');
				$('#hapustagihan').addClass('hidden disabled');
				$('#tambahtagihan').addClass('hidden disabled');
				$('#aktif').addClass('hidden disabled');
				$('#nonaktif').addClass('hidden disabled');
				$('#batal').addClass('hidden');
				$('#simpan').removeClass('hidden');
				$('#simpangantiUser').addClass('hidden');
				$('#gantiUser').addClass('hidden');
				$('#email-lama').addClass('hidden');
				$('#email-lama').val('');
				$('#modal-adduser input[type=radio').attr('disabled',false)
			}

			function setAdmBill(cust_id,email,adminBilling){
				var admB = adminBilling == 'Y' ? 'N' : 'Y';
				var confirmAdmin = admB == 'Y' ? 'Aktifkan' : 'Nonaktifkan';
				var dbname = $('#form-user #dbname').val();

				Swal.fire({
					title : 'Apakah Anda yakin?',
					html : '<h4>"'+confirmAdmin+'"</h4> <h4> '+email+' sebagai Admin Billing?</h4>',
					type : 'warning',
					position: 'top',
					showCancelButton : true,
					confirmButtonColor : '#3085d6',
					cancelButtonColor : '#d33',
					confirmButtonText: 'Ya',
					cancelButtonText :'Batal' 
				}).then((result) => {
					if(result.value){
						$.ajax({
							type:'post',
							dataType:'json',
							url:'action.php',
							data:{action:'updateAdmBilling', cust_id:cust_id, billing_admin:admB, dbname:dbname},
							success: function(record){
								if (record.status=='success') {
									ambiluser(dbname);
									swal.fire(
										'Success!',
										'User berhasil di update.',
										'success'
									)
								}
							}
						})
					}
				});
			}

			function setLevel(cust_id,email,custLevel,uslevel){
				var levelnya = custLevel == 'O' ? 'Operator' : 'Administrator';
				var dbname = $('#form-user #dbname').val();
				Swal.fire({
					title : 'Apakah Anda yakin?',
					html : '<h4>Ubah level '+email+' sebagai </h4><h4>"'+levelnya+'"?</h4>',
					type : 'warning',
					position: 'top',
					showCancelButton : true,
					confirmButtonColor : '#3085d6',
					cancelButtonColor : '#d33',
					confirmButtonText: 'Ya',
					cancelButtonText :'Batal' 
				}).then((result) => {
					if(result.value){
						$.ajax({
							type:'post',
							dataType:'json',
							url:'action.php',
							data:{action:'updateLevel', cust_id:cust_id, email:email, custLevel:custLevel, uslevel:uslevel, dbname:dbname},
							success: function(record){
								if (record.status=='success') {
									ambiluser(dbname);
									swal.fire(
										'Success!',
										'User berhasil di update.',
										'success'
									)
								}
							}
						})
					}
				});
			}

			//FUNCTION DIM START
			function getCabangSiserp(){
				var dbname = $('#form-loginApp #dbname').val();

				var email= '<?=$_SESSION['custEmail'];?>';
				
				$.ajax({
					type:'post',
					dataType:'json',
					url:'action.php',
					data:{action:'getCabangSiserp', dbname:dbname, email : email},
					success: function(record){
						// console.log(record.data)
						
						if (record.status=='success') {
							var htmlDetail = '';
							var aksesCabang = record.data.aksesCabang;
							var aksesCabang = aksesCabang.split(' ')
							record.data.cabang.forEach(function(data){
								if (aksesCabang.includes(data.KODE)) {
									htmlDetail += '<option value="'+ data.KODE +'">'+ data.NAMA + '</option>';
								}	
							});
							
							$('select#pilCab').html(htmlDetail);
						}
					}
				})					
			}
			//FUNCTION DIM END					
			
			function hapusTagihan() {
				var emailUser = $('#email').val();
				var dbname = $('#form-user #dbname').val();
				var ver_id = $('#form-user #versi').val();
				Swal.fire({
					title : 'Apakah Anda yakin?',
					html : '<h4>Menghapuskan tagihan akan membuat user tidak aktif.</h4>',
					type : 'warning',
					position: 'top',
					showCancelButton : true,
					confirmButtonColor : '#3085d6',
					cancelButtonColor : '#d33',
					confirmButtonText: 'Ya, Hapuskan tagihan!',
					cancelButtonText :'Batal' 
				}).then((result) => {
					if(result.value){
						$.ajax({
							type:'post',
							dataType:'json',
							url:'action.php',
							data:{action:'hapustagihan', emailUser:emailUser, dbname:dbname, ver_id:ver_id},
							success: function(record){
								if (record.status=='success') {
									ambiluser(dbname);
									swal.fire(
										'Hapuskan tagihan!',
										'User sudah tidak aktif dan tidak termasuk dalam tagihan.',
										'success'
									)
								}
							}
						})
					}
				})
				
			}
			
			function tambahTagihan() {
				if ($('#tambahtagihan').attr("disabled", true)) {
					if ($('#tambahtagihan').attr('class') == 'btn btn-success btn-sm pull-left disabled') {
						swal.fire(
							'Tombol Aktifkan Tagihan tidak bisa dipilih!',
							'Harap selesaikan pembayaran tagihan terlebih dahulu jika sudah berlangganan atau tagihan belum diaktifkan jika status belum berlangganan.',
							'warning'
						).then(function(){
							location.reload();
						});
					} else {
						swal.fire(
							'Tombol Aktifkan Tagihan tidak bisa dipilih!',
							'Harap selesaikan pembayaran tagihan terlebih dahulu.',
							'warning'
						).then(function(){
							location.reload();
						});
					}
				} else {
					var emailUser = $('#email').val();
					var dbname = $('#form-user #dbname').val();
					var ver_id = $('#form-user #versi').val();
					Swal.fire({
						title : 'Apakah Anda yakin?',
						html : '<h5 style="color: #d33">Mengaktifkan tagihan akan membuat tagihan baru untuk user.</h5>',
						type : 'warning',
						position: 'top',
						showCancelButton : true,
						confirmButtonColor : '#3085d6',
						cancelButtonColor : '#d33',
						confirmButtonText: 'Ya, Aktifkan tagihan!',
						cancelButtonText :'Batal' 
					}).then((result) => {
						if(result.value){
							$.ajax({
								type:'post',
								dataType:'json',
								url:'action.php',
								data:{action:'tambahtagihan', emailUser:emailUser, dbname:dbname, ver_id:ver_id},
								success: function(record){
									if (record.status=='success') {
										ambiluser(dbname);
										swal.fire(
											'Tambahkan tagihan!',
											'Tagihan user telah dibuat.',
											'success'
										)
									}
								}
							})		
						}
					})
				}
			}
			
			function hapusUser() {
				var custID = $('#email').val();
				var dbname = $('#form-user #dbname').val();
				var ver_id = $('#form-user #versi').val();
				$.ajax({
					type:'post',
					dataType:'json',
					url:'action.php',
					data:{action:'hapususer', cust_id:custID, dbname:dbname, ver_id:ver_id},
					success: function(record){
						if (record.status=='success') {
							ambiluser(dbname);
						}
					}
				})
			}

			function nonaktifUser() {
				var emailUser = $('#email').val();
				var dbname = $('#form-user #dbname').val();
				var ver_id = $('#form-user #versi').val();
				Swal.fire({
					title : 'Apakah Anda yakin?',
					html : '<h4>Nonaktifkan user sekarang.</h4>',
					type : 'warning',
					position: 'top',
					showCancelButton : true,
					confirmButtonColor : '#3085d6',
					cancelButtonColor : '#d33',
					confirmButtonText: 'Ya, Nonaktifkan!',
					cancelButtonText :'Batal' 
				}).then((result) => {
					if(result.value){
						$.ajax({
							type:'post',
							dataType:'json',
							url:'action.php',
							data:{action:'nonaktifuser', emailUser:emailUser, dbname:dbname, ver_id:ver_id},
							success: function(record){
								if (record.status=='success') {
									ambiluser(dbname);
									swal.fire(
										'Nonaktif!',
										'User telah dinonaktifkan.',
										'success'
									)
								}
							}
						})
					}
				})
			}

			function aktifUser() {
				if ($('#aktif').attr("disabled", true)) {
					if ($('#tambahtagihan').attr('class') == 'btn btn-success btn-sm pull-left') {
						swal.fire(
							'Tombol Aktifkan Pengguna tidak bisa dipilih!',
							'Harap tekan Tombol Aktifkan Tagihan terlebih dahulu.',
							'warning'
						).then(function(){
							location.reload();
						});
					} else if ($('#tambahtagihan').attr('class') == 'btn btn-success btn-sm pull-left disabled') {
						swal.fire(
							'Tombol Aktifkan Pengguna tidak bisa dipilih!',
							'Harap selesaikan pembayaran tagihan terlebih dahulu jika sudah berlangganan atau tagihan belum diaktifkan jika status belum berlangganan.',
							'warning'
						).then(function(){
							location.reload();
						});
					} else if ($('#tambahtagihan').attr('class') == 'btn btn-success btn-sm pull-left hidden disabled') {
						var emailUser = $('#email').val();
						var dbname = $('#form-user #dbname').val();
						var ver_id = $('#form-user #versi').val();
						Swal.fire({
							title : 'Apakah Anda yakin?',
							html : '<h5 style="color: #d33">Mengaktifkan user sekarang.</h5>',
							type : 'warning',
							position: 'top',
							showCancelButton : true,
							confirmButtonColor : '#3085d6',
							cancelButtonColor : '#d33',
							confirmButtonText: 'Ya, Aktifkan!',
							cancelButtonText :'Batal' 
						}).then((result) => {
							if(result.value){
								$.ajax({
									type:'post',
									dataType:'json',
									url:'action.php',
									data:{action:'aktifuser', emailUser:emailUser, dbname:dbname, ver_id:ver_id},
									success: function(record){
										if (record.status=='success') {
											ambiluser(dbname);
											swal.fire(
												'Aktif!',
												'User telah diaktifkan.',
												'success'
											)
										}
									}
								})		
							}
						})
					}
				} else {
					var emailUser = $('#email').val();
					var dbname = $('#form-user #dbname').val();
					var ver_id = $('#form-user #versi').val();
					Swal.fire({
						title : 'Apakah Anda yakin?',
						html : '<h5 style="color: #d33">Mengaktifkan user sekarang.</h5>',
						type : 'warning',
						position: 'top',
						showCancelButton : true,
						confirmButtonColor : '#3085d6',
						cancelButtonColor : '#d33',
						confirmButtonText: 'Ya, Aktifkan!',
						cancelButtonText :'Batal' 
					}).then((result) => {
						if(result.value){
							$.ajax({
								type:'post',
								dataType:'json',
								url:'action.php',
								data:{action:'aktifuser', emailUser:emailUser, dbname:dbname, ver_id:ver_id},
								success: function(record){
									if (record.status=='success') {
										ambiluser(dbname);
										swal.fire(
											'Aktif!',
											'User telah diaktifkan.',
											'success'
										)
									}
								}
							})		
						}
					})
				}
			}

			function login(used, active, dbname, versi, companyname, begindate, enddate, used, custid, custom) {
				if (active == 'A' && used != 'D') {

					$.ajax({
						url:'action.php',
						type:'post',
						data: {dbname:dbname, action:'infoDB'},
						dataType: 'json',
						success: function(data){
							// console.log(data)
							// console.log('cek stat '+data.status)
							if(data.status == 'success')
							{
								var periodeAwal = data.awal
								var periodeAkhir = data.akhir
								var html = '';
									html = periodeAwal+' - '+periodeAkhir
								var cekHtml = $('#modal-login .modal-body #label1').html();
								var cekHtml2 = $('#modal-login .modal-body #label2').html();

								if(cekHtml == '')
								{
									if(cekHtml2 != '')
									{
										$('#modal-login .modal-body #label2').empty();
									}
									$('#modal-login .modal-body #label1').append(html);
								} else {
									var remove = $('#modal-login .modal-body #label1').empty();
									if(remove)
									{
										var html2 = '';
											html2 = periodeAwal+' - '+periodeAkhir
										$('#modal-login .modal-body #label2').append(html2);

									}
								}								
							}
						},
						error: function(data){
							console.log('data error || data db kosong')
							$('#modal-login .modal-body #label1').empty().html('Database tidak ada');
							$('#modal-login .modal-body #label2').empty();

						}
					})

					$('#form-loginApp #dbname').val(dbname);
					$('#form-loginApp #versi').val(versi);
					$('#form-loginApp #companyname').val(companyname);
					$('#form-loginApp #begindate').val(begindate);
					$('#form-loginApp #enddate').val(enddate);
					$('#form-loginApp #used').val(used);
					$('#form-loginApp #custid').val(custid);
					$('#form-loginApp #customapp').val(custom);
					getCabangSiserp();
					
					var date = new Date();
					$('#year').val(date.getFullYear());
					
					$('#modal-login').modal('show')
				}
				else{
					alert('Anda tidak memiliki akses untuk menggunakan program.');
				}				
			}

			function loginApp(){
				var host = window.location.hostname;
				var email= '<?=$_SESSION['custEmail'];?>';
				var data = $('#form-loginApp').serialize()+'&email='+email;
				var dbname = $('#form-loginApp #dbname').val();
				var versi = $('#form-loginApp #versi').val();
				var custid = $('#form-loginApp #custid').val();	
				var year = $('#year').val();
				var customapp = $('#form-loginApp #customapp').val();

				if (year.length <4){
					alert('Format tahun harus 4 digit');
				} else {
					$.ajax({
						url:'action.php',
						type:'post',
						data: {dbname:dbname, custid:custid, versi:versi, action:'log'},
						dataType: 'json',
						success: function(record){
							$('.lds-dual-ring').css('display', 'block');
							if (record.status == 'successlog') {
								$.ajax({
									type 	: 'POST',
									dataType : 'json',
									//url		: 'https://'+host+'/siserp/login/loginApp',
									url		: '<?=$abs2?>/login/loginApp',
									data: data,
									success: function(record){
										if (record.status == 'success') {
											//window.location = 'https://'+host+'/siserp/dashboard';
											sessionStorage.setItem('login','isLogin')
											window.location = '<?=$abs2?>/dashboard';										
										}
										else{
											$('.lds-dual-ring').css('display', 'none');
											$('#modal-login .modal-body .alert').remove();
											html='<div class="alert alert-danger alert-dismissible" role="alert">'+
													'<button type="button" class="close" style="font-size: 25px; font-weight:bold" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times</span></button>'+
													'<strong>Warning!!&emsp;</strong>'+record.error+' !!'
												'</div>';
											$('#modal-login .modal-body').append(html);
										}
									}		
								})
							}
						}
					})
					$('.lds-dual-ring').css('display', 'none');
				}
				
				/*$.ajax({
					type 	: 'POST',
					dataType : 'json',
					//url		: 'https://'+host+'/siserp/login/loginApp',
					url		: '<?=$abs2?>/login/loginApp',
					data: data,
					success: function(record){
						if (record.status == 'success') {						
							//window.location = 'https://'+host+'/siserp/dashboard';
							window.location = '<?$abs2?>/dashboard';							
						}
						else{
							$('#modal-login .modal-body .alert').remove();
							html='<div class="alert alert-danger alert-dismissible" role="alert">'+
									'<button type="button" class="close" style="font-size: 25px; font-weight:bold" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times</span></button>'+
									'<strong>Warning!!&emsp;</strong>'+record.error+' !!'
								'</div>';
							$('#modal-login .modal-body').append(html);
						}
					}
				
				})*/
			}

			function copyMaster(dbMaster, cust_id, ver_id){
				$('#modal-copy-master').modal('show');
				judul = dbMaster.charAt(0).toUpperCase() + dbMaster.slice(1);
				$('#judulModal').text(judul);
				$('#dbMaster').val(dbMaster);

				$.ajax({
					url:'action.php',
					type:'post',
					data: {dbname:dbMaster, custID:cust_id, action:'tampil'},
					dataType: 'json',
					success: function(record){
						if (record.status == 'successTampil') {
							$('#modal-data #simple-table tbody').empty();
							var html = '';

							$('#modal-copy-master #selectBis option[value="'+record.data[0]['business_id']+'"]').prop('selected', true);
							$('#modal-copy-master #hp').val(record.data[0]['phone_no']);
							$('#modal-copy-master #wa').val(record.data[0]['wa_no']);
							// $('#modal-copy-master #emailU').val(record.data[0]['email']).attr('readonly', 'true');
							$('#modal-copy-master #npwp').val(record.data[0]['npwp_no']);
							// $('#modal-copy-master #selectperiod').attr('disabled', 'true');
							$('#modal-copy-master #address').val(record.data[0]['address']);
							$('#modal-copy-master #kota').val(record.data[0]['city']);
							$('#modal-copy-master #selectProv option[value="'+record.data[0]['prov_code']+'"]').prop('selected', true);
							$('#modal-copy-master #kodepos').val(record.data[0]['zip_code']);
							$('#modal-copy-master #selectNeg option[value="'+record.data[0]['country_code']+'"]').prop('selected', true);
							$('#modal-copy-master #selectVer option[value='+ver_id+']').prop('selected', true);
							
							console.log(record)
						}
					}
				})

			}

			// function duplicateDb(dbLama, dbBaru){
			// 	if(dbLama != '' && dbBaru !=''){
			// 		$.ajax({
			// 			type: 'post',
			// 			url: 'action.php',
			// 			dataType: 'json',
			// 			data: {dbLama: dbLama, dbBaru: dbBaru, action:'duplicate-database'},
			// 			success: function(record){
			// 				if(record.status == 'success'){

			// 					Swal.fire({
			// 						title : 'Berhasil!',
			// 						html : '<h4>Copy master database '+dbBaru+'.</h4>',
			// 						type : 'success',
			// 						timer: 1500
			// 					})
			// 					$('.lds-dual-ring').css('display', 'none');
			// 					setTimeout(function() {
			// 						location.reload();
			// 					},1500)
			// 				}
			// 				else{
			// 					alert('Gagal Duplikat database '+dbBaru);
			// 				}

			// 				$('.lds-dual-ring').css('display', 'none');
			// 			}
			// 		})
			// 	}	
			// }


    	</script>
    	<script type="text/javascript">
			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true
			})
			//show datepicker when clicking on the icon
			.next().on(ace.click_event, function(){
				$(this).prev().focus();
			});

			function tagihanInfo(namadb){
				// alert('Database '+namadb+' tidak memiliki tagihan.');
				Swal.fire({
					title : 'Informasi tagihan:',
					html : '<h4>Database '+namadb+' tidak memiliki tagihan.</h4>',
					type : 'warning',
					position: 'top'
				})
			}
    	</script>
		<script type="text/javascript">
			function selectFocus(){
				let agen = $('#modal-table #agen');
					if (agen.is(':checked')){
						$('#modal-table #selectAgen').removeAttr('readonly');//enable input
						$('#modal-table #selectAgen').slideDown("fast");
						$('#modal-table #infoAgn').removeAttr('disabled').slideDown("fast"); //enable alert*
					} else{
						$('#modal-table #selectAgen').attr('required', false).slideUp("fast"); //disable required
						$('#modal-table #selectAgen').attr('readonly', true);
						$('#modal-table #selectAgen-error').hide();
						$('#modal-table #infoAgn').slideUp("fast"); //disable alert*
						$('#modal-table #selectAgen').val('');
						$('#modal-table #infoAgn').text('*Hubungi agen penjualan untuk mengetahui kode agen').css('color','#ec6f6f');
						$('#modal-table #next').prop('disabled', false); // disable btn next
					}
							
				let combobox = $('#modal-table #combo .ace');
					if (combobox.is(':checked')){
						$('#modal-table #combo #combobox').removeAttr('readonly').slideDown("fast"); //enable input
						$('#modal-table #combo #combobox').attr('required', true);
					} else {
						$('#modal-table #combo #combobox').attr('readonly', true).slideUp("fast"); //disable input	
						$('#modal-table #combo #combobox').removeAttr('required');
						$('#modal-table #combo #combobox-error').hide();
					}

				let inputan = $('#modal-table #remarks .ace');
					if (inputan.is(':checked')){
						$('#modal-table #remarks #inputan').removeAttr('readonly').slideDown("fast"); //enable input
						$('#modal-table #remarks #inputan').attr('required', true);
					} else {
						$('#modal-table #remarks #inputan').attr('readonly', true).slideUp("fast"); //disable input
						$('#modal-table #remarks #inputan').removeAttr('required');
						$('#modal-table #remarks #inputan-error').hide();
					}	
			}
    	</script>
		<script>
			$('#modal-table #combo #combobox').on('change', function(){
				if ($('#modal-table #combo #combobox').val() != '') {
					$('#modal-table #next').prop('disabled', false); // disable btn next
				} else {
					$('#modal-table #next').prop('disabled', true); // enable btn next
				}
			})
			
			$('#modal-table #remarks #inputan').on('keyup', function(){
				if ($('#modal-table #remarks #inputan').val() != '') {
					$('#modal-table #next').prop('disabled', false); // disable btn next
				} else {
					$('#modal-table #next').prop('disabled', true); // enable btn next
				}
			})
		</script>

	</body>
</html>
