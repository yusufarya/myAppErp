<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	//use Dompdf\Dompdf;
	include 'includes/style.php';
	//include 'includes/dompdf/autoload.inc.php';
	
	//$dompdf = new Dompdf();

	if (isset($_GET)) {
		$inv_id = $_GET['inv_id'];
		
		$sql = "SELECT inv.id AS inv_id, inv.attach_file, inv.total_amount, inv.ppn, inv.initial_amount, 
				inv.inv_no, inv.inv_date, inv.paid_off, inv.due_date, inv.paid_date, inv.paid_name, 
				inv_d.begin_date, inv_d.end_date, inv_d.expired_date, co.dbname, cs.email, cs.name, co.company_id, 
				cmp.name as nama_perusahaan, cmp.address, cmp.npwp_no, v.name AS versi, pck.name AS paket, 
				cb.bank_no AS acc_no, cb.bank_account AS acc_name, b.name AS bank_name, b.img AS bank_img, 
				inv.discount, (inv.total_amount - inv.discount + inv.ppn + inv.initial_amount) AS grand_total, 
				inv_d.amount, co.ver_id, pc.package_type, vc.description, s.value_month, sm.name AS salesman, 
				s.name AS subscribe_name, inv_d.subscribe_times, pc.subscribe_id, co.db_date, co.created_on,
				inv.stsrec AS statusinvoice 
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
				WHERE inv.id = '$inv_id'";
		$excute = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_array($excute);
		$totalharga = number_format($data['grand_total'],2,',','.');

		//periode
		$begin_date = date('d M Y', strtotime($data['begin_date']));
		$end_date = date('d M Y', strtotime($data['end_date']));
		$periode = $begin_date.' s/d '.$end_date;
		$ppn 	= $data['ppn'];
		$discount = $data['discount'];
		$total_amount = $data['total_amount'] - $discount;
		$initial_amount = $data['initial_amount'];
		$salesman = $data['salesman'];
		$subscribeID = $data['subscribe_id'];
		$subscribe_name = $data['subscribe_name'];
		$dbname = $data['dbname'];
		$beginPeriod = $data['begin_date'];
		$db_date = $data['db_date'];
		$created_on = $data['created_on'];
		$statusInvoice = $data['statusinvoice'];
		$ppn_value = ($ppn/$total_amount) * 100;

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
	    /*if($package_type == 4 || $package_type == 5) {
			$sql2 = mysql_query("SELECT p.id AS package_id, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date <= CURDATE()  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.ver_id = '$verId' AND s.stsrec = 'A' AND package_type = '".$package_type."'");
		} else {
		    if(strtotime($created_on) < strtotime('2021-02-01 00:00:00')) {
				$sql2 = mysql_query("SELECT *, p.id AS package_id FROM `package` p JOIN subscribe s ON p.subscribe_id = s.id WHERE ver_id = '$verId' AND package_type = '2' AND s.stsrec = 'A'");	  
			} else if(strtotime($created_on) >= strtotime('2021-02-01 00:00:00') and $created_on < strtotime('2021-11-01 00:00:00')) {
				$sql2 = mysql_query("SELECT p.id AS package_id,
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date <= '$created_on' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type  
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
						AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
						AND s.stsrec = 'A'");
			} else if(strtotime($created_on) >= strtotime('2021-11-01 00:00:00') and $created_on < strtotime('2022-01-01 00:00:00')) {
				$sql2 = mysql_query("SELECT p.id AS package_id,
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= '$created_on' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type  
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
						AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
						AND s.stsrec = 'A'");
			} else {
				$sql2 = mysql_query("SELECT p.id AS package_id,
						(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= '$beginPeriod' 
						ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type 
						FROM package p 
						JOIN subscribe s ON p.subscribe_id = s.id 
						WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
						AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
						AND s.stsrec = 'A'");
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
					            WHERE inv.id='$inv_id' $package_typeW ") or die (mysql_error());
        $jumlah_user = mysql_num_rows($sqluser);
        $rowtambah = mysql_fetch_array($sqluser);
        $harga2 = $rowtambah['amount'];
        $valmonth = $rowtambah['subscribe_times'];

        $totuser1 = 0;
        if($data['package_type']==1 or $data['package_type']==3){
        	$totuser1 = 1;
        }

        $month = 1;
        if($data['package_type']==1 or $data['package_type']==3){
        	$month = $data['value_month'];
        } else if($data['package_type']==2) {
			$month = $data['subscribe_times'];
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
				$subscribename = $rowtbh['subscribe_name'];
				$vername = $rowtbh['ver_name'];
				$month = $data['subscribe_times'];
			}
		}

		if($data['package_type']==4){
			$sqlEmailL = "SELECT email from cust_order join cust on cust.id = cust_order.cust_id where order_id = '$data[order_idlama]'";
			$qr = mysql_query($sqlEmailL) or die(mysql_error());
			$dataElama = mysql_fetch_array($qr);
			$emailLama = $dataElama['email'];
		}
		else if($data['package_type']==5){
			$sqlBrc = mysql_query("SELECT invd.add_branch from invoice inv join invoice_detail invd on invd.inv_id = inv.id where inv.id = '$inv_id'");
			$rowBrc = mysql_fetch_array($sqlBrc);
			$totalBranch = $rowBrc['add_branch'];
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

	//ob_start();
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
		
		<style type="text/css">
			.col-lg-8{
				width: 100%;
			}
			.pull-right{
				float: right;
				margin-right: 20px;
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
				/*border-color: white !important;*/
			}
			table thead{
				background-color: #ddd;
				color: black;
			}
			table thead tr td{
				vertical-align: middle !important;
				/*border-color: white !important;*/
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
			/*#detail td{
				border:0px;
			}*/
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

			/**/
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
			.logo{
				float: left;
			}
			.tagihan{
				float: right;
				margin-top: 20px;
				margin-right: 15px;
			}

			.tagihan2{
				float: left;
				margin-top: 20px;
				margin-left: 50px;
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

			@media only screen and (max-width: 420px){
				.header-tagihan img{
					width: 150px;
				}

				.header-tagihan .txt{
					font-size: 9px;
				}

				.header-tagihan img, .header-tagihan .txt{
					margin-top: 15px;
				}
			}
		</style>

	</head>

	<body>
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-body" style="display: flex; align-items: center;justify-content: center; align-self: center;">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="tagihan">
						<div class="header-tagihan" style="background: #F8FAFF; padding: 10px; margin-bottom: 15px; height: 100px; width: 100%;">
							<div class="logo">
								<img src="<?=$abs?>/reg/img/LOGO-SISCOM1.png" width="200px">
							</div>
							<div class="tagihan2 txt" style="margin-left: 10px;">
								+6221-5694-5002<br>
								+6221-5694-5003<br>
								finance@siscomonline.co.id<br>
								siscomonline.co.id
							</div>
							<div class="tagihan txt">
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
						<!-- x border -->

						<section>
							<table width="100%">
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
								<tr>
									<td colspan="3" style="border-bottom: 1px solid #E9EDF5!important;"><span class="judul-faktur2"><?=$dbname;?></span> <span style="color: #868FA0;">( <?=$periode?> )</span></td>
								</tr>
								<tr>
									<td colspan="2" class="judul-faktur2">Paket Dasar</td>
									<td align="right" class="judul-faktur2"><?=number_format(($totuser1*$data['amount']),2,',','.');?></td>
								</tr>
								<tr>
									<!-- <td colspan="3" style="color: #868FA0; border-bottom: 1px solid #E9EDF5;"><?=$data['versi'] ? '( Versi '.$data['versi'].' )' : ''?></td> -->
									<td colspan="3" style="color: #868FA0; border-bottom: 1px solid #E9EDF5;">&nbsp;</td>
								</tr>

								<!-- paket tambah cabang -->
								<?php if($data['package_type'] == '5'){ ?>
									<tr>
										<td colspan="2" class="judul-faktur2">Tambah Cabang</td>
										<td align="right" class="judul-faktur2"><?=number_format(($totalBranch * $harga2 * $month),2,',','.');?></td>
									</tr>
									<tr>
										<td colspan="3" style="color: #868FA0; border-bottom: 1px solid #E9EDF5;">( <?=$totalBranch?> Cabang x @ Rp. <?=number_format($harga2,2,',','.');?> &nbsp;x&nbsp;<?=$month?> Bulan )</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td rowspan="5" width="50%" class="value-faktur2">
										</td>
									</tr>

								<?php }else{ ?>
									<tr>
										<td colspan="2" class="judul-faktur2">Tambah User</td>
										<td align="right" class="judul-faktur2"><?=number_format(($jumlah_user*$harga2*$month),2,',','.');?></td>
									</tr>
									<tr>
										<td colspan="3" style="color: #868FA0; border-bottom: 1px solid #E9EDF5;">( <?=$jumlah_user?> User @ Rp. <?=number_format($harga2,2,',','.');?> &nbsp;x&nbsp;<?=$month?> Bulan )</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td rowspan="5" width="50%" class="value-faktur2 v-top">
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
								<tr>
									<td align="left" class="value-faktur2" style="border-bottom: 0.5px solid #E9EDF5;">PPN <?=$ppn_value?>%</td>
									<td align="right" class="judul-faktur2" style="border-bottom: 0.5px solid #E9EDF5;"><?=number_format($ppn,2,',','.');?></td>
								</tr>
								<tr>
									<td align="left" class="gt-label">TOTAL</td>
									<td align="right" class="gt-nominal">IDR <?=$totalharga;?></td>
								</tr>
							</table>
						</section>
							
						<section class="footer-tagihan">
							<br>
							<div class="informasi">
								<div class="row"> 
                                    <div class="col-lg-12 col-md-12">
										<h2>Informasi Pembayaran</h2>
										<?php 
											while ($dbank = mysql_fetch_array($queryBank)) {
										?>	
											<div class="col-lg-4 col-md-4">
												<img src="<?=$abs?>/reg/img/<?=$dbank['img']?>" width="80px"><br>
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
		</div>

	</body>
</html>

<?php 
	//$html = ob_get_contents();
	// ob_get_clean();

	//$dompdf->load_html($html);
	//$dompdf->render();
	//$file = $dompdf->output();
	//file_put_contents('tagihan.pdf', $file);
?>