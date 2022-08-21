<?php
require_once '../../config.inc.php';
require_once $app_config['lib_dir'].'/dbconn2.php';

function ubahFormattgl($tanggal){
	$pisah = explode('-',$tanggal);
	$urutan = array($pisah[2],$pisah[1],$pisah[0]);
	$satukan = implode('-',$urutan);
	return $satukan;
}

$id = $_POST['txtUser'];

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
$last_education = $_POST['optPend'];
$major = $_POST['optPend'];
$lama = $_POST['txtLama'];
if($_POST['txtTglGabung'] != ''){
 	$tgl_gabung = ubahFormattgl($_POST['txtTglGabung']);
}else{
	$tgl_gabung = 'NULL';
}
$getDate = date('Y-m-d H:i:s');

$qry = "INSERT INTO applicant (user_id, name, nickname, gender, birth_place, birth_date, address, 
			mobile_phone, last_education, major, work_duration, join_date, photo_file, doc_file, created_on)  
			VALUES ('$id', '$nama_lengkap', '$nama_panggilan', '$jk', '$tempat_lahir', '$tgl_lahir', 
			'$alamat', '$no_hp', '$last_education', '$major', '$lama', '$tgl_gabung', '', '', '$getDate')";
$sql = mysql_query($qry);

if (isset($_POST['Jenis']))
{
	foreach ($_POST['Jenis'] as $selectedOption)
	{
		$line = $selectedOption;
		$qry2 = "INSERT INTO applicant_job (user_id, job_id)  
					VALUES ('$id', '$line')";
		$sql2 = mysql_query($qry2);
	}
}

$fileName = $_FILES['uploadImage']['name'];
$alamat = "../../img/photo/".$_FILES['uploadImage']['name'];

$dir = $app_config['doc_dir']."/cv";
$target = $app_config['doc_dir']."/cv/";
if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

if(!empty($_FILES['fleImage']['name']) || $_FILES['fleImage']['name'] != ""){
	$target1 = $target . basename( $_FILES['fleImage']['name']);
	if(move_uploaded_file($_FILES['fleImage']['tmp_name'], $target1))
	{	
		$doc = $_FILES['fleImage']['name'];
		$sqlFile = "UPDATE applicant
					SET doc_file = '$doc'
					WHERE user_id = '$id'";
		$resFile = mysql_query($sqlFile) or die (mysql_error());					
	}
}

if($sql2){
	move_uploaded_file($_FILES['uploadImage']['tmp_name'], $alamat);
	move_uploaded_file($_FILES['fleImage']['tmp_name'], $target1);
	echo "<script language='javascript'>alert('Data has been saved successfuly.');</script>";
	echo '<script language="javascript">window.location = "../index.php"</script>';	
}else{
	echo "<script language='javascript'>alert('Error on save.');</script>";
	echo '<script language="javascript">window.location = "../index.php"</script>';
}
exit;

?>