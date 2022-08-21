<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];

	if (isset($_GET['hash'])) {
		$hash = $_GET['hash'];
		$email = $_GET['email'];
		$appcode = $_GET['app'];

		$sql = mysql_query("SELECT user_id FROM user_addon WHERE user_email='$email'") or die(mysql_error());
		$data = mysql_fetch_array($sql);
	}
	else{
		die('no direct access allowed');
	}

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
                    <img src="<?=$abs2?>/module/reg/img/LOGO-SISCOM.png" width="200px">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account&emsp;|&emsp;<strong>Konfirmasi Email</strong></p>
				</div>
			</div>
		</div>
		<div class="body">
			<div class="pull-left">
				<h3>Konfirmasi email Anda!</h3>
				<p>Halo <strong><?=$data['user_id'];?>,</strong></p>
				<div class="text">
					<p>Email Anda telah didaftarkan untuk layanan SISCOM Addon. Demi keamanan mohon untuk tidak memberitahukan security code anda kepada siapapun.</p>
					<p>Mohon untuk mengkonfirmasikan email Anda dengan mengklik tombol di bawah ini.</p>
					<br><br><br>
						<a href="<?=$abs2?>/module/reg/confirm_addon.php?email=<?=$email;?>&hash=<?=$hash;?>" class="btn">Konfirmasi Email</a>
						<br><br><br>
						<p>Untuk Masuk kedalam aplikasi SISCOM Addon, silahkan gunakan security code berikut : <strong><?=$appcode;?></strong>.</p> 
					<p>Jika Anda merasa tidak mendaftar ke SISCOM, mohon untuk melaporkan kepada kami untuk menghindari penyalahgunaan identitas anda.</p>
                    
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