<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['SERVER_NAME'];

	$email = $_GET['email'];

	$sql = mysql_query("SELECT * FROM `cust` WHERE email='$email'") or die(mysql_error());
	$data = mysql_fetch_array($sql);

	$name = $data['name'];
	$email = $data['email'];
	$phone = $data['phone'];

	if($data!=null){
		$host = $_SERVER['SERVER_NAME'];
		mysql_query("UPDATE cust SET valid='Y' WHERE email='$email'") or die(mysql_error());

		//kirim email ke sales
		$salesman_id  = $data['salesman_id'];
		// var_dump($salesman_id);
		$sales 		  = mysql_query("SELECT c.id, c.email AS email_cust, c.name, c.salesman_id, 
										s.email AS email_sales, c.phone, s.name AS sales_name  
										FROM `cust` c
										LEFT JOIN salesman s ON s.id = c.salesman_id
										WHERE c.salesman_id = '$salesman_id'") or die (mysql_error());
		$dataS 		  = mysql_fetch_array($sales);
	} else {
		echo "error";
	}
?>
<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<style type='text/css'>
				body{
					width: 100%;
					height: 100%;
					margin: 0px;
					background-color: #dcdcdc;
					font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
				}
				.container{
					background: white;
					width: 70%;
					height: 100%;
					padding: 23px 45px; margin: 25px auto;
					border: 1px solid #dcdcdc; 
					border-radius: 8px; box-shadow: 1px 2px 7px #abb7b8;
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
					font-size: 14.5px;
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
					content: '';
					width: 0;
					height: 0;
					position: absolute;
					bottom: 50px;
					left: -10px;
					border-width: 10px;
					border-style: solid;
					border-color: transparent white transparent transparent;
				}
				.border-green{
					width: 8px;
					height: 60px;
					background-color: #96d666;
				}
				.border-green:after{
					content: '';
					width: 0;
					height: 0;
					position: absolute;
					bottom: 0px;
					left: -10px;
					border-width: 10px;
					border-style: solid;
					border-color: transparent transparent white transparent;
				}
				.text{
					text-align: center;
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
			</style>
		</head>
		<body style='background-color: #d2dbdd;'>
			<div class='container'>
				<div class='header'>
					<div class='row'>
						<div class='icon'>
							<img src='<?php echo $abs; ?>/reg/img/LOGO-SISCOM1.png' width='200px'>
						</div>
						<div class='official'>
						</div>	
					</div>
					<div class='row'>
						<div class='pull-right'>
							<p>Account&emsp;|&emsp;<strong>Konfirmasi Email Customer</strong></p>
						</div>
					</div>
				</div>
				<div class='body'>
					<div class='pull-left'>
						<h3>Pemberitahuan Konfirmasi Email Customer</h3>
						<p>Halo <strong><?php echo $dataS['sales_name']; ?>,</strong></p>
						<div class='text'>
		                	<p>Customer Anda : <strong><?php echo strtoupper($data['name']); ?></strong></p>
		                	<p>Email &emsp;&emsp;&emsp;&emsp;: <strong><?php echo strtolower($data['email']); ?></strong></p>
		                	<p>No. Telp&emsp;&emsp;&emsp;: <strong><?php echo strtoupper($data['phone']); ?></strong></p>
							<p>Telah melakukan konfirmasi email layanan SISCOM Online.  Terimakasih.</p>
							<br>
						</div>
					</div>
					<div class='row'>
						<div class='text'>
							<br><br>
							<strong>PT. Shan Informasi Sistem</strong><br>
							City Resort Rukan Malibu Blok J/75-77 <br>
							Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
							Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/''>SISCOM Online</a>
						</div>
					</div>
				</div>
				<div class='sosmed'>
	                <a href='https://www.facebook.com/siscomsoftware/' style='font-size: 14px;'>
	                    <i class='bi bi-facebook'></i> Facebook
	                </a>
	                <a href='https://www.instagram.com/siscomsoftware/' style='font-size: 14px;'>
	                    <i class='bi bi-instagram'></i> Instagram
	                </a>
	                <a href='https://api.whatsapp.com/send?phone=62811803519&text=Hai, Saya ingin konsultasi' style='font-size: 14px;'>
	                    <i class='bi bi-whatsapp'></i> Whastapp
	                </a>
	            </div>
			</div>

			
		</body>
		</html>