<?php

session_start();
include 'includes/style.php';
require_once '../includes/koneksi2.php';

$custID = $_SESSION['custID'];
$inv_no = explode('_', $_GET['inv_no']);

foreach ($inv_no as $key => $value) {
	$query 	= mysql_query("SELECT * FROM invoice WHERE inv_no = '$value'") or die (mysql_error());
	while ( $row	= mysql_fetch_array($query)) {
		$data[] = $row;
	}
}

// echo"<pre>";
// print_r($data);
	
			// $inv_date 		= $row['inv_date'] ;
			// $due_date 		= $row['due_date'] ;
			// $total_amount1  = $row['total_amount'];
			// $initial_amount = $row['initial_amount'];
			// $keterangan 	= $row['paid_remark'];
			// $ppn 			= $row['ppn'];
	$count = count($inv_no);
	if($count != 0){
		for ($i=0; $i < $count; $i++) { 
			$paidOff 		= $data[$i]['paid_off'];
			$inv_No 		= $data[$i]['inv_no'];
			$receiptBank 	= $data[$i]['receipt_bank'];
			$keterangan 	= $data[$i]['paid_remark'];
		}
	}

	$name 			= ($paidOff == 'C') ? $paidName : $_SESSION['custName'];
	$paid_date 		= $row['paid_date'];

	if($paidOff == 'C'){
		$disabled 	= 'disabled';
		$none 		= 'block';
		$warning 	= '<strong>Pembayaran  Anda Sudah diproses</strong>, mohon menunggu konfirmasi.';
	}
	elseif($paidOff == 'Y'){
		$disabled 	= 'disabled';
		$none 		= 'block';
		// $paid_date  = date('d-M-Y', strtotime($paid_date));
		$warning 	= "<strong>Tagihan sudah dibayar</strong>";
	}
	else{
		$disabled 	= '';
		$none 		= 'none';
		$warning 	= '';
	}

	$scDbname = mysql_query("SELECT I.id, I.inv_no, I.cust_id, D.order_id, CO.dbname AS dbname, C.email AS email 
						FROM `invoice` I 
						LEFT JOIN invoice_detail D ON D.inv_id = I.id
						LEFT JOIN cust_order CO ON CO.order_id = D.order_id
						LEFT JOIN cust C ON C.id = I.cust_id
						WHERE inv_no = '$inv_No'") or die(mysql_error());

	while ($rowdb = mysql_fetch_array($scDbname)) {
	 	$dbname = strtolower($rowdb['dbname']);
	 	$email 	= $rowdb['email'];
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
			.disabled{
				background-color: #eeeeee !important;
			}
			body{
				font-size: 12px !important;
			}
			.control-label{
				font-size: 12px !important;
			}
			#bukti{
				font-size: 12px !important;
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
		<div class="container" style="margin-top: 30px;">
			<div class="row" style="display: flex; justify-content: center; align-items: center;width: 100%;">
				<div class="col-md-6 col-sm-6 col-lg-6">
					<div class="panel panel-primary">
						<div class="panel-heading" style="text-align: center;">
							<h3 style="padding: 0px !important; margin: 0px;">Faktur Proforma</h3>
						</div>
						<div class="panel-body">
							<div class="alert alert-danger alert-dismissible" role="alert" style="display: <?=$none?>"><button type="button" class="close" style="font-size: 25px; font-weight:bold" data-dismiss="alert" aria-label="close"><span aria-hidden="true">×</span></button><?=$warning?></div>
							<div id="form" >
								<form id="form-konfirmasi" class="" method="post" action="act_konfirmasi.php" enctype="multipart/form-data">
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<div class="form-group">
												<label class="control-label">Nama Database</label>
												<input type="text" name="namaDb" id="namaDb" class="form-control disabled" value="<?=$dbname; ?>" readonly>
											</div>
										</div>
										<div class="col-lg-6 col-md-6">
											<div class="form-group">
												<label class="control-label">Email</label>
												<input type="text" name="email" id="email" class="form-control disabled" value="<?=$email?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<div class="form-group">
												<label class="control-label">Nama Pengirim*</label>
												<input type="text" name="nama" id="nama" class="form-control " placeholder="Masukkan nama pengirim" value="<?=$name; ?>" required <?=$disabled?>>
											</div>
										</div>
										<div class="col-lg-6 col-md-6">	
											<div class="form-group">
												<label class="control-label">Tgl. Transfer*</label>
												<input type="text" name="tgl_transfer" id="tgl_transfer" class="form-control datepicker" placeholder="Tanggal pembayaran" value="<?=date("d-m-Y")?>" onKeyPress="return goodchars(event, '0123456789-', this)" maxlength="10" required <?=$disabled?>>
											</div>
										</div>
									</div>
									<div class="row">
										<div style="padding: 15px">
											<table class="table">
												<thead>
													<tr>
														<td>No. Tagihan</td>
														<td>Tgl. Tagihan</td>
														<td>Jatuh Tempo</td>
														<td style="text-align: right; width: 100px">Total Amount</td>
													</tr>
												</thead>
												<tbody>

											<?php
											$total = 0;
												foreach ($data as $key => $value) {
													$discount = $value['discount'];
													$ppn = $value['ppn'];
													$initial_amount = $value['initial_amount'];
													$total_amount = 0;

													$paid_off = $value['paid_off'];
													$total_amount = $value['total_amount'] - $discount + $ppn + $initial_amount;
													if($paid_off == 'Y'){							
														$stsbyr = "Sudah Lunas : ".number_format($total_amount);
														$total_amount = 0;
													}
													elseif($paid_off == 'C'){
														$stsbyr = "Sudah dibayar : ".number_format($total_amount);
														$total_amount = 0;
													}
													elseif($paid_off == 'N'){
														$stsbyr = '';
													}
													$total += $total_amount;

											?>
													<tr>
														<td><?=$value['inv_no'];?></td>
														<td>
															<?=date('d-m-Y', strtotime($value['inv_date']));?>
														</td>
														<td>
															<?=date('d-m-Y', strtotime($value['due_date']));?>
														</td>
														<td style="text-align: right;">
															<?=number_format($total_amount);?>
														</td>
													</tr>
													
													<input type="hidden" name="inv_no[]" value="<?=$value['inv_no'];?>">
											<?php } ?>
													<tr style="background-color: #f3f3f3">
														<td colspan="3" style="text-align: right;">
															Total Pembayaran:
														</td>
														<td style="text-align: right;">
															<?=number_format($total);?>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
											<!-- <div class="col-lg-4 col-md-4">
												<div class="form-group">
													<label class="control-label">No. Tagihan</label>
													<input type="text" name="inv_no" id="inv_no" class="form-control " placeholder="Masukkan nomor invoice" value="<?=$value['inv_no'];?>" readonly>
												</div>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="form-group">
													<label class="control-label">Tgl. Invoice</label>
													<input type="text" name="tgl_inv" id="tgl_inv" class="form-control disabled" placeholder="Tanggal invoice" value="<?=date('d-m-Y', strtotime($value['inv_date']));?>" readonly>
												</div>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="form-group">
													<label class="control-label">Total Amount</label>
													<input type="text" name="total_amount" id="total_amount" class="form-control disabled text-right" value="<?=number_format($total_amount);?>" readonly>
													<small><?=$stsbyr?></small>
												</div>
											</div> -->
										
									</div>
									<!-- <div class="row">
										<div class="col-lg-4 col-md-4 pull-right">
											<div class="form-group">
													<label class="control-label">Total</label>
													<input type="text" name="total_amount" id="total_amount" class="form-control disabled text-right" value="<?=number_format($total);?>" readonly>
												</div>
										</div>
									</div> -->
									<div class="form-group">
										<label class="control-label">Metode Pembayaran</label>
										<select name="" class="form-control" disabled>
											<option>-- Pilih Metode Pembayaran --</option>
											<option value="1">Tunai</option>
											<option value="2" selected="selected">Transfer Bank</option>
											<option value="3">metode lainnya</option>
										</select>
										<input type="hidden" name="metode_pembayaran" value="2">
									</div>
									<div class="row">
										<div class="form-group">
											<?php
												$query1 = mysql_query("SELECT A.*, A.id AS id_bank, B.name AS nama_bank, B.img FROM `contact_bank` A LEFT JOIN bank B ON B.code = A.bank_code WHERE view = 1 AND A.stsrec = 'A' ORDER BY A.id ASC") or die (mysql_error());
												$i = 0;
												while ($row1	= mysql_fetch_array($query1)){
													$id 			= $row1['id_bank'];
													$nama_bank		= $row1['nama_bank'];
													$bank_no		= $row1['bank_no'];
													$bank_acc		= $row1['bank_account'];
													$checked1		= ($i==0 && $paidOff != 'C') ? "checked" : '';

													if ($checked1 == '') {
														$checked1 = ($row1['id']==$receiptBank) ? 'checked' : '';
													}
													
													$display		= $checked1 == 'checked' ? 'block' : 'none';

													
											?>
													<div class="col-lg-12 col-md-12">
														<div class="col-lg-5 col-md-5">
															<label class="control-label" onclick="show('ket<?=$id?>')">
																<input name="bank" id="bank" value="<?=$id?>" type="radio" <?=$checked1?> <?=$disabled?> />

																<img src="../img/<?=$row1['img']?>" width="40px" style="padding-bottom: 6px">
															</label>
														</div>

														<div class="col-lg-7 col-md-7" style="padding-top: 5px;">
															<span id="ket<?=$id?>" class="control-label nmbank" style="display: <?=$display?>">
																<?=$bank_no.'&emsp;-&emsp;'.$bank_acc ?>
															</span>
														</div>
													</div><br>
											<?php $i++; }?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Keterangan</label>
										<textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan" <?=$disabled?>> <?=$keterangan?></textarea>
									</div>
									<div class="form-group">
										<label class="control-label">Upload Bukti Pembayaran</label>
										<input type="file" name="bukti" id="bukti" class="form-control" <?=$disabled?>>
										<small>Format yang didukung: .jpg, .jpeg, .png, .gif</small>
									</div>
									<div class="form-group pull-left">
										<button name="submit" id="submit" class="btn btn-primary" <?=$disabled?>><i class="fa fa-send"></i>&emsp;Konfirmasi</button>	
									</div>
									<div class="form-group pull-right">
										<a href="info_tagihan.php?dbname=<?=$dbname;?>" class="btn btn-warning" ><i class="fa fa-undo"></i>&emsp;Kembali</a>
									</div>
								</form>
								
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
		<script src="assets/js/bootstrap-datepicker.min.js"></script>

		<!-- inline scripts related to this page -->
		<script>
			// $('input[name=konfirmasi]').on('click', function(){
			// 	var confirm = $('input[name=konfirmasi]:checked').val();
			// 	if (confirm == 'wa') {
			// 		$('a').show().siblings().hide();
			// 	}else{
			// 		$('#form-konfirmasi').show().siblings().hide();
			// 	}
			// })

			$('.datepicker').datepicker({
				format:'dd-mm-yyyy',
				autoclose:true
			})

			// $(document.ready(function(){
			// 	$('#'+id).hide()
			// })

			function show(id){
				$('#'+id).show()
				$('span.nmbank').not('#'+id).hide();
			}
			
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
		</script>
	</body>
</html>
