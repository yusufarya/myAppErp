<?php 
	session_start();
	require_once 'includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];

	// if (isset($_GET['hash'])) {
		$email = $_GET['email'];
		$_email = strtolower($email);

		$sql = mysql_query("SELECT name FROM cust WHERE email = '$_email'") or die(mysql_error());
		$data = mysql_fetch_array($sql);
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

	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="row">
				<div class="icon">
					<!--<img src="../img/LOGO-SISCOM.png" width="200px">-->
                    <!--<img src="https://".$_SERVER['SERVER_NAME']."/siserp/module/reg/img/LOGO-SISCOM.png" width="200px">-->
					<img src="<?=$abs?>/reg/img/LOGO-SISCOM.png" width="200px">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account&emsp;|&emsp;<strong>Reset Password</strong></p>
				</div>
			</div>
		</div>
		<div class="body">
			<div class="pull-left">
				<h3>Instruksi Reset Password </h3>
				<p>Halo <strong><?=$_email;?>,</strong></p>
				<div class="text">
					<p>Untuk mereset password Anda, silahkan klik tombol Reset dan masuk ke website dengan menggunakan password yang telah diberikan.</p>
					<br><br><br>
					<!--<a href="https://<?=$host;?>/siserp/module/reg/confirm.php?email=<?=$email;?>&hash=resetpassword&password=<?=$_GET['password']?>" class="btn">Reset</a>-->
					<a href="<?=$abs?>/reg/confirm.php?email=<?=$_email;?>&hash=resetpassword&password=<?=$_GET['password']?>" class="btn">Reset</a>
					<br><br><br>
                    <?php if ($_GET['password']){ ?>
						<p>Untuk Masuk kedalam website, silahkan gunakan password : <strong><?=$_GET['password'];?></strong></p>
					<?php }else{ ?> 
					<p>Jika Anda merasa tidak mendaftar ke SISCOM, mohon untuk melaporkan kepada kami untuk menghindari penyalahgunaan identitas anda.</p>
                    <?php } ?>
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