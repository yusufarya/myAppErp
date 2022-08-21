<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];

	$email 		 = $_GET['email'];
	$email_cust 	= $_GET['emailcust'];

	$sql = mysql_query("SELECT c.email, c.name, c.phone, s.name AS sales_name FROM `cust` c 
						LEFT JOIN salesman s on s.id = c.salesman_id
						WHERE c.email = '$email_cust'") or die(mysql_error());
	$data = mysql_fetch_array($sql);
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

	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="row">
				<div class="icon">
					<!--<img src="../img/LOGO-SISCOM.png" width="200px">-->
					<img src="<?=$abs?>/reg/img/LOGO-SISCOM.png" width="200px">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account&emsp;|&emsp;<strong>Konfirmasi Email Customer</strong></p>
				</div>
			</div>
		</div>
		<div class="body">
			<div class="pull-left">
				<h3>Pemberitahuan Konfirmasi Email Customer</h3>
				<p>Halo <strong><?=$data['sales_name'];?>,</strong></p>
				<div class="text">
                	<p>Customer Anda : <strong><?=strtoupper($data['name'])?></strong></p>
                	<p>Email &emsp;&emsp;&emsp;&emsp;: <strong><?=strtolower($data['email'])?></strong></p>
                	<p>No. Telp&emsp;&emsp;&emsp;: <strong><?=strtoupper($data['phone'])?></strong></p>
					<p>Telah melakukan konfirmasi email layanan SISCOM Online.  Terimakasih.</p>
					<br><br><br>
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