<?php 
session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Login dan Register</title>
 	<!-- Tell the browser to be responsive to screen width -->
 	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 	<!-- Bootstrap 3.3.7 -->
 	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
 	<!-- DataTables -->
 	<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
 	<!-- Font Awesome -->
 	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
 	<!-- Theme style -->
 	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  	<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
   	<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- Magnify -->
    <link rel="stylesheet" href="magnify/magnify.min.css">

    <!-- Google Font -->
   	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- inter -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <!-- Google Recaptcha -->
   	<script src='https://www.google.com/recaptcha/api.js'></script>
	
    <!-- Custom CSS -->
    <style type="text/css">

    /* Small devices (tablets, 768px and up) */
	@media (min-width: 768px){ 
		#navbar-search-input{ 
			width: 60px; 
		}
		#navbar-search-input:focus{ 
			width: 100px; 
		}
	}

    /* Medium devices (desktops, 992px and up) */
	@media (min-width: 992px){ 
		#navbar-search-input{ 
			width: 150px; 
		}
		#navbar-search-input:focus{ 
			width: 250px; 
		} 
	}
/*

    .word-wrap{
      overflow-wrap: break-word;
    }
    .prod-body{
      height:300px;
    }

    .box:hover {
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    #trending{
      list-style: none;
      padding:10px 5px 10px 15px;
    }
    #trending li {
      padding-left: 1.3em;
    }
    #trending li:before {
      content: "\f046";
      font-family: FontAwesome;
      display: inline-block;
      margin-left: -1.3em; 
      width: 1.3em;
    }

    Magnify
    .magnify > .magnify-lens {
      width: 100px;
      height: 100px;
    }
*/

    /*tes saja*/
    .login-page,.register-page {
		background-image: url(img/home.png);
		background-size: 100% 100%;
		background-attachment:fixed;
    }
/*
    .login-box-body,.register-box-body {
    background: #0d085859;
    padding: 20px;
    border-top: 0;
    color: #666;
    }
*/
/*
    .login-box,.register-box {
    color: #0a0a83
    width: 100px auto;
    float: right;
    }
*/
    .login-left, .register-left {
    	width: 100px auto;
    	padding: 30px;
    	margin-bottom: 0px;
    	float: left;
    }
    .static {
      	position: fixed;
      	bottom: 120px;
      	width: 600px;
      	border: 3px;
    }
    .judul1 {
     	font-size: 18px;
     	color: #294d76;
    }
    .judul2 {
      	font-size: 12px;
      	color: #bfcdec;
      	padding: 0;
    }
	.judul3 {
      	font-size: 12px;
      	color: #8c9ab9;
      	padding-top: 180px;
		font-style: italic;
    }
    .footer {
      	font-size: 18px;
      	color: #8c9ab9;
      	font-style: italic;
	  	position:relative;
	  	bottom:0px;
	  	text-align:center;
    }
    .pos_img {
    	width: 20%;
		padding: 6px;
		margin-bottom: 0;
    }
	#container {
		position: relative;
		min-height:100%;

	}
		 
	/*form login, signup, forgot pass*/
	.login-layout .widget-box{
		visibility: hidden;
		position: fixed;
		z-index: -5;
		border-bottom: none;
		box-shadow: 2px 2px 2px rgba(0,0,0,0.8);
		border-radius: 20px;
		padding: 6px;
		background-color: aliceblue;
		-moz-transform: scale(0,1) translate(-150px);
		-webkit-transform: scale(0,1) translate(-150px);
		-o-transform: scale(0,1) translate(-150px);
		-ms-transform: scale(0,1) translate(-150px);
		transform: scale(0,1) translate(-150px); 
	}
	.login-box .toolbar{
		background: white;
		border-top: 2px solid transparent!important; 
		border-bottom-left-radius: 20px; 
		border-bottom-right-radius: 20px; 
	}
	.signup-box .toolbar{
		background: white;
		border-top: 2px solid transparent!important;
		padding: 9px 18px; 
		border-bottom-left-radius: 20px; 
		border-bottom-right-radius: 20px;
	}
	.forgot-box .toolbar{
		background: white;
		border-top: 2px solid transparent!important;
		padding: 9px 18px;  
		border-bottom-left-radius: 20px; 
		border-bottom-right-radius: 20px;
	}

  .toolbar {
    border-top: 2px solid transparent!important;
    background: white!important;
  }
	
	.scroll {
		max-height: 360px;
		overflow: scroll;
		padding: 10px;
	}

  .isax {
    margin-top: 7px;
    margin-left: 3px;
    width: 22px;
    height: 17px;
  }

  .isax-eye {
    width: 22px;
    height: 17px;
    margin-top: 7px;
    margin-left: 89%;
  }

  @media (max-width: 368px){
    .isax-eye {
      margin-left: 85%;
    }
  }

  .label-reg{
    color: #9A9A9A;
    float: left;
    margin-top: 10px;
  }

  .label-reg a{
    margin-left: 2px;
  }

  .label-forgot{
    float: right;
    margin-top: 2px;
  }

  .label-forgot a{
    color: #B42929!important
  }

  .filter-gray {
    filter: invert(71%) sepia(2%) saturate(0%) hue-rotate(205deg) brightness(87%) contrast(87%);
  }

  .filter-blue {
    filter: invert(66%) sepia(41%) saturate(2571%) hue-rotate(176deg) brightness(103%) contrast(99%);
  }

  .btn-submit {
    background: #0F3E71!important;
  }

  .btn-submit:hover {
    background: #275280!important;
  }

  .btn-reset {
    background: #B42929!important;
  }

  .btn-reset:hover {
    background: #BF2C2C!important;
  }

 @font-face {
  font-family: 'Geometr415 Lt BT';
  src: url("assets/fonts/geometric.ttf") format('truetype');
}


.form-title {
  font-family: "Geometr415 Lt BT", sans-serif;
  font-style: normal;
  font-size: 32px;
  line-height: 35.2px;
  font-weight: 900;
  padding-bottom: 10px;

  /* Brand Color/Primary - 100 */

  color: #0F3E71;
}
  
  .input-reg{
    padding-left: 30px!important;
  }

  input:focus, input:hover {
    border: 2px solid #4CBCFE!important;

  }

  input[type=email]{
    text-transform: lowercase;
  }

  	</style>
     
</head>