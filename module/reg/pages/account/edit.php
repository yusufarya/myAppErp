<?php
if(!isset($_SESSION['email']))
{
	echo "<script language='javascript'>alert('Your session has expired, please log in again.');</script>";	
	echo '<script language="javascript">window.location = "../../index.php"</script>';
	exit;
} 

$email = $_SESSION['email'];

$qry = mysql_query("SELECT a.* 
					FROM applicant a 
					WHERE a.user_id = '$email'");
$row = mysql_fetch_array($qry);

$cb1 = '';
$cb2 = '';
$p1 = '';
$p2 = '';
$p3 = '';
$p4 = '';
$p5 = '';
$imgfile = '';
//$dir = "../../../img/photo/";
$dir = "../../../document/cv/".$row['id']."/";

if ($row['user_id'] == '') {

	$user_id_exist = '';
	$tgl_lahir = date('d-m-Y');
	$cb1 = 'checked';
	$foto = 'default.jpg';

} else {
	
	$user_id = $row['user_id'];
	$user_id_exist = $row['user_id'];
	
	if($row['gender'] == 'L'){
		$cb1 = 'checked';
	} else if($row['gender'] == 'P'){
		$cb2 = 'checked';
	} 
	if($row['last_education'] == 'SLTA'){
		$p1 = 'selected';
	} else if($row['last_education'] == 'D3'){
		$p2 = 'selected';
	} else if($row['last_education'] == 'S1'){
		$p3 = 'selected';
	} else if($row['last_education'] == 'S2'){
		$p4 = 'selected';
	} else if($row['last_education'] == 'S3'){
		$p5 = 'selected';
	}
	if($row['birth_date'] != '' or $row['birth_date'] != '0000-00-00'){
		$tgl_lahir = date('d-m-Y', strtotime($row['birth_date']));
	}else{
		$tgl_lahir = date('d-m-Y');
	}
	
	$ext = pathinfo($row['doc_file'], PATHINFO_EXTENSION);
	if($ext == 'doc') {
		$imgfile = 'doc.png';
	} else if($ext == 'docx') {
		$imgfile = 'docx.png';
	} else if($ext == 'gif') {
		$imgfile = 'gif.png';
	} else if($ext == 'jpg') {
		$imgfile = 'jpg.png';
	} else if($ext == 'pdf') {
		$imgfile = 'pdf.png';
	} else if($ext == 'png') {
		$imgfile = 'png.gif';
	} else if($ext == 'ppt') {
		$imgfile = 'ppt.png';
	} else if($ext == 'pptx') {
		$imgfile = 'pptx.png';
	} else if($ext == 'rar') {
		$imgfile = 'rar.jpg';
	} else if($ext == 'txt') {
		$imgfile = 'doc.png';
	} else if($ext == 'xls') {
		$imgfile = 'xls.png';
	} else if($ext == 'xlsx') {
		$imgfile = 'xlsx.png';
	} else if($ext == 'zip') {
		$imgfile = 'zip.jpg';
	} else {
		$imgfile = 'doc.jpg';
	}
	$imgsrc = "../../../img/icons/".$imgfile;
	//$pathDoc = $app_config['doc_dir']."/cv/";
	$pathDoc = $app_config['doc_dir']."/cv/".$row['id']."/";
	
	if($row['photo_file'] == '') {
		$foto = 'default.jpg';
	} else {
		$foto = $row['photo_file'];
	}
}
?>

<form role="form" action="qedit.php" method="post" id="frm" name="frm" onSubmit="return checkForm(this)" enctype="multipart/form-data">
<input type="hidden" name="txtUser" id="txtUser" value="<?=$email?>"/>
<input type="hidden" name="txtID" id="txtID" value="<?=$row['id']?>"/>
<div id="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-top:2px"></h1>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class='table table-hover'>
                                <tr>
                                    <td>Email Address *</td>
                                    <td colspan="4">
                                    <input type='text' name="txtMail" id="txtMail" class="form-control" readonly value="<?=$email?>"/>
                                    <input type='hidden' name="txtMailH" id="txtMailH" value="<?=$user_id_exist?>">
                                    </td>
                                </tr>
                                <tr>
                                	<td>Password</td>
                                    <td colspan="2"><input class="form-control" id="txtPassword" name="txtPassword" type="password" placeholder="Password" maxlength="10"></td>
                                	<td colspan="2">* Filled this field if you want to change the password</td>
                                </tr>
                                <tr>
                                	<td>Password Confirmation</td>
                                    <td colspan="2"><input class="form-control" id="txtKonfirmasi" name="txtKonfirmasi" type="password" placeholder="Password Confirmation" maxlength="10"></td>
                                	<td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td>Full Name *</td>
                                    <td colspan="4"><input type='text' name="txtName" id="txtName" class="form-control" placeholder="Full Name" onKeyPress="return isNumberAlphaKey(event)" onKeyUp="ChangeCase(this)" value="<?=$row['name']?>" autocomplete="off"/></td>
                                </tr>
                                <tr>
                                    <td>Nickname</td>
                                    <td colspan="2"><input type='text' name="txtNickname" id="txtNickname" class="form-control" placeholder="Nickname" onKeyPress="return isNumberAlphaKey(event)" onKeyUp="ChangeCase(this)" value="<?=$row['nickname']?>" autocomplete="off"/></td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td colspan="2">
                                        <input type='radio' name="cbJK" id="cbJK" value="L" <?=$cb1?> /> Laki-laki 
                                        <input type='radio' name="cbJK" id="cbJK" value="P" <?=$cb2?> /> Perempuan
                                    </td>
                                </tr>
                                <tr>
                                    <td>Birth Place / Date *</td>
                                    <td colspan="2"><input type='text' name="txtTmptLahir" id="txtTmptLahir" class="form-control" placeholder="Birth Place" onKeyPress="return isNumberAlphaKey(event)" onKeyUp="ChangeCase(this)" value="<?=$row['birth_place']?>" autocomplete="off" /></td>
                                    <td colspan="2"><input type='text' name="txtTglLahir" id="txtTglLahir" class="form-control" placeholder="dd-mm-yyyy" maxlength="10" value="<?=$tgl_lahir?>" autocomplete="off" /></td>
                                </tr>
                                <tr>
                                    <td>Mobile Number *</td>
                                    <td colspan="2"><input type="text" name="txtNoHp" id="txtNoHp" class="form-control" onKeyPress="return isNumberKey(event)" placeholder="Mobile Number" value="<?=$row['mobile_phone']?>" autocomplete="off"/></td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td>Current Address *</td>
                                    <td colspan="4"><textarea name="txtAlamat" id="txtAlamat" class="form-control" cols="10" rows="3" placeholder="Current Address" onKeyPress="return isNumberAlphaKey(event)" onKeyUp="ChangeCase(this)"><?=$row['address']?></textarea></td>
                                </tr>
                                <tr>
                                    <td>Last Education - Majors</td>
                                    <td colspan="2">
                                        <select name="optPend" id="optPend" class="form-control">
                                            <option value="SLTA" <?=$p1?>>SLTA (Sederajat)</option>
                                            <option value="D3" <?=$p2?>>D3</option>
                                            <option value="S1" <?=$p3?>>S1</option>
                                            <option value="S2" <?=$p4?>>S2</option>
                                            <option value="S3" <?=$p5?>>S3</option>
                                        </select>
                                    </td>
                                    <td colspan="2"><input type='text' name="txtJurusan" id="txtJurusan" class="form-control" placeholder="Majors" onKeyPress="return isNumberAlphaKey(event)" onKeyUp="ChangeCase(this)" value="<?=$row['major']?>" autocomplete="off" /></td>
                                </tr>
                                <tr>
                                    <td>Working Experience</td>
                                    <td><input type="text" name="txtLamaTahun" id="txtLamaTahun" class="form-control" onKeyPress="return isNumberKey(event)" placeholder="0" value="<?=$row['year_exp']?>" autocomplete="off"/></td>
                                    <td>Years</td>
                                    <td><input type="text" name="txtLamaBulan" id="txtLamaBulan" class="form-control" onKeyPress="return isNumberKey(event)" placeholder="0" value="<?=$row['month_exp']?>" autocomplete="off"/></td>
                                    <td>Months</td>
                                </tr>
                                <tr>
                                    <td>Foto</td>
                                    <td colspan="2">
                                        <img id="uploadPreview" style="width: 150px; height: 150px;" src="<?=$dir.$foto?>"/><br>
                                        <input type="hidden" name="hFoto" id="hFoto" value="<?=$foto?>">
                                        <input type="file" name="uploadImage" id="uploadImage" onChange="PreviewImage();"> 	
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 5px">CV (prefer in PDF format)</td>
                                    <td colspan="2">
                                    	<?php if($row['doc_file'] != '') { ?>
                                        <a href="../../../inc/download.php?path=<?=$pathDoc?>&name=<?=$row['doc_file']?>"><img src="<?=$imgsrc?>" width="auto" height="16" alt="Download" title="Download" style="cursor:pointer;cursor:hand" /></a>&nbsp;
                                        <?php
										} else {
										?>
										<font color="#FF0000">No Attachment</font>
										<?php
											}
										?>
                                        <input type="text" name="txtInput" id="txtInput" style="vertical-align:top; border:none; background-color:transparent" value="<?php echo $row['doc_file'];?>" onBlur="getImage(this.id)" onClick="getImage(this.id)" disabled />
                                        <input type="hidden" name="txtInputH" id="txtInputH" style="vertical-align:top" value="<?php echo $row['doc_file'];?>" />
                                        <script>
                                            $('#txtInput').click(function() {
                                                $('#fleImage').click();
                                            });
                                        </script>
                                        <input name="fleImage" id="fleImage" type="file" />
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                	<td></td>
                                    <td>                        
                                        <input type="submit" name="Save" value="Submit" class="btn btn-primary">
                                        <a href="../index.php" target="_parent">
                                        <input type="button" name="Cancel" value="Cancel" class="btn btn-danger"/>
                                        </a>
                                    </td>
                                </tr>
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
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
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
	a( "#txtTglLahir").datepicker({
		dateFormat: 'dd-mm-yy',
		changeMonth: true,
		changeYear: true
	});
});
</script>
<script type="text/javascript">
	function PreviewImage() {
	var oFReader = new FileReader();
	oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);
	oFReader.onload = function (oFREvent)
	 {
		document.getElementById("uploadPreview").src = oFREvent.target.result;
	};
	};
</script>
<script>
function getImage(id) {
	if(id == "txtInput")
	{
		var txt = document.getElementById("txtInput");
		var fle = document.getElementById("fleImage");
		
		txt.value = fle.value;
	}
}
</script>
<script>
function checkForm(f) {
	if (document.getElementById("txtMailH").value == '' && document.getElementById("txtPassword").value == '') {
		alert('Password must be filled !');
		document.getElementById("txtPassword").select();
	} else if (document.getElementById("txtMailH").value == '' && document.getElementById("txtKonfirmasi").value == '') {
		alert('Password Confirmation must be filled !');
		document.getElementById("txtKonfirmasi").select();
	} else if (document.getElementById("txtPassword").value != document.getElementById("txtKonfirmasi").value) {
		alert('Password Confirmation is not same with Password !');
		document.getElementById("txtKonfirmasi").select();
	} else if (document.getElementById('txtName').value == '') {
		alert('Full Name must be filled !');
		document.getElementById('txtName').focus();
	} else if (document.getElementById('txtTmptLahir').value == '') {
		alert('Birth Place must be filled !');
		document.getElementById('txtTmptLahir').focus();
	} else if (document.getElementById('txtTglLahir').value == '') {
		alert('Birth Date must be filled !');
		document.getElementById('txtTglLahir').focus();
	} else if (document.getElementById('txtNoHp').value == '') {
		alert('Mobile Number must be filled !');
		document.getElementById('txtNoHp').focus();
	} else if (document.getElementById('txtAlamat').value == '') {
		alert('Current Address must be filled !');
		document.getElementById('txtAlamat').focus();
	} else if (confirm("Save data ?")) {
		return true;
		f.action = 'qedit.php';
		f.method = 'post';
	}
	return false;
}
</script>
