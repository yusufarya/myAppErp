<?php 

    session_start();
    require_once '../includes/koneksi2.php';
    $host = $_SERVER['HTTP_HOST'];

    $email = $_GET['email'];

    $sql = mysql_query("SELECT name, hash FROM cust WHERE email = '$email'") or die(mysql_error());
    $data = mysql_fetch_array($sql);
    $name = $data['name'];

    // playlist yt siscom
    // $ytlink = 'https://www.youtube.com/playlist?list=PL5AK93KsmlytgLHqi_U1iPGDjFIelPypO';
    $ytlink = 'https://www.youtube.com/watch?v=sSl-wtnn0Wk&list=PL5AK93KsmlyuEvyeK6lnixKcZysg5YL2w';

    //memanggil url server name
    $host = "http://$_SERVER[SERVER_NAME]/yusuf/siscomwebnew/pages/";
    // memanggil file untuk menampilkan halaman pdf
    $filePath = 'SISCOM_Online_Manual_Book.php';
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type='text/css'>

        @media only screen and (min-width: 105px) {
            body {
                width: 100%; height: 100%;
                margin: 0px; padding: 0px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .container{
                padding: 16px 25px; margin: 0px auto;
                background-color: rgb(255, 255, 255);
                border: 1px solid #dcdcdc; border-radius: 8px;
                box-shadow: 1px 2px 7px #abb7b8;
                width: 75%; height: 50%; margin-top: 0px; margin-bottom: 30px;
            }
            .row{
                display: block;
                clear: both;
            }
            .official{
                border-radius: 7px;
                border-bottom: 2px solid #d2dbdd;
                padding-bottom: 35px;
            }
            .pull-right{
                float: right;
                display: inline-block;
            }
            .icon img {
                display: block;
                margin: 0 auto; padding: 10px;
                max-height: 70px;
                align-items: center;
            }
            .pull-left{
                display: block;
            }
            .pull-left p {
                text-align: left !important;
            }
            .header p{
                font-size: 12.5px;
                font-weight: 500;
            }
            p {
                text-align: justify !important;
                color: rgb(89, 89, 89) !important;
                font-size: 14px;
            }
            .title {
                font-size: 14px;
                display: block;
                margin: 0;
                margin-top: 7px;
            }
            strong{
                color:black;
            }

            .text1 {
                align-items: center;
            }
            .text1 p {
                font-size: 11.5px;
                text-align: justify !important;
            }
            .text1 a {
                text-decoration: none;
            }

            .text2 {
                text-align: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 14px;
                padding-top: 10px !important;
            }

            .text1 .card {
                padding: 0px;
            }

            .card-body {
                border: 1px solid #e3f2f34b;
                border-radius: 8px;
                border-bottom: 3px solid #d2dbdd;
            }

            .card-body .col-icon img {
                max-width: 35px;
                margin: 12px 0 0 7px;
            }

            .card-body .col-text {
                margin-left: 3px;
            }
            .card-body .col-text .heading {
                font-weight: 500; font-size: 12px; display: block; margin-bottom: -8px !important; margin-top: 10px;
            }

            .card-body .col-text .paragraph {
                display: block;
                font-size: 11px;
                /*line-height: 1.6;*/
            }

            .card-body .col-text .link {
                font-size: 10.9px; padding-bottom: 5px; margin-top: -7px; display: block; color: #3781ac;
            }

            span {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            }

            .btn-login {
                display: block;
                font-size: 14px; font-weight: 500; text-align: center;
                width: 70px; height: 20px;
                margin: 10px auto 0; padding: 4px 10px 3px;
                background-color: #4094c5; color: white;
                border-radius: 3px;
            }
            .sosmed {
                margin: 12px auto 0;
                text-align: center;
            }
            .sosmed a {
                text-decoration: none;
                color: #3c678f;
                margin: 2px;
            }
            .sosmed img {
                max-width: 12.5px;
            }
        }

        @media only screen and (min-width: 789px) {
            body{
                width: 100%; height: 100%;
                margin: 0px; padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .container{
                padding: 23px 45px; margin:0px auto;
                background-color: rgb(255, 255, 255);
                border: 1px solid #dcdcdc; border-radius: 8px;
                box-shadow: 1px 2px 7px #abb7b8;
                width: 55%; height: 50%; margin-top: 20px; margin-bottom: 30px;
            }
            .row{
                display: block;
                clear: both;
            }
            .official{
                border-radius: 10px;
                border-bottom: 3px solid #d2dbdd;
                padding-bottom: 45px;
            }
            .pull-right{
                float: right;
                display: inline-block;
            }
            .icon img {
                display: block;
                margin: 0 auto; padding: 10px;
                max-height: 120px;
                align-items: center;
                border-radius: 12px;
            }
            .pull-left{
                display: block;
            }
            .pull-left p {
                text-align: left !important;
            }
            .header p{
                font-size: 16px;
                font-weight: 600;
            }
            .title {
                font-size: 18.5px;
                display: block;
                margin: 7px 0 3px;
            }
            p {
                text-align: justify !important;
                color: rgb(89, 89, 89);
                font-size: 14px;
            }
            strong{
                color:black;
            }

            .text1 {
                align-items: center;
            }
            .text1 p {
                font-size: 13.8px;
                text-align: justify !important;
            }
            .text1 a {
                text-decoration: none;
            }
            .text2 {
                text-align: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 14px;
                padding-top: 10px !important;
            }

            .text1 .card {
                padding: 0 42px;
            }

            .card-body {
                border: 1px solid #e3f2f34b; border-radius: 10px;
                border-bottom: 3px solid #d2dbdd;
                padding: 0 10px;
            }

            .card-body .col-icon img {
                max-width: 70px;
                margin: 10px 0 0 20px;
            }

            .card-body .col-text {
                margin-left: 15px;
            }
            .card-body .col-text .heading {
                font-weight: 500; font-size: 15px; display: block; line-height: 1px; margin-bottom: 9px !important; margin-top: 25px !important;
            }

            .card-body .col-text .paragraph {
                display: inline;
                font-size: 13px;
            }

            .card-body .col-text .link {
                font-size: 12.6px; padding-bottom: 5px; margin-top: 4px; display: block; color: #3781ac;
            }

            span {
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            }

            .btn-login {
                display: block;
                font-size: 14px; font-weight: 500; text-align: center;
                width: 70px; height: 20px;
                margin: 14px auto 0; padding: 4px 10px;
                background-color: #4094c5; color: white;
                border-radius: 3px;
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
                    <div class='pull-right'>
                        <p>Account&emsp;|&emsp;<strong>Notice</strong>&nbsp; &nbsp;</p>
                    </div>
                </div>
                <div class='official'>
                </div>	
                <div class='row'>
                    <h3 class="title">Selamat Datang di SISCOM Online</h3>
                    <div class='icon'>
                        <img src="<?php echo $abs; ?>/reg/img/LOGO-SISCOM1.png" alt="logo">
                    </div>
                </div>
            </div>
            <div class='body'>
                <div class='pull-left'>
                    <p>Halo, <strong><?php echo $name; ?>!</strong></p> 
                    <div class='text1'>
                        <p>SISCOM, pionir software akuntansi di Indonesia sejak tahun 1994. Bersama SISCOM, Anda dapat kelola pembukuan dengan mudah, cepat, dan akurat. Berikut panduan untuk memulai:</p>
                        <div class="card">
                            <div class="card-body" style="margin-bottom: 4px;">
                                <div class="row" style="display: flex;">
                                    <div class="col-icon" style="margin-right: 10px;">
                                        <img src="<?php echo $abs; ?>/reg/img/book-2.png" alt="manual book" style="float: left;">
                                    </div>
                                    <div class="col-text" style="float: right;">
                                        <p class="heading" style="font-weight: 500;">Buku Panduan</p>
                                        <p class="paragraph">Baca dan pelajari panduan menggunakan SISCOM Online</p>
                                        <a href="<?php echo $host . $filePath; ?>" class="link" target="_blank">Buku Panduan <span>></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="padding-bottom: 4px;">
                                <div class="row" style="display: flex;">
                                    <div class="col-icon" style="margin-right: 10px;">
                                        <img src="<?php echo $abs; ?>/reg/img/youtube-2.png" alt="youtube" style="float: left;">
                                    </div>
                                    <div class="col-text" style="float: right;">
                                        <p class="heading" style="font-weight: 500;">Video Panduan SISCOM Online</p>
                                        <p class="paragraph">Tonton dan pelajari panduan menggunakan SISCOM Online</p>
                                        <a href="<?php echo $ytlink; ?>" class="link" target="_blank">Panduan SISCOM Online <span>></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin-bottom: 4px;">
                                <div class="row" style="display: flex;">
                                    <div class="col-icon" style="margin-right: 10px; margin-bottom: 10px">
                                        <img src="<?php echo $abs; ?>/reg/img/whatsapp.png" alt="manual book" style="float: left;">
                                    </div>
                                    <div class="col-text" style="float: right;">
                                        <p class="heading" style="font-weight: 500;">Contact SISCOM Online</p>
                                        <p class="paragraph">Untuk panduan, harga, opsi Private Cloud, atau Modifikasi sistem</p>
                                        <a href="https://api.whatsapp.com/send?phone=6281381333515&text=Hai, Saya ada pertanyaan mengenai SISCOM Online" class="link" target="_blank">Contact SISCOM Online <span>></span></a>
                                    </div>
                                </div>
                            </div><br>
                            <p style="margin: 15px auto 0; text-align: center !important; display: block; font-weight: 500;">Login SISCOM Online ?</p>
                            <a href="<?php echo $abs; ?>/reg/login.php?id=log" class="btn-login" target="_blank" style="color: white;">Login</a>
                        </div>
                        <br><br>
                    </div>
                </div>
                <div class='row'>
                    <div class='text2'>
                        <strong>PT. Shan Informasi Sistem</strong><br>
                        City Resort Rukan Malibu Blok J/75-77 <br>
                        Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
                        Telp : +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/' target="_blank" style="color: #3781ac;">SISCOM Online</a>
                    </div>
                </div>
            </div>
            <div class="sosmed">
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