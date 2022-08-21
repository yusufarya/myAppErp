<?php 
	session_start();
	require_once '../includes/koneksi2.php';
	$host = $_SERVER['HTTP_HOST'];

	$email = $_GET['email'];


	$sql = mysql_query("SELECT inv.*, c.email, c.name
						FROM `invoice` inv
						JOIN cust c ON c.id = inv.cust_id
						WHERE c.email = '$email'") or die(mysql_error());
	$data = mysql_fetch_array($sql);
	$name = $data['name'];

?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css'>
        <style type='text/css'>
            
            @media only screen and (min-width: 250px){
            	/*PHONE*/
            	body{
	                width: 100%;
	                height: 100%;
	                margin: 0px;
	                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	            }
	            .row{
	                display: block;
	                clear: both;
	            }
	            .header{
	                border-bottom: 1px solid #dcdcdc;
	                padding-bottom: 33px;
	            }
	            .header .icon img {
	            	max-width: 150px
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
	                font-size: 12px;
	                font-weight: 600;
	            }
	            .title {
	            	font-size: 17px;
	            }
	            p {
	                text-align: justify !important;
	                color: rgb(89, 89, 89);
	                font-size: 14px;
	            }
	            strong{
	                color:black;
	            }
            	.container{
	                padding: 23px 20px;
	                background-color: white;
	                border: 1px solid #dcdcdc;
	                border-radius: 8px;
	                box-shadow: 1px 2px 7px #abb7b8;
	                margin: 0px auto;
	                width: 75%; height: 50%; margin-top: 10px; margin-bottom: 10px;
	            }
	            .text1 {
                	align-items: center;
                	/*width: 900px;*/
	            }
	            .text1 a {
	                text-decoration: none;
	            }
	            .text1 .btn-confirm {
	                display: block;
	                margin: 1px auto 0px;
	                text-align: center;
	                padding: 5px 15px;
	                width: 39%;
	                height: 20px;
	                font-weight: 400;
	                font-size: 14px;
	                background-color: #4898c7;
	                color: white !important;
	                border-radius: 5px;
	                transition: .2s;
	            }
	            .text1 .btn-confirm:hover {
	                background-color: #3781ac;
	                color: #dcdcdc;
	            }
	            .text2 {
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
	                margin: 3px;
	            }
	            .sosmed img {
	                max-width: 13px;
	            }
            }
            @media only screen and (min-width: 789px) {
			  	/* For desktop: */
			  	body{
	                width: 100%;
	                height: 100%;
	                margin: 0px;
	                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	            }
	            .row{
	                display: block;
	                clear: both;
	            }
	            .header{
	            	border-radius: 9px;
	                border-bottom: 2px solid #dcdcdc;
	                padding-bottom: 45px;
	            }
	            .header .icon img {
	            	max-width: 200px;
	            	padding: 2px 8px 5px;
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
	            p {
	                text-align: justify !important;
	                color: rgb(89, 89, 89);
	                font-size: 14px;
	            }
	            strong{
	                color:black;
	            }
	            .container{
	                padding: 23px 45px;
	                background-color: white;
	                border: 1px solid #dcdcdc;
	                border-radius: 8px;
	                box-shadow: 1px 2px 7px #abb7b8;
	                margin: 0px auto;
	                width: 50%; height: 50%; margin-top: 25px; margin-bottom: 30px;
	            }

	            .text1 {
	                align-items: center;
	            }
	            .text1 a {
	                text-decoration: none;
	            }
	            .text1 .btn-confirm {
	                display: block;
	                margin: 1px auto 0px;
	                text-align: center;
	                padding: 5px 8px;
	                width: 20%;
	                height: 20px;
	                font-weight: 400;
	                font-size: 14px;
	                background-color: #4898c7;
	                color: white !important;
	                border-radius: 5px;
	                transition: .2s;
	            }
	            .text1 .btn-confirm:hover {
	                background-color: #3781ac;
	                color: #dcdcdc;
	            }
	            .text2 {
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
	            .sosmed img {
	                max-width: 15px;
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
                        <img src='<?php echo $abs ?>/reg/img/LOGO-SISCOM1.png'>
                    </div>
                    <div class='official'>	
                    </div>	
                </div>
                <div class='row'>
                    <div class='pull-right'>
                        <p>Account | <strong>Caution</strong> &nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='body'>
                <div class='pull-left'>
                    <h3 class="title">Peringatan Database Akan di Hapus</h3>
                    <p>Halo <strong><?php echo $name; ?>,</strong></p>
                    <div class='text1'>
                        <p style='font-size: 13.5px;'>Kami ingin memberitahu bahwa jika dalam 7 hari kedepan, tidak ada aktivitas apapun di dashboard siscomonline.co.id, maka database Anda akan terhapus otomatis oleh sistem..</p>
                        
                        <p style='font-size: 13.5px;'>Jika masih ada pertanyaan, Anda dapat menghubungi<b> <i>  Product Consultant kami </i> </b> <a href="https://api.whatsapp.com/send?phone=6281381333515" target="_blank">081381333515</a></p>
                        <br>
                    </div>
                </div>
                <div class='row'>
                    <div class='text2'>
                        <strong>PT. Shan Informasi Sistem</strong><br>
                        City Resort Rukan Malibu Blok J/75-77 <br>
                        Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
                        Telp : +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
                    </div>
                </div>
            </div>
            <div class='sosmed'>
                <a href="https://www.facebook.com/siscomsoftware/" style="font-size: 14px;" target="_blank">
                    <img src="<?php echo $abs; ?>/reg/img/facebook.svg">
                </a>
                <a href="https://www.instagram.com/siscomsoftware/" style="font-size: 14px;" target="_blank">
                    <img src="<?php echo $abs; ?>/reg/img/instagram.svg">
                </a>
                <a href="https://api.whatsapp.com/send?phone=6281381333515&text=Hai, Saya ingin konsultasi" style="font-size: 14px;" target="_blank">
                    <img src="<?php echo $abs; ?>/reg/img/whatsapp.svg">
                </a>
                <a href="https://id.linkedin.com/company/pt-shan-informasi-sistem" style="font-size: 14px;" target="_blank">
                    <img src="<?php echo $abs; ?>/reg/img/linkedin-brands.svg">
                </a>
            </div>
        </div>
        <br>
        
    </body>
</html>