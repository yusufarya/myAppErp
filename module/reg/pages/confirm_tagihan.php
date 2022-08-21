<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];
// 
	// if (isset($_GET['hash'])) {
	// 	$hash = $_GET['hash'];
		$email = $_GET['email'];
		$inv_no = $_GET['inv_no'];

		$query = mysql_query("SELECT inv.*, c.name 
				FROM `invoice` inv
				LEFT JOIN invoice_detail inv_d on inv_d.inv_id = inv.id
				LEFT JOIN cust_order co on co.order_id = inv_d.order_id
				LEFT JOIN cust c on c.id = co.cust_id
				WHERE inv.inv_no = '$inv_no'") or die(mysql_error());
		$data = mysql_fetch_array($query);
		$total = $data['total_amount'] + $data['initial_amount'];
		$paid_date = date('d-M-Y', strtotime($data['paid_date']));
		$inv_date = date('d-M-Y', strtotime($data['inv_date']));
	// }
	// else{
	// 	die('no direct access allowed');
	// }

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
					<!--<img src="../img/LOGO-SISCOM.png" width="200px">-->
                    <!--<img src="https://<?=$_SERVER['SERVER_NAME'];?>/module/reg/img/LOGO-SISCOM.png" width="200px">-->
					<img src="<?=$abs?>/reg/img/LOGO-SISCOM.png" width="200px">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account&emsp;|&emsp;<strong>Customer Payment Confirmation</strong></p>
				</div>
			</div>
		</div> 
		<div class="body">
			<div class="pull-left">
				<p>Kepada Pelanggan Yang Terhormat,<br>
					<strong>Bpk/Ibu &nbsp;<?=strtoupper($data['name']);?></strong><br>
					<strong>Tagihan&emsp;&emsp;&emsp;&emsp;: #<?=$inv_no;?></strong><br>
					<strong>Tanggal Tagihan&nbsp;: <?=$inv_date;?></strong><br>
				<div class="text">
					<p>Terimakasih atas transaksi pembayaran tagihan siscomonline anda sebesar <strong>Rp. <?=number_format($total, 2,',','.');?></strong>.- pada tanggal <strong><?=$paid_date;?></strong></p>
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