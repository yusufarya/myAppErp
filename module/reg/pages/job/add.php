<?php
require_once '../../../config.inc.php';

if(!isset($_SESSION['email']))
{
	echo "<script language='javascript'>alert('Your session has expired, please log in again.');</script>";	
	echo '<script language="javascript">window.location = "../index.php"</script>';
	exit;
} 

$email = $_SESSION['email'];
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
                <a class="navbar-brand" href="#">
                    <img src="http://placehold.it/150x50&text=Logo" alt="">
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
    
                <form role="form" action="qadd.php" method="post" id="frm" name="frm" onSubmit="return checkForm(this)" enctype="multipart/form-data">
                <input type="hidden" name="txtUser" id="txtUser" value="<?=$email?>"/>
                <div id="wrapper">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class='table table-hover'>
                                        <tr>
                                            <td>Email Address</td>
                                            <td colspan="2"><input type='text' name="txtMail" id="txtMail" class="form-control" readonly value="<?=$email?>"/></td>
                                        </tr>
                                        <tr>
                                            <td>Job Position</td>
                                            <td>
                                                <select name="Jenis[]" id="Jenis[]" multiple="multiple" class="form-control">
                                                <?php
                                                    $sqlJenis = "SELECT DISTINCT j.id, p.name AS position_name 
                                                                    FROM job j 
                                                                    LEFT JOIN position p ON p.id = j.position_id 
                                                                    WHERE j.stsview = 'P' 
                                                                    AND j.stsrec = 'A' 
                                                                    AND j.begin_date <= DATE(NOW()) 
                                                                    AND j.end_date >= DATE(NOW()) 
                                                                    ORDER BY p.name";
                                                    $resJenis = mysql_query($sqlJenis) or die (mysql_error());
                                                    while($rowJenis = mysql_fetch_array($resJenis)){
                                                ?>
                                                        <option value="<?php echo $rowJenis['id']?>"><?php echo $rowJenis['position_name'] ?></option>
                                                <?php } ?>
                                                </select>
                                            </td>
                                            <td style="font-size:12px; font-style:italic">*Pilihan boleh lebih dari 1</td>
                                        </tr>    
                                        <tr>
                                            <td>Join Date</td>
                                            <td>
                                                <input type='text' name="txtTglGabung" id="txtTglGabung" class="form-control" placeholder="dd-mm-yyyy" maxlength="10" width="200px" value="<?=$tgl_gabung?>" autocomplete="off"/>
                                            </td>
                                            <td></td>
                                        </tr>          
                                    </table>
                                    <tr>
                                        <td></td>
                                        <td>
                                        	<input type="submit" name="Save" value="Submit" class="btn btn-primary">
                                            <a href="index.php" target="_parent">
                                            <input type="button" name="Cancel" value="Cancel" class="btn btn-danger"/>
                                            </a>
                                        </td>
                                        </form>
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
                f.action = 'qadd.php';
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
