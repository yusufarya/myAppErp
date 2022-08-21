<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	use Dompdf\Dompdf;
	include 'includes/style.php';
	include 'includes/dompdf/autoload.inc.php';
	
	$dompdf = new Dompdf();

	if (isset($_GET)) {
		$inv_id = $_GET['inv_id'];
		
		$sql = "SELECT inv.id AS inv_id, inv.attach_file, inv.total_amount, inv.ppn, inv.initial_amount, 
				inv.inv_no, inv.inv_date, inv.paid_off, inv.due_date, inv.paid_date, inv.paid_name, 
				inv_d.begin_date, inv_d.end_date, inv_d.expired_date, co.dbname, cs.email, cs.name, co.company_id, 
				cmp.name as nama_perusahaan, cmp.address, cmp.npwp_no, v.name AS versi, pck.name AS paket, 
				cb.bank_no AS acc_no, cb.bank_account AS acc_name, b.name AS bank_name, b.img AS bank_img, 
				inv.discount, (inv.total_amount - inv.discount + inv.ppn + inv.initial_amount) AS grand_total, 
				inv_d.amount, co.ver_id, pc.package_type, vc.description, s.value_month, sm.name AS salesman, 
				s.name AS subscribe_name, inv_d.subscribe_times, inv_d.order_idlama  
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
		$subscribe_name = $data['subscribe_name'];
		$dbname = $data['dbname'];
		$order_idlama = $data['order_idlama'];

		// GET EMAIL USER LAMA
		$sqlEmailL = "SELECT email from cust_order join cust on cust.id = cust_order.cust_id where order_id = '$order_idlama'";
		$qr = mysql_query($sqlEmailL) or die(mysql_error());
		$dataElama = mysql_fetch_array($qr);
		$emailLama = $dataElama['email'];

		//status tagihan
		$statusTagihan = '';
		
		$paidOff = $data['paid_off'];
		if($paidOff == 'Y'){
			$statusTagihan = 'Lunas';
		} 
		else if($paidOff == 'C'){
			$statusTagihan = 'Sedang Diproses';
		}
		else if($paidOff == 'N'){
			$statusTagihan = 'Belum Dibayar';
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

	    //Jenis Paket Ganti User
	    $verId = $data['ver_id'];
		$sql2 = mysql_query("SELECT *, p.id AS package_id FROM `package` p JOIN subscribe s ON p.subscribe_id = s.id WHERE ver_id = '$verId' AND package_type = '4' AND s.stsrec = 'A'");	   
		$row2 = mysql_fetch_array($sql2);
		$harga2 = $row2['price'];

		//ambil data Tambahan User
        $sqluser = mysql_query("SELECT inv.id, inv.inv_no, invd.order_id, co.cust_id, c.email, p.package_type
            FROM `invoice` inv
            LEFT JOIN invoice_detail invd on invd.inv_id = inv.id
            LEFT JOIN cust_order co on co.order_id = invd.order_id
            LEFT JOIN cust c on c.id = co.cust_id
            LEFT JOIN package p on p.id = invd.package_id
            WHERE inv.id='$inv_id' and package_type != 1") or die (mysql_error());
            $jumlah_user = mysql_num_rows($sqluser);
		
	}

	ob_start();
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
		</style>

	</head>

	<body>
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-body" style="display: flex; align-items: center;justify-content: center; align-self: center;">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="tagihan">
						<div class="header-tagihan">
							<div class="pull-left">
								<!--<img src="../img/LOGO-SISCOM.png" width="200px">-->
								<img src="<?=$abs?>/reg/img/LOGO-SISCOM.png" width="200px">
							</div>
							<div class="pull-right">
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
										<td colspan="2" class="col-width-33">
											<span class="judul-faktur">Kepada</span><br>
											<?=$data['nama_perusahaan'];?>
										</td>
										<td class="col-width-33">
											<span class="judul-faktur">penanggung jawab</span><br>
											<?=$data['name'].' ('.$data['email'].')';?>
										</td>
										<td rowspan="" class="right">
											<span class="judul-faktur">Salesman</span><br>
											<?=strtoupper($salesman);?>
										</td>
									</tr>
									<tr>
										<td rowspan="2" class="col-width-33 v-top">
											<span class="judul-faktur">alamat</span><br>
											<?=$data['address'];?>
										</td>
										<td colspan="2" class="col-width-33 v-top">
											<span class="judul-faktur">npwp</span><br>
											<?=$data['npwp_no'];?>
										</td>
										<td rowspan="2" class="right">
											<span class="judul-faktur">total</span><br>
											<span class="nominal">IDR <?=$totalharga;?></span>
										</td>
									</tr>
								</thead>
							</table>

							<table class="table" width="100%" id="detail">
								<thead>
									<tr>
										<td colspan="2">
											<strong>NAMA PRODUK</strong>
										</td>
										<td class="right" width="20%">
											<span class="judul-faktur">sub-total</span>
										</td>										
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="3">
											<span><strong>Database&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;:</strong><strong>&nbsp;<?=$data['dbname']?>&nbsp;</strong> (Periode <?=$periode;?>)</span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<strong>Paket Dasar&emsp;&emsp;&emsp;&emsp;: <?=$subscribe_name?>&emsp;(Versi <?=$data['versi'];?>)</strong>
										</td>
										<td class="right harga">
											<strong><?=number_format(($data['amount']),2,',','.');?></strong>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<strong>
												Ganti User&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:  @ Rp. <?=number_format($harga2,2,',','.');?>
											</strong>
										</td>
										<td class="right harga">
											<strong><?=number_format(($harga2),2,',','.');?></strong>
										</td>
									</tr>

                                    <tr>
		                                <td rowspan="5" class="v-top">
		                                    <span class="" style="margin-right: 10px;">User lama : <?=$emailLama;?></span> <br>
		                                    <?php while ($data = mysql_fetch_array($sqluser)) {
		                                        if($jumlah_user != 0){
		                                    ?>  
		                                        <span class="" style="margin-right: 10px;">User baru : <?=$data['email'];?></span>
		                                    <?php 
		                                        }
		                                    } ?>
		                                </td>
                                        <td class="right total">                               
                                            <span class="judul-faktur">Potongan Voucher</span>
                                        </td>
                                        <td class="right harga">
                                            <span><?=number_format($discount,2,',','.');?></span>
                                        </td>
                                    </tr>
									<tr>
										<td class="right total">										
											<span class="judul-faktur">sub total</span>
										</td>
										<td class="right harga">
											<span><?=number_format($total_amount,2,',','.');?></span>
										</td>
									</tr>
									<tr>
										<td class="right total">										
											<span class="judul-faktur">PPN 10%</span>
										</td>
										<td class="right harga">
											<span><?=number_format($ppn,2,',','.');?></span>
										</td>
									</tr>
                                    <!-- <tr>
                                        <td class="right total">                                        
                                            <span class="judul-faktur">Kode unik</span>
                                        </td>
                                        <td class="right harga">
                                            <span><?=number_format($initial_amount,2,',','.');?></span>
                                        </td>
                                    </tr> -->
									<tr>
										<td class="right total">										
											<span class="judul-faktur">total</span>
										</td>
										<td class="right harga">
											<span>IDR &emsp;<?=$totalharga;?></span>
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
											<div class="col-lg-4 col-md-4">
												<!--<img src="../img/<?=$dbank['img']?>" width="80px"><br>-->
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
							<div class="alamat">
								<p><strong>PT. Shan Informasi Sistem</strong><br>
                                    City Resort Rukan Malibu Blok J/75-77 <br>
                                    Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
                                    Tel: +62 21 5694 5002 | <a href="https://www.siscomonline.co.id/">SISCOM Online</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</body>
</html>

<?php 
	$html = ob_get_contents();
	// ob_get_clean();

	$dompdf->load_html($html);
	$dompdf->render();
	$file = $dompdf->output();
	file_put_contents('tagihan.pdf', $file);
?>