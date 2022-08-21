<?php
require_once '../../../config.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SISCOM CAREER</title>
    <link rel="icon" type="image/png" href="../../img/SISCOM Logo.png" style="height:10px; width:auto">
    
    <link href="../../dist/css/jquery-ui.css" rel="stylesheet">
    
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/logo-nav.css" rel="stylesheet">
    
</head>

<body>
	
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">SISCOM
                    <img src="" alt="">
                </a>
            </div>
            
            <ul class="nav navbar-top-links navbar-right">
                <li class="nav-item">
                    <a href="<?=$app_config['base_url'].'/form/pages/qout.php'?>" target="_parent">
                      	Logout
                    </a>
                </li>
            </ul>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$app_config['base_url'].'/form/pages/index.php'?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$app_config['base_url'].'/form/pages/job/index.php'?>">Job</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?=$app_config['base_url'].'/form/pages/account/index.php'?>">My Account
                        	<span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                    	<a>ID: <?=$_SESSION['email']?></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
   	
    <!-- Page Content -->
    <div class="container">
      	<div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12">
                <h1>My Account</h1>
        		<?php include 'edit.php'; ?>
        	</div>
       	</div>
    </div>
    <!-- /.container -->
    
    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>