<?php
require_once '../../../config.inc.php';

$email = $_SESSION['email'];
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
	
    <!-- Bootstrap Core CSS -->
   	 <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    
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
                <!--<p>Note: You may need to adjust some CSS based on the size of your logo. The default logo size is 150x50 pixels.</p>-->
               <!-- <div class="row">
                    <div class="col-sm-12 col-xs-12 col-md-12">
                        <div class="form-group" style="float: right">
                            <img src="../../../img/data_add.png" alt="Apply" title="Apply" style="padding-right:30px;cursor:pointer;cursor:hand" style="background-color:#FFF" onClick="doAdd()"></a>
                        </div>
                    </div>
                </div>-->
        
                <div class="row">
                    <div class="col-sm-12 col-xs-12 col-md-12">                  
                      	<div class="panel panel-primary">
                            <div class="panel-heading">
                                My Job Application List
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <form action="" method="get" name="frmSearch" id="frmSearch">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="width:200px">Position</th>
                                                <th>Description</th>
                                                <th style="width:100px; text-align:center">Join Date</th>
                                                <th style="width:100px; text-align:center">Action Date</th>
                                                <th style="width:60px; text-align:center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $id = 1;
                                            $strQry = "SELECT TBL.job_name, TBL.remark, 
                                                        TBL.join_date, TBL.action_date, 
														TBL.created_on, MAX(TBL.status) AS status, 
                                                        TBL.job_id 
														FROM ( 
														SELECT p.name AS job_name, j.remark, 
                                                        aj.join_date, aj.action_date, aj.status, 
                                                        aj.job_id, aj.created_on 
                                                        FROM applicant a 
                                                        LEFT JOIN applicant_job aj ON aj.user_id = a.user_id 
                                                        LEFT JOIN job j ON j.id = aj.job_id 
                                                        LEFT JOIN position p ON p.id = j.position_id 
                                                        WHERE a.user_id = '$email') TBL 
														GROUP BY TBL.job_name, TBL.remark, 
                                                        TBL.join_date, TBL.job_id 
                                            			ORDER BY TBL.created_on DESC";
                                            $query = mysql_query($strQry);
                                            while ($rs = mysql_fetch_array($query)){ 
                                            
                                            if($rs['join_date'] == '' || $rs['join_date'] == '0000-00-00') {
                                                $tgl_gabung = '';
                                            } else {
                                                $tgl_gabung = date("d/m/Y",strtotime($rs['join_date']));
                                            }
											
											if($rs['action_date'] == '' || $rs['action_date'] == '0000-00-00') {
                                                $tgl_status = '';
                                            } else {
                                                $tgl_status = date("d/m/Y",strtotime($rs['action_date']));
                                            }
                                            
                                            if($rs['created_on'] == '' || $rs['created_on'] == '0000-00-00') {
                                                $tgl_input = '';
                                            } else {
                                                $tgl_input = date("d/m/Y",strtotime($rs['created_on']));
                                            }
                                            
                                            if($rs['status'] == 0) {
                                                $status = 'New';
                                                $color = '#000';
                                                $bgcolor = 'transparant';
                                            } else if($rs['status'] == 1) {
                                                $status = 'Process';
                                                $color = '#000';
                                                $bgcolor = '#ebc200';
                                            } else if($rs['status'] == 2) {
                                                $status = 'Interview';
                                                $color = '#fff';
                                                $bgcolor = '#497d2b';
                                            } else if($rs['status'] == 3) {
                                                $status = 'Decline';
                                                $color = '#fff';
                                                $bgcolor = '#d03153';
                                            }
                                            ?>
                                            <tr>
                                                <td style="padding-left:10px; color:<?=$color?>; background-color:<?=$bgcolor?>"><?=$rs['job_name']?></td>
                                                <td style="padding-left:10px"><?=$rs['remark']?></td>
                                                <td align="center"><?=$tgl_gabung?></td>
                                                <td align="center"><?=$tgl_status?></td>
                                                <td class="center" style="text-align: center">
                                                    <img src="../../../img/find-icon.png" width="auto" height="24" alt="View" title="View" style="cursor:pointer;cursor:hand" style="background-color:#FFF" onClick="doEdit('<?=$rs['user_id']?>','<?=$rs['job_id']?>')"></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    </form>
                                </div>
                                <div style="border: none;">
                                    <div style="float: left; width: 150px;">
                                        <div style="margin: 5px 0 0 0;">
                                            <div style="float: left; width: 60px;">
                                                <div style="width: 50px; height: 18px; background: url('../../../img/bg_white.gif'); border: 1px solid #DDD;"></div>
                                            </div>
                                            <div>
                                                New
                                            </div>
                                        </div>
                                     </div>
                                     <div style="float: left; width: 150px;">
                                        <div style="margin: 5px 0 0 0;">
                                            <div style="float: left; width: 60px;">
                                                <div style="width: 50px; height: 18px; background: url('../../../img/bg_yellow.gif'); border: 1px solid #DDD;"></div>
                                            </div>
                                            <div>
                                                Process
                                            </div>
                                        </div>
                                     </div>
                                     <div style="float: left; width: 150px;">
                                        <div style="margin: 5px 0 0 0;">
                                            <div style="float: left; width: 60px;">
                                                <div style="width: 50px; height: 18px; background: url('../../../img/bg_green.gif'); border: 1px solid #DDD;"></div>
                                            </div>
                                            <div>
                                                Interview
                                            </div>
                                        </div>
                                     </div>
                                     <div style="float: left; width: 150px;">
                                        <div style="margin: 5px 0 0 0;">
                                            <div style="float: left; width: 60px;">
                                                <div style="width: 50px; height: 18px; background: url('../../../img/bg_red.gif'); border: 1px solid #DDD;"></div>
                                            </div>
                                            <div>
                                                Decline
                                            </div>
                                         </div>
                                    </div>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
          	</div>
    	</div>
    </div>
    <!-- /.container -->
	
    <!-- jQuery -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>
    <!-- DataTables JavaScript -->
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                "aaSorting":[[4, "desc"]],
				responsive: true
        });
    });
	
	function doAdd(){
		location.href = "add.php";
	}
	
	function doEdit(code,code2){
		location.href = "edit.php?code="+code+"&code2="+code2;
	}
    </script>
    
    <!-- jQuery -->
    <script src="../../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../js/bootstrap.min.js"></script>

</body>

</html>