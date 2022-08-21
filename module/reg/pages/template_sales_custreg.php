<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];

	$email 		 = $_GET['email'];
	$email_cust  = $_GET['emailcust'];

	$sql = mysql_query("SELECT c.email, c.name, c.phone, s.name AS sales_name FROM `cust` c 
						LEFT JOIN salesman s ON s.id = c.salesman_id
						WHERE c.email = '$email_cust'") or die(mysql_error());
	$data = mysql_fetch_array($sql);
	// var_dump($data);

	$email_cust  = $data['email'];
?>

<!-- <!DOCTYPE html>
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
					<img src="https://myapp.siscom.id/module/reg/img/LOGO-SISCOM1.png" width="200px">
				</div>
				<div class="official">
					
				</div>	
			</div>
			<div class="row">
				<div class="pull-right">
					<p>Account&emsp;|&emsp;<strong>Customer Registration</strong></p>
				</div>
			</div>
		</div>
		<div class="body">
			<div class="pull-left">
				<h3>Pemberitahuan Registrasi Email Customer</h3>
				<p>Halo <strong><?=$data['sales_name'];?>,</strong></p>
				<div class="text">
                	<p>Customer Anda : <strong><?=strtoupper($data['name'])?></strong></p>
                	<p>Email &emsp;&emsp;&emsp;&emsp;: <strong><?=strtolower($data['email'])?></strong></p>
                	<p>No. Telp&emsp;&emsp;&emsp;: <strong><?=strtoupper($data['phone'])?></strong></p>
					<p>Telah melakukan registrasi email layanan SISCOM Online.  Terimakasih.</p>
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
</html> -->

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css'>
        <style type='text/css'>

        	@media only screen and (min-width: 260px) {
            	/*for desktop */
            	body{
		            width: 100%;
		            height: 100%;
		            margin: 0px;
		            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	            }
	            .container{
	                padding: 20px 20px;
	                background-color: white;
	                border: 1px solid #dcdcdc;
	                border-radius: 8px;
	                box-shadow: 1px 2px 7px #abb7b8;
	                margin:0px auto;
	                width: 75%; height: 50%; margin-top: 10px; margin-bottom: 10px;
	            }
	            .row{
	                display: block;
	                clear: both;
	            }
	            .header{
	                border-bottom: 1px solid #dcdcdc;
	                padding-bottom: 38px;
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
	                font-size: 13px;
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
	            .title {
	            	font-size: 17px;
	            }
	            .text2{
	                text-align: center;
	                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	                font-size: 14px;
	                padding-top: 10px !important;
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
            }
            
            @media only screen and (min-width: 768px) {
            	/*for desktop */
            	body{
		            width: 100%;
		            height: 100%;
		            margin: 0px;
		            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	            }
	            .container{
	                margin-top: 30px !important;
	                padding: 20px 40px;
	                background-color: white;
	                border: 1px solid #dcdcdc;
	                border-radius: 8px;
	                box-shadow: 1px 2px 7px #abb7b8;
	                margin:0px auto;
	                width: 55%; height: 50%; margin-top: 15px; margin-bottom: 30px;
	            }
	            .row{
	                display: block;
	                clear: both;
	            }
	            .header{
	                border-bottom: 1px solid #dcdcdc;
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
	            a.btn{
	                text-decoration: none !important;
	            }
	            .btn{
	                margin-top: 90px;
	                text-align: center;
	                padding: 8px 10px;
	                height: 40px;
	                background-color: #438EB9;
	                color:white !important;
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
	            .text2{
	                text-align: center;
	                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	                font-size: 14px;
	                padding-top: 10px !important;
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
            }
        </style>
    </head>
    <body style='background-color: #d2dbdd;'>
        <div>
        <br>
        </div>
        <div class='container'>
            <div class='header'>
                <div class='row'>
                    <div class='icon'>
                        <img src='<?php echo $abs ?>/reg/img/LOGO-SISCOM1.png' width='200px'>
                    </div>
                    <div class='official'>	
                    </div>	
                </div>
                <div class='row'>
                    <div class='pull-right'>
                        <p>Account&emsp;|&emsp;<strong>Customer Registration</strong></p>
                    </div>
                </div>
            </div>
            <div class='body'>
                <div class='pull-left'>
                    <h3 class="title">Pemberitahuan Registrasi Email Customer</h3>
                    <p>Halo <strong><?php echo $data['sales_name']; ?>,</strong></p>
                    <div class='text1'>
                        <table style='font-size: 13px; color: #878787; margin-left: -3px;'>
                            <tr>
                                <td>Customer Anda</td>
                                <td> &emsp;: </td>
                                <td><strong><?php echo strtoupper($data['name']); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td> &emsp;: </td>
                                <td><strong><?php echo strtolower($data['email']); ?></strong></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td> &emsp;: </td>
                                <td><strong><?php echo strtoupper($data['phone']); ?></strong></td>
                            </tr>
                        </table>
                        <p style='font-size: 13px;'>Telah melakukan registrasi email layanan SISCOM Online. Terimakasih.</p>
                        <br><br>
                    </div>
                </div>
                <div class='row'>
                    <div class='text2'>
                        <strong>PT. Shan Informasi Sistem</strong><br>
                        City Resort Rukan Malibu Blok J/75-77 <br>
                        Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
                        Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
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
        <br>
        
    </body>
</html>