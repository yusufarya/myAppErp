<?php
require_once '../../../config.inc.php';

if(!isset($_SESSION['email']))
{
	echo "<script language='javascript'>alert('Your session has expired, please log in again.');</script>";	
	echo '<script language="javascript">window.location = "../index.php"</script>';
	exit;
} 

$email = $_GET['code'];
$job_id = $_GET['code2'];

$qry = mysql_query("SELECT a.*, p.name AS position_name, j.remark,
					aj.join_date, aj.action_date, j.begin_date,
					j.end_date, aj.remark AS applicant_remark   	 
					FROM applicant a 
					LEFT JOIN applicant_job aj ON aj.user_id = a.user_id 
					LEFT JOIN job j ON j.id = aj.job_id 
					LEFT JOIN position p ON p.id = j.position_id 
					WHERE a.user_id = '$email' 
					AND aj.job_id = '$job_id'");
$row = mysql_fetch_array($qry);

$tgl_awal = date('d-m-Y', strtotime($row['begin_date']));
$tgl_akhir = date('d-m-Y', strtotime($row['end_date']));
$tgl_gabung = date('d-m-Y');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SISCOM CAREER</title>
    <link rel="icon" type="image/png" href="../../../img/SISCOM Logo.png" style="height:10px; width:auto">
    
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
                    <li class="nav-item active">
                        <a class="nav-link" href="<?=$app_config['base_url'].'/form/pages/job/index.php'?>">Job
                        	<span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$app_config['base_url'].'/form/pages/account/index.php'?>">My Account</a>
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
                <h1>Job Application</h1>
    
                <form role="form" action="qedit.php" method="post" id="frm" name="frm" onSubmit="return checkForm(this)" enctype="multipart/form-data">
                <input type="hidden" name="txtUser" id="txtUser" value="<?=$email?>"/>
                <div id="wrapper">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class='table table-hover'>
                                        <tr>
                                            <td style="width: 150px">Position</td>
                                            <td><label><?=$row['position_name']?></label></td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td>
                                                <label><?=$row['remark']?></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Period</td>
                                            <td>
                                                <label>From <?=$tgl_awal.' until '.$tgl_akhir?></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Remark</td>
                                            <td>
                                                <label><?=$row['applicant_remark']?></label>
                                            </td>
                                        </tr>  
                                        <tr>
                                            <td>Join Date</td>
                                            <td>
                                                <label><?=$tgl_gabung?></label>
                                            </td>
                                        </tr>       
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a href="index.php" target="_parent">
                                                <input type="button" name="Back" value="Back" class="btn btn-danger" />
                                                </a>
                                            </td>
                                            </form>
                                        </tr>
                                    </table>
                                </div>
                                <!-- .panel-body -->
                            </div>
                            <!-- /.panel-default -->
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->
                </div>
			</div>
      	</div>
        
        <!-- Custom theme JavaScript -->
        <script type="text/javascript" src="../../dist/js/sb-admin-2.js"></script>
        <script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui.js"></script>
        <script type="text/javascript" src="../../js/my.js"></script>
        <script type="text/javascript" src="../../../js/number.js"></script>
        <script type="text/javascript">
        var a = jQuery.noConflict(true)
        a(function() {
            a( "#txtTglGabung").datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });
        });
        </script>
        <script>
        function checkForm(f) {
            if (document.getElementById('txtTglGabung').value == '') {
                alert('Join Date must be filled !');
                document.getElementById('txtTglGabung').focus();
            } else if (confirm("Save data ?")) {
                return true;
                f.action = 'qedit.php';
                f.method = 'post';
            }
            return false;
        }
        </script>

	</div>
    <!-- /.container -->
    
    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>
