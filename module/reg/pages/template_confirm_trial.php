<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];

	$cust_id 	= $_GET['cust_id'];

	$sql = mysql_query("SELECT co.order_id, co.cust_id, c.email, c.name, c.phone, co.dbname 
						FROM `cust` c 
						LEFT JOIN cust_order co on co.cust_id = c.id
						WHERE cust_id = '$cust_id'
						ORDER BY order_id DESC") or die(mysql_error());
	$data = mysql_fetch_array($sql);
	// var_dump($data);
	$name = $data['name'];
	$email = $data['email'];
	$telp = $data['phone'];
	$dbname = $data['dbname'];
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		@media only screen and (min-width: 222px) {
			body{
				width: 100%;
				height: 100%;
				margin: 0px;
				font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			}
			.container{
				padding: 20px 30px;
				background-color: white;
				border: 1px solid #dcdcdc; border-radius: 4px;
	            box-shadow: 1px 2px 7px #abb7b8;
				margin: 0 auto;
				width: 76%; height: 100%; margin-top: -5px; margin-bottom: 10px;
			}
			.row{
				display: block;
				clear: both;
			}
			.row .icon img {
				max-width: 130px;
			}
			.header{
				border-bottom: 1px solid grey;
				padding-bottom: 40px;
			}
			.pull-right{
				float: right;
				display: inline-block;
			}
			.pull-left{
				float: left;
				display: inline-block;
			}
			.pull-left .title {
				font-size: 14.5px;
			}
			.pull-left p {
				font-size: 13px;
				line-height: 1;
			}
			.header p{
				font-size: 13.5px;
				font-weight: 600;
			}
			p{
				text-align: left !important;
				color: grey;
				font-size: 12.5px;
			}
			strong{
				color:black;
			}
			.text{
				text-align: center;
				font-size: 12.5px;
			}

			.sosmed {
	            margin-top: 15px !important;
	            margin: 0 auto;
	            text-align: center;
	        }
	        .sosmed a {
	            text-decoration: none;
	            color: #3c678f;
	            margin: 3px;
	        }
	        .sosmed img {
	            max-width: 12px;
	        }
		}
		@media only screen and (min-width: 789px) {
			body{
				width: 100%;
				height: 100%;
				margin: 0px;
				font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			}
			.container{
				padding: 20px 30px;
				background-color: white;
				border: 1px solid #dcdcdc; border-radius: 8px;
	            box-shadow: 1px 2px 7px #abb7b8;
				margin: 0 auto;
				width: 60%; height: 100%; margin-top: 15px; margin-bottom: 15px;
			}
			.row{
				display: block;
				clear: both;
			}
			.row .icon img {
				max-width: 200px;
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
			.pull-left .title {
				font-size: 15.8px;
			}
			.pull-left p {
				font-size: 13px;
				line-height: 1;
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
				color: black;
			}
			
			.text{
				text-align: center;
				font-size: 13.6px;
			}

			.sosmed {
	            margin-top: 15px !important;
	            margin: 0 auto;
	            text-align: center;
	        }
	        .sosmed a {
	            text-decoration: none;
	            color: #3c678f;
	            margin: 5px;
	        }
	        .sosmed img {
	            max-width: 15px;
	        }
		}

	</style>
</head>
<body style="background-color: #d2dbdd;">
	<div>
    	<br>
    </div>
	<div class="container">
		<div class="header">
			<div class="row">
				<div class="icon">
					<!--<img src="../img/LOGO-SISCOM.png" width="200px">-->
					<img src="<?=$abs?>/reg/img/LOGO-SISCOM1.png">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account | <strong>Trial Email Customer</strong></p>
				</div>
			</div>
		</div>
		<div class="body">
			<div class="pull-left">
				<h3 class="title">Pemberitahuan Trial Email Customer</h3>
				<p>Halo <strong>Admin,</strong></p>
				<div class="text">
                	<p>Customer Anda : <strong><?php echo $name; ?></strong></p>
                	<p>Email &emsp;&emsp;&emsp;&emsp;: <strong><?php echo $email; ?></strong></p>
                	<p>No. Telp&emsp;&emsp;&emsp;: <strong><?php echo $telp; ?></strong></p>
                	<p>Database &emsp;&emsp; : <strong><?php echo $dbname; ?></strong></p>
					<p>Telah melakukan trial layanan SISCOM Online. Terimakasih.</p>
					<br>
				</div>
					
			</div>
			<div class="row">
				<div class="text">
					<br>
					<strong>PT. Shan Informasi Sistem</strong><br>
					City Resort Rukan Malibu Blok J/75-77 <br>
					Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
					Tel: +62 21 5694 5002 | <a href="https://www.siscomonline.co.id/">SISCOM Online</a>
				</div>
			</div>
		</div>

		<div class="sosmed">
            <a href="https://www.facebook.com/siscomsoftware/" style="font-size: 14px;">
                <img src="<?php echo $abs; ?>/reg/img/facebook.svg">
            </a>
            <a href="https://www.instagram.com/siscomsoftware/" style="font-size: 14px;">
                <img src="<?php echo $abs; ?>/reg/img/instagram.svg">
            </a>
            <a href="https://api.whatsapp.com/send?phone=62811803519&text=Hai, Saya ingin konsultasi" style="font-size: 14px;">
                <img src="<?php echo $abs; ?>/reg/img/whatsapp.svg">
            </a>
            <a href="https://id.linkedin.com/company/pt-shan-informasi-sistem" style="font-size: 14px;">
                <img src="<?php echo $abs; ?>/reg/img/linkedin-brands.svg">
            </a>
        </div>
	</div>

	<br>
	
</body>
</html>