<?php
	session_start();
	include 'includes/style.php';
	require_once '../includes/koneksi2.php';

	$host 	= $_SERVER['HTTP_HOST'];
	$host2 = "https://$_SERVER[SERVER_NAME]/yusuf/siserp/module/reg/";
	$dbname	= $_GET['dbname'];
	
	if (isset($_GET['dbname'])) {
		$dbname = $_GET['dbname'];

		$query = mysql_query("SELECT DISTINCT inv.inv_no, inv.inv_date, inv.due_date, inv.paid_date, inv_reff, 
								c.email, (inv.total_amount + inv.initial_amount + inv.ppn - inv.discount) AS grand_total,
								inv.paid_off, inv.stsrec, ind.end_date, ind.expired_date, ind.inv_id       
								FROM cust_order co 
								JOIN invoice_detail ind ON ind.order_id = co.order_id 
								LEFT JOIN invoice inv ON inv.id = ind.inv_id 
								LEFT JOIN cust c ON c.id = inv.cust_id 
								WHERE co.dbname = '$dbname' 
								ORDER BY inv.inv_no DESC") or die(mysql_error());    
	}
	$buatTagihan = '';
	$cntOuts = 0;
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

		<link rel="stylesheet" href="assets/datatables/jquery.dataTables.min.css" />

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
			body {
				background-color: white !important;
			}
			.right{
				text-align: right;
			}
			.center{
				text-align: center;
			}
			.navbar {
				background-color: #ffffff00;
				box-shadow:none !important;
			}
			.ace-nav>li.light-blue>a {
			    background-color: #62a8d130;
			}
			.info {
				float: right; margin-top: -30px; display: inline-block; color: #708090; 
				font-size: 13.5px;
			}
			.info:hover {
				text-decoration: none; font-weight: 500px;
				padding-top: 1px;
			}
		</style>
	</head>
	
	<body class="main-container">
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="" class="navbar-brand">
						<small>
							<a href="account.php"><img class="pos_img" src="../img/LOGO-SISCOM.png" style="max-height: 60px"></a>
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

								<!--<li>
									<a href="ubah_password.php">
										<i class="ace-icon fa fa-user"></i>
										Profile
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
		<div class="container" style="margin-top: 30px;">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row" style="display: flex; justify-content: center; align-items: center;width: 100%;">
						<!-- table -->
						<div class="col-xs-12">

							<h3 class="smaller lighter blue">Riwayat Tagihan Database : <?=$dbname?></h3>
							<a href="https://youtu.be/IcLU8QY7F_Q" target="_blank" class="info">
								<img src="<?php echo $host2; ?>img/Info-merah.png" width="15" style="margin: -2px 1px 0 !important;">
								Cara Membuat Tagihan
							</a>
							<br>

							<div class="clearfix">
							</div>

		    				<form id="infotagihan" enctype="multipart/form-data" method="post" action="action.php">
								<div>
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th class="center">
													<label class="pos-rel">
														<!-- <input type="checkbox" class="ace" /> -->
														<span class="lbl"></span>
													</label>
												</th>
												<th>#Tagihan</th>
												<!--<th>Email User</th>-->
												<th class="center">Tgl Tagihan</th>
												<th class="center">Jatuh Tempo</th>
												<th class="center">Masa Tenggang Pemakaian</th>
												<th>Paket</th>
												<!--<th class="center">Tgl Bayar</th>-->
												<th class="right">Total</th>
												<th class="hidden-480">Status Pembayaran</th>
												<th>Referensi Tagihan</th>
												<!-- <th class="hidden-480">Stsrec</th> -->
												<th class="center" width="20px">Faktur Pajak</th>
												<th width="75px">Action</th>
											</tr>
										</thead>

										<tbody>
											<?php
												$i = 1;
												while($data = mysql_fetch_array($query)){
													
													$inv_no 		= $data['inv_no'];
													$inv_reff 		= $data['inv_reff'];
													$email 			= $data['email'];
												    $inv_date 		= date('d-m-Y', strtotime($data['inv_date']));
												    $due_date 		= date('d-m-Y', strtotime($data['due_date']));
												    $paid_date 		= ($data['paid_date'] != '0000-00-00') ? date('d-m-Y', strtotime($data['paid_date'])) : '';
												    $grand_total 	= number_format($data['grand_total'],2, ',','.');
												    $paid_off 		= $data['paid_off'];
													$stsrec 		= $data['stsrec'];
												    $hrefConfirm 	= "faktur.php?inv_no=$inv_no";
												    $hrefDelete 	= '#';
													// $orderId		= $data['order_id'];
													$end_date   	= date('d-m-Y', strtotime($data['end_date']));
													$exp_date   	= date('d-m-Y', strtotime($data['expired_date']));
													$cur_date 		= date('Y-m-d');
													
													//$sqlIv = mysql_query("SELECT end_date, expired_date 
													//						FROM cust_order     
													//						WHERE order_id = '$orderId' 
													//						AND stsrec = 'A'
													//						ORDER BY begin_date DESC LIMIT 1") or die(mysql_error()); 		
													//$rowIv = mysql_fetch_array($sqlIv);
													//$end_date   	= date('d-m-Y', strtotime($rowIv['end_date']));
													//$exp_date   	= date('d-m-Y', strtotime($rowIv['expired_date']));
													
													$totpackage1 = 0;
													$totpackage2 = 0;
													$idpackage1 = 0;
													$idpackage2 = 0;
													$namepackage1 = '';
													$namepackage2 = '';
													$idPaket = '';
													$namaPaket = '';
													$ast = '';
													$package_type = '';
													$qry = mysql_query("SELECT inv.inv_no, ind.package_id, p.package_type, s.name AS subscribe_name,  
																		COUNT(p.package_type) AS tot_type 
																		FROM invoice inv JOIN invoice_detail ind ON ind.inv_id = inv.id 
																		JOIN cust_order co ON co.order_id = ind.order_id 
																		JOIN package p ON p.id = ind.package_id 
																		JOIN subscribe s ON s.id = p.subscribe_id 
																		WHERE inv.inv_no = '$inv_no' 
																		GROUP BY inv.inv_no, ind.package_id, p.package_type, s.name  
																		ORDER BY p.package_type") or die(mysql_error());
													while($data1 = mysql_fetch_array($qry)){
														$package_id = $data1['package_id'];
														$package_type = $data1['package_type'];
														if($data1['package_type'] == '1' or $data1['package_type'] == '3') {
															$totpackage1 = $data1['tot_type'];
															$idpackage1 = $data1['package_type'];
															$namaPaket = $data1['subscribe_name'];
														} else if($data1['package_type'] == '2') {
															$totpackage2 = $data1['tot_type'];
															$idpackage2 = $data1['package_type'];
														} 
														//$namaPaket = $data1['subscribe_name'];
													}
													
													if($totpackage1 == 0) {
														if($totpackage2 > 0) {
															$idPaket = $idpackage2;
															$ast = '';
														} 
													} else {
														$idPaket = $idpackage1;
														if($totpackage2 > 0) {
															$ast = ' (+'.$totpackage2.')';
														} else {
															$ast = '';
														}
													}
													
													if($package_type == '2' and $totpackage1 == 0) {
														$sqltbh = mysql_query("SELECT co.order_id, co.cust_id, co.dbname, co.ver_id, co.package_id, co.company_id, 
																	p.package_type, s.name AS subscribe_name, v.name AS ver_name, s.value_month 
																	FROM `cust_order` co 
																	LEFT JOIN `package` p ON p.id = co.package_id 
																	LEFT JOIN `subscribe` s ON s.id = p.subscribe_id 
																	LEFT JOIN `version` v ON v.id = p.ver_id 
																	WHERE co.dbname = '$dbname' AND p.package_type = 1") or die (mysql_error());
														$rowtbh = mysql_fetch_array($sqltbh);
														$month = $rowtbh['value_month'];
														$namaPaket = $rowtbh['subscribe_name'];
													}
													
													//$qryPaket = mysql_query("SELECT name FROM package_type   
													//							WHERE id = '$idPaket'") or die(mysql_error());
													//$rowPaket = mysql_fetch_array($qryPaket);
													//$namaPaket = $rowPaket['name'];

													// Cek referensi invoicenya dibayar atau tidak
													$ststgh = '';
													if($inv_reff != '') {
														$sqltgh = mysql_query("SELECT stsrec FROM `invoice`  
																	WHERE inv_no = '$inv_reff'") or die (mysql_error());
														$rowtgh = mysql_fetch_array($sqltgh);
														$ststgh = $rowtgh['stsrec'];
													} 

													$onclickDel = "";

													if($stsrec == 'D') {
														if($inv_reff == '') {
															$sts_bayar = 'BATAL';
															$color = 'label-black';
															$confirmColor = '#69AA46!important';
															$hrefTagihan = "#";
															$hrefPajak = "#";
															$tagihanColor = "#337ab780!important";
															$pajakColor = "#337ab780!important";
														} else {
															if($ststgh == 'A') {
																$sts_bayar = 'REVISI';
																$color = 'purple';
																$hrefTagihan = "#";
																$hrefPajak = "#";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#337ab780!important";
															} else if($ststgh == 'D') {
																$sts_bayar = 'BATAL';
																$color = 'label-black';
																$confirmColor = '#69AA46!important';
																$hrefTagihan = "#";
																$hrefPajak = "#";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#337ab780!important";
															}
														}
														$onclickDel = "return false;";
														$batalColor = "gray";
														/*if((strtotime($data['end_date']) < strtotime($cur_date)) && (strtotime($data['expired_date']) >= strtotime($cur_date))) {
															if($inv_reff == '') {
																$sts_bayar = 'MASA TENGGANG';
																$color = 'warning arrowed-right';
																$confirmColor = '#69AA46!important';
																$hrefTagihan = "#";
																$hrefPajak = "faktur_pajak.php?dbname=$dbname";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#3A87AD";
																$buatTagihan = 'disabled';
																$cntOuts++;
															} else {
																$sts_bayar = 'REVISI';
																$color = 'purple';
																$hrefTagihan = "#";
																$hrefPajak = "#";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#337ab780!important";
															}
														} else {
															if($inv_reff == '') {
																$sts_bayar = 'BATAL';
																$color = 'label-black';
																$confirmColor = '#69AA46!important';
																$hrefTagihan = "#";
																$hrefPajak = "#";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#337ab780!important";
															} else if($inv_reff != '') {
																$sts_bayar = 'REVISI';
																$color = 'purple';
																$hrefTagihan = "#";
																$hrefPajak = "#";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#337ab780!important";
															}
														}*/
														//$cntOuts++;
														/*if($paid_off == 'C') {
															$sts_bayar = 'BATAL';
															$color = 'label-black';
															$confirmColor = '#69AA46!important';
															$hrefTagihan = "#";
															$hrefPajak = "#";
															$tagihanColor = "#337ab780!important";
															$pajakColor = "#337ab780!important";
														} else {
															if((strtotime($data['end_date']) < strtotime($cur_date)) && (strtotime($data['expired_date']) >= strtotime($cur_date))) {
																if($inv_reff == '') {
																	$sts_bayar = 'MASA TENGGANG';
																	$color = 'warning arrowed-right';
																	$confirmColor = '#69AA46!important';
																	$hrefTagihan = "#";
																	$hrefPajak = "faktur_pajak.php?dbname=$dbname";
																	$tagihanColor = "#337ab780!important";
																	$pajakColor = "#3A87AD";
																	$buatTagihan = 'disabled';
																} else if($inv_reff != '') {
																	$sts_bayar = 'REVISI';
																	$color = 'purple';
																	$hrefTagihan = "#";
																	$hrefPajak = "#";
																	$tagihanColor = "#337ab780!important";
																	$pajakColor = "#337ab780!important";
																}
																/*$sts_bayar = 'MASA TENGGANG';
																$color = 'warning arrowed-right';
																$confirmColor = '#69AA46!important';
																$hrefTagihan = "#";
																$hrefPajak = "faktur_pajak.php?dbname=$dbname";
																$tagihanColor = "#337ab780!important";
																$pajakColor = "#3A87AD";
																$buatTagihan = 'disabled';
																$cntOuts++;
															} else {
																if($paid_off == 'C') {
																	$sts_bayar = 'BATAL';
																	$color = 'label-black';
																	$hrefTagihan = "#";
																	$hrefPajak = "#";
																	$tagihanColor = "#337ab780!important";
																	$pajakColor = "#337ab780!important";
																} else {
																	//echo $inv_reff.'.'.$paid_off;
																	if($inv_reff == '') {
																		$sts_bayar = 'BELUM DIBAYAR';
																		$color = 'danger arrowed-right';
																		$hrefTagihan = "tagihan.php?dbname=$dbname&invoice=$inv_no";
																		$hrefPajak = "faktur_pajak.php?dbname=$dbname";
																		$buatTagihan = 'disabled';
																		$tagihanColor = "#3A87AD";
																		$pajakColor = "#3A87AD";
																		$cntOuts++;
																	} else if($inv_reff != '') {
																		$sts_bayar = 'REVISI';
																		$color = 'purple';
																		$hrefTagihan = "#";
																		$hrefPajak = "#";
																		$tagihanColor = "#337ab780!important";
																		$pajakColor = "#337ab780!important";
																	}
																}
																$confirmColor = '#69AA46!important';
															}
														}*/
													} else if($stsrec == 'A') {
														if($paid_off == 'Y'){
															$sts_bayar = 'LUNAS';
															$color = 'success arrowed-right arrowed-in';
															$confirmColor = '#69AA46!important';
															$hrefTagihan = "#";
															//$hrefPajak = "#";
															$hrefPajak = "faktur_pajak.php?dbname=$dbname";
															$tagihanColor = "#337ab780!important";
															$pajakColor = "#3A87AD";
															//$pajakColor = "#337ab780!important";
															$buatTagihan = '';
															$onclickDel = "return false;";
															$batalColor = "gray";
														}
														elseif($paid_off == 'C'){
															$sts_bayar = 'DIPROSES';
															$color = 'info arrowed-right arrowed-in';
															$confirmColor = '#69AA46!important';
															$hrefTagihan = "#";
															$hrefPajak = "#";
															$tagihanColor = "#337ab780!important";
															$pajakColor = "#337ab780!important";
															$buatTagihan = 'disabled';
															$onclickDel = "return false;";
															$batalColor = "gray";
															$cntOuts++;
														}
														elseif ($paid_off == 'N'){
															if((strtotime($data['end_date']) < strtotime($cur_date)) && (strtotime($data['expired_date']) >= strtotime($cur_date))) {
																$sts_bayar = 'MASA TENGGANG';
																$color = 'warning arrowed-right';
																$confirmColor = '#69AA46!important';
																$hrefTagihan = "#";
																$hrefPajak = "#";
																//$hrefPajak = "faktur_pajak.php?dbname=$dbname";
																$tagihanColor = "#337ab780!important";
																//$pajakColor = "#3A87AD";
																$pajakColor = "#337ab780!important";
																$buatTagihan = 'disabled';
																$onclickDel = "return false;";
																$batalColor = "gray";
																$cntOuts++;
															} else {
																$sts_bayar = 'BELUM DIBAYAR';
																$color = 'danger arrowed-right';
																$confirmColor = '#69AA46!important';
																$hrefTagihan = "tagihan.php?dbname=$dbname&invoice=$inv_no";
																$hrefPajak = "#";
																//$hrefPajak = "faktur_pajak.php?dbname=$dbname";
																$tagihanColor = "#3A87AD";
																//$pajakColor = "#3A87AD";
																$pajakColor = "#337ab780!important";
																$buatTagihan = 'disabled';
																$onclickDel = "batal_tagihan('$inv_no')";
																$batalColor = "#f44336";
																$cntOuts++;
															}
														}
													}

													// paket ganti user
													if($package_type == '4' and $totpackage1 == 0){
														$namaPaket = 'Ganti User';
														$hrefTagihan = "#";
														//$tagihanColor = "#337ab780!important";
														$hrefConfirm 	= "faktur.php?inv_no=$inv_no";
													}
													else if($package_type == '5' and $totpackage1 == 0){
														$namaPaket = 'Tambah Cabang';
														//$hrefTagihan = "#";
														//$tagihanColor = "#337ab780!important";
														$hrefConfirm 	= "faktur.php?inv_no=$inv_no";
													}

											?>
											<tr id="row<?=$i?>">
												<td class="center">
													<label class="pos-rel">
														<input id="idx<?=$i?>" name="idx<?=$i?>" type="checkbox" class="ace" onclick="getInvNo(this)" />
														<span class="lbl"></span>
													</label>
												</td>

												<td><?=$inv_no?></td>
												<!--<td><?=$email?></td>-->
												<td class="center"><?=$inv_date?></td>
												<td class="center"><?=$due_date?></td>
												<td class="center"><?=$end_date.' sd '.$exp_date?></td>
												<td><?=$namaPaket.$ast?></td>
												<!--<td class="center"><?=$paid_date?></td>-->
												<td class="right"><?=$grand_total?></td>
												<td class="hidden-480">
													<span class="label label-sm label-<?=$color?>"><?=$sts_bayar?></span>
												</td>
												<!-- <td class="hidden-480 center">
													<span class="label label-sm label-<?=$color2?>"><?=$status?></span>
												</td> -->
												<td><?=$inv_reff?></td>
												<td class="center action-buttons">
													<a style="color:<?=$pajakColor?>" href="<?=$hrefPajak?>" title="Faktur Pajak">
														<i class="ace-icon fa fa-book bigger-130"></i>
													</a>
												</td>

												<td class="center">
													<div class="action-buttons">
														<a style="color:<?=$tagihanColor?>" href="<?=$hrefTagihan?>" title="Ubah Metode Pembayaran">
															<i class="ace-icon fa fa-credit-card bigger-130"></i>
														</a>

														<a style="color:<?=$confirmColor?>" href="<?=$hrefConfirm?>" title="Lihat Faktur" >
															<i class="ace-icon fa fa-search bigger-130"></i> 
														</a>

				                                        <a style="color:<?=$batalColor?>; cursor: pointer;" id="btnDelete" name="btnDelete" onclick="<?=$onclickDel?>">
				                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
				                                        </a>
				                                        
													</div>

													<div class="hidden-md hidden-lg">
														<div class="inline pos-rel">
															<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
															</button>

															<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																<li>
																	<a style="color:<?=$tagihanColor?>" href="<?=$hrefTagihan?>" title="Ubah Metode Pembayaran">
																		<i class="ace-icon fa fa-credit-card bigger-130"></i>
																	</a>
																</li>

																<li>
																	<a style="color:<?=$confirmColor?>" href="<?=$hrefConfirm?>" title="Lihat Faktur" >
																		<i class="ace-icon fa fa-search bigger-130"></i>
																	</a>
																</li>
																
																<li>
																	<a href="<?=$hrefDelete?>" title="Hapus Faktur" onclick="<?=$onclickDel?>">
																		<i class="ace-icon fa fa-delete bigger-130"></i>
																	</a>
																</li>
															</ul>
														</div>
													</div>
												</td>
											</tr>
											<?php
													$i++;
											 	} 
											 	
											 	if($cntOuts > 0) {
											 		$buatTagihan = 'disabled';
											 	}

										 	?>
										</tbody>
									</table>
								</div>
								
								<input type="hidden" name="custIDInfo" id="custIDInfo" value="<?=$_SESSION['custID']?>">
								<input type="hidden" name="dbnameInfo" id="dbnameInfo" value="<?=$dbname?>">
								
								<div class="pull-left" style="margin-top: 30px">
			                		<button type="button" class="btn btn-primary btn-lg" onclick="konfirmasi()">Konfirmasi Pembayaran</button><br><br>
			            		</div>
								<div class="pull-left" style="margin-top: 30px">
			                		&nbsp;
			            		</div>

								<div class="pull-left" style="margin-top: 30px">
			                		<button type="button" id="buatTagihan" <?=$buatTagihan?> class="btn btn-info btn-lg" onclick="buat_tagihan()">Buat Tagihan</button><br><br>
			            		</div>
								<div class="pull-right" style="margin-top: 30px">
			                		<a href="account.php" class="btn btn-warning btn-lg" >Kembali</a><br><br>
			            		</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>

		</div>

		<script src="assets/js/jquery-2.1.4.min.js"></script>
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
		<script src="assets/js/bootstrap-datepicker.min.js"></script>

		<script src="assets/datatables/jquery.dataTables.min.js"></script>
		<script src="assets/sweetalert/sweetalert2.all.min.js"></script>

		<script type="text/javascript">		
			$('#dynamic-table').DataTable()

			var getInv = [];
			var getPaid = [];

			function getInvNo(el){
				
				var idTr = $(el).closest('tr').attr('id');
				
				var check =el.checked;
				inv_no = $('#'+idTr+' td:nth-child(2)').text();
				due_date = $('#'+idTr+' td:nth-child(4)').text();
				grace = $('#'+idTr+' td:nth-child(5)').text();
				paid_off = $('#'+idTr+' td:nth-child(8) span').text().replace(' ','')
				now = new Date();
				end_date = grace.substr(0,10);
				exp_date = grace.substr(14,10);
				var tgl1 = end_date.split('-');
				tgl1 = tgl1[1]+'-'+tgl1[0]+'-'+tgl1[2];
				var tgl2 = exp_date.split('-');
				tgl2 = tgl2[1]+'-'+tgl2[0]+'-'+tgl2[2];
				
				tgl_end = new Date(tgl1);
				tgl_exp = new Date(tgl2);

				// alert(check)
				if(check){
					var n = getInv.includes(inv_no)
					
					if(paid_off == 'LUNAS'){
						Swal.fire({
							title : 'Success',
							html : '<h4>Tagihan sudah LUNAS. Silakan pilih tagihan yang belum dibayar.</h4>',
							type : 'warning',
							position: 'top',
							confirmButtonColor : '#3085d6',
						})
						$(el).attr('checked', false)
					}
					else if(paid_off == 'DIPROSES'){
						Swal.fire({
							title : 'Warning',
							html : '<h4>Tagihan sudah DIPROSES. Silakan pilih tagihan yang belum dibayar.</h4>',
							type : 'warning',
							position: 'top',
							confirmButtonColor : '#3085d6',
						})
						$(el).attr('checked', false)
					}
					else if(paid_off == 'BATAL'){
						Swal.fire({
							title : 'Danger',
							html : '<h4>Tagihan sudah DIBATALKAN oleh Finance. Tidak bisa melakukan pembayaran.</h4>',
							type : 'warning',
							position: 'top',
							confirmButtonColor : '#3085d6',
						})
						$(el).attr('checked', false)
					}
					else if(paid_off == 'REVISI'){
						Swal.fire({
							title : 'Danger',
							html : '<h4>Tagihan sudah DIREVISI oleh User. Tidak bisa melakukan pembayaran.</h4>',
							type : 'warning',
							position: 'top',
							confirmButtonColor : '#3085d6',
						})
						$(el).attr('checked', false)
					}
					else{
						if (!n) {
							getInv.push(inv_no);
						}
					}
					
				}else{
					invoice = getInv.filter(function(data){
							return data != this
					}, inv_no)

					getInv = invoice
				}

				console.log(getInv)
			}

			function konfirmasi(){
				var host = window.location.hostname;
				invNo = getInv.join('_');

				if (getInv.length <= 0) {
					// alert('Silahkan ceklis tagihan terlebih dahulu.')
					Swal.fire({
						title : 'Warning',
						html : '<h4>Silakan pilih tagihan terlebih dahulu.</h4>',
						type : 'warning',
						position: 'top',
						confirmButtonColor : '#3085d6',
					})	
					
				}
				else{
					//window.location = 'https://'+host+'/siserp/module/reg/pages/konfirmasi_bayar.php?inv_no='+invNo;
					window.location = '<?=$abs?>/reg/pages/konfirmasi_bayar.php?inv_no='+invNo;
				}

				// console.log(typeof invNo)
				// console.log(invNo)
			}
			
			function buat_tagihan(){
				var host = window.location.hostname;
				var custID = $('#custIDInfo').val();
				var dbname = $('#dbnameInfo').val();
				//alert(custID);
				//alert(dbname);
				//alert(ver_id);
				Swal.fire({
					title : 'Apakah Anda yakin?',
					html : '<h4>Buat tagihan sekarang.</h4>',
					type : 'warning',
					position: 'top',
					showCancelButton : true,
					confirmButtonColor : '#3085d6',
					cancelButtonColor : '#d33',
					confirmButtonText: 'Ya, buat tagihan!',
					cancelButtonText :'Batal' 
				}).then((result) => {
					if(result.value){
						$.ajax({
							type:'post',
							dataType:'json',
							url:'action.php',
							data:{action:'buattagihan', cust_id:custID, dbname:dbname},
							success: function(record){
								if (record.status=='success') {
									swal.fire(
										'Proses selesai!',
										'Tagihan sudah dibuat.',
										'success'
									)
									window.location.reload(); 
								}
								
							}
						})
					}
				})
			}

			function batal_tagihan(inv_no){
				
				Swal.fire({
					title : 'Apakah Anda yakin?',
					html : '<h4>Batalkan tagihan.</h4>',
					type : 'warning',
					position: 'top',
					showCancelButton : true,
					confirmButtonColor : '#3085d6',
					cancelButtonColor : '#d33',
					confirmButtonText: 'Ya, batalkan tagihan!',
					cancelButtonText :'Batal' 
				}).then((result) => {
					if(result.value){
						$.ajax({
							type:'post',
							dataType:'json',
							url:'action.php',
							data:{action:'bataltagihan', inv_no:inv_no},
							success: function(record){
								if (record.status=='success') {
									swal.fire(
										'Proses selesai!',
										'Tagihan sudah dibatalkan.',
										'success'
									)
									window.location.reload(); 
								}
								
							}
						})
					}
				})
			}
		</script>
	</body>
</html>