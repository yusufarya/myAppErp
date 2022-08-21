<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	// use Dompdf\Dompdf;
	include 'includes/style.php';
	// include 'includes/dompdf/autoload.inc.php';
	
	// $dompdf = new Dompdf();

	if (isset($_GET)) {
		$inv_no = $_GET['inv_no'];
	 	
		$sql = "SELECT inv.id AS inv_id, inv.attach_file, inv.total_amount, inv.ppn, inv.initial_amount, inv.inv_no, 
				inv.inv_date, inv.paid_off, inv.due_date, inv.paid_date, inv.paid_name, inv_d.begin_date, inv_d.end_date, 
				inv_d.expired_date, co.dbname, cs.email, cs.name, co.company_id, cmp.name as nama_perusahaan, cmp.address, cmp.npwp_no, 
				v.name AS versi, pck.name AS paket, cb.bank_no AS acc_no, cb.bank_account AS acc_name, b.name AS bank_name, 
				b.img AS bank_img, inv.discount, (inv.total_amount - inv.discount + inv.ppn + inv.initial_amount) AS grand_total, 
				inv_d.amount, co.ver_id, pc.package_type, vc.description, s.value_month, sm.name AS salesman, s.name AS subscribe_name, inv_d.order_idlama, co.db_date, pc.subscribe_id, co.created_on, co.total_branch, inv.stsrec AS statusinvoice   
				FROM `invoice` inv 
				LEFT JOIN invoice_detail inv_d on inv_d.inv_id = inv.id 
				LEFT join cust_order co on co.order_id = inv_d.order_id 
				LEFT JOIN cust cs on cs.id = inv.cust_id 
				LEFT JOIN company cmp on cmp.id = co.company_id 
				LEFT JOIN version v ON v.id = co.ver_id 
				JOIN package pc on inv_d.package_id = pc.id 
				JOIN package_type pck on pck.id=pc.package_type 
				LEFT JOIN contact_bank cb on cb.id=inv.receipt_bank 
				LEFT JOIN bank b on b.code=cb.bank_code 
				LEFT JOIN voucher vc on vc.name = inv.voucher_code 
				LEFT JOIN subscribe s on s.id = pc.subscribe_id 
				LEFT JOIN salesman sm on sm.id = co.salesman_id
				WHERE inv.inv_no = '$inv_no'";
		$excute = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_array($excute);
		
		$dbname = $data['dbname'];
		$paketDasar = $data['subscribe_name'];
		$vername = $data['versi'];
		$totalharga = number_format($data['grand_total'],2,',','.');

		//periode
		$begin_date = date('d M Y', strtotime($data['begin_date']));
		$end_date = date('d M Y', strtotime($data['end_date']));
		$tglmulai = strtotime($data['begin_date']);
		$tglakhir = strtotime($data['end_date']);
		//$inthari = ($tglakhir - $tglmulai)/60/60/24;
		//$valbulan = $inthari/30;
		//$valmonth = ceil($valbulan);
		//if($valmonth > 12) {
		//	$valmonth = 12;
		//}

		if($tglakhir > strtotime(date('Y-m-d'))) {
			$valmonth = $data['value_month'];
		} else {
			$inthari = ($tglakhir - $tglmulai)/60/60/24;
			$valbulan = $inthari/30;
			$valmonth = ceil($valbulan);
			if($valmonth > 12) {
				$valmonth = 12;
			}
		}

		$periode = $begin_date.' s/d '.$end_date;
		$ppn 	 = $data['ppn'];
		$discount = $data['discount'];
		$total_amount = $data['total_amount'] - $discount;
		$initial_amount = $data['initial_amount'];
		$voucher_info = $data['description'];
		$package_type = $data['package_type'];
		$ppn_value = ($ppn/$total_amount) * 100;

		$beginPeriod = $data['begin_date'];
		$tglDB = $data['db_date'];
		$salesman = $data['salesman'];
		$subscribeID = $data['subscribe_id'];
		$created_on = $data['created_on'];
		$add_branch = $data['total_branch'] - 1;
		$statusInvoice = $data['statusinvoice'];

		//status tagihan
		$statusTagihan = '';
		$display_color = '';
		
		$paidOff = $data['paid_off'];
		if($paidOff == 'Y'){
			$display_color = 'blue';
			$statusTagihan = 'LUNAS';
		} 
		else if($paidOff == 'C'){
			$statusTagihan = 'Sedang Diproses';
		}
		else if($paidOff == 'N'){
			if($statusInvoice == 'A') {
				$display_color = 'red';
				$statusTagihan = 'Belum Dibayar';
			} else {
				$display_color = 'grey';
				$statusTagihan = 'BATAL';
			}
		}

		$queryBank = mysql_query("SELECT A.*, A.id AS id_bank, A.bank_account AS namanasabah, B.name AS nama_bank, B.img FROM `contact_bank` A LEFT JOIN bank B ON B.code = A.bank_code WHERE view = 1 AND A.stsrec= 'A' ORDER BY A.id ASC") or die(mysql_error());
		
		$queryterm = mysql_query("SELECT description FROM `term` WHERE form = 'I' AND status = 'P' AND stsrec = 'A'") or die (mysql_error());

		$countbank = mysql_num_rows($queryBank);
		//jumlah bank utk menentukan kolom bank
	    if($countbank == 1){
	        $column = '12';
	    }
	    else if($countbank == 2){
	        $column = '6';
	    }
	    else{
	        $column = '4';
	    }

	    //Jenis Paket Tambah User
	    $verId = $data['ver_id'];
	    /*if($package_type == 2) {
		    if($created_on < '2021-02-01') {
				$qr2 = "SELECT price, package.id AS package_id FROM package 
						WHERE package_type = 2 AND subscribe_id = '".$subscribeID."' AND ver_id = '".$verId."'";
			}
			else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			    $qr2 = "SELECT p.id AS package_id,
						(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '".$created_on."' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '".$created_on."'  
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' AND subscribe_id = '".$subscribeID."'";
			}
			else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			    $qr2 = "SELECT p.id AS package_id,
						(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '".$created_on."' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '".$created_on."'  
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' AND subscribe_id = '".$subscribeID."'";
			}
			else{
			    $qr2 = "SELECT p.id AS package_id,
						(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= CURDATE() 
						ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= CURDATE() 
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' AND subscribe_id = '".$subscribeID."'";
			}
		} else if($package_type == 4 || $package_type == 5) {
			$qr2 = "SELECT p.id AS package_id, 
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date <= CURDATE()  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.ver_id = '$verId' AND s.stsrec = 'A' AND subscribe_id = '".$subscribeID."'";
		}*/

		/*if($package_type == 4 || $package_type == 5) {
			$qr2 = "SELECT p.id AS package_id, 
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date <= CURDATE()  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.ver_id = '$verId' AND s.stsrec = 'A' AND package_type = '".$package_type."'";
		} else {
			if($created_on < '2021-02-01') {
				$qr2 = "SELECT price, package.id AS package_id FROM package 
						WHERE package_type = 2 AND subscribe_id = '".$subscribeID."' AND ver_id = '".$verId."'";				
			}
			else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			    $qr2 = "SELECT p.id AS package_id,
						(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date <= '".$created_on."' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '".$created_on."'  
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' ";
			}
			else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			    $qr2 = "SELECT p.id AS package_id,
						(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= '".$created_on."' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '".$created_on."'  
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' ";
			}
			else{
			    $qr2 = "SELECT p.id AS package_id,
						(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
						ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' ";
			}
		}*/
		if($package_type == 4 || $package_type == 5) {
			$qr2 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '".$package_type."' AND p.ver_id = '$verId' AND s.stsrec = 'A' ";
		} else {
			$qr2 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.stsrec = 'A' ";
		} 
		//$sql2 = mysql_query("SELECT *, p.id AS package_id FROM `package` p JOIN subscribe s ON p.subscribe_id = s.id WHERE ver_id = '$verId' AND package_type = '2' AND s.stsrec = 'A'");	
		$sql2 = mysql_query($qr2);   
		$row2 = mysql_fetch_array($sql2);
		$harga2 = $row2['price'];

		//ambil data Tambahan User
		$package_typeW = $package_type == '1' ? " AND p.package_type != '$package_type'" : " AND p.package_type = '$package_type'";
        $sqluser = mysql_query("SELECT inv.id, inv.inv_no, invd.order_id, co.cust_id, c.email, 
        						p.package_type, invd.subscribe_times, invd.amount   
					            FROM `invoice` inv
					            LEFT JOIN invoice_detail invd on invd.inv_id = inv.id
					            LEFT JOIN cust_order co on co.order_id = invd.order_id
					            LEFT JOIN cust c on c.id = co.cust_id
					            LEFT JOIN package p on p.id = invd.package_id 
					            WHERE inv.inv_no = '$inv_no' $package_typeW ") or die (mysql_error());
        $jumlah_user = mysql_num_rows($sqluser);
        $rowtambah = mysql_fetch_array($sqluser);
        if($jumlah_user > 0) {
        	$harga2 = $rowtambah['amount'];
    	}
        $valmonth = $rowtambah['subscribe_times'];
        $totuser1 = 0;
        if($data['package_type']==1 or $data['package_type']==3){
        	$totuser1 = 1;
        }

        $month = 1;
        if($data['package_type']==1 or $data['package_type']==3){
        	$month = $data['value_month'];
        } else {
        	$month = $rowtambah['subscribe_times'];
        }
		
		if($jumlah_user > 0) {
			$sqltbh = mysql_query("SELECT co.order_id, co.cust_id, co.dbname, co.ver_id, co.package_id, co.company_id, 
						p.package_type, s.name AS subscribe_name, v.name AS ver_name, s.value_month 
						FROM `cust_order` co 
						LEFT JOIN `package` p ON p.id = co.package_id 
						LEFT JOIN `subscribe` s ON s.id = p.subscribe_id 
						LEFT JOIN `version` v ON v.id = p.ver_id 
						WHERE co.dbname = '$dbname' AND p.package_type = 1") or die (mysql_error());
			$rowtbh = mysql_fetch_array($sqltbh);
			//$month = $rowtbh['value_month'];
			if($totuser1 == 0) {
				$paketDasar = $rowtbh['subscribe_name'];
				$vername = $rowtbh['ver_name'];
			}
		}

		if($created_on < '2022-01-01')
		{
			$tampilversi = '(Versi '.$vername.')';
		} else {
			$tampilversi = '';
		}

		// GET EMAIL USER LAMA
		$emailLama = '';
		$totalBranch = 0;
		if($data['package_type']==4){
			$sqlEmailL = "SELECT email from cust_order join cust on cust.id = cust_order.cust_id where order_id = '$data[order_idlama]'";
			$qr = mysql_query($sqlEmailL) or die(mysql_error());
			$dataElama = mysql_fetch_array($qr);
			$emailLama = $dataElama['email'];

			//Jenis Paket Ganti User
		    /*$verId = $data['ver_id'];
			$sql2 = mysql_query("SELECT *, p.id AS package_id FROM `package` p JOIN subscribe s ON p.subscribe_id = s.id WHERE ver_id = '$verId' AND package_type = '4' AND s.stsrec = 'A'");	   
			$row2 = mysql_fetch_array($sql2);*/
			//$harga2 = $row2['price'];
		}
		else if($data['package_type']==5){
			$sqlBrc = mysql_query("SELECT invd.add_branch from invoice inv join invoice_detail invd on invd.inv_id = inv.id where inv.inv_no = '$inv_no'");
			$rowBrc = mysql_fetch_array($sqlBrc);
			$totalBranch = $rowBrc['add_branch'];
			
			//Jenis Paket Tambah Cabang
		    /*$verId = $data['ver_id'];
		    $now = date('Y-m-d');
			$sql2 = mysql_query("SELECT pr.price FROM package_price pr JOIN package p on pr.package_id = p.id
				WHERE pr.begin_date <= '$beginPeriod' AND p.package_type = '5' AND p.ver_id = '$verId' ORDER BY pr.begin_date DESC LIMIT 1");
			$row2 = mysql_fetch_array($sql2);*/
			//$harga2 = $row2['price'];
		}

		if($dbname == 'db') {	//Rotary Created: 2020-09-08
			if($package_type == 1) {
				$harga2 = '100000';
			} else if($package_type == 2) {
				$harga2 = '100000';
			} else if($package_type == 4) {
				$harga2 = '100000';
			} else if($package_type == 5) {
				$harga2 = '18000';
			} 
		}
		
	}
	// ob_start();
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
		
		<style type="text/css">
			.col-lg-8{
				width: 100%;
			}
			.pull-right{
				float: right;
			}
			.pull-left{
				float: left;
			}
			.container{
				margin-top: 30px;
			}
			#tagihan{
				
				align-self: center;
			}
			table{
				border-color: white !important;
			}
			table thead{
				background-color: #ddd;
				color: black;
			}
			table thead tr td{
				vertical-align: middle !important;
				border-color: white !important;
			}
			table td{
				padding: 5px !important;
			}
			.right{
				text-align: right !important;
			}
			span.judul-faktur{
				text-transform: uppercase;
				color: #999999 !important;
			}
			.judul-faktur2{
				font-weight: 900;
				font-size: 16px;
				line-height: 16px;
			}
			.value-faktur2{
				font-size: 16px;
				line-height: 16px;
				color: #868FA0;
			}
			.nominal2{
				font-size: 18px; 
				line-height: 16px; 
				font-weight: 900; 
				color: #868FA0;
			}
			.gt-label{
				font-size: 18px; 
				font-weight: 700; 
				color: #868FA0;
			}
			.gt-nominal{
				font-size: 24px; 
				font-weight: 700; 
				color: #2F80ED;
			}
			.sub-judul-tagihan{
				font-size: 18px; 
				line-height: 16px; 
				font-weight: 900; 
				color: #868FA0;
			}
			.ver-desk{
				display: block;
			}

			.ver-mob{
				display: none;
			}
			@media only screen and (max-width: 700px){
				.ver-desk{
					display: none;
				}

				.ver-mob{
					display: block;
				}

				.judul-faktur2{
					font-size: 14px;
				}
				.value-faktur2{
					font-size: 14px;
				}

				.nominal2{
					font-size: 16px; 
				}
				.gt-label{
					font-size: 16px; 
				}
				.gt-nominal{
					font-size: 19px; 
				}
				.header-tagihan .logo-tagihan{
					text-align: center;
					margin-bottom: 10px;
				}
				.header-tagihan .contact, .header-tagihan .alamat-tagihan{
					font-size: 10px;
				}
				.sub-judul-tagihan{
					font-size: 15px;
				}
			}
			span.nominal{
				font-size: 25px;
				font-weight: bold;
			}
			span.keterangan{
				font-size: 12px;
				font-weight: lighter;
			}
			.informasi{
				text-align: center;
				font-weight: 700;
			}
			h2{
				font-weight: 700 !important;
			}
			.informasi p{
				font-size: 18px;
			}
			.alamat{
				text-align: center;
				color: grey;
			}
			#detail td{
				border:0px;
			}
			#detail tbody td.harga{
				font-weight: 700;
				font-size: 16px;
			}
			.total{
				border-right: 5px solid #ddd !important;
			}
			.status-tagihan h4{
				color: grey;
				margin-bottom: 0px;
				padding: 5px;
			}
			.col-lg-4 {
				width: 33%;
				display: inline-block;
			}
			.v-top{
				vertical-align: top !important;
			}
			.col-width-33{
				width: 33% !important;
			}
			.navbar {
				background-color: #ffffff00;
				box-shadow:none !important;
			}
			.ace-nav>li.light-blue>a {
			    background-color: #62a8d130;
			}

			.logo{
				float: left;
			}
			.tagihan{
				float: right;
				margin-top: 20px;
				font-size: 10px;
			}

			.tagihan2{
				float: left;
				margin-top: 20px;
				font-size: 10px;
				margin-left: 50px;
			}


			@media only screen and (min-width: 360px) and (max-width: 430px){
				span.judul-faktur{
					text-transform: uppercase;
					color: #999999 !important;
					font-size: 9px;
				}
				span.value-faktur{
					font-size: 9px;
				}
				span.nominal{
					font-size: 12px;
				}
				.logo{
					float: none;
					text-align: center;
				}
				.tagihan{
					float: none;
					text-align: right;
				}
				.tagihan2{
					margin-left: 0;
				}
				.email-member{
					font-size: 1.5vw;
					line-height: 14px;
				}
			}

			@media only screen and (max-width: 700px){
				.ver-desk{
					display: none;
				}

				.ver-mob{
					display: block;
				}

				.judul-faktur2{
					font-size: 14px;
				}
				.value-faktur2{
					font-size: 14px;
				}

				.nominal2{
					font-size: 16px; 
				}
				.gt-label{
					font-size: 16px; 
				}
				.gt-nominal{
					font-size: 19px; 
				}
				.header-tagihan .logo-tagihan{
					text-align: center;
					margin-bottom: 10px;
				}
				.header-tagihan .contact, .header-tagihan .alamat-tagihan{
					font-size: 10px;
				}
				.sub-judul-tagihan{
					font-size: 15px;
				}
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
		
		<div class="container">
			<div id="print" class="panel panel-default">
				<div class="panel-body" style="display: flex; align-items: center;justify-content: center; align-self: center;">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="tagihan">
						<div class="row header-tagihan" style="background: #F8FAFF; padding: 10px; margin-bottom: 15px;">
							<div class="logo">
								<img src="../img/LOGO-SISCOM1.png" width="150px">
							</div>
							<div class="tagihan2">
								+6221-5694-5002<br>
								+6221-5694-5003<br>
								finance@siscomonline.co.id<br>
								siscomonline.co.id
							</div>
							<div class="tagihan">
								City Resort Rukan Malibu<br>
	                            Blok J/75-77<br>
	                            Mutiara Taman Palem<br>
	                            Cengkareng - Jakarta Barat<br>
	                            11730
							</div>
						</div>

						<section class="body-tagihan ver-desk">
							<table width="100%">
								<tr>
									<td class="judul-faktur2" width="45%">Kepada</td>
									<td class="judul-faktur2">Tagihan</td>
									<td rowspan="2" style="font-size: 16px; font-style: italic; color:<?=$display_color?>"><strong><?=$statusTagihan?></strong></td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=$data['nama_perusahaan'];?></td>
									<td class="value-faktur2">#<?=$data['inv_no'];?></td>
								</tr>
								<tr>
									<td class="judul-faktur2">Alamat</td>
									<td class="judul-faktur2">NPWP</td>
									<td class="judul-faktur2">Tgl Tagihan</td>
									<td class="judul-faktur2">Jatuh Tempo</td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=$data['address'];?></td>
									<td class="value-faktur2"><?=$data['npwp_no'];?></td>
									<td class="value-faktur2"><?=date('d-m-Y', strtotime($data['inv_date']));?></td>
									<td class="value-faktur2"><?=date('d-m-Y', strtotime($data['due_date']));?></td>
								</tr>
								<tr>
									<td class="judul-faktur2">Penanggung Jawab</td>
									<td class="judul-faktur2">Salesman</td>
									<td class="judul-faktur2">TOTAL</td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=$data['name'].' ('.$data['email'].')';?></td>
									<td class="value-faktur2"><?=$salesman?></td>
									<td class="nominal2">IDR <?=$totalharga;?></td>
								</tr>
							</table>
						</section>
						<!-- ver mob -->
						<section class="body-tagihan ver-mob">
							<table width="100%">
								<tr>
									<td class="judul-faktur2">Tagihan</td>
									<td rowspan="2" style="font-size: 16px; font-style: italic; color:<?=$display_color?>"><strong><?=$statusTagihan?></strong></td>
								</tr>
								<tr>
									<td class="value-faktur2">#<?=$data['inv_no'];?></td>
								</tr>
								<tr>
									<td class="judul-faktur2">Jatuh Tempo</td>
									<td class="judul-faktur2">Tgl Tagihan</td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=date('d-m-Y', strtotime($data['due_date']));?></td>
									<td class="value-faktur2"><?=date('d-m-Y', strtotime($data['inv_date']));?></td>
								</tr>
								<tr>
									<td class="judul-faktur2">Kepada</td>
									<td class="judul-faktur2">Penanggung Jawab</td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=$data['nama_perusahaan'];?></td>
									<td class="value-faktur2"><?=$data['name'].' ('.$data['email'].')';?></td>
								</tr>
								<tr>
									<td class="judul-faktur2">Alamat</td>
									<td class="judul-faktur2">NPWP</td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=$data['address'];?></td>
									<td class="value-faktur2"><?=$data['npwp_no'];?></td>
								</tr>
								<tr>
									<td class="judul-faktur2">Salesman</td>
									<td class="judul-faktur2">TOTAL</td>
								</tr>
								<tr>
									<td class="value-faktur2"><?=$salesman?></td>
									<td class="nominal2">IDR <?=$totalharga;?></td>
								</tr>
							</table>
						</section>
						<!-- x ver mob -->
						<!-- border -->
						<div style="border-top: 2px solid #868FA0; width: 100%; margin: 10px 0;"></div>
						<section>
							<table class="" width="100%">
								<tr>
									<td colspan="2" class="sub-judul-tagihan">NAMA PRODUK</td>
									<td align="right" class="sub-judul-tagihan">JUMLAH</td>
								</tr>
								<tr>
									<td colspan="3">&emsp;</td>
								</tr>
								<tr>
									<td colspan="3" class="judul-faktur2">Database</td>
								</tr>
								<tr style="border-bottom: 0.5px solid #E9EDF5;">
									<td colspan="3"><span class="judul-faktur2"><?=$dbname;?></span> <span style="color: #868FA0;">( <?=$periode?> )</span></td>
								</tr>
								<tr>
									<td colspan="2" class="judul-faktur2">Paket Dasar</td>
									<td align="right" class="judul-faktur2"><?=number_format(($totuser1*$data['amount']),2,',','.');?></td>
								</tr>
								<tr style="border-bottom: 1px solid #E9EDF5;">
									<!-- <td colspan="3" style="color: #868FA0;"><?=$tampilversi ? '('.$tampilversi.')' : ''?></td> -->
									<td colspan="3" style="color: #868FA0;">&emsp;</td>
								</tr>

								<!-- paket ganti user -->
								<?php if($data['package_type'] == '4'){ ?>
									<tr>
										<td colspan="2" class="judul-faktur2">Ganti User</td>
										<td align="right" class="judul-faktur2"><?=number_format($harga2,2,',','.');?></td>
									</tr>
									<tr style="border-bottom: 1px solid #E9EDF5;">
										<td colspan="3" style="color: #868FA0;">( @ Rp. <?=number_format($harga2,2,',','.');?> )</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td rowspan="5" width="50%" class="email-member v-top">
											<span>User lama : <?=$emailLama;?></span> <br>
		                                    <?php while ($data = mysql_fetch_array($sqluser)) {
		                                        if($jumlah_user != 0){
		                                    ?>  
		                                        <span>User baru : <?=$data['email'];?></span>
		                                    <?php 
		                                        }
		                                    } ?>
										</td>
									</tr>

								<!-- paket tambah cabang -->
								<?php }else if($data['package_type'] == '5'){ ?>
									<tr>
										<td colspan="2" class="judul-faktur2">Tambah Cabang</td>
										<td align="right" class="judul-faktur2"><?=number_format(($totalBranch * $harga2 * $valmonth),2,',','.');?></td>
									</tr>
									<tr style="border-bottom: 1px solid #E9EDF5;">
										<td colspan="3" style="color: #868FA0;">( <?=$totalBranch?> Cabang x @ Rp. <?=number_format($harga2,2,',','.');?> &nbsp;x&nbsp;<?=$valmonth?> Bulan )</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td rowspan="5" width="50%" class="email-member">
										</td>
									</tr>

								<?php }else{ ?>
									<tr>
										<td colspan="2" class="judul-faktur2">Tambah User</td>
										<td align="right" class="judul-faktur2"><?=number_format(($jumlah_user*$harga2*$month),2,',','.');?></td>
									</tr>
									<tr style="border-bottom: 1px solid #E9EDF5;">
										<td colspan="3" style="color: #868FA0;">( <?=$jumlah_user?> User @ Rp. <?=number_format($harga2,2,',','.');?> &nbsp;x&nbsp;<?=$month?> Bulan )</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td rowspan="5" width="50%" class="email-member v-top">
											<?php
		                                    	while ($data = mysql_fetch_array($sqluser)) {
		                                        if($jumlah_user != 0){
		                                    ?>  
		                                        <?=$data['email'];?>
		                                    <?php 
		                                        }
		                                    } ?>
										</td>
									</tr>
								<?php }?>

								<tr>
									<td align="left" class="value-faktur2">Potongan Voucher</td>
									<td align="right" class="judul-faktur2"><?=number_format($discount,2,',','.');?></td>
								</tr>
								<tr>
									<td align="left" class="value-faktur2">Sub Total</td>
									<td align="right" class="judul-faktur2"><?=number_format($total_amount,2,',','.');?></td>
								</tr>
								<tr style="border-bottom: 0.5px solid #E9EDF5;">
									<td align="left" class="value-faktur2">PPN <?=$ppn_value?>%</td>
									<td align="right" class="judul-faktur2"><?=number_format($ppn,2,',','.');?></td>
								</tr>
								<!-- <tr style="border-bottom: 0.5px solid #E9EDF5;">
									<td align="left" class="value-faktur2">Kode Unik</td>
									<td align="right" class="judul-faktur2">0,00</td>
								</tr> -->
								<tr>
									<td align="left" class="gt-label">TOTAL</td>
									<td align="right" class="gt-nominal">IDR <?=$totalharga;?></td>
								</tr>
							</table>
						</section>

						<section class="footer-tagihan" style="margin-top: 20px;">
							<div class="informasi">
								<div class="row"> 
                                    <div class="col-lg-12 col-md-12">
										<h2>Informasi Pembayaran</h2>
										<?php 
											while ($dbank = mysql_fetch_array($queryBank)) {
										?>	
											<div class="col-lg-<?=$column?> col-md-<?=$column?>">
												<img src="../img/<?=$dbank['img']?>" width="80px"><br>
													<?=$dbank['bank_no'];?><br>
													<?=$dbank['namanasabah'];?></strong><br>
												</p>
											</div>
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<?php 
									while ($dbTerm = mysql_fetch_array($queryterm)) {										
							?>	
							<div class="term">
								<?=preg_replace('/[^(\20-\x7F)]*/','',$dbTerm['description']);?>
							</div>
							<?php }?>
						</section>
					</div>
				</div>
			</div>

			<!-- old -->
			<!-- <div id="print" class="panel panel-default">
				<div class="panel-body" style="display: flex; align-items: center;justify-content: center; align-self: center;">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="tagihan">
						<div class="header-tagihan">
							<div class="logo">
								<img src="../img/LOGO-SISCOM1.png" width="150px">
							</div>
							<div class="tagihan">
								<span><strong>Tagihan&emsp;&emsp;&emsp;: #<?=$data['inv_no'];?></strong></span><br>
                                <span><strong>Tgl Tagihan&emsp;&nbsp;: <?=date('d-m-Y', strtotime($data['inv_date']));?></strong></span><br><br>
                                <label class="status-tagihan"><strong><?=$statusTagihan?> &emsp;</strong><span>Jatuh Tempo: <?=date('d-m-Y', strtotime($data['due_date']));?></span></label>
							</div>
						</div>
						<div style="clear:both;"></div>
						<div class="body">
							<table class="table table-bordered" width="100%">
								<thead>
									<tr>
										<td colspan="2" class="">
											<span class="judul-faktur">Kepada</span><br>
											<span class="value-faktur"><?=$data['nama_perusahaan'];?></span>
										</td>
										<td class="">
											<span class="judul-faktur">penanggung jawab</span><br>
											<span class="value-faktur"><?=$data['name'].' ('.$data['email'].')';?></span>
										</td>
										<td rowspan="3" class="right">
											<span class="judul-faktur">total</span><br>
											<span class="nominal">IDR <?=$totalharga;?></span>
										</td>
									</tr>
									<tr>
										<td rowspan="2" class=" v-top">
											<span class="judul-faktur">alamat</span><br>
											<span class="value-faktur"><?=$data['address'];?></span>
										</td>
										<td colspan="2" class=" v-top">
											<span class="judul-faktur">npwp</span><br>
											<span class="value-faktur"><?=$data['npwp_no'];?></span>
										</td>
									</tr>
								</thead>
							</table>

							<table class="table" width="100%" id="detail">
								<thead>
									<tr>
										<td>
											<span style="font-weight: 900" class="judul-faktur">NAMA PRODUK</span>
										</td>
										<td>
											<span class="value-faktur"><?=$voucher_info?></span>
										</td>
										<td class="right">
											<span class="judul-faktur">sub-total</span>
										</td>										
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="3">
											<span class="value-faktur">Periode Database&emsp;&emsp;&nbsp;: <?=$periode;?></span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<span style="font-weight: 900" class="value-faktur">Paket Dasar&emsp;&emsp;&emsp;&emsp;&emsp;: <?=$paketDasar;?>&emsp;<?=$tampilversi;?></span>
										</td>
										<td class="right harga">
											<span style="font-weight: 900" class="value-faktur"><?=number_format(($totuser1*$data['amount']),2,',','.');?></span>
										</td>
									</tr> -->

									<!-- paket ganti user -->
									<!-- <?php if($data['package_type'] == '4'){ ?>
									<tr>
										<td colspan="2">
											<span style="font-weight: 900" class="value-faktur">
												Ganti User&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;: @ Rp. <?=number_format($harga2,2,',','.');?>
											</span>
										</td>
										<td class="right harga">
											<span style="font-weight: 900" class="value-faktur"><?=number_format($harga2,2,',','.');?></span>
										</td>
									</tr>
									<tr>
		                                <td rowspan="5" class="v-top">
		                                    <span class="value-faktur" style="margin-right: 10px;">User lama : <?=$emailLama;?></span> <br>
		                                    <?php while ($data = mysql_fetch_array($sqluser)) {
		                                        if($jumlah_user != 0){
		                                    ?>  
		                                        <span class="value-faktur" style="margin-right: 10px;">User baru : <?=$data['email'];?></span>
		                                    <?php 
		                                        }
		                                    } ?>
		                                </td>
                                        <td class="right total">                               
                                            <span class="judul-faktur">Potongan Voucher</span>
                                        </td>
                                        <td class="right harga">
                                            <span class="value-faktur"><?=number_format($discount,2,',','.');?></span>
                                        </td>
                                    </tr> -->
                                    <!-- end paket ganti user -->
									
									<!-- paket tambah cabang -->
									<!-- <?php }else if($data['package_type'] == '5'){ ?>
									<tr>
										<td colspan="2">
											<span style="font-weight: 900" class="value-faktur">
												Tambah Cabang&emsp;&emsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?=$totalBranch?> Cabang x @ Rp. <?=number_format($harga2,2,',','.');?> &nbsp;x&nbsp;<?=$valmonth?> Bulan
											</span>
										</td>
										<td class="right harga">
											<span style="font-weight: 900" class="value-faktur"><?=number_format(($totalBranch * $harga2 * $valmonth),2,',','.');?></span>
										</td>
									</tr>
									<tr>
		                                <td rowspan="5" class="v-top">
		                                    
		                                </td>
                                        <td class="right total">                               
                                            <span class="judul-faktur">Potongan Voucher</span>
                                        </td>
                                        <td class="right harga">
                                            <span class="value-faktur"><?=number_format($discount,2,',','.');?></span>
                                        </td>
                                    </tr> -->
                                    <!-- end paket tambah cabang -->

									<!-- <?php }else{ ?> 
									
									<tr>
										<td colspan="2">
											<span style="font-weight: 900" class="value-faktur">
												Tambah User&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;: <?=$jumlah_user?> User @ Rp. <?=number_format($harga2,2,',','.');?> &nbsp;x&nbsp;<?=$month?> Bulan
											</span>
										</td>
										<td class="right harga">
											<span style="font-weight: 900" class="value-faktur"><?=number_format(($jumlah_user*$harga2*$month),2,',','.');?></span>
										</td>
									</tr>
                                    <tr>
		                                <td rowspan="5" class="v-top" style="width: 60%">
		                                    <?php
		                                    	print_r($rowtambah['email']);
		                                    	while ($data = mysql_fetch_array($sqluser)) {
		                                        if($jumlah_user != 0){
		                                    ?>  
		                                        <span class="value-faktur" style="margin-right: 10px"><?=$data['email'];?>&emsp;</span>
		                                    <?php 
		                                        }
		                                    } ?>
		                                </td>
                                        <td class="right total">                               
                                            <span class="judul-faktur">Potongan Voucher</span>
                                        </td>
                                        <td class="right harga">
                                            <span class="value-faktur"><?=number_format($discount,2,',','.');?></span>
                                        </td>
                                    </tr>
									<?php } ?>
									<tr>
										<td class="right total">										
											<span class="judul-faktur">Sub Total</span>
										</td>
										<td class="right harga">
											<span class="value-faktur"><?=number_format($total_amount,2,',','.');?></span>
										</td>
									</tr>
									<tr>
										<td class="right total">										
											<span class="judul-faktur">PPN 10%</span>
										</td>
										<td class="right harga">
											<span class="value-faktur"><?=number_format($ppn,2,',','.');?></span>
										</td>
									</tr>
                                    <tr>
                                        <td class="right total">                                        
                                            <span class="judul-faktur">Kode unik</span>
                                        </td>
                                        <td class="right harga">
                                            <span><?=number_format($initial_amount,2,',','.');?></span>
                                        </td>
                                    </tr>
									<tr>
										<td class="right total">										
											<span class="judul-faktur">total</span>
										</td>
										<td class="right harga">
											<span class="value-faktur">IDR &emsp;<?=$totalharga;?></span>
										</td>
									</tr>
								</tbody>
							</table>
							<br>
							<div class="informasi">
								<div class="row"> 
                                    <div class="col-lg-12 col-md-12">
										<h2>Informasi Pembayaran</h2>
										<?php 
											while ($dbank = mysql_fetch_array($queryBank)) {
										?>	
											<div class="col-lg-<?=$column?> col-md-<?=$column?>">
												<img src="../img/<?=$dbank['img']?>" width="80px"><br>
													<?=$dbank['bank_no'];?><br>
													<?=$dbank['namanasabah'];?></strong><br>
												</p>
											</div>
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<?php 
									while ($dbTerm = mysql_fetch_array($queryterm)) {										
							?>	
							<div class="term">
								<?=preg_replace('/[^(\20-\x7F)]*/','',$dbTerm['description']);?>

							</div>
							<?php }?>
							<div class="alamat">
								<p><strong>PT. Shan Informasi Sistem</strong><br>
                                    City Resort Rukan Malibu Blok J/75-77 <br>
                                    Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
                                    Tel: +62 21 5694 5002 | <a href="https://www.siscomonline.co.id/">SISCOM Online</a><br>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div> -->
			<!-- old -->

			<!-- <div class="panel panel-default"> -->
				<!-- <div class="panel-body "> -->
					<div class="form-group pull-right">
						<a href="" class="btn btn-primary btn-primary btn-bold" id="btn-print" onclick="printOut()">
				            <i class="ace-icon fa fa-print bigger-120 white"></i>
				            &nbsp;Print
				        </a>&emsp;
						<a href="info_tagihan.php?dbname=<?=$dbname;?>" class="btn btn-warning" ><i class="ace-icon fa fa-undo bigger-120 white"></i>&nbsp;Kembali</a>
						
					</div>
				<!-- </div> -->
			<!-- </div> -->
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

		<script type="text/javascript">
			function printOut(){
				var mywindow = window.open('', 'PRINT', 'height:400, width=600');
				var content = $('#print').html();
				var style = '<style type="text/css">'+
								'body{font-family: "Open Sans";font-size: 13px;color: #393939;line-height: 1.5;}'+
								'.col-lg-4{width: 33%; display: inline-block;}'+
								'.col-lg-6{width: 40%; display: inline-block;}'+
								'.col-lg-12{width: 100%; display: inline-block;}'+
								'.container{margin-top: 30px;}'+
								'#tagihan{align-self: center;}'+
								'.logo{float: left;}'+
								'.tagihan{float: right;margin-top: 20px;font-size: 10px;}'+
								'.tagihan2{float: left;margin-left: 50px;margin-top: 20px;font-size: 10px;}'+
								'table{border-color: white;}'+
								'table thead{background-color: #ddd;color: black; -webkit-print-color-adjust: exact;}'+
								'table thead tr td{vertical-align: middle !important;border-color: white !important; padding: 5px !important; -webkit-print-color-adjust: exact;}'+
								'.right{text-align: right !important;}'+
								'span.judul-faktur{text-transform: uppercase;color: #999999 !important;}'+
								'span.nominal{font-size: 20px;font-weight: bold;}'+
								'span.keterangan{font-size: 12px;font-weight: lighter;}'+
								'.informasi{text-align: center;font-weight: 700;}'+
								'h2{font-weight: 700 !important;}'+
								'.informasi p{font-size: 18px;}'+
								'.alamat{text-align: center;color: grey;}'+
								'#detail td{border:0px; -webkit-print-color-adjust: exact;}'+
								'#detail thead tr td{vertical-align: middle !important;border-color: 0px solid transparent !important; -webkit-print-color-adjust: exact;}'+
								'#detail tbody td.harga{font-weight: 700;font-size: 16px; -webkit-print-color-adjust: exact;}'+
								'.total{border-right: 5px solid #ddd !important;}'+
								'.status-tagihan{font-size: 16px; padding-top: 10px !important;}'+
								'.col-width-33{width: 33% !important; -webkit-print-color-adjust: exact;}'+
								'.v-top{vertical-align: top !important;}'+
								'.pull-right{float: right;}'+
								'.pull-left{float: left;}'+
								'.ver-desk{display: block;}'+
								'.ver-mob{display: none;}'+
								'@media only screen and (max-width: 700px){'+
									'.ver-desk{display: none;}'+
									'.ver-mob{display: block;}'+
								'}'+
							'</style>'

				mywindow.document.write(style);
				mywindow.document.write(content);
				mywindow.document.close();
				mywindow.focus();
				mywindow.print();
				mywindow.close();
			}
		</script>
	</body>
</html>

<?php 
	// $html = ob_get_contents();
	// // ob_get_clean();

	// $dompdf->load_html($html);
	// $dompdf->render();
	// $file = $dompdf->output();
	// file_put_contents('tagihan.pdf', $file);
?>