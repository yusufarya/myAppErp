<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];
 
	$email = $_GET['email'];
	$inv_no = $_GET['inv_no'];

	$query = mysql_query("SELECT inv.*, c.name, c.email, co.end_date, co.expired_date
							FROM `invoice` inv
							LEFT JOIN invoice_detail inv_d on inv_d.inv_id = inv.id
							LEFT JOIN cust_order co on co.order_id = inv_d.order_id
							LEFT JOIN cust c on c.id = co.cust_id
							WHERE inv.inv_no = '$inv_no'") or die(mysql_error());
	$data = mysql_fetch_array($query);
	$total = $data['total_amount'] + $data['initial_amount'] + $data['ppn'] - $data['discount'];
	$paid_date = date('d-M-Y', strtotime($data['paid_date']));
	$end_date = date('d-M-Y', strtotime($data['end_date']));
	$expired_date = date('d-M-Y', strtotime($data['expired_date']));
	
	$sapaan 	 = ($email != 'tagihan@siscomonline.co.id') ? 'Kepada Pelanggan Yang Terhormat,' : 'Kepada Pelanggan Yang Terhormat,';
	$keterangan = ($email != 'tagihan@siscomonline.co.id') ? 'Pembayaran Anda sudah kami terima dan Database Anda sudah aktif hingga tanggal: ' : 'Pembayaran Anda sudah kami terima dan Database Anda sudah aktif hingga tanggal: ';
	$penutup	= ($email != 'tagihan@siscomonline.co.id') ? '. Terima kasih telah menggunakan layanan kami.' : '. Terima kasih telah menggunakan layanan kami.';

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body{
			width: 100%;
			height: 100%;
			margin: 0px;
			background-color: #dcdcdc;
		}
		.container{
			padding: 30px;
			background-color: white;
			width: 75%;
			height: 100%;
			margin:0px auto;
		}
		.row{
			display: block;
			clear: both;
		}
		.header{
			border-bottom: 1px solid grey;
			padding-bottom: 50px;
		}
		.pull-right{
			float: right;
			display: inline-block;
		}
		.pull-left{
			float: left;
			display: inline-block;
		}
		.header p{
			font-size: 16px;
			font-weight: 600;
		}
		p{
			text-align: justify !important;
			color: grey;
			font-size: 14px;
		}
		strong{
			color:black;
		}
		.text{
			margin-right: 30px;
		}
		a.btn{
			text-decoration: none !important;
		}
		.btn{
			margin-top:300px;
			text-align: center;
			padding: 10px;
			height: 40px;
			background-color: #438EB9;
			color:white;
			border-radius: 5px;
		}
		.btn:hover{
			background-color: #53b2e8;
		}
		#border{
			position: absolute;
			top: 165px;
			right: 12%;
		}
		.border-black{
			width: 8px;
			height: 200px;
			background-color: #003b6a;
		}
		.border-blue{
			width: 8px;
			height: 100px;
			background-color: #75ddf4;
		}
		.border-blue:after{
			content: "";
			width: 0;
			height: 0;
			position: absolute;
			bottom: 50px;
			left: -10px;
			border-width: 10px;
			border-style: solid;
			border-color: transparent white transparent transparent;
			/*transform: rotate(90deg);*/
		}
		.border-green{
			width: 8px;
			height: 60px;
			background-color: #96d666;
		}
		.border-green:after{
			content: "";
			width: 0;
			height: 0;
			position: absolute;
			bottom: 0px;
			left: -10px;
			border-width: 10px;
			border-style: solid;
			border-color: transparent transparent white transparent;
			/*transform: rotate(90deg);*/
		}
		.text{
			text-align: center;
		}
		p{
			line-height: 20px;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="row">
				<div class="icon">
                    <!--<img src="https://<?=$_SERVER['SERVER_NAME'];?>/module/reg/img/LOGO-SISCOM.png" width="200px">-->
					<img src="<?=$abs?>/reg/img/LOGO-SISCOM.png" width="200px">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account&emsp;|&emsp;<strong>Paid</strong></p>
				</div>
			</div>
		</div> 
		<div class="body">
			<div class="pull-left">
				<p><?=$sapaan?><br>
					Bpk/Ibu <strong><?=$data['name'];?> &nbsp; (<?=$data['email'];?>)</strong><br>
					Nomor Faktur : <strong><?=$inv_no;?></strong><br>
					Total Pembayaran: <strong>Rp <?=number_format($total, 2,',','.');?></strong><br>
					Tanggal Pembayaran: <strong><?=$paid_date;?></strong>
					</p>
				<div class="text">
					<p><?=$keterangan?><strong><?=$end_date?></strong><?=$penutup?></p>
					<br>
					<p>Hormat kami, <br>
						<strong>PT. Shan Informasi Sistem </strong>
					</p>
				</div>
					
			</div>
			<div class="row">
				<div class="text">
					<br><br><br>
					<strong>PT. Shan Informasi Sistem</strong><br>
					City Resort Rukan Malibu Blok J/75-77 <br>
					Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
					Tel: +62 21 5694 5002 | <a href="https://www.siscomonline.co.id/">SISCOM Online</a>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>