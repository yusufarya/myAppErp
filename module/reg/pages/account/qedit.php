<?php
require_once '../../../config.inc.php';
require_once $app_config['lib_dir'].'/dbconn2.php';

function ubahFormattgl($tanggal){
	$pisah = explode('-',$tanggal);
	$urutan = array($pisah[2],$pisah[1],$pisah[0]);
	$satukan = implode('-',$urutan);
	return $satukan;
}

$id = $_POST['txtID'];
$userid = $_POST['txtUser'];
$password = MD5(_VKEY.$userid.$_POST['txtPassword']);
$nama_lengkap = $_POST['txtName'];
$nama_panggilan = $_POST['txtNickname'];
if(isset($_POST['cbJK'])){
	$jk = $_POST['cbJK'];
}else{	
	$jk = '';
}
$tempat_lahir = $_POST['txtTmptLahir'];
if($_POST['txtTglLahir'] != ''){
 	$tgl_lahir = ubahFormattgl($_POST['txtTglLahir']);
}else{
	$tgl_lahir = 'NULL';
}
$no_hp = $_POST['txtNoHp'];
$alamat = $_POST['txtAlamat'];
$pend_akhir = $_POST['optPend'];
$jurusan = $_POST['txtJurusan'];
$lamaT = $_POST['txtLamaTahun'];
if($_POST['txtLamaTahun'] == ''){
	$lamaT = 0;
}
$lamaB = $_POST['txtLamaBulan'];
if($_POST['txtLamaBulan'] == ''){
	$lamaB = 0;
}
$getDate = date('Y-m-d H:i:s');
$fotoLama = $_POST['hFoto'];

$query = "SELECT * FROM applicant WHERE user_id = '$userid'";
$rs = mysql_fetch_array(mysql_query($query));
if(mysql_num_rows(mysql_query($query)) < 1){
	$qry = "INSERT INTO applicant (user_id, password, name, nickname, gender, birth_place, birth_date, address, 
			mobile_phone, last_education, major, year_exp, month_exp, photo_file, doc_file, created_on)  
			VALUES ('$userid', '$password', '$nama_lengkap', '$nama_panggilan', '$jk', '$tempat_lahir', '$tgl_lahir', 
			'$alamat', '$no_hp', '$pend_akhir', '$jurusan', '$lamaT', $lamaB, '', '', '$getDate')";
} else {
	if ($_POST['txtPassword'] == '') {
		$qry = "UPDATE applicant 
				SET name = '$nama_lengkap',
				nickname = '$nama_panggilan',
				gender = '$jk', birth_place = '$tempat_lahir',
				birth_date = '$tgl_lahir', address = '$alamat', 
				mobile_phone = '$no_hp', last_education = '$pend_akhir',
				major = '$jurusan', year_exp = '$lamaT', month_exp = '$lamaB', 
				modified_on = '$getDate' 
				WHERE user_id = '$userid'";
	} else if ($_POST['txtPassword'] != '') {
		$qry = "UPDATE applicant 
				SET password = '$password', 
				name = '$nama_lengkap',
				nickname = '$nama_panggilan',
				gender = '$jk', birth_place = '$tempat_lahir',
				birth_date = '$tgl_lahir', address = '$alamat', 
				mobile_phone = '$no_hp', last_education = '$pend_akhir',
				major = '$jurusan', year_exp = '$lamaT', month_exp = '$lamaB',
				modified_on = '$getDate' 
				WHERE user_id = '$userid'";
	}
}
$sql = mysql_query($qry);

$fileName = '';
$image = '';
$input = '';

//$dirFoto = $app_config['base_dir']."/img/photo";
//$targetFoto = $app_config['base_dir']."/img/photo/";
$dirFoto = $app_config['doc_dir']."/cv/".$id;
$targetFoto = $app_config['doc_dir']."/cv/".$id."/";
if (!file_exists($dirFoto)) {
	mkdir($dirFoto, 0777, true);
}

$alamatFoto = $targetFoto.$fileName;
$alamatFotoDel = $targetFoto.$fotoLama;

if(!empty($_FILES['uploadImage']['name']) || $_FILES['uploadImage']['name'] != '')
{
	$targetFoto1 = $targetFoto . basename( $_FILES['uploadImage']['name']);
	if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $targetFoto1))
	{	
		$fileName = $_FILES['uploadImage']['name'];
		unlink($alamatFotoDel);	
	}
}

if($fileName != '')
{	
	$sqlImg = "UPDATE applicant
				SET photo_file = '$fileName' 
				WHERE user_id = '$userid'";			
	$resImg = mysql_query($sqlImg) or die (mysql_error());
}

//$dirDoc = $app_config['doc_dir']."/cv";
//$targetDoc = $app_config['doc_dir']."/cv/";
$dirDoc = $app_config['doc_dir']."/cv/".$id;
$targetDoc = $app_config['doc_dir']."/cv/".$id."/";
if (!file_exists($dirDoc)) {
	mkdir($dirDoc, 0777, true);
}

$input = $_POST['txtInput'];
$inputLama = $_POST['txtInputH'];

//$alamatDoc = "../../../document/cv/".$image;
//$alamatDocDel = "../../../document/cv/".$inputLama;
$alamatDoc = "../../../document/cv/".$id."/".$image;
$alamatDocDel = "../../../document/cv/".$id."/".$inputLama;

if(!empty($_FILES['fleImage']['name']) || $_FILES['fleImage']['name'] != '')
{
	$targetDoc1 = $targetDoc . basename( $_FILES['fleImage']['name']);
	if(move_uploaded_file($_FILES['fleImage']['tmp_name'], $targetDoc1))
	{	
		$image = $_FILES['fleImage']['name'];		
		unlink($alamatDocDel);			
	}
}
else
{	
	$targetDoc1 = $targetDoc . basename($input);
	if(move_uploaded_file($input, $targetDoc1))
	{	
		$image = $input;					
	}
}

if($image != '')
{	
	$sqlFile = "UPDATE applicant
				SET doc_file = '$image'
				WHERE user_id = '$userid'";			
	$resFile = mysql_query($sqlFile) or die (mysql_error());
}

if($sql){
	echo "<script language='javascript'>alert('Data has been saved successfuly.');</script>";
	echo '<script language="javascript">window.location = "../index.php"</script>';	
}else{
	echo "<script language='javascript'>alert('Error on save.');</script>";
	echo '<script language="javascript">window.location = "../index.php"</script>';
}
exit;

?>