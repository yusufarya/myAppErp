<?php 
	$host=$_SERVER['SERVER_NAME'];
	$host2 = "https://$_SERVER[SERVER_NAME]/yusuf/siserp/module/reg/";

	// $userID = $this->session->userdata('usidT');
	// $password = $this->session->userdata('passT');
	
	$sel1 = '';
	$sel2 = '';
	$sel3 = '';
	$sel4 = '';
	$sel5 = '';
	$sel6 = '';
	$sel7 = '';
	$sel8 = '';
	$sel9 = '';
	$sel10 = '';
	$sel11 = '';
	$sel12 = '';
	$curYear = date('Y');
	$curMonth = date('m');
	switch($curMonth) {
		case '01': 
			$sel1 = 'selected';
			break;
		case '02': 
			$sel2 = 'selected';
			break;
		case '03': 
			$sel3 = 'selected';
			break;
		case '04': 
			$sel4 = 'selected';
			break;
		case '05': 
			$sel5 = 'selected';
			break;
		case '06': 
			$sel6 = 'selected';
			break;
		case '07': 
			$sel7 = 'selected';
			break;
		case '08': 
			$sel8 = 'selected';
			break;
		case '09': 
			$sel9 = 'selected';
			break;
		case '10': 
			$sel10 = 'selected';
			break;
		case '11': 
			$sel11 = 'selected';
			break;	
		case '12': 
			$sel12 = 'selected';
			break;	
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>SISCOM ONLINE</title>
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

		<link rel="stylesheet" href="assets/css/jquery-ui.min.css" />
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<!-- ace settings handler -->
		<script src="assets/js/ace-extra.min.js"></script>
		<!-- <script src="js/jquery.js"></script> -->

		<style type="text/css">
			.no-margin{
				margin:0px !important;
			}
			#tbl-user thead th{
				text-align: center;
				background-color: grey;
				color:white;
				border: 1px solid silver;
				width: 50%;
			}
			.hidden{
				display: none;
			}

			/*#legend div{
				display: inline-block;
				margin-top: 20px;
				padding-right: 15px;
				font-weight: bold;
			}*/

			/*.basic::before{
				content:'';
				position: absolute;
				left:41%;
				z-index: 2;
				height: 20px;
				width: 20px;
				background-color: #3eaaff;
				border-color: #3eaaff;
			}
			.starter::before{
				content:'';
				position: absolute;
				left:46.5%;
				z-index: 2;
				height: 20px;
				width: 20px;
				background-color: #006bbf;
				border-color: #006bbf;
			}
			.enterprise::before{
				content:'';
				position: absolute;
				left:50.5%;
				z-index: 2;
				height: 20px;
				width: 20px;
				background-color: #003b6a;
				border-color: #003b6a;
			}*/
		</style>
</head>
<?php 
		
		include 'includes/style.php';
		require_once '../includes/koneksi2.php';
		session_start();

		$custBilling = $_SESSION['custBilling'];
	?>

<body class="main-container">
	<style type="text/css">

		.btn-cdb a{
			border-radius: 8px;
		}

		.btn-cdb a:hover{
			/*background: #9A9A9A!important;*/
		}

		.btn-cdb .info{
			font-size: 12px;
			padding: 2px 6px 4px; color: white;
			transition: .1s;
		}

		.btn-cdb .info:hover {
			color: #F5F5DC !important;
			font-size: 12.3px;
			text-decoration: none;
		}


		.info .info {
			float: right; margin-top: -30px; display: inline-block; color: #708090; 
			font-size: 13.5px;
		}
		.info .info:hover {
			text-decoration: none; font-weight: 500px;
			padding-top: 1px;
		}

		#legend {
			width: 100%;
			position: absolute;
			padding-left: 36%;
		}

		@media (min-width: 1800px) and (max-width: 1920px){
			#legend {
				padding-left: 39.5%;
			}
		}

		#legend ul {
			list-style: none;
		}

		#legend ul li {
			float: left;
			color: #fff;
			padding: 5px 20px;
		}

		#legend .basic {
			height: 20px;
			width: 20px;
			background-color: #3eaaff;
			margin: 0px 5px;
			border-radius: 4px;
			border: 1px solid #fff;
		}

		#legend .starter {
			height: 20px;
			width: 20px;
			background-color: #006bbf;
			margin: 0px 5px;	
			border-radius: 4px;
			border: 1px solid #fff;
		}

		#legend .enterprise {
			height: 20px;
			width: 20px;
			background-color: #003b6a;
			margin: 0px 5px;
			border-radius: 4px;
			border: 1px solid #fff;
		}

		.ace-thumbnails{
			margin-top: 10%;
		}

		.ace-thumbnails:hover{
			transform: translateY(-10%);
			transition: 0.3s;
		}

	</style>
<!-- _____________________________________________Main Content____________________________________________ -->
		<!-- <?php
		if ($custBilling == 'Y') {
		?>
		<?php
		}
		?> -->
		<div id="add-db" style="text-align: center;">
			<div class="btn-cdb">
				<a href="#modal-table" id="show-option" role="button" class="btn" data-toggle="modal" style="width: 220px; margin-bottom: 1%; background: #fff!important; color: #000!important; border: 0;" title="Tambah database">
					<i class=" fa fa-plus small"></i> Tambah Database
				</a> <br>
				<a href="https://youtu.be/_dJXhJz7d90" target="_blank" class="info">
					<img src="https://myappdev.siscom.id/module/reg/img/Info-merah.png" width="15" style="margin: -1px 2px 0 !important;">
					Cara Menambah Database
				</a>
			</div>
		</div>

		<div class=" ace-save-state">
			<div class="main-content"  style="margin-top: 40px">
				<div class="main-content-inner">

					<div align="left">
						<!-- <a href="#modal-table" role="button" class="btn btn-danger btn-sm btn-sm" data-toggle="modal"> Tambah Database </a> -->
						<!-- <div class="container">
							<ul class="nav nav-pills" style="padding-left: 0px !important; text-align: left !important">
								<li class="active">
									<a href="#database" data-toggle="tab"><strong>Database</strong></a>
								</li>
							<?php if ($_SESSION['custLevel']=='A' && $_SESSION['custBilling']=='Y'): 
										?>
								<li>
									<a href="#konfirmasi" data-toggle="tab"><strong>Konfirmasi Pembayaran</strong></a>
								</li>
							<?php endif ?>
							</ul>
						</div> -->

							<div class="container">
								<div class="tab-content" style="border:0 !important">
									<div class="tab-pane" id="konfirmasi">
										<?php if ($_SESSION['custLevel']=='A' && $_SESSION['custBilling']=='Y'): 
										?>
											<form id="form-konfirmasi" class="col-md-6 col-sm-6 col-lg-6" method="post" action="konfirmasi.php" enctype="multipart/form-data">
												<div class="form-group">
													<label class="control-label">Nama</label>
													<input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama anda">
												</div>
												<div class="form-group">
													<label class="control-label">No Invoice</label>
													<input type="text" name="inv_no" id="inv_no" class="form-control" placeholder="Masukkan nomor invoice">
												</div>
												<div class="form-group">
													<label class="control-label">Upload Bukti Pembayaran</label>
													<input type="file" name="bukti" id="bukti" class="form-control ">
												</div>
												<div class="form-group pull-right">
													<button name="submit" id="submit" class="btn btn-primary"><i class="fa fa-envelope"></i>&emsp;By Email</button>
												</div>
											</form>
										<?php endif ?>
									</div>

									<!--MANAGE DATABASE-->
									<div  id="database" class="tab-pane in active" style="overflow-x: auto; ">
										<div  style="max-height: 480px; width:100px; display: flex; flex-direction: column; flex-wrap: wrap; align-content: stretch;">
										<!-- PAGE CONTENT BEGINS -->
										<?php 
											// $custom = $_SESSION['custom'];
											$custID = $_SESSION['custID'];
											// $custBilling = $_SESSION['custBilling'];
											
											$scDbname = mysql_query("SELECT cs.custom as custom ,cs.order_id as db_id, cs.cust_id, cs.dbname, v.name AS versi, cs.active, cs.ver_id AS versi_id, cs.begin_date, cs.end_date, cs.expired_date, cs.used, c.name AS company_name, cs.file_identity as ktp, ct.billing_send, cs.billing_admin, cs.ctype AS level FROM cust_order cs JOIN cust ct ON ct.id = cs.cust_id JOIN version v ON cs.ver_id = v.id JOIN company c ON c.id = cs.company_id WHERE cs.cust_id = '$custID' and cs.stsrec='A' and cs.expired_date > CURRENT_DATE") or die(mysql_error());

											$disabled2=FALSE;
											// var_dump(mysql_fetch_array($scDbname));

											while ($rowdb = mysql_fetch_array($scDbname)) {
											 	$dbname = strtolower($rowdb['dbname']);
											 	$custom = $rowdb['custom'];
												$custid = $rowdb['cust_id'];
											 	$used = $rowdb['used']; //T=trial, R=registered, D=Disable, N=Nonaktif
											 	$ktp = ($rowdb['ktp']=='' || $rowdb['ktp']==NULL) ? FALSE : TRUE;
											 	$versi_name = $rowdb['versi'];
											 	$active =$rowdb['active'];
											 	$versi = $rowdb['versi_id'];
											 	$begindate = $rowdb['begin_date'];
												$enddate = $rowdb['end_date'];
												$expdate = $rowdb['expired_date'];
												$companyname = $rowdb['company_name'];
												$level = $rowdb['level'];
											 	$disabled = ($used!='T' && $used!='R') ? TRUE : FALSE;
											 	$login = ($disabled!=TRUE) ? "login('$used','$active','$dbname','$versi','$companyname','$begindate','$enddate','$used','$custid')" : "tampil('$dbname', '$custID', '$used', '$custom')"; // mengisi modal info ketika database diklik dan status db disabled
											 	$db_id = $rowdb['db_id'];
												$billing_send = $rowdb['billing_send'];
												$billing_admin = $rowdb['billing_admin'];
												
											 	$disabled2 != $disabled2;
											 	$modal = ($disabled==TRUE) ? 'data-target="#modal-data" data-toggle="modal"':'';

											 	$sql = mysql_query("SELECT * FROM cust_order WHERE LOWER(dbname) = '$dbname' AND used <> 'D'");
											 	$jumlah_user = mysql_num_rows($sql);
												
												//$sql = mysql_query("SELECT inv_no, paid_off FROM invoice inv JOIN invoice_detail inv_d ON inv.id = inv_d.inv_id WHERE inv_d.order_id = '$db_id' AND inv.stsrec = 'A'") or die(mysql_error());
												$sqlstr = "SELECT DISTINCT inv.inv_no, inv.paid_off FROM invoice inv JOIN invoice_detail inv_d ON inv.id = inv_d.inv_id JOIN cust_order co ON co.order_id = inv_d.order_id WHERE co.dbname = '$dbname' AND inv.stsrec = 'A' AND inv.paid_off = 'N'";
												$sql = mysql_query($sqlstr) or die(mysql_error());
											 	$data = mysql_fetch_array($sql);
												$jumlah_tagihan = mysql_num_rows($sql);

											 	if ($versi == 1) {
											 		$color = '#3eaaff';
											 	}
											 	elseif ($versi ==2) {
											 		$color = '#006bbf';
											 	}
											 	else{
											 		$color = '#003b6a';
											 	}

											 	//cek apakah masa berakhir aplikasi tinggal 7 hari lgi
											 	/*$disabledConfirm = 'disabled';
											 	$href = '';
										 		$icon = 'check';
										 		$text = 'OK';
											 	$h7 = date('d-m-Y', strtotime('-7 days', strtotime($enddate)));
											 	if (strtotime($h7) <= strtotime(date('d-m-Y'))) {
											 		if ($data['inv_no']!='' &&  $data['paid_off'] != 'Y'){
											 			$href = 'href="halaman_konfirmasi.php?inv_no='.$data['inv_no'].'"';
												 		$disabledConfirm='';
													 	$icon='money';
													 	$text = 'Konfirmasi Pembayaran';
											 		}
											 	}*/
												
												$disabledConfirm = 'disabled';
											 	$href = '';
												$text = '';
												$icon = '';
												$bgcol = '';
												if($used == 'T') {
													$text = 'Trial: Expired '.date("d/m/Y",strtotime($enddate));
													$bgcol = '#cdc189';
													$col = 'black';
												} else if($used == 'R') {
													if (strtotime(date('Y-m-d')) <= strtotime($enddate)) {
														$text = 'Register: Expired '.date("d/m/Y",strtotime($enddate));
														$bgcol = 'red';
														$col = 'white';
													} else {
														if (strtotime(date('Y-m-d')) <= strtotime($expdate)) {
															$text = 'Register: Deleted '.date("d/m/Y",strtotime($expdate));
															$bgcol = 'black';
															$col = 'white';
														}
													}
												} else if($used == 'D') {
													$text = 'Disabled: Deleted '.date("d/m/Y",strtotime($expdate));
													$bgcol = 'black';
													$col = 'white';
												}

										 ?>

												<div class="col" id="data-box" style="margin-right: 10px" >
													<ul class="ace-thumbnails clearfix" style="border: 1px solid #fff; border-radius: 8px; overflow: hidden;">
															
														<li style="margin-bottom: 0px;">
															<div style="width: 250px;" onclick="<?=$login;?>" <?=$modal;?>>
																 <a class="btn" style="width: 100%; background-color: <?=$color;?> !important; border-color: <?=$color;?> !important">							
																	<i class="fa fa-database bigger-500"></i>

																	<div class="tags">
																		<?php
																			if($billing_send == 'Y') {
																		?>
																		<?php
																				if($jumlah_tagihan > 0) {
																		?>
																		<span class="label-holder" id="jumlah_tagihan" style="margin-bottom: 40px; height: 25px; width: 25px;">
																			<div class="badge badge-danger" id="tagno"><font color="white"><?=$jumlah_tagihan?></font></div>
																			
																			<span id="info" style="display: none; font-size: 12.5px; padding: 0 6px; border-radius: 5px; margin-left: 20px;" class="alert alert-danger">Cek tagihan pembayaran Anda</span>
																		</span>
																		<?php
																			}
																		?>
																		<?php
																			}
																		?>
																	
																		<!-- <span class="label-holder">
																			<font color="white">&nbsp;<?=$jumlah_user;?>&nbsp;<i class="fa fa-user"></i></font>
																		</span>

																		<span class="label-holder">
																			<font color="yellow">&nbsp;<?=$dbname;?>&nbsp;<i class="fa fa-database"></i></font>			
																		</span> -->
																	</div>
																</a>
															</div>

															<div class="tools tools-top">
													<?php 
														// if ($disabled) {
															$none = '';
															if($used == 'D'){
																$clickTambah = '';
																$clickEdit = '';
																$clickCopy = '';
																$icon = 'color: #efecec78!important';
															}
															elseif($used == 'T'){
																$none = 'display: none';
																$clickTambah = 'onclick="tambahuser(`'.$dbname.'`, `'.$custID.'`, `'.$versi.'`, `'.$enddate.'`, `'.$used.'`)"';
																$clickEdit = 'onclick="edit(`'.$dbname.'`, `'.$custID.'`)"';
																$clickCopy = 'onclick="copyMaster(`'.$dbname.'`, `'.$custID.'`, `'.$versi.'`)"';
																$icon = '';
															}
															else {
																$clickTambah = 'onclick="tambahuser(`'.$dbname.'`, `'.$custID.'`, `'.$versi.'`, `'.$enddate.'`, `'.$used.'`)"';
																$clickEdit = 'onclick="edit(`'.$dbname.'`, `'.$custID.'`)"';
																$clickCopy = 'onclick="copyMaster(`'.$dbname.'`, `'.$custID.'`, `'.$versi.'`)"';
																$icon = '';
															}
														// }
														// else{

													?>

														<a id="show-option" onclick="tampil('<?=$dbname;?>', '<?=$custID;?>')" href="#modal-data" data-toggle="modal" title="Info Database">
															<i class="ace-icon fa fa-info"></i>
														</a>

														<?php if($billing_send == 'Y') { ?>
														<a id="show-option" onclick="tampilcabang('<?=$dbname;?>', '<?=$custID;?>','<?=$versi?>','<?=$enddate?>','<?=$used?>','<?=$db_id?>')" href="#modal-data" data-toggle="modal" title="Cabang">
															<i class="ace-icon fa fa-building"></i>
														</a>
														<?php } ?>
														
														<?php 
														/* echo "<script>alert('$custBilling')</script>";*/
															if ($level == 'C' || $billing_admin == 'Y') {
																// if ($used=='R') {
														?>
																	<a id="show-option" href="#" title="User" style="<?=$icon;?>" <?=$clickTambah;?>>
																		<i class="ace-icon fa fa-user"></i>
																	</a>
															<?php //} ?>

														<!-- tombol tagihan -->
														<?php 
															$sql2= mysql_query("SELECT inv_no, paid_off FROM invoice inv JOIN invoice_detail inv_d ON inv.id = inv_d.inv_id WHERE inv_d.order_id = '$db_id' AND inv.stsrec = 'A'") or die(mysql_error());
												 			$data2 = mysql_fetch_array($sql2);
												 			$paid_off = $data2['paid_off'];
															$onclick = "";
															//$link1 = "https://$host/siserp/module/reg/pages/info_tagihan.php?dbname=$dbname";
															//$link2 = "https://$host/siserp/module/reg/pages/info_tagihan.php?dbname=$dbname&versi=$versi";
															$link1 = "$abs/reg/pages/info_tagihan.php?dbname=$dbname";
															$link2 = "$abs/reg/pages/info_tagihan.php?dbname=$dbname&versi=$versi";

															if ($ktp) {
														?>

															<a id="show-option" href="<?=$link1;?>" title="Tagihan" <?=$onclick?>>
																<i class="ace-icon fa fa-credit-card"></i>
															</a>

														<?php
															}
															else{
														?>	
															<a id="show-option" href="<?=$link2;?>" title="Tagihan" <?=$onclick?>>
																<i class="ace-icon fa fa-credit-card"></i>
															</a>
														<?php
															}
														?>

															<a href="#" <?=$clickEdit;?> id="show-option" title="Edit Database" style="<?=$icon;?>">
																<i class="ace-icon fa fa-pencil"></i>
															</a>

															<a href="#" <?=$clickCopy;?> id="show-option" title="Copy master" style="<?=$icon;?>; <?=$none?>">
																<i class="ace-icon fa fa-copy"></i>
															</a>
														<?php 
															} else {

																if ($billing_admin == 'Y') {
																	$onclick = "";
																	$link2 = "$abs/reg/pages/info_tagihan.php?dbname=$dbname&versi=$versi";

														?>
																<a id="show-option" href="<?=$link2;?>" title="Tagihan" <?=$onclick?>>
																	<i class="ace-icon fa fa-credit-card"></i>
																</a>
														<?php
																}

															}
														?>

													<?php 
														//}
													?>
																
															</div>
														</li>
														<li style="width: 100%; margin: 0px; text-align: center; padding: 5px 0px; font-size: 14px; background: rgba(0, 0, 0, 0.25);">
															<span><font color="white"><i class="fa fa-user"></i>&nbsp;<?=$jumlah_user;?>&nbsp;</font>&nbsp;&nbsp;<font color="yellow"><i class="fa fa-database"></i>&nbsp;<?=$dbname;?>&nbsp;</font>	</span>
														</li>
														<li style="width: 100%; margin: 0px;">
															<a id="show-option" class="btn btn-sm btn-warning" style="width: 100%; border: 0px; font-size:14px; background-color: <?=$bgcol?> !important; color: <?=$col?> !important" <?=$href;?> <?=$disabledConfirm;?>>
																<?=$text;?>
															</a>
														</li>
													</ul>
												</div>
												
										<?php 
											} //tutup while

										?>
										</div> <!-- /.max-hight -->
									</div>
									<!-- <div class="row"> -->
										<!-- <div class="panel panel-default"> -->
											<!-- <div id="legend" style="padding: 20px;">
												<div class="basic">
													<p>&emsp; Starter</p>
												</div>
												<div class="starter">
													<p>&emsp; <font size="-1">Pro</font></p>
												</div>
												<div class="enterprise">
													<p>&emsp; <font size="-1">Enterprise</font></p>
												</div>
											</div> -->
										<!-- </div> -->
									<!-- </div> -->
								</div>
							</div> <!-- /.container -->
					</div> <!-- /.align="left" -->
				</div><!-- /.main-content-inner -->
			</div> <!-- /.main-content -->

			<!-- ___________modal view data company__________ -->

			<div id="modal-data" class="modal fade" tabindex="-1">
			<div class="modal-dialog modal-lg" style="width: 100% auto">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">&times;</span>
							</button>
							<span id="title">Info Database</span>
						</div>
					</div>

						<!-- Info Database -->
					<div class="modal-body">
						<!-- <div class="col-xs-12"> -->
							<table id="simple-table" class="table  table-bordered table-hover" style="vertical-align: center">
								<thead>
									
								</thead>

								<tbody>
									
									
								</tbody>
							</table>
						<!-- </div>/.span -->
						<div class="row">
							<div class="col-xs-12" id="message" >
								
							</div>
						</div>

						<!-- inputan tambah cabang -->
						<div class="row" id="branchContainer" style="display: none;">
							<div class="col-xs-3">
								<label>Jumlah Cabang :</label>
							</div>
							<div class="col-xs-6">
								<input type="text" name="tambahCabang" id="tambahCabang" class="form-control" onKeyPress="return goodchars(event, '0123456789', this)" autocomplete="off">
							</div>
							<div class="col-xs-3">
								<div style="float: right;">
									<button type="button" class="btn btn-sm btn-warning" id="cancelBranch" onclick="cancelBranch()"><i class="fa fa-times"></i>&nbsp;Batal</button>
									<button type="button" class="btn btn-sm btn-success" id="submitBranch" onclick="submitBranch()"><i class="fa fa-save"></i>&nbsp;Simpan</button>
								</div>
							</div>
						</div>

						<input type="hidden" name="dbname" id="dbname">
						<input type="hidden" name="versi" id="versi">
						<input type="hidden" name="enddate" id="enddate">
						<input type="hidden" name="used" id="used">
						<input type="hidden" name="order_id" id="order_id">

					</div><!-- /.row -->
					<div class="modal-footer">
						
					</div>
					
				</div> <!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
			</div> <!-- /.modal-table -->


			<div id="modal-adduser" class="modal fade" tabindex="-1">
			<div class="modal-dialog modal-lg" style="width: 100% auto">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">&times;</span>
							</button>
							Tambah User
						</div>
					</div>
					<div class="modal-body" style="padding: 20px !important">
						<form action="simpan" id="form-user">
						
							<div class="row form-group">
								<div class="col-md-12 info">
									<h4>Tentukan jenis akses yang akan Anda berikan:</h4>
									<a href="https://youtu.be/_dJXhJz7d90" target="_blank" class="info">
										<img src="<?php echo $host2; ?>img/Info-merah.png" width="15" style="margin: -2px 1px 0 !important;">
										Cara Menambah User
									</a>
								</div>
								<div class="col-md-6">
									<div class="col-md-12 no-margin">
										<label class="control-label">
											<input type="radio" name="level" id="level" value="O" class="no-margin" checked>
											Operator
										</label>
									<hr class="no-margin">
									</div>
									<div class="col-md-12 no-margin">
										<span>User dapat akses untuk membuka dan melihat rincian database.  Penentuan hak akses dapat dilakukan setelah login ke database user tersebut.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-12">
										<label class="control-label">
											<input type="radio" name="level" id="level" value="A">
											Administrator
										</label>
									<hr class="no-margin">
									</div>
									<div class="col-md-12">
										<span>User dapat akses untuk mengontrol aktifitas administrasi seperti contoh menghapus database.</span>
									</div>
								</div>
							</div>
							<!-- (this) -->
							<div class="row form-group">
								<div id="email-lama" class="col-md-12 hidden">
									<?php 
										$biayaGantiUsr = '20000';
										$ppnGantiUsr = '10';
										$totBiayaGantiUsr = $biayaGantiUsr + ($biayaGantiUsr * $ppnGantiUsr / 100);
									?>
									<h4>Email pengguna lama</h4>
									<input type="text" name="emailLama" id="emailLama" class="form-control" placeholder="Email Pengguna Lama" autocomplete="off" readonly style="background: #d15b4742 !important;">
									<small><strong>*Pergantian email user dikenakan biaya administrasi sebesar Rp. <?=number_format($totBiayaGantiUsr)?> (sudah termasuk PPN <?=$ppnGantiUsr?>%)</strong></small>
								</div>
								<div class="col-md-12">
									<h4>Masukkan email user yang diberikan hak akses database ini, password akses dikirimkan ke email user ini</h4>
								</div>
								<div class="col-md-12">
									<input type="text" onkeypress="return goodchars(event, 'abcdefghijklmnopqrstuvwxyz0123456789@._', this)" name="email" id="email" class="form-control" placeholder="Email Pengguna" autocomplete="off">
									<small><strong>*Password dikirim ke email user yang ditambahkan </strong></small>
								</div>
								<input type="hidden" name="dbname" id="dbname">
								<input type="hidden" name="versi" id="versi">
								<input type="hidden" name="enddate" id="enddate">
								<input type="hidden" name="addUser_used" id="addUser_used">
							</div>
						</form>
						<div class="row">
							<div class="col-md-12">					
								<!-- <button id="hapus" class="btn btn-warning btn-sm pull-left hidden" onclick="hapusUser()">Hapus</button> -->
                                <button id="hapustagihan" class="btn btn-danger btn-sm pull-left hidden disabled" onclick="hapusTagihan()" style="margin-right:20px;">Hapuskan Tagihan</button>
								<button id="tambahtagihan" class="btn btn-success btn-sm pull-left hidden disabled" onclick="tambahTagihan()" style="margin-right:20px;">Aktifkan Tagihan</button>
								<button id="nonaktif" class="btn btn-danger btn-sm pull-left hidden disabled" onclick="nonaktifUser()" style="margin-right:20px;">Nonaktifkan Pengguna</button>
								<button id="aktif" class="btn btn-success btn-sm pull-left hidden disabled" onclick="aktifUser()"  style="margin-right:20px;">Aktifkan Pengguna</button>
								<button id="gantiUser" class="btn btn-danger btn-sm pull-left hidden" onclick="gantiUser()">Ganti Pengguna</button>
								<button id="simpangantiUser" class="btn btn-success btn-sm pull-right hidden" style="margin-left: 20px;" onclick="simpangantiUser()">Simpan</button>
								<button id="simpan" class="btn btn-primary btn-sm pull-right" onclick="simpanUser()">Simpan</button>
								<button id="batal" class="btn btn-dark btn-sm pull-right hidden" onclick="reset()">Batal</button>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-md-12">
								<br>
								<br>
								<div style="max-height: 200px; overflow-y: scroll;">
									<table class="table table-bordered" id="tbl-user">
										<thead>
											<th style="text-align: left">
												Email 
											</th>
											<th style="text-align: left; width: 100px">
												Jenis Akses
											</th>
											<th style="text-align: left; width: 100px">
												Status Langganan
											</th>
                                            <th style="text-align: left; width: 100px">
												Status User
											</th>
											<th style="text-align: center; width: 50px">
												Valid
											</th>
											<th style="width: 50px">
												Tagihan
											</th>
											<th style="width: 50px">
												Admin Billing
											</th>
										</thead>
										<tbody>
											<tr class="baris">
												<td>fzul@gmail.com</td>
												<td>Administrator</td>
												<td>Register</td>
                                                <td>Nonaktif</td>
												<td>Valid</td>
												<td>0</td>
											</tr>
											<tr class="baris">
												<td>1231@gmail.com</td>
												<td>Operator</td>
												<td>Register</td>
                                                <td>Nonaktif</td>
												<td>Valid</td>
												<td>0</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
			</div> <!-- /.modal-table -->

			<div id="modal-login" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-md" >
					<div class="modal-content" style="alignment-adjust:central">
						<div class="modal-header no-padding">
							<div class="table-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">&times;</span>
								</button>
								LOGIN SISERP
							</div>
						</div>
						<form class="form-horizontal" id="form-loginApp" method="post">
						<!-- Info Database -->
						<div class="modal-body">
							
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-lg-3">
										<label for="password">Password</label>
									</div>
									<div class="col-md-9 col-sm-9 col-lg-9">
										<input type="password" name="password" placeholder="Masukkan Password" class="form-control" autocomplete="off" autofocus>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-lg-3">
										<label style="text-align: left !important">Periode</label>
									</div>
									<div class="col-md-4 col-sm-4 col-lg-4">
	                                    <select id="month" name="month" class="form-control">
	                                        <option value="01" <?=$sel1?>>Jan</option>
	                                        <option value="02" <?=$sel2?>>Feb</option>
	                                        <option value="03" <?=$sel3?>>Mar</option>
	                                        <option value="04" <?=$sel4?>>Apr</option>
	                                        <option value="05" <?=$sel5?>>Mei</option>
	                                        <option value="06" <?=$sel6?>>Jun</option>
	                                        <option value="07" <?=$sel7?>>Jul</option>
	                                        <option value="08" <?=$sel8?>>Agt</option>
	                                        <option value="09" <?=$sel9?>>Sep</option>
	                                        <option value="10" <?=$sel10?>>Okt</option>
	                                        <option value="11" <?=$sel11?>>Nov</option>
	                                        <option value="12" <?=$sel12?>>Des</option>
	                                    </select>
									</div>
									<div class="col-md-5 col-sm-5 col-lg-5">
										<div class="input-group">
											<input type="text" name="year" id="year" class="form-control datepicker" onKeyPress="return goodchars(event, '0123456789', this)" maxlength="4" minlength="4" autocomplete="off" required>
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>

								<!-- UBAHAN DIM START -->
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-lg-3">
										<label style="text-align: left !important">Cabang</label>
									</div>
									<div class="col-md-9 col-sm-9 col-lg-9">
	                                    <select id="pilCab" name="pilCab" class="form-control">
	                                        <!-- <option value="">Pilih Cabang</option> -->
	                                        
	                                    </select>
									</div>
								</div>
								<!-- UBAHAN DIM END -->

								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-lg-3">
										<label style="text-align: left !important">Periode Database</label>
									</div>
									<div class="col-md-9 col-sm-9 col-lg-9">
										<label id="label1" style="text-align: left !important"></label>
										<label id="label2" style="text-align: left !important"></label>
									</div>
								</div>

								<input type="hidden" name="dbname" id="dbname">
                                <input type="hidden" name="companyname" id="companyname">
                                <input type="hidden" name="begindate" id="begindate">
                                <input type="hidden" name="enddate" id="enddate">
                                <input type="hidden" name="used" id="used">
                                <input type="hidden" name="versi" id="versi">
								<!--<input type="hidden" name="email" value="<?=$_SESSION['custEmail'];?>" id="email">-->
                                <input type="hidden" name="custid" id="custid">

						</div><!-- /.row -->
						</form>
						<div class="modal-footer">
							<button type="submit" id='loginApp' onclick="loginApp()" class="btn btn-primary">
								Login
							</button>
						</div>
						
					</div> <!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div> <!-- /.modal-table -->

			<!-- <div id="modal-copy-master" class="modal fade" tabindex="-1">
				<div class="modal-dialog" >
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">&times;</span>
								</button>
								Copy Database Master
							</div>
						</div>
						<form class="form-horizontal" id="form-copyMaster" action="action.php" method="post">
						
							<div class="modal-body">
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-lg-3">
										<label for="password">Database</label>
									</div>
									<div class="col-md-9 col-sm-9 col-lg-9">
										<label id="db-now"></label>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-lg-3">
										<label for="password">Database Asal *</label>
									</div>
									<div class="col-md-9 col-sm-9 col-lg-9">
										<select name="pilihDb" id="pilihDb" class="form-control">
											<option value="">Pilih Database</option>
											
										</select>
									</div>
								</div>

							</div>
						</form>
						<div class="modal-footer">
							<button type="submit" id='proses' class="btn btn-primary">
								Proses
							</button>
						</div>
						
					</div> 
				</div>
			</div> -->

				<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
					<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
				</a>
		</div> <!-- /.ace-save-state -->

	
<!-- tooltips -->
	<script type="text/javascript">
		jQuery(function($) {
			$( "#show-option" ).tooltip({
					show: {
						effect: "slideDown",
						delay: 250
					}
				});
			
			$( "#hide-option" ).tooltip({
				hide: {
					effect: "explode",
					delay: 250
				}
			});
			
			$( "#open-event" ).tooltip({
				show: null,
				position: {
					my: "left top",
					at: "left bottom"
				},
				open: function( event, ui ) {
					ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
				}
			});

			$(document).on('click','#db-button');
		})

		var date = new Date();
		$('#year').val(date.getFullYear());


		// $('#modal-copy-master').on('hidden.bs.modal', function(){
			
		// 	$('#pilihDb').val('');
		// 	$('#modal-copy-master .modal-body .alert').remove();
		// })

	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#modal-data').on('hidden.bs.modal', function(){
				$('#addBranch').attr('disabled',false)
				$('#branchContainer').hide();
				$('#modal-data input').val('');
			})
		})
	</script>

	<script type="text/javascript">

		// $(document).ready(function(){

		// 	var text = "Cek tagihan pembayaran Anda";

		// 	$(".label-holder#jumlah_tagihan").on('mouseenter', function() {
		// 		alert('ok')
		// 	    $("#info").append(text);
		// 	},
		// 	function() {
		// 	    $("#info").slideUp(200);
		// 	});
			
		// })
		$("#jumlah_tagihan").mouseenter(function(){
			console.log('ok')
	        $("#info").css('display', 'block')
        	$('#tagno').css('display', 'none')
		});
		$("#jumlah_tagihan").mouseleave(function(){
			console.log('no')
        	$('#tagno').css('display', 'block')
        	$('#info').css('display', 'none')
		});

	</script>
</body>
</html>