<?php 
session_start();
require_once '../includes/koneksi2.php';
include '../includes/encrypt_decrypt.php';

/*$key = '5U7V9w19a21Tya15';
$GLOBALS['ENCRKEY'] = $key;

function encrypt($key, $payload)
{
	$IV_SIZE = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$iv = mcrypt_create_iv($IV_SIZE, MCRYPT_DEV_URANDOM);
	$crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $payload, MCRYPT_MODE_CBC, $iv);
	$combo = $iv . $crypt;
	$garble = base64_encode($iv . $crypt);
	return $garble;
}

function decrypt($key, $garble)
{
	$IV_SIZE = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$combo = base64_decode($garble);
	$iv = substr($combo, 0, $IV_SIZE);
	$crypt = substr($combo, $IV_SIZE, strlen($combo));
	$payload = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $crypt, MCRYPT_MODE_CBC, $iv);
	return $payload;
}*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/src/Exception.php';
require '../vendor/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/src/SMTP.php';

// print_r($_POST);

if (isset($_POST['action'])) {
	
	if ($_POST['action'] == 'tampil'){
		$dbname = $_POST['dbname'];
		$custID = $_POST['custID'];
    	$scJoin = mysql_query("SELECT cs.dbname, cs.custom, cs.db_date, cp.name AS companyname, cp.phone_no, cp.wa_no, cp.npwp_no,
			cp.address, cp.city, cp.zip_code, cp.prov_code, cp.country_code, cp.id AS companyid, cs.end_date, cs.expired_date, 
			c.email, cs.ver_id, v.name AS versi, c.ctype as level_user, c.billing_send, b.id AS business_id, cs.acc_period_begin, 
			cs.acc_period_end, cs.begin_date, cs.salesman_id, cs.salesman_remark, s.type as sales_type, s.name AS nama_sales, 
			s.parent_id AS parent_id, cs.ctype, cs.used   
			FROM cust_order cs 
			LEFT JOIN version v ON cs.ver_id = v.id 
			LEFT JOIN company cp ON cs.company_id = cp.id 
			LEFT JOIN cust c on c.id = cs.cust_id 
			LEFT JOIN business b on b.id = cp.business_id 
			LEFT JOIN salesman s on s.id = cs.salesman_id 
			WHERE cs.dbname = '$dbname' AND cs.stsrec = 'A' 
			ORDER BY c.ctype, c.email") or die(mysql_error());	
    	while ($rowj = mysql_fetch_array($scJoin)) {
    		$data[] = $rowj;
    		$end_date = $rowj['end_date'];
    		$expired_date = $rowj['expired_date'];
    		$tgl_db = $rowj['db_date'];
    		$sales_type = $rowj['sales_type'];
    		$sales_code = ($sales_type <= 3) ? $rowj['salesman_id'] : '';
    		$sales_name = ($sales_type <= 3) ? $rowj['nama_sales'] : '';
    	}
    	$db_date = date('d-m-Y',strtotime($tgl_db));
    	$now = strtotime(date('Y-m-d'));
    	$status_end = ($now > strtotime($end_date)) ? 'tenggang' : 'trial';

    	$end_date = date('d-m-Y', strtotime($end_date));
    	$expired_date = date('d-m-Y', strtotime($expired_date));
		$result = array('status' => 'successTampil', 'data'=> $data, 'used'=>$status_end, 'db_date'=>$db_date, 'end_date'=>$end_date, 'expired_date'=>$expired_date, 'sales_code' => $sales_code, 'sales_name' => $sales_name);
	} 

	else if($_POST['action'] == 'checkTrial') {
		$cust_id = $_POST['cust_id'];
		
		$sql = mysql_query("SELECT co.order_id, co.cust_id, c.email, c.name, c.phone, co.dbname 
							FROM `cust` c 
							LEFT JOIN cust_order co on co.cust_id = c.id
							WHERE cust_id = '".$cust_id."'
							ORDER BY order_id DESC") or die(mysql_error());
		$data = mysql_fetch_array($sql);
		// var_dump($data);
		$cust_id = $data['cust_id'];
		$email = $data['email'];

		if (count($data) > 0) {
			$result = array('status'=>'success', 'data'=> $data);
		}
		else {
			$result = array('status'=>'failed');
		}

		echo json_encode($result);

		$content = "http://$_SERVER[SERVER_NAME]/yusuf/siserp/module";

		$url = $content."/reg/pages/template_confirm_trial.php?cust_id=".$cust_id."&email=".$email;
		// var_dump($url);

		function getUrlContent($url) {
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_HEADER, false);
		    // execute and return string (this should be an empty string '')
		    $data = curl_exec($curl);
		    curl_close($curl);
		    // var_dump($data);
		    return $data;
		}

		$html = getUrlContent($url);

		$json = json_encode($html);
		$pesan = json_decode($json, TRUE);

	    $subject = 'Trial SISCOM Online';
	    $username = 'no-reply@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = "siscomnoplayoke515"; // decrypt($ENCRKEY, $password);;
		sendEmail('yusufaryadilla29@gmail.com', $pesan, $subject, $username, $passwordDecrypt, $name);
	}

	else if($_POST['action'] == 'tampilcabang'){
		$dbname = $_POST['dbname'];
		$custID = $_POST['custID'];
		$versi = $_POST['versi'];
		$order_id = $_POST['order_id'];
		$now = date('Y-m-d');

		$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());

		$sql = mysqli_query($koneksi4, "SELECT NAMA from SYSDATA WHERE KODE = '033'") or die(mysqli_error($koneksi4));
		$count = mysqli_num_rows($sql);

		while ($rowc = mysqli_fetch_array($sql)) {
			$data['maxCabang'] = $rowc['NAMA'];
		}

		$sqlG = mysqli_query($koneksi4, "SELECT CB.KODE AS KODECB, CB.NAMA AS NAMACB, G.KODE AS KODEGD, G.NAMA AS NAMAGD FROM CABANG CB LEFT JOIN GUDANG G ON G.CB = CB.KODE WHERE CB.AKTIF = 'Y' GROUP BY CB.KODE") or die(mysqli_error($koneksi4));
		$countG = mysqli_num_rows($sqlG);

		$i = 0;
		while ($rowG = mysqli_fetch_array($sqlG)) {
			$data['cabangInfo'][$i]['kodecb'] = $rowG['KODECB'];
			$data['cabangInfo'][$i]['namacb'] = $rowG['NAMACB'];
			$data['cabangInfo'][$i]['kodegd'] = $rowG['KODEGD'];
			$data['cabangInfo'][$i]['namagd'] = $rowG['NAMAGD'];

			$i++;
		}

		$sqlH = mysql_query("SELECT pr.price FROM package_price pr JOIN package p on pr.package_id = p.id
				WHERE pr.begin_date <= '$now' AND p.package_type = '5' AND p.ver_id = '$versi' ORDER BY pr.begin_date DESC LIMIT 1") or die(mysql_error());
		// $countH = mysql_num_rows($sqlH);

		while ($rowH = mysql_fetch_array($sqlH)) {
			$data['price'] = $rowH['price'];
		}

		// query get cabang yg belum dibayar
		//$sqlCab = mysql_query("SELECT ifnull(sum(invd.add_branch),0) as pending_branch from invoice_detail invd join invoice inv on inv.id = invd.inv_id where invd.order_id = '$order_id' and inv.stsrec = 'A'")or die(mysql_error());
		$sqlCab = mysql_query("SELECT IFNULL(SUM(invd.add_branch),0) AS pending_branch FROM invoice_detail invd JOIN invoice inv ON inv.id = invd.inv_id JOIN cust_order co ON co.order_id = invd.order_id WHERE co.dbname = '$dbname' AND inv.stsrec = 'A' AND inv.paid_off <> 'Y'") or die (mysql_error());

		while ($rowCab = mysql_fetch_array($sqlCab)) {
			$data['pending_branch'] = $rowCab['pending_branch'];
		}

		if($count > 0){
			$result = array("status" => "success", "data"=>$data);
		}else{
			$result = array("status" => "failed");
		}
	}
	
	else if ($_POST['action'] == 'log'){		
		$dbname = $_POST['dbname'];
		$custid = $_POST['custid'];
		$versi = $_POST['versi'];
		$now = date('Y-m-d H:i:s');
		
		$update = mysql_query("UPDATE cust_order SET last_on = '$now' WHERE cust_id = '$custid' AND dbname = '$dbname' AND ver_id = '$versi'") or die(mysql_error());
		$result = array('status' => 'successlog');	
	}

	else if ($_POST['action'] == 'infoDB'){		
		$dbname = $_POST['dbname'];
		$qry = "SELECT TABLE_SCHEMA AS dbname FROM information_schema.TABLES 
				WHERE TABLE_SCHEMA LIKE '".$dbname."_acc%' 
				GROUP BY TABLE_SCHEMA ORDER BY TABLE_SCHEMA";
		$query = mysql_query($qry) or die(mysql_error());
		$pdata = Array();
		while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		    $pdata[] =  $row['dbname'];  
		}
		
		$x = count($pdata) - 1;
		$y = count($pdata) - $x;

		$pAwal = $pdata[$y];
		$pAkhir = $pdata[$x];
		$length = strlen($pAwal) - 4;
		$yAwal = '20'.substr($pAwal, $length, 2);
		$mAwal = substr($pAwal, $length+2, 2);
		$yAkhir = '20'.substr($pAkhir, $length, 2);
		$mAkhir = substr($pAkhir, $length+2, 2);

		$bAwal = '';
		$bAkhir = '';
		switch($mAwal) {
			case '01': 
				$bAwal = 'Jan';
				break;
			case '02': 
				$bAwal = 'Feb';
				break;
			case '03': 
				$bAwal = 'Mar';
				break;
			case '04': 
				$bAwal = 'Apr';
				break;
			case '05': 
				$bAwal = 'Mei';
				break;
			case '06': 
				$bAwal = 'Jun';
				break;
			case '07': 
				$bAwal = 'Jul';
				break;
			case '08': 
				$bAwal = 'Agt';
				break;
			case '09': 
				$bAwal = 'Sep';
				break;
			case '10': 
				$bAwal = 'Okt';
				break;
			case '11': 
				$bAwal = 'Nov';
				break;	
			case '12': 
				$bAwal = 'Des';
				break;	
		}

		switch($mAkhir) {
			case '01': 
				$bAkhir = 'Jan';
				break;
			case '02': 
				$bAkhir = 'Feb';
				break;
			case '03': 
				$bAkhir = 'Mar';
				break;
			case '04': 
				$bAkhir = 'Apr';
				break;
			case '05': 
				$bAkhir = 'Mei';
				break;
			case '06': 
				$bAkhir = 'Jun';
				break;
			case '07': 
				$bAkhir = 'Jul';
				break;
			case '08': 
				$bAkhir = 'Agt';
				break;
			case '09': 
				$bAkhir = 'Sep';
				break;
			case '10': 
				$bAkhir = 'Okt';
				break;
			case '11': 
				$bAkhir = 'Nov';
				break;	
			case '12': 
				$bAkhir = 'Des';
				break;	
		}

		$pAwalfix = $bAwal.' '.$yAwal;
		$pAkhirfix = $bAkhir.' '.$yAkhir;

		$result = array('status' => 'success', 'awal' => $pAwalfix, 'akhir' => $pAkhirfix);
	}
	
	else if ($_POST['action'] == 'edit'){
		$dbname 		= $_POST['dbname'];
		$bussinessUp   	= $_POST['selectBis'];
		$phoneUp 	   	= $_POST['hp'];
		$waUp 		  	= $_POST['wa'];
		$npwpUp 		= $_POST['npwp'];
		$addrUp 		= $_POST['address'];
		$cityUp 		= $_POST['kota'];
		$provUp 		= $_POST['selectProv'];
		$zipUp 		 	= $_POST['kodepos'];
		$cpnnameUp 	 	= trim($_POST['nameU'], ' ');
		//$cpnidUP 	   = $_POST['nameU'];

		$update = mysql_query("UPDATE company set business_id='$bussinessUp', phone_no='$phoneUp', wa_no='$waUp' , npwp_no='$npwpUp', address='$addrUp', city='$cityUp', prov_code='$provUp', zip_code='$zipUp' WHERE name = '$cpnnameUp'") or die(mysql_error());
		$result= array('status' => 'successedit');		
	}
	
	else if ($_POST['action'] == 'cari'){
		$id_sales = $_POST['id'];
		$qcari = mysql_query("SELECT * FROM salesman WHERE type < 3 AND id ='$id_sales'") or die(mysql_error());
		$data = mysql_num_rows($qcari);

		if ($data > 0)  {
			$qSales = "SELECT name FROM salesman 
						WHERE id = '".$id_sales."'";
			$rSales = mysql_query($qSales) or die(mysql_error());
			$dSales = mysql_fetch_array($rSales);
			
			$result = array('status'=> $dSales['name']);
		}
		else{
			$result = array('status'=> 'gagal');
		}
	}
	
	else if ($_POST['action']=='simpanuser') {
		
		$hash = md5(rand(0,1000));
		$tgl = date('Y-m-d');
		$now = date('Y-m-d H:i:s');
		$email = strtolower(trim($_POST['email']));
		$username = substr($email,0,20);
		$level = $_POST['level'];
		$dbname = strtolower($_POST['dbname']);
		$versi = $_POST['versi'];
		$endDate = $_POST['enddate'];
		$used1 = $_POST['addUser_used'];

		$due_date = date('Y-m-d', strtotime($now. ' + 1 days'));
		if(strtotime($due_date) > strtotime($endDate)) {
			$due_date = $endDate;
		}
		
		$qVer = "SELECT name FROM version 
					WHERE id = '".$versi."'";
		$rVer = mysql_query($qVer) or die(mysql_error());
		$dVer = mysql_fetch_array($rVer);
		$namaversi = $dVer['name'];
		
		$custID = $_SESSION['custID'];
		//$passAcak = substr(str_shuffle(MD5(microtime())), 0,6);
		//$password = password_hash($email.$passAcak,PASSWORD_DEFAULT);
		
		$salesmanID = 'S0001';
		$paketInduk = '';
		$subsInduk = ''; 
		$tglakhirInduk = '';
		$qInduk = "SELECT co.salesman_id, s.stsrec, co.begin_date, 
					co.end_date, co.package_id, sc.value_month, 
					co.db_date, p.subscribe_id, co.total_branch, co.created_on      
					FROM cust_order co 
					LEFT JOIN salesman s ON s.id = co.salesman_id 
					LEFT JOIN package p ON p.id = co.package_id 
					LEFT JOIN subscribe sc ON sc.id = p.subscribe_id 
					WHERE co.cust_id = '".$custID."' 
					AND co.dbname = '".$dbname."' 
					AND co.ver_id = '".$versi."' 
					ORDER BY co.order_id DESC LIMIT 1";
		$rInduk = mysql_query($qInduk) or die(mysql_error());
		$dInduk = mysql_fetch_array($rInduk);
		if($dInduk['salesman_id'] == ''){
			$salesmanID = 'S0001';
		} else {
			if($dInduk['stsrec'] == 'A') {
				$salesmanID = $dInduk['salesman_id'];
			} else {
				$salesmanID = 'S0001';
			}
		}
		$paketInduk = $dInduk['package_id'];
		$subsInduk = $dInduk['value_month'];
		$tglawalInduk = $dInduk['begin_date'];
		$tglakhirInduk = $dInduk['end_date'];
		$tglDB = $dInduk['db_date'];
		$subscribeID = $dInduk['subscribe_id'];
		$totalBranch = $dInduk['total_branch'];
		$created_on = $dInduk['created_on'];
		
		// Mendapatkan jumlah bulan berlangganan
		//$tglmulai = strtotime(date("Y-m-d"));
		if(strtotime(date("Y-m-d")) < strtotime($tglawalInduk)) {
			$tglmulai = strtotime($tglawalInduk);
		} else {
			$tglmulai = strtotime(date("Y-m-d"));
		}
		$tglakhir = strtotime($tglakhirInduk);
		$inthari = ($tglakhir - $tglmulai)/60/60/24;
		$valbulan = $inthari/30;
		$valmonth = ceil($valbulan);
		if($valmonth > 12) {
			$valmonth = 12;
		}

		/*if($created_on < '2021-02-01') {
			$qPr = "SELECT price, package.id AS package_id FROM package 
					WHERE package_type = 2 AND subscribe_id = '".$subscribeID."' AND ver_id = '".$versi."'";
		} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2021-11-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2021-11-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 2 AND p.subscribe_id = '".$subscribeID."' AND p.ver_id = '".$versi."'";
		} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2022-01-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '2022-01-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 2 AND p.subscribe_id = '".$subscribeID."' AND p.ver_id = '".$versi."'";
		} else {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 2 AND p.subscribe_id = '".$subscribeID."' AND p.ver_id = '".$versi."'";
		}*/
		$qPr = "SELECT p.id AS package_id,
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				WHERE p.package_type = 2 AND p.subscribe_id = '".$subscribeID."' AND p.ver_id = '".$versi."'";
		$rPr = mysql_query($qPr) or die(mysql_error());
		$dPr = mysql_fetch_array($rPr);

		$price = $dPr['price'];
		if($dbname == 'db') {
			$price = 100000;
		}

		$amount = $dPr['price'] * $valmonth; 
		if(date('Y-m-d') < '2022-04-01') {
			$ppn = $amount * 10 / 100;
		} else {
			$ppn = $amount * 11 / 100; 
		}
		$package_id = $dPr['package_id'];

		$qCkEmail = "SELECT name, email, id, `password` FROM cust WHERE email = '$email'";
		$rowE = mysql_query($qCkEmail) or die(mysql_error());
		$num_rows = mysql_num_rows($rowE);
		$dataEmail = mysql_fetch_array($rowE);
		$custBaru = 0;

		//if(count($dataEmail) <= 0){
		if($num_rows <= 0){
			$passAcak = substr(str_shuffle(MD5(microtime())), 0,6);
			$password = password_hash($email.$passAcak,PASSWORD_DEFAULT);
			
			$insert = mysql_query("INSERT INTO cust(email, name, `password`, password_app, hash, salesman_id, created_on, ctype) VALUES('$email', '$username', '$password', '$password', '$hash', '$salesmanID', '$now', '$level')");
			$custId = mysql_fetch_array(mysql_query("SELECT id FROM cust WHERE email='$email'"));

			$emailId = $custId['id']; 
			$custBaru = '1';
		}
		else{
			$password = $dataEmail['password'];

			$emailId = $dataEmail['id']; 
			$custBaru = '0';
		}

		$stsused = '';
		$stsact = 'A';
		if($used1 == 'T'){
			$stsused = 'T';
			$stsact = 'A';
		} else if($used1 != 'T'){
			$stsused = 'D';
			$stsact = 'D';
		} 

		if($emailId != '') {
			$insert2 = mysql_query("INSERT INTO cust_order (`cust_id`,`package_id`,`dbname`,`ver_id`,`company_id`,`trial_days`,`expired_days`,`db_date`,`begin_date`,`end_date`,`expired_date`,`acc_period_begin`,`acc_period_end`,`acc_code`,`active`,`used`,`salesman_id`,`stsrec`,`created_by`,`created_on`,`modified_by`,`modified_on`, `ctype`, `total_branch`) SELECT '$emailId', '$package_id',`dbname`,`ver_id`,`company_id`,`trial_days`,`expired_days`,`db_date`,'$tgl',`end_date`,`expired_date`,`acc_period_begin`,`acc_period_end`,`acc_code`,'$stsact','$stsused','$salesmanID','A',`created_by`,`created_on`,`modified_by`,`modified_on`,'$level','$totalBranch' FROM `cust_order` WHERE LOWER(dbname)='$dbname' and ver_id='$versi' GROUP BY dbname") or die(mysql_error());
		}

		$invId['last_id'] = '';
		if($used1 != 'T'){
			$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
					FROM invoice 
					WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
			$rNO = mysql_query($qNO) or die(mysql_error());
			$dNO = mysql_fetch_array($rNO);
			if ($dNO['max_no'] == 0){
				$seq = 1;
			}else{
				$seq = $dNO['max_no'] + 1;
			}
			$no = 'FP'.date("Ym").sprintf("%06d", $seq);
			
			$initialMount = 0; //rand(10,99);
			
			$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `stsrec`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, `initial_amount`, `ppn`) VALUES ('$no', '$now', '$due_date', 'IDR', '$amount', '0', '0000-00-00', 'N', 'A', 'admin', NOW(), 'admin', NOW(), '$custID', '$initialMount', '$ppn')") or die(mysql_error());

			$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
			
			$cs = mysql_query("SELECT order_id, expired_date, expired_days, company_id FROM cust_order WHERE cust_id='$emailId' AND dbname = '$dbname'") or die(mysql_error());
			$orderId = mysql_fetch_array($cs);
			
			$companyId = $orderId['company_id'];	
			$orderId2 = $orderId['order_id'];
			$expDate = $orderId['expired_date'];
			$expired = $orderId['expired_days'];
			$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`, `order_idlama`, `add_branch`) VALUES ('$invId[last_id]', '$orderId2', '$now', '$endDate', '$expDate', '$expired', 'IDR', '$price', 'admin', NOW(), 'admin', NOW(), '$package_id', '$valmonth', '', '0')") or die(mysql_error());
		}

		if($emailId != '') {

			//masukkan username,email,pass owner kedalam tb sysuser
			$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());

			$id = rand(10,99);
			$usid = strtoupper(substr($dbname,0,1).$id);
			$level = ($level=='A') ? '1' : '2';

			$sql = mysqli_query($koneksi4, "SELECT COUNT(USID) AS CNT FROM SYSUSER WHERE USID = '$usid'") or die(mysqli_error($koneksi4));
			$rowc = mysqli_fetch_array($sql);
			
			$namauser = substr($email,0,20);
			if($rowc[0] == 0) {
				mysqli_query($koneksi4, "INSERT INTO SYSUSER (USID, `PASSWORD`, USERNAME, EMAIL, USLEVEL, IDPT, AKSESCABANG) VALUES ('$usid', '$password', '$namauser', '$email', '$level', '01', '01')") or die(mysqli_error($koneksi4));
			} else {
				$id = rand(1,999);
				$usid = sprintf('%03d', $id);
				mysqli_query($koneksi4, "INSERT INTO SYSUSER (USID, `PASSWORD`, USERNAME, EMAIL, USLEVEL, IDPT, AKSESCABANG) VALUES ('$usid', '$password', '$namauser', '$email', '$level', '01', '01')") or die(mysqli_error($koneksi4));
			}
		
			//OLD QUERY	
			//mysqli_query($koneksi4, "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, DOE, LOE, DEO) SELECT '".$usid."', LADD, LAPD, URUT, NOW(), '', '".$usid."' FROM SYSMENU WHERE LEVEL >= ".$level." AND URUT > 0") or die(mysqli_error($koneksi4));
			
			//NEW QUERY
			mysqli_query($koneksi4, "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, DOE, LOE, DEO) SELECT '".$usid."', S1.LADD, S2.LAPD, S2.URUT, NOW(), NOW(), '".$usid."' FROM SYSMENUGROUP S1 JOIN SYSMENU S2 ON S1.LADD = S2.LADD WHERE UPPER(S1.GRID) = '".strtoupper($namaversi)."'") or die(mysqli_error($koneksi4));
			
			$host = $_SERVER['HTTP_HOST'];
			//kirim konfirmasi ke user yang ditambahkan
			$subject = 'Konfirmasi SISCOM Online';
			//$pesan = file_get_contents("http://$host/siserp/module/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
			//$pesan = file_get_contents("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
			
			if($custBaru == '1') {
				//$pesan = url_get_content("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");

				$pesan =
				"
				<!DOCTYPE html>
				<html>
				<head>
					<title></title>
					<style type='text/css'>
						body{
							width: 100%;
							height: 100%;
							margin: 0px;
							background-color: #dcdcdc;
						}
						.container{
							padding: 30px;
							background-color: white;
							width: 75%;
							height: 100%;
							margin:0px auto;
						}
						.row{
							display: block;
							clear: both;
						}
						.header{
							border-bottom: 1px solid grey;
							padding-bottom: 50px;
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
						p{
							text-align: justify !important;
							color: grey;
							font-size: 14px;
						}
						strong{
							color:black;
						}
						.text{
							margin-right: 30px;
						}
						a.btn{
							text-decoration: none !important;
						}
						.btn{
							margin-top:300px;
							text-align: center;
							padding: 10px;
							height: 40px;
							background-color: #438EB9;
							color:white;
							border-radius: 5px;
						}
						.btn:hover{
							background-color: #53b2e8;
						}
						#border{
							position: absolute;
							top: 165px;
							right: 12%;
						}
						.border-black{
							width: 8px;
							height: 200px;
							background-color: #003b6a;
						}
						.border-blue{
							width: 8px;
							height: 100px;
							background-color: #75ddf4;
						}
						.border-blue:after{
							content: '';
							width: 0;
							height: 0;
							position: absolute;
							bottom: 50px;
							left: -10px;
							border-width: 10px;
							border-style: solid;
							border-color: transparent white transparent transparent;
						}
						.border-green{
							width: 8px;
							height: 60px;
							background-color: #96d666;
						}
						.border-green:after{
							content: '';
							width: 0;
							height: 0;
							position: absolute;
							bottom: 0px;
							left: -10px;
							border-width: 10px;
							border-style: solid;
							border-color: transparent transparent white transparent;
						}
						.text{
							text-align: center;
						}
					</style>
				</head>
				<body>
					<div class='container'>
						<div class='header'>
							<div class='row'>
								<div class='icon'>
									<img src='".$abs2."/module/reg/img/LOGO-SISCOM.png' width='200px'>
								</div>
								<div class='official'>
								</div>	
							</div>
							<div class='row'>
								<div class='pull-right'>
									<p>Account&emsp;|&emsp;<strong>Konfirmasi Email</strong></p>
								</div>
							</div>
						</div>
						<div class='body'>
							<div class='pull-left'>
								<h3>Konfirmasi email Anda!</h3>
								<p>Halo <strong>".$email.",</strong></p>
								<div class='text'>
									<p>Anda telah mendaftarkan email baru untuk layanan SISCOM Online.  Demi keamanan mohon untuk mengkonfirmasikan email Anda dengan mengklik link di bawah ini</p>
									<br><br><br>
									<a href='".$abs2."/module/reg/confirm.php?email=".$email."&hash=".$hash."' class='btn'>Konfirmasi Email</a>
									<br><br><br>
									<p>Untuk masuk ke dalam website, silakan gunakan password : <strong>".$passAcak."</strong></p>
								</div>
							</div>
							<div class='row'>
								<div class='text'>
									<br><br><br>
									<strong>PT. Shan Informasi Sistem</strong><br>
									City Resort Rukan Malibu Blok J/75-77 <br>
									Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
									Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
								</div>
							</div>
						</div>
					</div>
					
				</body>
				</html>
				";

			} else {
				//$pesan = url_get_content("$abs/reg/pages/template_welcome.php?email=$email&hash=$hash&db=$dbname");

				$pesan =
				"
				<!DOCTYPE html>
				<html>
				<head>
					<title></title>
					<style type='text/css'>
						body{
							width: 100%;
							height: 100%;
							margin: 0px;
							background-color: #dcdcdc;
						}
						.container{
							padding: 30px;
							background-color: white;
							width: 75%;
							height: 100%;
							margin:0px auto;
						}
						.row{
							display: block;
							clear: both;
						}
						.header{
							border-bottom: 1px solid grey;
							padding-bottom: 50px;
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
						p{
							text-align: justify !important;
							color: grey;
							font-size: 14px;
						}
						strong{
							color:black;
						}
						.text{
							margin-right: 30px;
						}
						a.btn{
							text-decoration: none !important;
						}
						.btn{
							margin-top:300px;
							text-align: center;
							padding: 10px;
							height: 40px;
							background-color: #438EB9;
							color:white;
							border-radius: 5px;
						}
						.btn:hover{
							background-color: #53b2e8;
						}
						#border{
							position: absolute;
							top: 165px;
							right: 12%;
						}
						.border-black{
							width: 8px;
							height: 200px;
							background-color: #003b6a;
						}
						.border-blue{
							width: 8px;
							height: 100px;
							background-color: #75ddf4;
						}
						.border-blue:after{
							content: '';
							width: 0;
							height: 0;
							position: absolute;
							bottom: 50px;
							left: -10px;
							border-width: 10px;
							border-style: solid;
							border-color: transparent white transparent transparent;
						}
						.border-green{
							width: 8px;
							height: 60px;
							background-color: #96d666;
						}
						.border-green:after{
							content: '';
							width: 0;
							height: 0;
							position: absolute;
							bottom: 0px;
							left: -10px;
							border-width: 10px;
							border-style: solid;
							border-color: transparent transparent white transparent;
						}
						.text{
							text-align: center;
						}
					</style>
				</head>
				<body>
					<div class='container'>
						<div class='header'>
							<div class='row'>
								<div class='icon'>
									<img src='".$abs2."/module/reg/img/LOGO-SISCOM.png' width='200px'>
								</div>
								<div class='official'>
								</div>	
							</div>
							<div class='row'>
								<div class='pull-right'>
									<p>Account&emsp;|&emsp;<strong>Pemberitahuan Email</strong></p>
								</div>
							</div>
						</div>
						<div class='body'>
							<div class='pull-left'>
								<h3>Pemberitahuan email</h3>
								<p>Halo <strong>".$dataEmail['name'].",</strong></p>
								<div class='text'>
									<p>Anda telah didaftarkan sebagai user pengguna database <strong>".$dbname."</strong> di layanan SISCOM Online.</p>
									<p>Untuk masuk ke dalam website, silakan gunakan password yang sudah Anda miliki.</strong></p>
									<br>
									<p>Semua informasi akan dikirim ke email ini. Jika Anda merasa tidak mendaftar ke SISCOM, mohon untuk melaporkan kepada kami untuk menghindari penyalahgunaan identitas anda.</p>
								</div>
							</div>
							<div class='row'>
								<div class='text'>
									<br><br><br>
									<strong>PT. Shan Informasi Sistem</strong><br>
									City Resort Rukan Malibu Blok J/75-77 <br>
									Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
									Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
								</div>
							</div>
						</div>
					</div>
					
				</body>
				</html>
				";
			}
			//sendEmail($email, $pesan, $subject); 
			$username = 'finance@siscomonline.co.id';
			$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
			$name = $emailrec['name'];
			$password = $emailrec['password'];
			$passwordDecrypt = 'financeoke515'; //decrypt($ENCRKEY, $password);
			sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name); 

			/*KIRIM EMAIL KE OWNER*/
			$emailOwner = $_SESSION['custEmail'];
			//$pesan = file_get_contents("http://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
			//$pesan = file_get_contents("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
			$pesan = url_get_content("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");

			ob_start();

			//$lampiran = 'tagihan.pdf';
			$subject = 'Tagihan SISCOM Online';
			//sendEmail2($emailOwner, $pesan, $subject, $invId['last_id'], $lampiran); //kirim tagihan ke email owner
			$username2 = 'finance@siscomonline.co.id';
			$emailrec2 = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username2'"));
			$name2 = $emailrec2['name'];
			$password2 = $emailrec2['password'];
			$passwordDecrypt2 = 'financeoke515'; //decrypt($ENCRKEY, $password2);
			sendEmail($emailOwner, $pesan, $subject, $username2, $passwordDecrypt2, $name2, $invId['last_id']); //kirim tagihan ke email owner
			// unlink($lampiran); //hapus file yang sudah dibuat

			if ($insert2) {
				$result = array('status'=>'success');
			}		

		} else {
			$result = array('status'=>'failed');
		}
	}
	
	else if ($_POST['action']=='gantiuser') {
		
		$emailLama = $_POST['emailLama'];
		$hash = md5(rand(0,1000));
		$tgl = date('Y-m-d');
		$now = date('Y-m-d H:i:s');
		$email = $_POST['email'];
		$level = $_POST['level'];
		$dbname = strtolower($_POST['dbname']);
		$versi = $_POST['versi'];
		$endDate = $_POST['enddate'];
		$used1 = $_POST['addUser_used'];

		$qUserLama = "SELECT cust.id,cust_order.order_id FROM cust JOIN cust_order ON cust_order.cust_id = cust.id WHERE email = '$emailLama' and dbname = '$dbname'";
		$rLama = mysql_query($qUserLama) or die(mysql_error());
		$dUserLama = mysql_fetch_array($rLama);
		$order_idlama = $dUserLama['order_id'];
		
		$qVer = "SELECT name FROM version 
					WHERE id = '".$versi."'";
		$rVer = mysql_query($qVer) or die(mysql_error());
		$dVer = mysql_fetch_array($rVer);
		$namaversi = $dVer['name'];
		
		$custID = $_SESSION['custID'];
		$passAcak = substr(str_shuffle(MD5(microtime())), 0,6);
		$password = password_hash($email.$passAcak,PASSWORD_DEFAULT);
		
		$salesmanID = 'S0001';
		$paketInduk = '';
		$subsInduk = ''; 
		$tglakhirInduk = '';
		$qInduk = "SELECT co.salesman_id, s.stsrec,  
					co.package_id, co.end_date, sc.value_month, 
					co.db_date, p.subscribe_id, co.total_branch, 
					co.created_on      
					FROM cust_order co 
					LEFT JOIN salesman s ON s.id = co.salesman_id 
					LEFT JOIN package p ON p.id = co.package_id 
					LEFT JOIN subscribe sc ON sc.id = p.subscribe_id 
					WHERE co.cust_id = '".$custID."' 
					AND co.dbName = '".$dbname."' 
					AND co.ver_id = '".$versi."' 
					ORDER BY co.order_id DESC LIMIT 1";
		$rInduk = mysql_query($qInduk) or die(mysql_error());
		$dInduk = mysql_fetch_array($rInduk);
		if($dInduk['salesman_id'] == ''){
			$salesmanID = 'S0001';
		} else {
			if($dInduk['stsrec'] == 'A') {
				$salesmanID = $dInduk['salesman_id'];
			} else {
				$salesmanID = 'S0001';
			}
		}
		$paketInduk = $dInduk['package_id'];
		$subsInduk = $dInduk['value_month'];
		$tglakhirInduk = $dInduk['end_date'];
		$tglDB = $dInduk['db_date'];
		$subscribeID = $dInduk['subscribe_id'];
		$totalBranch = $dInduk['total_branch'];
		$created_on = $dInduk['created_on'];
		
		// Mendapatkan jumlah bulan berlangganan
		$tglmulai = strtotime(date("Y-m-d"));
		$tglakhir = strtotime($tglakhirInduk);
		$inthari = ($tglakhir - $tglmulai)/60/60/24;
		$valbulan = $inthari/30;
		// $valmonth = ceil($valbulan);
		// if($valmonth > 12) {
		// 	$valmonth = 12;
		// }
		$valmonth = '1';

		$qPr = "SELECT p.id AS package_id,
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				WHERE p.package_type = 4 AND p.ver_id = '".$versi."'";
		$rPr = mysql_query($qPr) or die(mysql_error());
		$dPr = mysql_fetch_array($rPr);

		/*$qPr = "SELECT p.id AS package_id,
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				WHERE p.package_type = 4 AND p.ver_id = '".$versi."'";
		$rPr = mysql_query($qPr) or die(mysql_error());
		$dPr = mysql_fetch_array($rPr);*/
		// $effdate = $dPr['begin_date'];
		$price = $dPr['price'];
		$amount = $dPr['price']; 
		//if($tglawalInduk < '2022-04-01') {
		if(date('Y-m-d') < '2022-04-01') {
			$ppn = $amount * 10 / 100; 
		} else {
			$ppn = $amount * 11 / 100; 
		}
		$package_id = $dPr['package_id'];

		$qCntEmail = "SELECT COUNT(email) AS cntId from cust where email = '$email'";
		$rowCnt = mysql_query($qCntEmail) or die(mysql_error());
		$dataCnt = mysql_fetch_array($rowCnt);
		$cntUser = $dataCnt['cntId'];

		$qCkEmail = "SELECT email, id from cust where email = '$email'";
		$rowE = mysql_query($qCkEmail) or die(mysql_error());
		$dataEmail = mysql_fetch_array($rowE);

		if($cntUser > 0){
			$emailId = $dataEmail['id']; 
		} else {
			$insert = mysql_query("INSERT INTO cust(email, name, `password`, password_app, hash, salesman_id, created_on, ctype) VALUES('$email', '$email', '$password', '$password', '$hash', '$salesmanID', '$now', '$level')");
			$custId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM cust WHERE email = '$email'"));

			$emailId = $custId['last_id']; 
		}
		
		$stsused = '';
		$stsact = 'A';
		if($used1 == 'T'){
			$stsused = 'T';
			$stsact = 'A';
		} else if($used1 != 'T'){
			$stsused = 'D';
			$stsact = 'D';
		} 
		
		$insert2 = mysql_query("INSERT INTO cust_order (`cust_id`,`package_id`,`dbname`,`ver_id`,`company_id`,`trial_days`,`expired_days`,`db_date`,`begin_date`,`end_date`,`expired_date`,`acc_period_begin`,`acc_period_end`,`acc_code`,`active`,`used`,`salesman_id`,`stsrec`,`created_by`,`created_on`,`modified_by`,`modified_on`, `ctype`, `total_branch`) SELECT '$emailId', '$package_id',`dbname`,`ver_id`,`company_id`,`trial_days`,`expired_days`,`db_date`,'$tgl',`end_date`,`expired_date`,`acc_period_begin`,`acc_period_end`,`acc_code`,'$stsact','$stsused','$salesmanID','A',`created_by`,`created_on`,`modified_by`,`modified_on`,'$level','$totalBranch' FROM `cust_order` WHERE LOWER(dbname)='$dbname' 
			AND ver_id='$versi' GROUP BY dbname") or die(mysql_error());

		$invId['last_id'] = '';
		if($used1 != 'T'){
			$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
					FROM invoice 
					WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
			$rNO = mysql_query($qNO) or die(mysql_error());
			$dNO = mysql_fetch_array($rNO);
			if ($dNO['max_no'] == 0){
				$seq = 1;
			}else{
				$seq = $dNO['max_no'] + 1;
			}
			$no = 'FP'.date("Ym").sprintf("%06d", $seq);
			
			$initialMount = 0; //rand(10,99);
			
			$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `stsrec`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, `initial_amount`, `ppn`) VALUES ('$no', '$now', '$endDate', 'IDR', '$amount', '0', '0000-00-00', 'N', 'A', 'admin', NOW(), 'admin', NOW(), '$custID', '$initialMount', '$ppn')") or die(mysql_error());

			$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
			
			$cs = mysql_query("SELECT order_id, expired_date, expired_days, company_id FROM cust_order WHERE cust_id='$emailId' AND dbname = '$dbname'") or die(mysql_error());
			$orderId = mysql_fetch_array($cs);
			
			$companyId = $orderId['company_id'];	
			$orderId2 = $orderId['order_id'];
			$expDate = $orderId['expired_date'];
			$expired = $orderId['expired_days'];
			$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`, `order_idlama`, `add_branch`) VALUES ('$invId[last_id]', '$orderId2', '$now', '$endDate', '$expDate', '$expired', 'IDR', '$price', 'admin', NOW(), 'admin', NOW(), '$package_id', '$valmonth', '$order_idlama', '0')") or die(mysql_error());
		}

		// DISABLED USER LAMA
		$qryDisabled = mysql_query("UPDATE cust_order SET active = 'D', used = 'D', stsrec = 'D' WHERE order_id = '$order_idlama' AND dbname = '$dbname'") or die(mysql_error());

		//masukkan username,email,pass owner kedalam tb sysuser
		$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());

		$id = rand(10,99);
		$usid = strtoupper(substr($dbname,0,1).$id);
		$level = ($level=='A') ? '1' : '2';

		$sql = mysqli_query($koneksi4, "SELECT COUNT(USID) AS CNT FROM SYSUSER WHERE USID = '$usid'") or die(mysqli_error($koneksi4));
		$rowc = mysqli_fetch_array($sql);
		
		$namauser = substr($email,0,20);
		if($rowc[0] == 0) {
			mysqli_query($koneksi4, "INSERT INTO SYSUSER (USID, `PASSWORD`, USERNAME, EMAIL, USLEVEL, IDPT, AKSESCABANG) VALUES ('$usid', '$password', '$namauser', '$email', '$level', '01', '01')") or die(mysqli_error($koneksi4));
		} else {
			$id = rand(1,999);
			$usid = sprintf('%03d', $id);
			mysqli_query($koneksi4, "INSERT INTO SYSUSER (USID, `PASSWORD`, USERNAME, EMAIL, USLEVEL, IDPT, AKSESCABANG) VALUES ('$usid', '$password', '$namauser', '$email', '$level', '01', '01')") or die(mysqli_error($koneksi4));
		}
		
		//NEW QUERY
		mysqli_query($koneksi4, "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, DOE, LOE, DEO) SELECT '".$usid."', S1.LADD, S2.LAPD, S2.URUT, NOW(), NOW(), '".$usid."' FROM SYSMENUGROUP S1 JOIN SYSMENU S2 ON S1.LADD = S2.LADD WHERE UPPER(S1.GRID) = '".strtoupper($namaversi)."'") or die(mysqli_error($koneksi4));
		
		$host = $_SERVER['HTTP_HOST'];
		//kirim konfirmasi ke user yang ditambahkan
		$subject = 'Konfirmasi SISCOM Online';
		//$pesan = file_get_contents("http://$host/siserp/module/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
		//$pesan = file_get_contents("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
		//$pesan = url_get_content("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");

		$pesan = 
		"
		<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<style type='text/css'>
				body{
					width: 100%;
					height: 100%;
					margin: 0px;
					background-color: #dcdcdc;
				}
				.container{
					padding: 30px;
					background-color: white;
					width: 75%;
					height: 100%;
					margin:0px auto;
				}
				.row{
					display: block;
					clear: both;
				}
				.header{
					border-bottom: 1px solid grey;
					padding-bottom: 50px;
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
				p{
					text-align: justify !important;
					color: grey;
					font-size: 14px;
				}
				strong{
					color:black;
				}
				.text{
					margin-right: 30px;
				}
				a.btn{
					text-decoration: none !important;
				}
				.btn{
					margin-top:300px;
					text-align: center;
					padding: 10px;
					height: 40px;
					background-color: #438EB9;
					color:white;
					border-radius: 5px;
				}
				.btn:hover{
					background-color: #53b2e8;
				}
				#border{
					position: absolute;
					top: 165px;
					right: 12%;
				}
				.border-black{
					width: 8px;
					height: 200px;
					background-color: #003b6a;
				}
				.border-blue{
					width: 8px;
					height: 100px;
					background-color: #75ddf4;
				}
				.border-blue:after{
					content: '';
					width: 0;
					height: 0;
					position: absolute;
					bottom: 50px;
					left: -10px;
					border-width: 10px;
					border-style: solid;
					border-color: transparent white transparent transparent;
				}
				.border-green{
					width: 8px;
					height: 60px;
					background-color: #96d666;
				}
				.border-green:after{
					content: '';
					width: 0;
					height: 0;
					position: absolute;
					bottom: 0px;
					left: -10px;
					border-width: 10px;
					border-style: solid;
					border-color: transparent transparent white transparent;
				}
				.text{
					text-align: center;
				}
			</style>
		</head>
		<body>
			<div class='container'>
				<div class='header'>
					<div class='row'>
						<div class='icon'>
							<img src='".$abs."/reg/img/LOGO-SISCOM.png' width='200px'>
						</div>
						<div class='official'>
						</div>
					</div>
					<div class='row'>
						<div class='pull-right'>
							<p>Account&emsp;|&emsp;<strong>Konfirmasi Email</strong></p>
						</div>
					</div>
				</div>
				<div class='body'>
					<div class='pull-left'>
						<h3>Konfirmasi email Anda!</h3>
						<p>Halo <strong>".$email.",</strong></p>
						<div class='text'>
							<p>Anda telah mendaftarkan email baru untuk layanan SISCOM Online.  Demi keamanan mohon untuk mengkonfirmasikan email Anda dengan mengklik link di bawah ini</p>
							<br><br><br>
							<a href='".$abs2."/module/reg/confirm.php?email=".$email."&hash=".$hash."' class='btn'>Konfirmasi Email</a>
							<br><br><br>
							<p>Untuk masuk ke dalam website, silakan gunakan password : <strong>".$passAcak."</strong></p>
						</div>
					</div>
					<div class='row'>
						<div class='text'>
							<br><br><br>
							<strong>PT. Shan Informasi Sistem</strong><br>
							City Resort Rukan Malibu Blok J/75-77 <br>
							Mutiara Taman Palem, Cengkareng - Jakarta Barat 11730<br>
							Tel: +62 21 5694 5002 | <a href='https://www.siscomonline.co.id/'>SISCOM Online</a>
						</div>
					</div>
				</div>
			</div>
			
		</body>
		</html>
		";

		//sendEmail($email, $pesan, $subject); 
		$username = 'finance@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'financeoke515'; //decrypt($ENCRKEY, $password);
		//sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name); 

		/*KIRIM EMAIL KE OWNER*/
		$emailOwner = $_SESSION['custEmail'];
		//$pesan = file_get_contents("http://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		$pesan = url_get_content("$abs/reg/pages/tagihan_ganti_user.php?inv_id=$invId[last_id]");

		// ob_start();

		//$lampiran = 'tagihan.pdf';
		$subject = 'Tagihan SISCOM Online';
		//sendEmail2($emailOwner, $pesan, $subject, $invId['last_id'], $lampiran); //kirim tagihan ke email owner
		$username2 = 'finance@siscomonline.co.id';
		$emailrec2 = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username2'"));
		$name2 = $emailrec2['name'];
		$password2 = $emailrec2['password'];
		$passwordDecrypt2 = 'financeoke515'; //decrypt($ENCRKEY, $password2);
		//sendEmail($emailOwner, $pesan, $subject, $username2, $passwordDecrypt2, $name2, $invId['last_id']); //kirim tagihan ke email owner
		// unlink($lampiran); //hapus file yang sudah dibuat

		if ($insert2) {
			$result = array('status'=>'success');
		}		
	}
	
	else if ($_POST['action']=='tambahCabang') {
		$nilai = $_POST['nilai'];
		$tgl = date('Y-m-d');
		$now = date('Y-m-d H:i:s');
		$dbname = strtolower($_POST['dbname']);
		$versi = $_POST['versi'];
		$endDate = $_POST['enddate'];
		$used1 = $_POST['used'];
		
		$custID = $_SESSION['custID'];
		$paketInduk = '';
		$subsInduk = ''; 
		$tglakhirInduk = '';
		$qInduk = "SELECT co.salesman_id, s.stsrec,  
					co.package_id, co.begin_date, co.end_date, sc.value_month, 
					co.db_date, p.subscribe_id    
					FROM cust_order co 
					LEFT JOIN salesman s ON s.id = co.salesman_id 
					LEFT JOIN package p ON p.id = co.package_id 
					LEFT JOIN subscribe sc ON sc.id = p.subscribe_id 
					WHERE co.cust_id = '".$custID."' 
					AND co.dbName = '".$dbname."' 
					AND co.ver_id = '".$versi."' 
					ORDER BY co.order_id DESC LIMIT 1";
		$rInduk = mysql_query($qInduk) or die(mysql_error());
		$dInduk = mysql_fetch_array($rInduk);
		// $paketInduk = $dInduk['package_id'];
		// $subsInduk = $dInduk['value_month'];
		$tglawalInduk = $dInduk['begin_date'];
		$tglakhirInduk = $dInduk['end_date'];
		$tglDB = $dInduk['db_date'];
		$subscribeID = $dInduk['subscribe_id'];
		
		// Mendapatkan jumlah bulan berlangganan
		//$tglmulai = strtotime(date("Y-m-d"));
		if(strtotime(date("Y-m-d")) > strtotime($tglawalInduk)) {
			$tglmulai = strtotime(date("Y-m-d"));
			$beginDate = $now;
		} else {
			$tglmulai = strtotime($tglawalInduk);
			$beginDate = $tglawalInduk;
		}
		$tglakhir = strtotime($tglakhirInduk);
		$inthari = ($tglakhir - $tglmulai)/60/60/24;
		$valbulan = $inthari/30;
		$valmonth = ceil($valbulan);
		if($valmonth > 12) {
			$valmonth = 12;
		}

		$qPr = "SELECT p.id AS package_id,
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				WHERE p.package_type = 5 AND p.ver_id = '".$versi."'";

		/*
		if($created_on < '2021-02-01') {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date < '2021-02-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date < '2021-02-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 5 AND p.ver_id = '".$versi."'";
		} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date < '2021-02-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id 
					AND pr.begin_date < '2021-02-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 5 AND p.ver_id = '".$versi."'";
		} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {

		} else {

		}
		*/

		$rPr = mysql_query($qPr) or die(mysql_error());
		$dPr = mysql_fetch_array($rPr);
		// $effdate = $dPr['begin_date'];
		$price = $dPr['price'];
		$amount = $nilai * $dPr['price'] * $valmonth; 
		//if($tglawalInduk < '2022-04-01') {
		if(date('Y-m-d') < '2022-04-01') {
			$ppn = $amount * 10 / 100; 
		} else {
			$ppn = $amount * 11 / 100; 
		}
		$package_id = $dPr['package_id'];

		$invId['last_id'] = '';
		$insInv = false;
		
		if($used1 != 'T'){
			$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
					FROM invoice 
					WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
			$rNO = mysql_query($qNO) or die(mysql_error());
			$dNO = mysql_fetch_array($rNO);
			if ($dNO['max_no'] == 0){
				$seq = 1;
			}else{
				$seq = $dNO['max_no'] + 1;
			}
			$no = 'FP'.date("Ym").sprintf("%06d", $seq);
			
			$initialMount = 0; //rand(10,99);
			
			$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `stsrec`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, `initial_amount`, `ppn`) VALUES ('$no', '$now', '$endDate', 'IDR', '$amount', '0', '0000-00-00', 'N', 'A', 'admin', NOW(), 'admin', NOW(), '$custID', '$initialMount', '$ppn')") or die(mysql_error());

			$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
			
			$cs = mysql_query("SELECT order_id, expired_date, expired_days, company_id FROM cust_order WHERE cust_id='$custID' AND dbname = '$dbname'") or die(mysql_error());
			$orderId = mysql_fetch_array($cs);
			
			$companyId = $orderId['company_id'];	
			$orderId2 = $orderId['order_id'];
			$expDate = $orderId['expired_date'];
			$expired = $orderId['expired_days'];
			$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`, `order_idlama`, `add_branch`) VALUES ('$invId[last_id]', '$orderId2', '$beginDate', '$endDate', '$expDate', '$expired', 'IDR', '$price', 'admin', NOW(), 'admin', NOW(), '$package_id', '$valmonth', '', '$nilai')") or die(mysql_error());
			//masukkan username,email,pass owner kedalam tb sysuser
			$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());
		
			$host = $_SERVER['HTTP_HOST'];

			/*KIRIM EMAIL KE OWNER*/
			$emailOwner = $_SESSION['custEmail'];
			$pesan = url_get_content("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");

			// ob_start();
			//$lampiran = 'tagihan.pdf';

			$subject = 'Tagihan SISCOM Online';
			$username2 = 'finance@siscomonline.co.id';
			$emailrec2 = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username2'"));
			$name2 = $emailrec2['name'];
			$password2 = $emailrec2['password'];
			$passwordDecrypt2 = 'financeoke515'; //decrypt($ENCRKEY, $password2);
			sendEmail($emailOwner, $pesan, $subject, $username2, $passwordDecrypt2, $name2, $invId['last_id']); //kirim tagihan ke email owner
			// unlink($lampiran); //hapus file yang sudah dibuat

			if ($insInv) {
				$result = array('status'=>'success');
			}
			else{
				$result = array('status'=>'failed');
			}		
		}
		else{
			$result = array('status'=>'failed', 'message'=> 'status trial tidak bisa tambah cabang');
		}

	}

	elseif ($_POST['action']=='ubahpassword') {
		$email = strtolower($_SESSION['custEmail']);
		$password_lama = $email.$_POST['password_lama'];
		$password_baru = password_hash(strtolower($_SESSION['custEmail']).$_POST['password_baru'], PASSWORD_DEFAULT);

		$scEmail = mysql_query("SELECT id, email, name, `password`, password_app, ctype, billing_send 
					FROM cust WHERE email = '$email'") or die (mysql_error());
		$row = mysql_fetch_array($scEmail);
		
		if (password_verify($password_lama, $row['password'])) {
			mysql_query("UPDATE cust SET password='$password_baru', password_app='$password_baru' WHERE email='$email'") or die(mysql_error());
			
			$sql = mysql_query("SELECT co.dbname FROM `cust` c JOIN cust_order co ON c.id=co.cust_id WHERE c.email='$email' AND c.stsrec='A'") or die(mysql_error());

			while ($data = mysql_fetch_array($sql)) {
				$dbname = $data['dbname'];
				//masukkan username,email,pass owner kedalam tb sysuser
				$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'],$GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());
				
				mysqli_query($koneksi4, "UPDATE SYSUSER SET `PASSWORD`='$password_baru' WHERE EMAIL='$email'") or die(mysqli_error($koneksi4));					
			}

			$result = array("status"=>"success");

		}else{
			
			$result = array("status"=>"failed", "error"=>"Password Lama Tidak Sesuai, masukkan kembali password lama anda!!");

		}
	}
	
	elseif ($_POST['action']=='ubahprofil') {
		$email = $_SESSION['custEmail'];
		$nama = $_POST['nama'];

		$sql = mysql_query("SELECT co.dbname FROM `cust` c JOIN cust_order co ON c.id=co.cust_id WHERE c.email='$email' AND c.stsrec='A'") or die(mysql_error());

		// while ($data = mysql_fetch_array($sql)) {
		// 	$dbname = $data['dbname'];
		// 	//masukkan username,email,pass owner kedalam tb sysuser
		// 	$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());
				
		// 	mysqli_query($koneksi4, "UPDATE SYSUSER SET USERNAME='$nama' WHERE EMAIL='$email'") or die(mysqli_error($koneksi4));		
		// }
		
		mysql_query("UPDATE cust SET name='$nama' WHERE email='$email'") or die(mysql_error());
		
		$result = array("status"=>"success");		
	}
	
	else if ($_POST['action']=='ambiluser') {
		$dbname = $_POST['dbname'];
		$strqry = "SELECT DISTINCT c.email, cs.ctype AS level, c.billing_send, cs.used, cs.active, c.valid, 
					COUNT(iv.id) AS cntinv, cs.billing_admin, cs.cust_id 
					FROM cust_order cs 
					JOIN cust c on c.id = cs.cust_id   
					LEFT JOIN invoice_detail ivd ON ivd.order_id = cs.order_id 
					LEFT JOIN invoice iv ON iv.id = ivd.inv_id AND iv.paid_off <> 'Y' AND iv.stsrec = 'A'  
					WHERE cs.dbname = '$dbname' 
					GROUP BY c.email, c.ctype, c.billing_send, cs.used, cs.active, c.valid 
					ORDER BY c.ctype, cs.order_id";
		$custId = mysql_query($strqry);
		while ($row = mysql_fetch_array($custId)) {
			$data[]=$row;
		}
		
		$result = array('status'=>'success', 'data'=>$data);
	}
	
	else if ($_POST['action']=='hapususer') {
		$cust_id = $_POST['cust_id'];
		$delete1 = mysql_query("delete cr,cs from cust_order cr INNER JOIN cust cs on cs.id = cr.cust_id where cs.email = '$cust_id'") or die(mysql_error());
		
		if ($delete1) {
			$result = array('status'=>'success');	
		}	
	}

	else if ($_POST['action'] == 'updateAdmBilling'){
		$cust_id = $_POST['cust_id'];
		$billing_admin = $_POST['billing_admin'];
		$dbname = $_POST['dbname'];

		try {
			$update = mysql_query("UPDATE cust_order set billing_admin='$billing_admin' WHERE cust_id = '$cust_id' AND dbname = '$dbname'") or die(mysql_error());
			$result= array('status' => 'success');
			
		} catch (Exception $e) {
			$result= array('status' => 'failed');
		}

	}
	
	else if ($_POST['action']=='buattagihan') {
		$custID5 = $_POST['cust_id'];
		$dbname = $_POST['dbname'];
		$now = date('Y-m-d');
		
		// Trial days
		$sqlD1 = mysql_query("SELECT * FROM sysdata WHERE id = 1") or die(mysql_error());
		$sysdata1 = mysql_fetch_array($sqlD1);
		$trialday = $sysdata1['value'];
		
		// Expired days
		$sqlD2 = mysql_query("SELECT * FROM sysdata WHERE id = 2") or die(mysql_error());
		$sysdata2 = mysql_fetch_array($sqlD2);
		$expday = $sysdata2['value'];
		
		// Invoice create date
		$sqlD3 = mysql_query("SELECT * FROM sysdata WHERE id = 3") or die(mysql_error());
		$sysdata3 = mysql_fetch_array($sqlD3);
		$invcrt = $sysdata3['value'];	
		
		// Invoice due date
		$sqlD4 = mysql_query("SELECT * FROM sysdata WHERE id = 4") or die(mysql_error());
		$sysdata4 = mysql_fetch_array($sqlD4);
		$invdue = $sysdata4['value'];
		$due_date = date('Y-m-d', strtotime('+'.$invdue.' days', strtotime($now)));
		
		// Warning 1
		$sqlW1 = mysql_query("SELECT * FROM sysdata WHERE id = 5") or die(mysql_error());
		$sysdataW1 = mysql_fetch_array($sqlW1);
		$warning1 = $sysdataW1['value'];
		
		// Warning 2
		$sqlW2 = mysql_query("SELECT * FROM sysdata WHERE id = 6") or die(mysql_error());
		$sysdataW2 = mysql_fetch_array($sqlW2);
		$warning2 = $sysdataW2['value'];
		
		// Warning 3
		$sqlW3 = mysql_query("SELECT * FROM sysdata WHERE id = 7") or die(mysql_error());
		$sysdataW3 = mysql_fetch_array($sqlW3);
		$warning3 = $sysdataW3['value'];
		
		$dbNameBefore = $dbName;
		$invoiceIDBefore = '';
		$invoiceID = '';
		$totamount = 0;
		$initialMount = 0;
		$inthari = 0;
		$valbulan = 0;
		$valmonth = 0;
		$cntInduk = 0;
		
		$strqry = "SELECT DISTINCT co.*, c.email, p.package_type, sc.value_month, 
					p.price AS package_price, p.subscribe_id       
					FROM `cust_order` co 
					JOIN cust c ON c.id = co.cust_id 
					JOIN package p ON co.package_id = p.id  
					JOIN subscribe sc ON sc.id = p.subscribe_id 
					LEFT JOIN invoice_detail ind ON ind.inv_id = co.inv_id 
					WHERE co.dbname = '$dbname' AND co.stsrec <> 'D' 
					ORDER BY co.dbname, p.package_type, co.cust_id, co.ver_id, co.end_date";
		$query = mysql_query($strqry) or die(mysql_error());
		while ($rowDb = mysql_fetch_array($query)) {
			$custID = $rowDb['cust_id'];
			$custEmail = $rowDb['email'];
			$dbName2 = $rowDb['dbname'];
			$orderId = $rowDb['order_id'];
			$beginDate = $rowDb['begin_date'];
			$endDate = $rowDb['end_date'];
			$tglmulai = strtotime($data['begin_date']);
			$tglakhir = strtotime($data['end_date']);
			$subscribe = $rowDb['subscribe_days'];		
			$expired = $rowDb['expired_days'];
			$expDate = $rowDb['expired_date'];
			$package = $rowDb['package_id'];
			$package_type = $rowDb['package_type'];
			$invID = $rowDb['inv_id'];
			$used = $rowDb['used'];
			$subscribe_month = $rowDb['value_month'];
			//$subscribe_times = $rowDb['subscribe_times']; //ind.subscribe_times, 
			$verID = $rowDb['ver_id'];
			$package_price = $rowDb['package_price'];
			$subscribeID = $rowDb['subscribe_id'];
			$tglDB = $rowDb['db_date'];
			$created_on = $rowDb['created_on'];
			$total_branch = $rowDb['total_branch'];
			$add_branch = $total_branch - 1;
		
			// Mendapatkan jumlah bulan berlangganan
			//if($package_type == 1) {
			//	$valmonth = 1;
			//} else {				
			//	$tglmulai = new DateTime($now);
			//	$tglakhir = new DateTime($endDate);
			//	$inthari = $tglmulai->diff($tglakhir);
			//	$valbulan = $inthari/30;
			//	$valmonth = ceil($valbulan);
			//}
			
			// Mendapatkan jumlah bulan berlangganan
			if($package_type == 1) {
				$valmonth = 1;
				$cntInduk = 1;
				$valmonth2 = $subscribe_month;
			} else {
				$valmonth = $valmonth2; //$subscribe_month;
				/*
				$tglmulai = strtotime(date("Y-m-d"));
				$tglakhir = strtotime($endDate);
				$inthari = ($tglakhir - $tglmulai)/60/60/24;
				$valbulan = $inthari/30;
				$valmonth = ceil($valbulan);
				if($valmonth > 12) {
					$valmonth = 12;
				}
				*/
			}
			
			$amount2 = 0;
			/*if(strtotime($created_on) < strtotime('2021-02-01 00:00:00')) {
				$qPr = "SELECT price FROM package  
						WHERE id = '$package'";
			} else if((strtotime($created_on) >= strtotime('2021-02-01 00:00:00')) and (strtotime($created_on) < strtotime('2021-11-01 00:00:00'))) {
				$qPr = "SELECT 
						(SELECT pr.price AS price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date <= '$created_on'
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						WHERE p.id = '$package'";
			} else if((strtotime($created_on) >= strtotime('2021-11-01 00:00:00')) and (strtotime($created_on) < strtotime('2022-01-01 00:00:00'))) {
				$qPr = "SELECT 
						(SELECT pr.price AS price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= '$created_on'  
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						WHERE p.id = '$package'";
			} else {
				$qPr = "SELECT 
						(SELECT pr.price AS price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
						ORDER BY pr.begin_date DESC LIMIT 1) AS price 
						FROM package p 
						WHERE p.id = '$package'";
			}*/
			$qPr = "SELECT 
					(SELECT pr.price AS price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.id = '$package'";
			$rPr = mysql_query($qPr);
			$dPr = mysql_fetch_array($rPr);
			//$amount2 = $dPr['price'] * $valmonth; 
			$amount2 = $dPr['price'];
			//$amount2 = $package_price * $valmonth;

			if($dbname == 'db') {	//Rotary Created: 2020-09-08
				if($package_type == 1) {
					$amount2 = '350000';
				} else if($package_type == 2) {
					$amount2 = '100000';
				} else if($package_type == 4) {
					$amount2 = '100000';
				} else if($package_type == 5) {
					$amount2 = '18000';
				} 
			}
			
			/*$qPr = "SELECT price FROM package  
					WHERE id = '$package'";
			$rPr = mysql_query($qPr) or die(mysql_error());
			$dPr = mysql_fetch_array($rPr);
			$price = $dPr['price'];
			$amount2 = $dPr['price'] * $valmonth; 
			$ppn = $amount2 * 10 / 100; 
			$package_id = $dPr['package_id'];*/

			//Harga Tambah Cabang
			$packageBr = '19';
			if($verID == '1') {
				$packageBr = '19';
			} else if($verID == '2') {
				$packageBr = '20';
			} else if($verID == '3') {
				$packageBr = '21';
			}
			$amount_br = 0;
			$qPrb = "SELECT 
					(SELECT pr.price AS price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() AND pr.package_id = '$packageBr' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.id = '$packageBr'";
			$rPrb = mysql_query($qPrb);
			$dPrb = mysql_fetch_array($rPrb);
			$amount_br = $dPrb['price'];

			if($dbname == 'db') {	//Rotary Created: 2020-09-08
				$amount_br = '18000';
			}

			// cek apakah order id di invoice detail 
			$sqlIv = mysql_query("SELECT iv.inv_no, iv.paid_off, ind.* 
									FROM invoice_detail ind  
									JOIN invoice iv ON iv.id = ind.inv_id  
									WHERE ind.order_id = '$orderId'
									AND iv.stsrec = 'A' 
									ORDER BY ind.id DESC LIMIT 1") or die(mysql_error()); 		
			$rowIv = mysql_fetch_array($sqlIv);
			
			if (empty($rowIv)){	// jika order id tidak ada di invoice detail 
				
				if($used == 'T') {				  				
					$beginDate2 = date('Y-m-d', strtotime('+1 days', strtotime($endDate)));
					$endDate2 = date('Y-m-d', strtotime('+'.$expired.' days', strtotime($beginDate2)));
					$expDate2 = date('Y-m-d', strtotime('+'.$expday.' days', strtotime($endDate2)));
					// update file identity di cust
					$updCust2 = mysql_query("UPDATE cust_order SET file_identity = ' ' WHERE order_id = '$orderId'") or die(mysql_error());	
				} else {	
					$beginDate2 = date('Y-m-d', strtotime('+1 days', strtotime($endDate)));
					$endDate2 = date('Y-m-d', strtotime('+'.$expired.' days', strtotime($beginDate2)));
					$expDate2 = date('Y-m-d', strtotime('+'.$expday.' days', strtotime($endDate2)));
				} 
				
				if($dbNameBefore != $dbName2) {
					
					$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
							FROM invoice
							WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP'";
					$rNO = mysql_query($qNO);
					$dNO = mysql_fetch_array($rNO);
					if ($dNO['max_no'] == 0){
						$seq = 1;
					}else{
						$seq = $dNO['max_no'] + 1;
					}
					$no = 'FP'.date("Ym").sprintf("%06d", $seq);
					
					$initialMount = 0; //rand(10,99);
					
					// tambah data ke tabel invoice 
					$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, initial_amount, ppn, voucher_code) VALUES ('$no', '$now', '$due_date', 'IDR', '0', '0', '0000-00-00', 'N', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$custID', '$initialMount', '0', '')") or die(mysql_error());
					
					$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
					$invoiceID = $invId['last_id'];
					
					$totamount = 0;

					if($total_branch > 1) {
						$total_amount_br = 0;
						$ppn_br = 0;
						$qNOb = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
								FROM invoice
								WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP'";
						$rNOb = mysql_query($qNOb);
						$dNOb = mysql_fetch_array($rNOb);
						if ($dNOb['max_no'] == 0){
							$seqb = 1;
						}else{
							$seqb = $dNOb['max_no'] + 1;
						}
						$nob = 'FP'.date("Ym").sprintf("%06d", $seqb);
						$total_amount_br = $add_branch * $valmonth * $amount_br;
						//if($beginDate2 < '2022-04-01') {
						if(date('Y-m-d') < '2022-04-01') {
							$ppn_br = (10*$total_amount_br)/100;
						} else {
							$ppn_br = (11*$total_amount_br)/100;
						}

						// tambah data ke tabel invoice 
						$insInvB = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, initial_amount, ppn, voucher_code) VALUES ('$nob', '$now', '$due_date', 'IDR', '$total_amount_br', '0', '0000-00-00', 'N', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$custID', '$initialMount', '$ppn_br', '')") or die(mysql_error());
						
						$invIdB = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
						$invoiceIDb = $invIdB['last_id'];

						// tambah data ke tabel invoice_detail 
						$insInvBd = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`,`order_idlama`, `add_branch`) VALUES ('$invoiceIDb', '$orderId', '$beginDate2', '$endDate2', '$expDate2', '$expday', 'IDR', '$amount_br', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$packageBr', '$valmonth', '', '$add_branch')") or die(mysql_error());
					}
				
				} else {
					
					$invoiceID = $invoiceIDBefore;
				
				}
				
				// tambah data ke tabel invoice_detail 
				$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`,`order_idlama`, `add_branch`) VALUES ('$invoiceID', '$orderId', '$beginDate2', '$endDate2', '$expDate2', '$expday', 'IDR', '$amount2', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$package', '$valmonth', '', '0')") or die(mysql_error());
				
				$totamount = $totamount + ($amount2*$valmonth);
				//if($beginDate2 < '2022-04-01') {
				if(date('Y-m-d') < '2022-04-01') {
					$ppn = (10*$totamount)/100;
				} else {
					$ppn = (11*$totamount)/100;
				}
				
				$updInv = mysql_query("UPDATE invoice SET total_amount = '$totamount', ppn = '$ppn' WHERE id = '$invoiceID'") or die(mysql_error());	

				/*$sqlI2 = mysql_query("SELECT begin_date, end_date, expired_date 
									FROM invoice_detail   
									WHERE inv_id = '$invoiceID' 
									AND package_id <= 12") or die(mysql_error()); 		
				$rowI2 = mysql_fetch_array($sqlI2);
				$begindate_ = $rowI2['begin_date'];
				$enddate_ = $rowI2['end_date'];
				$expireddate_ = $rowI2['expired_date'];

				if($package > 12 and $cntInduk > 0) {
					$updInv = mysql_query("UPDATE invoice_detail SET begin_date = '$begindate_', end_date = '$enddate_', expired_date = '$expireddate_' WHERE inv_id = '$invoiceID'") or die(mysql_error());	
				}*/
			
			} else {
							
				$orderInv = $rowIv['order_id'];
				$beginDateInv = $rowIv['begin_date'];
				$endDateInv = $rowIv['end_date'];
				
				if($used == 'T') {				  				
					$beginDate2 = date('Y-m-d', strtotime('+1 days', strtotime($endDate)));
					$endDate2 = date('Y-m-d', strtotime('+'.$expired.' days', strtotime($beginDate2)));
					$expDate2 = date('Y-m-d', strtotime('+'.$expday.' days', strtotime($endDate2)));
					// update file identity di cust
					$updCust2 = mysql_query("UPDATE cust_order SET file_identity = ' ' WHERE order_id = '$orderId'") or die(mysql_error());	
				} else {	
					$beginDate2 = date('Y-m-d', strtotime('+1 days', strtotime($endDate)));
					$endDate2 = date('Y-m-d', strtotime('+'.$expired.' days', strtotime($beginDate2)));
					$expDate2 = date('Y-m-d', strtotime('+'.$expday.' days', strtotime($endDate2)));
				}
				
				if(($orderId == $orderInv) and ($beginDateInv != $beginDate2)) {
					
					if($dbNameBefore != $dbName2) {
				
						$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
								FROM invoice
								WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP'";
						$rNO = mysql_query($qNO);
						$dNO = mysql_fetch_array($rNO);
						if ($dNO['max_no'] == 0){
							$seq = 1;
						}else{
							$seq = $dNO['max_no'] + 1;
						}
						$no = 'FP'.date("Ym").sprintf("%06d", $seq);
						
						$initialMount = 0; //rand(10,99);
						
						// tambah data ke tabel invoice 
						$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, initial_amount, ppn, voucher_code) VALUES ('$no', '$now', '$due_date', 'IDR', '0', '0', '0000-00-00', 'N', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$custID', '$initialMount', '0', '')") or die(mysql_error());
						
						$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
						$invoiceID = $invId['last_id'];
						
						$totamount = 0;

						if($total_branch > 1) {
							$total_amount_br = 0;
							$ppn_br = 0;
							$qNOb = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
									FROM invoice
									WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP'";
							$rNOb = mysql_query($qNOb);
							$dNOb = mysql_fetch_array($rNOb);
							if ($dNOb['max_no'] == 0){
								$seqb = 1;
							}else{
								$seqb = $dNOb['max_no'] + 1;
							}
							$nob = 'FP'.date("Ym").sprintf("%06d", $seqb);
							$total_amount_br = $add_branch * $valmonth * $amount_br;
							//if($beginDate2 < '2022-04-01') {
							if(date('Y-m-d') < '2022-04-01') {
								$ppn_br = (10*$total_amount_br)/100;
							} else {
								$ppn_br = (11*$total_amount_br)/100;
							}

							// tambah data ke tabel invoice 
							$insInvB = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, initial_amount, ppn, voucher_code) VALUES ('$nob', '$now', '$due_date', 'IDR', '$total_amount_br', '0', '0000-00-00', 'N', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$custID', '$initialMount', '$ppn_br', '')") or die(mysql_error());
							
							$invIdB = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
							$invoiceIDb = $invIdB['last_id'];

							// tambah data ke tabel invoice_detail 
							$insInvBd = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`,`order_idlama`, `add_branch`) VALUES ('$invoiceIDb', '$orderId', '$beginDate2', '$endDate2', '$expDate2', '$expday', 'IDR', '$amount_br', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$packageBr', '$valmonth', '', '$add_branch')") or die(mysql_error());
						}
					
					} else {
						
						$invoiceID = $invoiceIDBefore;
					
					}
					
					// tambah data ke tabel invoice_detail 
					$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`) VALUES ('$invoiceID', '$orderId', '$beginDate2', '$endDate2', '$expDate2', '$expday', 'IDR', '$amount2', '$_SESSION[custID]', NOW(), '$_SESSION[custID]', NOW(), '$package', '$valmonth')") or die(mysql_error());		
					$totamount = $totamount + ($amount2*$valmonth);
					//if($beginDate2 < '2022-04-01') {
					if(date('Y-m-d') < '2022-04-01') {
						$ppn = (10*$totamount)/100;
					} else {
						$ppn = (11*$totamount)/100;
					}
					
					$updInv = mysql_query("UPDATE invoice SET total_amount = '$totamount', ppn = '$ppn' WHERE id = '$invoiceID'") or die(mysql_error());

					/*$sqlI2 = mysql_query("SELECT begin_date, end_date, expired_date 
									FROM invoice_detail   
									WHERE inv_id = '$invoiceID' 
									AND package_id <= 12") or die(mysql_error()); 		
					$rowI2 = mysql_fetch_array($sqlI2);
					$begindate_ = $rowI2['begin_date'];
					$enddate_ = $rowI2['end_date'];
					$expireddate_ = $rowI2['expired_date'];

					if($package > 12 and $cntInduk > 0) {
						$updInv = mysql_query("UPDATE invoice_detail SET begin_date = '$begindate_', end_date = '$enddate_', expired_date = '$expireddate_' WHERE inv_id = '$invoiceID'") or die(mysql_error());	
					}*/
					
				}
					
			}
			
			$dbNameBefore = $dbName2;
			$invoiceIDBefore = $invoiceID;
		}
		
		if ($updInv) {
			//$host = $_SERVER['HTTP_HOST'];
			//$emailOwner = $_SESSION['custEmail'];
			//$subject = 'Tagihan Siscom Online';
			//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invoiceID");
			//$pesan = file_get_contents("$abs/reg/pages/tagihan_email.php?inv_id=$invoiceID");
			//$username = 'finance@siscomonline.co.id';
			//$emailrec = mysql_fetch_array(mysql_query("SELECT name, password FROM sysemail WHERE email = '$username'"));
			//$name = $emailrec['name'];
			//$password = $emailrec['password'];
			//$passwordDecrypt = decrypt($ENCRKEY, $password);
			//sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name);
			
			$result = array('status'=>'success');	
		}
		
	}
	
	else if ($_POST['action']=='hapustagihan') {		
		$emailUser3 = $_POST['emailUser'];
		$dbname = $_POST['dbname'];
		$ver_id = $_POST['ver_id'];
		$sqlCust3 = mysql_query("SELECT id FROM cust WHERE email = '$emailUser3'") OR die(mysql_error());
		$data3 = mysql_fetch_array($sqlCust3);
		$cust_id3 = $data3['id'];

		$delete2 = mysql_query("UPDATE cust_order SET used = 'D', active = 'D' WHERE cust_id = '$cust_id3' AND dbname = '$dbname' AND ver_id = '$ver_id'") or die(mysql_error());
		
		if ($delete2) {
			$result = array('status'=>'success');	
		}	
	}

	else if ($_POST['action']=='bataltagihan') {		
		$inv_no = $_POST['inv_no'];

		$batal = mysql_query("UPDATE invoice SET stsrec = 'D', paid_off = 'N' WHERE inv_no = '$inv_no'") or die(mysql_error());
		
		if ($batal) {
			$result = array('status'=>'success');	
		}	
	}
	
	else if ($_POST['action']=='tambahtagihan') {		
		$emailUser4 = $_POST['emailUser'];
		$dbname = $_POST['dbname'];
		$ver_id = $_POST['ver_id'];
		$sqlCust4 = mysql_query("SELECT id FROM cust WHERE email = '$emailUser4'") OR die(mysql_error());
		$data4 = mysql_fetch_array($sqlCust4);
		$cust_id4 = $data4['id'];
		
		//Buat invoice
		
		$hash = md5(rand(0,1000));
		$now = date('Y-m-d H:i:s');
		$email = $_POST['emailUser'];
		$level = $_POST['level'];
		$dbname = strtolower($_POST['dbname']);
		$versi = $_POST['ver_id'];
		$endDate = $_POST['enddate'];
		
		$qVer = "SELECT name FROM version 
					WHERE id = '".$versi."'";
		$rVer = mysql_query($qVer) or die(mysql_error());
		$dVer = mysql_fetch_array($rVer);
		$namaversi = $dVer['name'];

		$qDB = "SELECT DISTINCT db_date, created_on 
				FROM cust_order 
				WHERE dbname = '".$dbname."' 
				AND db_date != '0000-00-00'";
		$rDB = mysql_query($qDB) or die(mysql_error());
		$dDB = mysql_fetch_array($rDB);
		$tglDB = $dDB['dbdate'];
		$created_on = $dDB['created_on'];
		
		$custID = $_SESSION['custID'];
		$passAcak = substr(str_shuffle(MD5(microtime())), 0,6);
		$password = password_hash($email.$passAcak,PASSWORD_DEFAULT);
		
		$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
				FROM invoice
				WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
		$rNO = mysql_query($qNO) or die(mysql_error());
		$dNO = mysql_fetch_array($rNO);
		if ($dNO['max_no'] == 0){
			$seq = 1;
		}else{
			$seq = $dNO['max_no'] + 1;
		}
		$no = 'FP'.date("Ym").sprintf("%06d", $seq);

		// Mendapatkan jumlah bulan berlangganan
		if(strtotime(date("Y-m-d")) < strtotime($tglawalInduk)) {
			$tglmulai = strtotime($tglawalInduk);
		} else {
			$tglmulai = strtotime(date("Y-m-d"));
		}
		$tglakhir = strtotime($tglakhirInduk);
		$inthari = ($tglakhir - $tglmulai)/60/60/24;
		$valbulan = $inthari/30;
		$valmonth = ceil($valbulan);
		if($valmonth > 12) {
			$valmonth = 12;
		}
		
		//if($tglDB < '2021-02-01') {
		/*if($created_on < '2021-02-01') {
			$qPr = "SELECT price, package.id as package_id FROM package
					WHERE package_type = 2 AND subscribe_id ='1' AND ver_id = '".$versi."'";
		} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2021-11-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2021-11-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 2 AND p.subscribe_id = '1' AND p.ver_id = '".$versi."'";
		} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '2022-01-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '2022-01-01' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 2 AND p.subscribe_id = '1' AND p.ver_id = '".$versi."'";
		} else {
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 2 AND p.subscribe_id = '1' AND p.ver_id = '".$versi."'";
		}*/
		$qPr = "SELECT p.id AS package_id,
				(SELECT pr.price AS price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				WHERE p.package_type = 2 AND p.subscribe_id = '1' AND p.ver_id = '".$versi."'";
		$rPr = mysql_query($qPr) or die(mysql_error());
		$dPr = mysql_fetch_array($rPr);
		$effdate = $dPr['begin_date'];
		$amount = $dPr['price']; 

		if($dbname == 'db') {	//Rotary Created: 2020-09-08
			$amount = '100000';
		}

		//$ppn = $amount * 10 / 100; 
		if(date('Y-m-d') < '2022-04-01') {
			$ppn = (10*$amount)/100;
		} else {
			$ppn = (11*$amount)/100;
		}
		$package_id = $dPr['package_id'];
		
		$initialMount = 0; //rand(10,99);
		
		$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, `initial_amount`, `ppn`) VALUES ('$no', '$now', '$endDate', 'IDR', '$amount', '0', '0000-00-00', 'N', 'admin', NOW(), 'admin', NOW(), '$custID', '$initialMount', '$ppn')") or die(mysql_error());

		$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
		$cs = mysql_query("SELECT order_id, expired_date, expired_days, company_id FROM cust_order where cust_id='$_SESSION[custID]' and dbname = '$dbname'") or die(mysql_error());
		$orderId = mysql_fetch_array($cs);
		
		$companyId = $orderId['company_id'];	
		$orderId2 = $orderId['order_id'];
		$expDate= $orderId['expired_date'];
		$expired = $orderId['expired_days'];
		$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`) VALUES ('$invId[last_id]', '$orderId2', '$now', '$endDate', '$expDate', '$expired', 'IDR', '$amount', 'admin', NOW(), 'admin', NOW(), '$package_id')") or die(mysql_error());

		//masukkan username,email,pass owner kedalam tb sysuser
		//$koneksi4 = mysqli_connect($GLOBALS['hostname2'],$GLOBALS['username2'], $GLOBALS['password2'],$dbname.'_acc') or die(mysqli_connect_error());

		//$id = rand(10,99);
		//$usid = substr($dbname,0,1).$id;
		//$level = ($level=='A') ? '1' : '2';
		
		//mysqli_query($koneksi4, "INSERT INTO SYSUSER (USID, PASSWORD, USERNAME, EMAIL, USLEVEL) VALUES ('$usid', '$password', '$email', '$email', '$level')") or die(mysqli_error($koneksi4));
		
		//OLD QUERY	
		//mysqli_query($koneksi4, "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, DOE, LOE, DEO) SELECT '".$usid."', LADD, LAPD, URUT, NOW(), '', '".$usid."' FROM SYSMENU WHERE LEVEL >= ".$level." AND URUT > 0") or die(mysqli_error($koneksi4));
		
		//NEW QUERY
		//mysqli_query($koneksi4, "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, DOE, LOE, DEO) SELECT '".$usid."', S1.LADD, S2.LAPD, S2.URUT, NOW(), '', '".$usid."' FROM SYSMENUGROUP S1 JOIN SYSMENU S2 ON S1.LADD = S2.LADD WHERE UPPER(S1.GRID) = '".strtoupper($namaversi)."'") or die(mysqli_error($koneksi4));
		
		$host = $_SERVER['HTTP_HOST'];
		//kirim konfirmasi ke user yang ditambahkan
		$subject = 'Konfirmasi Email SISCOM Online';
		//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
		//$pesan = file_get_contents("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
		$pesan = url_get_content("$abs/reg/pages/template_confirm.php?email=$email&hash=$hash&password=$passAcak");
		//sendEmail($email, $pesan, $subject); 
		$username = 'finance@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'financeoke515'; //decrypt($ENCRKEY, $password);
		sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name);

		/*KIRIM EMAIL KE OWNER*/
		$emailOwner = $_SESSION['custEmail'];
		//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		//$pesan = file_get_contents("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		$pesan = url_get_content("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");

		//ob_start();

		$lampiran = 'tagihan.pdf';
		$subject = 'Tagihan SISCOM Online';
		//sendEmail2($emailOwner, $pesan, $subject, $invId['last_id'], $lampiran); //kirim tagihan ke email owner	
		$username2 = 'finance@siscomonline.co.id';
		$emailrec2 = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username2'"));
		$name2 = $emailrec2['name'];
		$password2 = $emailrec2['password'];
		$passwordDecrypt2 = 'financeoke515'; //decrypt($ENCRKEY, $password2);
		sendEmail($emailOwner, $pesan, $subject, $username2, $passwordDecrypt2, $name2, $invId['last_id'], $lampiran); //kirim tagihan ke email owner
		// unlink($lampiran); //hapus file yang sudah dibuat

		//if ($insert && $insert2) {
		//	$result = array('status'=>'success');
		//}		
		
		//$tambahtagihan = mysql_query("UPDATE cust_order SET active = 'A' WHERE cust_id = '$cust_id4' AND dbname = '$dbname' AND ver_id = '$ver_id'") or die(mysql_error());
		
		//if ($tambahtagihan) {
		if ($insInv) {
			$result = array('status'=>'success');	
		}	
	}
	
	else if($_POST['action']=='nonaktifuser'){
		//db siserp
		$host = $_SERVER['SERVER_ADDR'];
		$hostname2 = $GLOBALS['hostname2'];
		$username2 = $GLOBALS['username2'];
		$password2 = $GLOBALS['password2'];

		$emailUser = $_POST['emailUser'];
		$dbname = $_POST['dbname'];
		$ver_id = $_POST['ver_id'];

		//koneksi siserp
		$dbName = strtolower($dbname).'_acc';
		$koneksi = mysqli_connect($hostname2,$username2, $password2,$dbName) or die(mysqli_connect_error());


		$sqlCust = mysql_query("SELECT id FROM cust WHERE email = '$emailUser'") OR die(mysql_error());
		$data = mysql_fetch_array($sqlCust);
		$cust_id = $data['id'];

		$nonaktif = mysql_query("UPDATE cust_order SET active = 'D' WHERE cust_id = '$cust_id' AND dbname = '$dbname' AND ver_id = '$ver_id'") or die(mysql_error());
		
		if ($nonaktif) {
			// update sysuser
			mysqli_query($koneksi, "UPDATE SYSUSER SET AKTIF = 'N' WHERE EMAIL = '$emailUser'") or die(mysqli_error($koneksi));

			$result = array('status'=>'success');	
		}	
	}
	
	else if($_POST['action']=='aktifuser'){
		//db siserp
		$host = $_SERVER['SERVER_ADDR'];
		$hostname2 = $GLOBALS['hostname2'];
		$username2 = $GLOBALS['username2'];
		$password2 = $GLOBALS['password2'];

		$emailUser2 = $_POST['emailUser'];
		$dbname = $_POST['dbname'];
		$ver_id = $_POST['ver_id'];

		//koneksi siserp
		$dbName = strtolower($dbname).'_acc';
		$koneksi = mysqli_connect($hostname2,$username2, $password2,$dbName) or die(mysqli_connect_error());

		$sqlCust2 = mysql_query("SELECT id FROM cust WHERE email = '$emailUser2'") OR die(mysql_error());
		$data2 = mysql_fetch_array($sqlCust2);
		$cust_id2 = $data2['id'];

		$aktif = mysql_query("UPDATE cust_order SET active = 'A' WHERE cust_id = '$cust_id2' AND dbname = '$dbname' AND ver_id = '$ver_id'") or die(mysql_error());
		
		if ($aktif) {
			// update sysuser
			mysqli_query($koneksi, "UPDATE SYSUSER SET AKTIF = 'Y' WHERE EMAIL = '$emailUser2'") or die(mysqli_error($koneksi));
			
			$result = array('status'=>'success');	
		}	
	}
	
	else if($_POST['action']=='updateLevel'){
		//db siserp
		$host = $_SERVER['SERVER_ADDR'];
		$hostname2 = $GLOBALS['hostname2'];
		$username2 = $GLOBALS['username2'];
		$password2 = $GLOBALS['password2'];

		$email = $_POST['email'];
		$dbname = $_POST['dbname'];
		$cust_id = $_POST['cust_id'];
		$custLevel = $_POST['custLevel'];
		$uslevel = $_POST['uslevel'];

		//koneksi siserp
		$dbName = strtolower($dbname).'_acc';
		$koneksi = mysqli_connect($hostname2,$username2, $password2,$dbName) or die(mysqli_connect_error());

		$update = mysql_query("UPDATE cust_order SET ctype = '$custLevel' WHERE cust_id = '$cust_id' AND dbname = '$dbname'") or die(mysql_error());
		
		if ($update) {
			// update sysuser
			mysqli_query($koneksi, "UPDATE SYSUSER SET USLEVEL = '$uslevel' WHERE EMAIL = '$email'") or die(mysqli_error($koneksi));
			
			$result = array('status'=>'success');	
		}	
	}

	else if ($_POST['action']=='ambilvoucher') {
		$kode_voucher = $_POST['kode_voucher'];
		$now = date('Y-m-d');
		if($_POST['jenis']=='voucherlama'){
			$sql = mysql_query("SELECT value, minimum_amount FROM `voucher` WHERE name='$kode_voucher'") or die(mysql_error());
		}
		else{
			$sql = mysql_query("SELECT value, minimum_amount FROM `voucher` WHERE name='$kode_voucher' AND stsrec='A' AND (expired_date>=$now AND begin_date>=$now)") or die(mysql_error());
		}

		$data= mysql_fetch_array($sql);
		if ($data) {
			$result = array('status'=>'success', 'data'=> $data);
		}
		else{
			$result = array('status'=>'failed', 'data'=>['minimum_amount'=>0]);	
		}
	}

	elseif ($_POST['action']=='ambilactivate') {
		$kode_aktivasi = $_POST['kode_aktivasi'];
		$dbname = $_POST['dbname'];
		$sql = mysql_query("SELECT code, s.value FROM `activate` a JOIN subscribe s ON a.subscribe_id=s.id where a.name='$kode_aktivasi' and a.stsrec='A'") or die(mysql_error());
		$data = mysql_fetch_array($sql);

		$sql = mysql_query("select * from cust_order where cust_id='$_SESSION[custID]' and dbname = '$dbname'") or die(mysql_error());

		$data2 = mysql_fetch_array($sql);
		$end_date = $data2['end_date'];
		$newEnd_date = date('d-M-Y', strtotime("+".$data['value']." days", strtotime($end_date)));
			
		if ($data) {
			$result = array('status'=>'success', 'data'=> $data, 'end_date'=>$newEnd_date);
		}
		else{
			$result = array('status'=>'failed');	
		}
	}

	elseif ($_POST['action'] == 'simpaninvoice') {
		// print_r($_POST);
		$dbname = $_POST['dbname'];
		$voucher = (isset($_POST['kode_voucher'])) ? $_POST['kode_voucher'] : '';
		$order_id = $_POST['order_id'];
		$beginDate = $_POST['begin_date'];
		$endDate = $_POST['end_date'];
		$used = $_POST['used'];
		
		// $activate = $_POST['activate'];

		// if ($activate != '') {
		// 	$sql = mysql_query("select * from cust_order where cust_id='$_SESSION[custID]' and dbname = '$dbname'") or die(mysql_error());

		// 	$data 			= mysql_fetch_array($sql);
		// 	$end_date 		= $data['end_date'];
		// 	$expired_date 	= $data['expired_date'];

		// 	$qtrial = mysql_query("SELECT * FROM sysdata WHERE id=2") or die(mysql_error());
		// 	if (mysql_num_rows($qtrial)){
		// 		while ($rtrial = mysql_fetch_array($qtrial)) {
		// 				$expired = $rtrial['value'];
		// 		}
		// 	}
		// 	$newEnd_date	= date('Y-m-d', strtotime("+".$activate." days", strtotime($end_date)));
		// 	$newExp_date	= date('Y-m-d', strtotime("+".$expired." days", strtotime($newEnd_date)));

		// 	$updateCo = mysql_query("update cust_order set end_date='$newEnd_date', expired_date='$newExp_date', used='R' where cust_id='$_SESSION[custID]' and dbname = '$dbname'") or die(mysql_error());

		// 	mysql_query("update voucher set stsrec='U' where code='$voucher'") or die(mysql_error());
		// 	// mysql_query("update activate set stsrec='U' where code='$activate'") or die(mysql_error());

		// 	echo "<script type='text/javascript'>
		//           alert('Data successfully activated');
		//           window.location='account.php';
		//         ;
		// }
		// else{
		$ppn 	= $_POST['pajak'];
		$package = $_POST['package'];
		$amount = $_POST['amount'];
		$custID = $_SESSION['custID'];
		$faktur = (isset($_POST['faktur']) && $_POST['faktur']=='Y') ? 1 : 0;
		$jenisfaktur = ($faktur ==1 ) ? $_POST['jenisfaktur'] : '';

		$gambar     		= $_FILES['ktp']['tmp_name'];
		$gambar2     		= $_FILES['npwp']['tmp_name'];

		$namagambarktp='';
		if ($gambar !='') {
			$acak1=rand(000000,999999);
			$acak2=rand(000000,999999);
			$acak3=rand(000000,999999);
			$acak=$acak1."_".$acak2."_".$acak3;

			$namagambarktp = "siscom-".$acak.".jpg";
			$upload = "../../../images/payment/".$namagambarktp;
	 
			move_uploaded_file($gambar,$upload);	        	
		}

		$namagambarnpwp='';
		if ($package !='') {
			$acak1=rand(000000,999999);
			$acak2=rand(000000,999999);
			$acak3=rand(000000,999999);
			$acak=$acak1."_".$acak2."_".$acak3;

			$namagambarnpwp = "siscom-".$acak.".jpg";
			$upload = "../../../images/payment/".$namagambarnpwp;
	 
			move_uploaded_file($gambar2,$upload);	        	
		}

		$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
				FROM invoice
				WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
		$rNO = mysql_query($qNO);
		$dNO = mysql_fetch_array($rNO);
		if ($dNO['max_no'] == 0){
			$seq = 1;
		}else{
			$seq = $dNO['max_no'] + 1;
		}
		$no = 'FP'.date("Ym").sprintf("%06d", $seq);
		
		//$qPr = "SELECT price FROM package 
		//		WHERE id = '$package'";
		$qPr = "SELECT 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				WHERE p.id = '$package'";
		$rPr = mysql_query($qPr);
		$dPr = mysql_fetch_array($rPr);
		$amount2 = $dPr['price'];
		//$amount2 = $dPr['price'] * $valmonth; 

		$updateCo = mysql_query("UPDATE cust_order SET inv_tax = '$faktur', taxpayer_id = '$jenisfaktur', file_identity = '$namagambarktp', package_id = '$package', file_tax = '$namagambarnpwp' WHERE cust_id = '$_SESSION[custID]' AND dbname = '$dbname'") or die(mysql_error());
		
		/*ambil data cust order*/
		$cs = mysql_query("SELECT * FROM cust_order WHERE cust_id = '$_SESSION[custID]' AND dbname = '$dbname'") or die(mysql_error());
		$orderId = mysql_fetch_array($cs);
		
		$orderId2 = $orderId['order_id'];
		//$expDate = $orderId['expired_date'];
		//$expired = $orderId['expired_days'];

		$sqlD = mysql_query("SELECT * FROM sysdata WHERE id=4") or die(mysql_error());
		$due_date = mysql_fetch_array($sqlD);
		$batas = $due_date['value'];
		$now = date('Y-m-d');
		$due_date = date('Y-m-d', strtotime('+'.$batas.' days', strtotime($now))) ;
		
		$initialMount = 0; //rand(10,99);
		
		$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, initial_amount, voucher_code, ppn) VALUES ('$no', '$now', '$due_date', 'IDR', '$amount', '0', '0000-00-00', 'N', 'admin', NOW(), 'admin', NOW(), '$custID', '$initialMount', '$voucher', '$ppn')") or die(mysql_error());
		
		$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
		
		/* simpan paket 1 database + 1 user di invoice detail*/
		$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`) VALUES ('$invId[last_id]', '$orderId2', '$beginDate', '$endDate', '$expDate', '$expired', 'IDR', '$amount2', 'admin', NOW(), 'admin', NOW(), '$package')") or die(mysql_error());

		/* simpan paket 1 user di invoice detail*/
		$qCo = mysql_query("select *, p.id as package_id from cust_order co JOIN package p ON co.package_id=p.id where co.dbname='$dbname' and p.package_type='2'") or die(mysql_error());
		while ($dataCO = mysql_fetch_array($qCo)) {
			$amount = $dataCO['price'];
			$package_id = $dataCO['package_id'];
			$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`) VALUES ('$invId[last_id]', '$orderId2', '$beginDate', '$endDate', '$expDate', '$expired', 'IDR', '$amount', 'admin', NOW(), 'admin', NOW(), '$package_id')") or die(mysql_error());
		}

		mysql_query("UPDATE voucher SET stsrec='U' WHERE code='$voucher' AND multi =0") or die(mysql_error());
		// mysql_query("update activate set stsrec='U' where code='$activate'") or die(mysql_error());
		// }

		// $data = http_build_query(
		// 	array(
		// 		"inv_id" => $invId['last_id']
		// 	)
		// );

		// $opts = array(
		// 	"http" => array(
		// 		"method" => "GET",
		// 		"header" => "Content-Type: application/x-www-form-urlencode",
		// 		"content" => $data
		// 	)
		// );

		// $context = stream_context_create($opts);

		// $invoice = $invId['last_id'];
		$host = $_SERVER['HTTP_HOST'];
		$emailOwner = $_SESSION['custEmail'];
		$subject = 'Tagihan Siscom Online';
		//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		//$pesan = file_get_contents("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		$pesan = url_get_content("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		//sendEmail($emailOwner, $pesan, $subject); //kirim tagihan ke email
		$username = 'finance@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'financeoke515'; //decrypt($ENCRKEY, $password);
		sendEmail($email, $pesan, $subject, $username, $passwordDecrypt, $name);
		
		$result = array("status"=>'success');
		echo "<script type='text/javascript'>
           		alert('Data successfully added');
           		window.location='info_tagihan.php?dbname=$dbname';
        	  </script>";
		
	}

	elseif ($_POST['action'] == 'saveinvoice') {
		$dbname = $_POST['dbname'];
		$inv_id = $_POST['inv_id'];

		$voucher = (isset($_POST['kode_voucher'])) ? strtoupper($_POST['kode_voucher']) : '';
		$amount = $_POST['amount'];
		$discount = $_POST['val_discount'];
		$ppn 	= $_POST['pajak'];

		$ppn_value = ($amount - $discount) * (11/100);

		mysql_query("UPDATE invoice SET discount = '$discount', ppn = '$ppn_value', voucher_code = '$voucher' WHERE id = '$inv_id'") or die(mysql_error());

		mysql_query("UPDATE voucher SET stsrec = 'U' WHERE name = '$voucher' AND multi = '0'") or die(mysql_error());

		$result = array("status"=>'success');
		echo "<script type='text/javascript'>
				alert('Data successfully added');
				window.location='info_tagihan.php?dbname=$dbname';
			  </script>";
	}

	elseif ($_POST['action'] == 'editinvoice') {
		$dbname = $_POST['dbname'];
		// $activate = $_POST['activate'];
		$voucher = (isset($_POST['kode_voucher'])) ? strtoupper($_POST['kode_voucher']) : '';
		$invoice = $_POST['invoice'];
		$beginDate = $_POST['begin_date'];
		$endDate = date('Y-m-d', strtotime($_POST['end_date']));
		$jmlh_hari = $_POST['jmlh_hari'];

		$package = isset($_POST['package']) ? $_POST['package'] : '';
		$amount = $_POST['amount'];
		$custID = $_SESSION['custID'];
		$ppn 	= $_POST['pajak'];
		$subscribe = $_POST['jmlh_hari'];
		$discount = $_POST['val_discount'];

		$subscribe_times = $_POST['subscribe_times'];
		$package_type = $_POST['package_type'];
		$add_branch = $_POST['add_branch'];

		$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
				FROM invoice
				WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
		$rNO = mysql_query($qNO);
		$dNO = mysql_fetch_array($rNO);
		if ($dNO['max_no'] == 0){
			$seq = 1;
		}else{
			$seq = $dNO['max_no'] + 1;
		}
		$no = 'FP'.date("Ym").sprintf("%06d", $seq);
		
		//$qPr = "SELECT p.price, p.package_type,
		//		s.value, s.value_month 
		//		FROM package p
		//		LEFT JOIN subscribe s ON s.id = p.subscribe_id 
		//		WHERE p.id = '$package'";
		$qPr = "SELECT p.package_type, s.value, s.value_month, 
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price 
				FROM package p 
				LEFT JOIN subscribe s ON s.id = p.subscribe_id 
				WHERE p.id = '".$package."'";
		$rPr = mysql_query($qPr);
		$dPr = mysql_fetch_array($rPr);
		$amount2 = $dPr['price']; 
		$package_type = $dPr['package_type']; 
		$value_days = $dPr['value'];
		$value_month = $dPr['value_month'];
		
		/*ambil data cust order*/
		$cs = mysql_query("SELECT * FROM cust_order WHERE cust_id = '$_SESSION[custID]' AND dbname = '$dbname'") or die(mysql_error());
		$orderId = mysql_fetch_array($cs);
		
		$orderId2 = $orderId['order_id'];
		$expDate = $orderId['expired_date'];
		$expired = $orderId['expired_days'];
		//$beginDate = $orderId['begin_date'];
		//$endDate = $orderId['end_date'];
		
		$sqlD = mysql_query("SELECT * FROM sysdata WHERE id = 4") or die(mysql_error());
		$due_date = mysql_fetch_array($sqlD);
		$batas = $due_date['value'];
		$now = date('Y-m-d');
		$due_date = date('Y-m-d', strtotime('+'.$batas.' days', strtotime($now))) ;
		
		$sqlD2 = mysql_query("SELECT * FROM sysdata WHERE id = 2") or die(mysql_error());
		$sysdata2 = mysql_fetch_array($sqlD2);
		$exptabel = $sysdata2['value'];
		$expDate = date('Y-m-d', strtotime('+'.$exptabel.' days', strtotime($endDate)));
		
		$sub = $amount - $discount;
		//if($sub > 0) {
		//	$initialMount = rand(10,99);
		//} else {
		//	$initialMount = 0;
		//}
		$initialMount = 0;
		
		$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `inv_reff`, `curr_id`, `total_amount`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, initial_amount, voucher_code, ppn, discount) VALUES ('$no', '$now', '$due_date', '$invoice', 'IDR', '$amount', '0', '0000-00-00', 'N', '$custID', NOW(), '$custID', NOW(), '$custID', '$initialMount', '$voucher', '$ppn', '$discount')") or die(mysql_error());
		
		$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));

		//delete invoice detail user
		$invd_id = explode(',', $_POST['invd_id']);
		if(count($invd_id) > 0){
			foreach ($invd_id as $key => $value) {
				mysql_query("UPDATE cust_order SET used = 'D' WHERE order_id in (SELECT order_id FROM invoice_detail WHERE id = '$value')") or die (mysql_error());

				mysql_query("DELETE FROM invoice_detail WHERE id = '$value'") or die(mysql_error());
			}
		}
		
		//$tglmulai = new DateTime($now);
		//$tglakhir = new DateTime($endDate);
		//$inthari = $tglmulai->diff($tglakhir);
		//$valbulan = $inthari/30;
		//$valmonth = ceil($valbulan);
		
		if($package != ''){
			/* simpan paket 1 database + 1 user di invoice detail */
			$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`) VALUES ('$invId[last_id]', '$orderId2', '$beginDate', '$endDate', '$expDate', '$exptabel', 'IDR', '$amount2', '$custID', NOW(), '$_SESSION[custID]', NOW(), '$package', '1')") or die(mysql_error());

			// insert invdetail utk user yg ditambahkan
			$qCo = mysql_query("SELECT p.id AS package_id, p.price, invd.order_id, co.dbname
				FROM cust_order co 
				JOIN package p ON co.package_id=p.id 
				LEFT JOIN invoice_detail invd on invd.order_id = co.order_id
				LEFT JOIN invoice inv on inv.id = invd.inv_id
				WHERE inv.inv_no = '$invoice' AND co.dbname = '$dbname' AND p.package_type = '2'") 
				or die(mysql_error());
			while ($dataCO = mysql_fetch_array($qCo)) {
				$amount = $dataCO['price'];
				$amount2 = $dataCO['price'] * $value_month;
				$package_id = $dataCO['package_id'];
				$orderId3 = $dataCO['order_id'];
				$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`) VALUES ('$invId[last_id]', '$orderId3', '$beginDate', '$endDate', '$expDate', '$expired', 'IDR', '$amount2', '$custID', NOW(), '$custID', NOW(), '$package_id', '$value_month')") or die(mysql_error());
			}
		}
		else{
			/* simpan paket 1 user di invoice detail */
			$qCo = mysql_query("SELECT p.id AS package_id, p.price, invd.order_id, co.dbname
				FROM cust_order co 
				JOIN package p ON co.package_id=p.id 
				LEFT JOIN invoice_detail invd on invd.order_id = co.order_id
				LEFT JOIN invoice inv on inv.id = invd.inv_id
				WHERE inv.inv_no = '$invoice' AND co.dbname = '$dbname' AND p.package_type = '2'") 
				or die(mysql_error());
			while ($dataCO = mysql_fetch_array($qCo)) {
				$amount = $dataCO['price'];
				$amount2 = $dataCO['price'] * $value_month;
				$package_id = $dataCO['package_id'];
				$orderId3 = $dataCO['order_id'];
				$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount2`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`) VALUES ('$invId[last_id]', '$orderId3', '$beginDate', '$endDate', '$expDate', '$expired', 'IDR', '$amount', '$custID', NOW(), '$custID', NOW(), '$package_id', '$value_month')") or die(mysql_error());
			}
		}
		
		/* nonaktifkan invoice sebelumnya */
		mysql_query("UPDATE invoice SET inv_reff = '$no', stsrec = 'D' WHERE inv_no = '$invoice'") or die(mysql_error());
		
		mysql_query("UPDATE voucher SET stsrec = 'U' WHERE name = '$voucher' AND multi = '0'") or die(mysql_error());
		
		$host = $_SERVER['HTTP_HOST'];
		$emailOwner = $_SESSION['custEmail'];
		$subject = 'Tagihan Siscom Online';
		//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		//$pesan = file_get_contents("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		$pesan = url_get_content("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
		//sendEmail($emailOwner, $pesan, $subject); //kirim tagihan ke email
		$username = 'finance@siscomonline.co.id';
		$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
		$name = $emailrec['name'];
		$password = $emailrec['password'];
		$passwordDecrypt = 'financeoke515'; //decrypt($ENCRKEY, $password);
		sendEmail($emailOwner, $pesan, $subject, $username, $passwordDecrypt, $name); //kirim tagihan ke email

		$result = array("status"=>'success');
		echo "<script type='text/javascript'>
				alert('Data successfully added');
				window.location='info_tagihan.php?dbname=$dbname';
			  </script>";
	}

	elseif ($_POST['action'] == 'simpanfakturpajak'){
		$custID 		= $_SESSION['custID'];

		$dbname = $_POST['dbname'];
		$faktur = (isset($_POST['faktur']) && $_POST['faktur']=='Y') ? 1 : 0;
		$jenisfaktur = ($faktur ==1 ) ? $_POST['jenisfaktur'] : '';
		$npwp_no  = $_POST['npwp'];
		$company_id = $_POST['company_id'];

		$gambarktp     		= $_FILES['ktp']['tmp_name'];
		$gambarnpwp     	= $_FILES['npwp']['tmp_name'];

		$namagambarktp='';
		if ($gambarktp !='') {
			$acak1=rand(000000,999999);
			$acak2=rand(000000,999999);
			$acak3=rand(000000,999999);
			$acak=$acak1."_".$acak2."_".$acak3;

	
			$namagambarktp = "siscom-".$acak.".jpg";
			$upload = "../../../images/payment/".$namagambarktp;
	 
			move_uploaded_file($gambarktp,$upload);	        	
		}
		$namagambarnpwp = '';
		if($gambarnpwp !=''){
			$acak1=rand(000000,999999);
			$acak2=rand(000000,999999);
			$acak3=rand(000000,999999);
			$acak=$acak1."_".$acak2."_".$acak3;


			$namagambarnpwp = "siscom-".$acak.".jpg";
			$upload = "../../../images/payment/".$namagambarnpwp;
	 
			move_uploaded_file($gambarnpwp,$upload);
		}     	
		
		$updateFaktur = mysql_query("update cust_order set inv_tax='$faktur', taxpayer_id='$jenisfaktur', file_identity='$namagambarktp', file_tax='$namagambarnpwp' where cust_id='$custID' and dbname = '$dbname'") or die(mysql_error());
		$updateNpwp = mysql_query("update company set npwp_no='$npwp_no' where id='$company_id'")or die(mysql_error());

		$result = array("status"=>'success');
		echo "<script type='text/javascript'>
           		alert('Data successfully added');
           		window.location='info_tagihan.php?dbname=$dbname';
        	  </script>";
	}

	elseif ($_POST['action'] == 'ambildb'){
		$dbLama = $_POST['dbLama'];
		$cust_Id = $_POST['cust_id'];

		$sqlDb = mysql_query("SELECT co.dbname, co.cust_id, c.billing_send
			FROM `cust_order` co
			LEFT JOIN cust c on c.id = co.cust_id
			WHERE cust_id = '$cust_Id' AND c.billing_send = 'Y' AND co.dbname <> '$dbLama'")
			or die(mysql_error());

		while ( $dataDb = mysql_fetch_array($sqlDb)) {
			$data[] = $dataDb;
		}

		$result = array('status'=>'success', 'data'=>$data);
	}
	//delete db lama sebelum copy master
	elseif ($_POST['action'] == 'delete-database'){
		$dbname = $_POST['dbname'];
		$database = $dbname.'_acc';

		$delete = mysql_query("DROP DATABASE $database") or die(mysql_error());
		if($delete){
			$result = array('status'=>'success', 'deleted'=>$database);
		}
		else{
			$result = array('status'=>'failed');
		}
	}

	elseif ($_POST['action'] == 'duplicate-database'){
		$dbLama = $_POST['dbLama'];
		$dbBaru = $_POST['dbBaru'];

		$dbLama_acc = $dbLama."_acc";
		$dbBaru_acc = $dbBaru."_acc";

		$host = $_SERVER['SERVER_ADDR'];
		$hostname2 = $GLOBALS['hostname2'];
		$username2 = $GLOBALS['username2'];
		$password2 = $GLOBALS['password2'];

		/* koneksi untuk membuat database user*/
		$connCreateDB = mysqli_connect($hostname2,$username2, $password2) or die(mysqli_connect_error());

		//create database user
		$query = 'CREATE DATABASE '.$dbLama_acc;
		mysqli_query($connCreateDB, $query) or die(mysqli_error($connCreateDB));
		
		$koneksi2 = mysqli_connect($hostname2,$username2, $password2, $dbLama_acc) or die(mysqli_connect_error()); //buat koneksi baru dengan database yang telah dibuat

		//koneksi database Master dbBaru_acc
		$koneksi = mysqli_connect($hostname2,$username2, $password2, $dbBaru_acc) or die(mysqli_connect_error());

		//copy struktur database dari dbBaru_acc;
		$sql = mysqli_query($koneksi,"SHOW TABLES");
		while($tables = mysqli_fetch_array($sql)){
			$table = $tables[0];
			$table = strtoupper($table);
			$table1 = strtolower($table);

			$query = mysqli_query($koneksi, "SHOW CREATE TABLE `$table1`") or die(mysqli_error($koneksi));
			$tb = mysqli_fetch_array($query);
			$create = str_replace("`$table1`","`$table`",$tb[1]);
			$duplicate = mysqli_query($koneksi2, "$create")or die(mysqli_error($koneksi2));

			mysqli_query($koneksi2, "INSERT INTO `$table` SELECT * FROM $dbBaru_acc.`$table1`") or die(mysqli_error($koneksi2));
		}


		if($duplicate){
			$result = array('status'=>'success', 'db'=>$dbLama_acc);
		}
		else{
			$result = array('status'=>'failed');
		}
	}

	//UBAHAN DIM START
	else if ($_POST['action'] == 'getCabangSiserp') {
		$hostname2 = $GLOBALS['hostname2'];
		$username2 = $GLOBALS['username2'];
		$password2 = $GLOBALS['password2'];
		$dbname = $_POST['dbname'];
		$email = $_POST['email'];
		
		$dbName = strtolower($dbname).'_acc';
		$koneksi = mysqli_connect($hostname2,$username2, $password2,$dbName) or die(mysqli_connect_error());

		$sql2 = mysqli_query($koneksi, "SELECT AKSESCABANG FROM SYSUSER WHERE EMAIL='$email' AND AKTIF='Y'") or die(mysqli_error($koneksi));
		$aksesCabang = '';
		
		while($dtUser = mysqli_fetch_array($sql2)){
			$data['aksesCabang'] = $dtUser['AKSESCABANG'];
		}

		$sql = mysqli_query($koneksi, "SELECT NAMA, KODE FROM CABANG") or die(mysqli_error($koneksi));
				
		while ( $dataDb = mysqli_fetch_array($sql)) {
			$data['cabang'][]= $dataDb;
		}

		if (count($data)>0) {
			$result = array('status'=>'success', 'data'=> $data);
		}
		else{
			$result = array('status'=>'failed');	
		}
	}
	//UBAHAN DIM END	

	echo json_encode($result);
	
}

else{
	
	$qtrial = mysql_query("SELECT * FROM sysdata WHERE id IN ('1','2')") or die(mysql_error());
	if (mysql_num_rows($qtrial)){
		while ($rtrial = mysql_fetch_array($qtrial)) {
			if($rtrial['id'] == 1) {
				$trial = $rtrial['value'];
			} else if($rtrial['id'] == '2') {
				$expired = $rtrial['value'];
			}
		}
	}
	
	$cName 			= trim($_POST['nameU'], ' ');
	$business 		= $_POST['selectBis'];
	$phone 			= $_POST['hp'];
	$wa 			= $_POST['wa'];
	$cEmail 		= $_SESSION['custEmail'];
	$npwp 			= $_POST['npwp'];
	$addr 			= $_POST['address'];
	$city 			= $_POST['kota'];
	$prov 			= $_POST['selectProv'];
	$zip 			= $_POST['kodepos'];
	$custID 		= $_SESSION['custID'];
	$custName 		= $_SESSION['custName'];
	$country 		= $_POST['selectNeg'];
	$acno 			= isset($_POST['auto']) ? $_POST['auto'] : '';
	$status_used    = 'T';
	
	// $salesman 		= (isset($_POST['dari']) && $_POST['dari'] !='') ? $_POST['dari'] : $_POST['agen'];
	$salesman_remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;

	$period 	   = $_POST['selectperiod'];
	$periodBegin   = substr($period, 0, 2);
	$periodEnd 	   = substr($period, 3, 2);
	$dbName 	   = strtolower($_POST['dbname']);

	$beginDate 	   = date('Y-m-d');
	$dbDate 	   = date('Y-m-d', strtotime($_POST['beginDate']));
	$endDate 	   = date('Y-m-d', strtotime('+'.$trial.' days', strtotime($beginDate)));
	$expDate 	   = date('Y-m-d', strtotime('+'.$expired.' days', strtotime($endDate)));
	$ver 		   = $_POST['selectVer'];
	$active 	   = isset($_POST['active']) ? $_POST['active'] : 'A';

	if(isset($_POST['post']) && $_POST['post'] == 'copy-master' && $acno == ''){ //isi acc_code saat simpan copy master
		$acno 			= 'Y';
		$status_used    = 'D';
	}

	if($ver == '1' or $ver == '2'){
		$acno = 'Y';
	}

	$cName2 	   = str_replace(' ', '', $cName);
	$scCpn = mysql_query("SELECT REPLACE(name,' ', '') as nm FROM company WHERE REPLACE(name,' ', '') = '$cName2'") or die(mysql_error());
	$matchCpn = mysql_num_rows($scCpn);
	
	if(strtolower(trim($dbName)) == 'sis' or strtolower(trim($dbName)) == 'def') {
		$matchCs = 1;
	} else {
		$sCs = mysql_query("SELECT * FROM cust_order WHERE dbname = '$dbName'") or die (mysql_error());
		$matchCs = mysql_num_rows($sCs);
	}
	
	// $sSales = mysql_query("SELECT * FROM salesman where id = '$salesman'") or die (mysql_error());
	// $matchSales = mysql_num_rows($sSales);
	
	// if ($matchCpn == 0 && $matchCs == 0) {
	if ($matchCs == 0) {
		
		$inCpn = mysql_query("INSERT INTO company(id, name, business_id, address, city, prov_code, zip_code, country_code, phone_no, wa_no, email, npwp_no) VALUES (NULL, '$cName', '$business', '$addr', '$city', '$prov', '$zip', '$country', '$phone', '$wa', '$cEmail', '$npwp')") or die(mysql_error());

		$scCpn1 = mysql_query("SELECT id FROM company ORDER BY id DESC LIMIT 1") or die(mysql_error());
		$row = mysql_fetch_array($scCpn1);

		$companyId = $row['id'];

		// ambil package id
		$sql_pk = mysql_query("SELECT p.*, s.value_month FROM package p LEFT JOIN subscribe s ON s.id = p.subscribe_id WHERE p.package_type = '1' AND p.ver_id = '$ver' AND p.subscribe_id = '1'");
		$data_package = mysql_fetch_array($sql_pk);
		$package_id = $data_package['id'];
		$subscribe_id = $data_package['subscribe_id'];
		$subscribe_month = $data_package['value_month'];
		
		//$salesman1 = 'S0001';
		
		if (isset($_POST['dari']) && $_POST['dari'] != '') {
			$salesman1 	= $_POST['dari'];
			if (isset($_POST['combobox'])){
				$salesman1 	= $_POST['combobox'];
			}
		}
		else if (isset($_POST['agen']) && $_POST['agen'] != ''){
			$salesman1 	= $_POST['agen'];
		}
		
		if($salesman1 == '') {
			$salesman1 = 'S0001';
		}
		
		$salesman = strtoupper($salesman1);
		
		// ambil posisi salesman
		$salesman_email = '';
		if($salesman != '') {
			$sql_sales = mysql_query("SELECT id, email FROM `salesman` WHERE id = '$salesman'") or die(mysql_error());
			$data_sales = mysql_fetch_array($sql_sales);
			$salesman_email = $data_sales['email'];
		} 
		
		// insert ke cust_order
		$incSet = mysql_query("INSERT INTO cust_order (cust_id, company_id, trial_days, expired_days, ver_id, db_date, begin_date, end_date, expired_date, acc_period_begin, acc_period_end, dbname, salesman_id, salesman_remark, active, created_by, created_on, modified_by, modified_on, package_id, acc_code, used, ctype) VALUES ('$custID', '$companyId', '$trial', '$expired', '$ver', '$dbDate', '$beginDate', '$endDate', '$expDate', '$periodBegin', '$periodEnd', '$dbName', '$salesman', '$salesman_remark', '$active', '$custID', NOW(), '$custID', NOW(), '$package_id', '$acno', '$status_used', 'C')") or die(mysql_error());

		$sql_pass = mysql_query("SELECT password_app FROM `cust` WHERE email = '$cEmail'") or die(mysql_error());
		$data = mysql_fetch_array($sql_pass);
		
		//Kirim Email ke Training, CS dan Agen 
		if($incSet){
			$host = $_SERVER['HTTP_HOST'];
			/*if($salesman_email != '') {
				$emailOwner = array('training@siscomonline.co.id', 'cs@siscomonline.co.id', $salesman_email);
			} else {
				$emailOwner = array('training@siscomonline.co.id', 'cs@siscomonline.co.id');
			}*/
			//$emailOwner = array('shan3519@gmail.com', 'jsonhary@gmail.com', 'iwansetiadik@gmail.com');
			$emailOwner = array('iwansetiadik@gmail.com');
			$subject = 'Customer SISCOM Online';
			//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/training_email.php?dbName=$dbName");
			//$pesan = file_get_contents("$abs/reg/pages/training_email.php?dbName=$dbName");
			//$pesan = url_get_content("$abs/reg/pages/training_email.php?dbName=$dbName");
			
			$url = $abs."/reg/pages/training_email.php?dbName=".$dbName;
			$html = getUrlContent($url);

			$xml = simplexml_load_string($html);
			$json = json_encode($xml);
			$pesan = json_decode($json,TRUE);

			//sendEmail($emailOwner, $pesan, $subject);
			$username = 'no-reply@siscomonline.co.id';
			$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
			$name = $emailrec['name'];
			$password = $emailrec['password'];
			$passwordDecrypt = decrypt($ENCRKEY, $password);
			sendEmail($emailOwner, $pesan, $subject, $username, $passwordDecrypt, $name);
		}
		
		if(isset($_POST['post']) && $_POST['post'] == 'copy-master'){
			$db = $_POST['dbMaster'];
			$dbMaster = $db.'_acc';

			// Kirim Faktur Proforma Tagihan
			$qNO = "SELECT IFNULL(CAST(MAX(RIGHT(inv_no,6)) AS UNSIGNED),0) AS max_no 
					FROM invoice
					WHERE LEFT(inv_date, 7) = '".date("Y-m")."' AND LEFT(inv_no,2) = 'FP' ";
			$rNO = mysql_query($qNO) or die(mysql_error());
			$dNO = mysql_fetch_array($rNO);
			if ($dNO['max_no'] == 0){
				$seq = 1;
			}else{
				$seq = $dNO['max_no'] + 1;
			}
			$no = 'FP'.date("Ym").sprintf("%06d", $seq);
			
			//$qPr = "SELECT price, package.id AS package_id FROM package
			//		WHERE package_type = 1 AND subscribe_id = '1' AND ver_id = '".$ver."'";
			$qPr = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price 
					FROM package p 
					WHERE p.package_type = 1 AND p.subscribe_id = '1' AND p.ver_id = '".$ver."'";
			$rPr = mysql_query($qPr) or die(mysql_error());
			$dPr = mysql_fetch_array($rPr);
			$amount = $dPr['price'];
			$ppn = (10*$amount)/100;
			$package_id = $dPr['package_id'];
			$now = date('Y-m-d');
			$initialMount = 0; //rand(10,99);
			
			$insInv = mysql_query("INSERT INTO invoice (`inv_no`, `inv_date`, `due_date`, `curr_id`, `total_amount`, `ppn`, `payment_id`, `paid_date`, `paid_off`, `created_by`, `created_on`, `modified_by`, `modified_on`, `cust_id`, `initial_amount`) VALUES ('$no', '$now', '$endDate', 'IDR', '$amount', '$ppn', '0', '0000-00-00', 'N', 'admin', NOW(), 'admin', NOW(), '$custID', '$initialMount')") or die(mysql_error());

			$invId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS last_id FROM invoice"));
			
			$cs = mysql_query("SELECT order_id, expired_date, expired_days, company_id FROM cust_order WHERE cust_id='$_SESSION[custID]' AND dbname = '$dbName'") or die(mysql_error());
			$orderId = mysql_fetch_array($cs);
			
			$companyId = $orderId['company_id'];	
			$orderId2 = $orderId['order_id'];
			$expDate = $orderId['expired_date'];
			$expired = $orderId['expired_days'];
			$insInv = mysql_query("INSERT INTO invoice_detail (`inv_id`, `order_id`, `begin_date`, `end_date`, `expired_date`, `expired_days`, `curr_id`, `amount`, `created_by`, `created_on`, `modified_by`, `modified_on`, `package_id`, `subscribe_times`) VALUES ('$invId[last_id]', '$orderId2', '$now', '$endDate', '$expDate', '$expired', 'IDR', '$amount', 'admin', NOW(), 'admin', NOW(), '$package_id', '1'', '', '0')") or die(mysql_error());
			
			$invoice = $invId['last_id'];
			$host = $_SERVER['HTTP_HOST'];
			$emailOwner = $_SESSION['custEmail'];
			$subject = 'Tagihan SISCOM Online';
			//$pesan = file_get_contents("https://$host/siserp/module/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
			//$pesan = file_get_contents("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
			$pesan = url_get_content("$abs/reg/pages/tagihan_email.php?inv_id=$invId[last_id]");
			$username = 'finance@siscomonline.co.id';
			$emailrec = mysql_fetch_array(mysql_query("SELECT name, `password` FROM sysemail WHERE email = '$username'"));
			$name = $emailrec['name'];
			$password = $emailrec['password'];
			$passwordDecrypt = 'financeoke515'; //decrypt($ENCRKEY, $password);
			sendEmail($emailOwner, $pesan, $subject, $username, $passwordDecrypt, $name); //kirim tagihan ke email

			createDB($dbName, $dbDate, $acno, $data['password_app'], $companyId, $ver, $periodBegin, $dbMaster);
			$result = array('status' => 'success', 'custID' => $_SESSION['custID']);
		}
		else{ 
			createDB($dbName, $dbDate, $acno, $data['password_app'], $companyId, $ver, $periodBegin);
			$result = array('status' => 'success', 'custID' => $_SESSION['custID']);
		}
	}
	else{
		if ($matchCs != 0) {
			$result = array('status' =>  'gagal1');
		}

		// if ($matchCpn != 0) {
		// 	$result = array('status' =>  'gagal');
		// }

	}

	echo json_encode($result);

}

function createDB($dbname, $periode, $acno, $password, $companyId, $versi, $periodBegin, $dbMaster='def_acc'){
	ini_set('max_execution_time', 600); // max execute 5 menit
	//buat database;
	$host = $_SERVER['SERVER_ADDR'];
	$hostname2 = $GLOBALS['hostname2'];
	$username2 = $GLOBALS['username2'];
	$password2 = $GLOBALS['password2'];
	
	/* koneksi untuk membuat database user*/
	$connCreateDB = mysqli_connect($hostname2, $username2, $password2) or die(mysqli_connect_error());

	//ambil periode
	$periode = explode('-', $periode);
	$tahuns = $periode[0];
	$bulan = $periode[1];
	$tahun = substr($periode[0], 2);
	$periode = $tahun.$bulan;
	$periodes = $tahuns.$bulan;
	
	$qVer = "SELECT name FROM version 
				WHERE id = '".$versi."'";
	$rVer = mysql_query($qVer) or die(mysql_error());
	$dVer = mysql_fetch_array($rVer);
	$namaversi = $dVer['name'];
	
	//db user
	$dbName = strtolower($dbname).'_acc';
	$dbName2 = strtolower($dbname).'_acc'.$periode;
	
	//koneksi database default def_acc
	$koneksi = mysqli_connect($hostname2,$username2,$password2,$dbMaster) or die(mysqli_connect_error());
	
	/*buat database user*/
	$query = 'CREATE DATABASE '.$dbName;
	mysqli_query($connCreateDB, $query) or die(mysqli_error($connCreateDB));
	
	$koneksi4 = mysqli_connect($hostname2,$username2,$password2,$dbName) or die(mysqli_connect_error()); //buat koneksi baru dengan database yang telah dibuat

	//copy struktur database dari def_acc;
	$sql = mysqli_query($koneksi,"SHOW TABLES");
	while($tables = mysqli_fetch_array($sql)){
		if ($tables[0]=='GROUP' || $tables[0]=='group') {
			$table = "group";		
		}else{
			$table = $tables[0];
		}

		$table = strtoupper($table);
		if($dbMaster == 'def_acc') {
			$table1 = strtolower($table);
		} else {
			$table1 = strtoupper($table);
		}
		$query = mysqli_query($koneksi, "SHOW CREATE TABLE `$table1`") or die(mysqli_error($koneksi));
		$tb = mysqli_fetch_array($query);
		$create = str_replace("`$table1`","`$table`",$tb[1]);
		mysqli_query($koneksi4, "$create")or die(mysqli_error($koneksi4));
		
		//Old Condition

		/*
		if ($table=='ACNO' && $acno == 'Y') {
			$tblacno = $dbMaster == 'def_acc' ? 'acno' : 'ACNO';
			mysqli_query($koneksi4, "INSERT INTO ACNO SELECT * FROM $dbMaster.$tblacno")or die(mysqli_error($koneksi4));
		}
		else if($table == 'ACTRANS' && $acno == 'Y') {
			$tblactrans = $dbMaster == 'def_acc' ? 'actrans' : 'ACTRANS';
			mysqli_query($koneksi4, "INSERT INTO ACTRANS SELECT * FROM $dbMaster.$tblactrans")or die(mysqli_error($koneksi4));
		}
		else if($table == 'ACMASTER' && $acno == 'Y') {
			$tblacmaster = $dbMaster == 'def_acc' ? 'acmaster' : 'ACMASTER';
			mysqli_query($koneksi4, "INSERT INTO ACMASTER SELECT * FROM $dbMaster.$tblacmaster")or die(mysqli_error($koneksi4));
		}
		else if($table != 'ACNO' and $table != 'ACMASTER' and $table != 'ACTRANS'){
			mysqli_query($koneksi4, "INSERT INTO `$table` SELECT * FROM $dbMaster.`$table1`") or die(mysqli_error($koneksi4));
		}
		*/

		//New Condition (01/04/2021)

		//$acno = 'Y';

		if ($table == 'TBAYAR' && $acno == 'Y') {
			$tblbayar = $dbMaster == 'def_acc' ? 'tbayar' : 'TBAYAR';
			mysqli_query($koneksi4, "TRUNCATE TABLE $dbMaster.$tblbayar")or die(mysqli_error($koneksi4));
			if($versi == '1') {
				$tblbayar = $dbMaster == 'def_acc' ? 'tbayar_v1' : 'TBAYAR_V1';
				mysqli_query($koneksi4, "INSERT INTO TBAYAR SELECT * FROM $dbMaster.$tblbayar")or die(mysqli_error($koneksi4));
			} else if($versi == '2') {
				$tblbayar = $dbMaster == 'def_acc' ? 'tbayar_v2' : 'TBAYAR_V2';
				mysqli_query($koneksi4, "INSERT INTO TBAYAR SELECT * FROM $dbMaster.$tblbayar")or die(mysqli_error($koneksi4));
			} else if($versi == '3') {
				$tblbayar = $dbMaster == 'def_acc' ? 'tbayar_v3' : 'TBAYAR_V3';
				mysqli_query($koneksi4, "INSERT INTO TBAYAR SELECT * FROM $dbMaster.$tblbayar")or die(mysqli_error($koneksi4));
			}
		}
		else if ($table == 'ACNO' && $acno == 'Y') {
			$tblacno = $dbMaster == 'def_acc' ? 'acno' : 'ACNO';
			mysqli_query($koneksi4, "TRUNCATE TABLE $dbMaster.$tblacno")or die(mysqli_error($koneksi4));
			if($versi == '1') {
				$tblacno = $dbMaster == 'def_acc' ? 'acno_v1' : 'ACNO_V1';
				mysqli_query($koneksi4, "INSERT INTO ACNO SELECT * FROM $dbMaster.$tblacno")or die(mysqli_error($koneksi4));
			} else if($versi == '2') {
				$tblacno = $dbMaster == 'def_acc' ? 'acno_v2' : 'ACNO_V2';
				mysqli_query($koneksi4, "INSERT INTO ACNO SELECT * FROM $dbMaster.$tblacno")or die(mysqli_error($koneksi4));
			} else if($versi == '3') {
				$tblacno = $dbMaster == 'def_acc' ? 'acno_v3' : 'ACNO_V3';
				mysqli_query($koneksi4, "INSERT INTO ACNO SELECT * FROM $dbMaster.$tblacno")or die(mysqli_error($koneksi4));
			}
		}
		else if($table == 'ACTRANS' && $acno == 'Y') {
			$tblactrans = $dbMaster == 'def_acc' ? 'actrans' : 'ACTRANS';
			mysqli_query($koneksi4, "TRUNCATE TABLE $dbMaster.$tblactrans")or die(mysqli_error($koneksi4));
			if($versi == '1') {
				$tblactrans = $dbMaster == 'def_acc' ? 'actrans_v1' : 'ACTRANS_V1';
				mysqli_query($koneksi4, "INSERT INTO ACTRANS SELECT * FROM $dbMaster.$tblactrans")or die(mysqli_error($koneksi4));
			} else if($versi == '2') {
				$tblactrans = $dbMaster == 'def_acc' ? 'actrans_v2' : 'ACTRANS_V2';
				mysqli_query($koneksi4, "INSERT INTO ACTRANS SELECT * FROM $dbMaster.$tblactrans")or die(mysqli_error($koneksi4));
			} else if($versi == '3') {
				$tblactrans = $dbMaster == 'def_acc' ? 'actrans_v3' : 'ACTRANS_V3';
				mysqli_query($koneksi4, "INSERT INTO ACTRANS SELECT * FROM $dbMaster.$tblactrans")or die(mysqli_error($koneksi4));
			}
		}
		else if($table == 'ACMASTER' && $acno == 'Y') {
			$tblacmaster = $dbMaster == 'def_acc' ? 'acmaster' : 'ACMASTER';
			mysqli_query($koneksi4, "TRUNCATE TABLE $dbMaster.$tblacmaster")or die(mysqli_error($koneksi4));
			if($versi == '1') {
				$tblacmaster = $dbMaster == 'def_acc' ? 'acmaster_v1' : 'ACMASTER_V1';
				mysqli_query($koneksi4, "INSERT INTO ACMASTER SELECT * FROM $dbMaster.$tblacmaster")or die(mysqli_error($koneksi4));
			} else if($versi == '2') {
				$tblacmaster = $dbMaster == 'def_acc' ? 'acmaster_v2' : 'ACMASTER_V2';
				mysqli_query($koneksi4, "INSERT INTO ACMASTER SELECT * FROM $dbMaster.$tblacmaster")or die(mysqli_error($koneksi4));
			} else if($versi == '3') {
				$tblacmaster = $dbMaster == 'def_acc' ? 'acmaster_v3' : 'ACMASTER_V3';
				mysqli_query($koneksi4, "INSERT INTO ACMASTER SELECT * FROM $dbMaster.$tblacmaster")or die(mysqli_error($koneksi4));
			}
		}
		else if($table == 'BS2021' && $acno == 'Y') {
			$tblbs = $dbMaster == 'def_acc' ? 'bs2021' : 'BS2021';
			mysqli_query($koneksi4, "TRUNCATE TABLE $dbMaster.$tblbs")or die(mysqli_error($koneksi4));
			if($versi == '1') {
				$tblbs = $dbMaster == 'def_acc' ? 'bs2021_v1' : 'BS2021_V1';
				mysqli_query($koneksi4, "INSERT INTO BS2021 SELECT * FROM $dbMaster.$tblbs")or die(mysqli_error($koneksi4));
			} else if($versi == '2') {
				$tblbs = $dbMaster == 'def_acc' ? 'bs2021_v2' : 'BS2021_V2';
				mysqli_query($koneksi4, "INSERT INTO BS2021 SELECT * FROM $dbMaster.$tblbs")or die(mysqli_error($koneksi4));
			} else if($versi == '3') {
				$tblbs = $dbMaster == 'def_acc' ? 'bs2021_v3' : 'BS2021_V3';
				mysqli_query($koneksi4, "INSERT INTO BS2021 SELECT * FROM $dbMaster.$tblbs")or die(mysqli_error($koneksi4));
			}
		}
		else if($table == 'RL2021' && $acno == 'Y') {
			$tblrl = $dbMaster == 'def_acc' ? 'rl2021' : 'RL2021';
			mysqli_query($koneksi4, "TRUNCATE TABLE $dbMaster.$tblrl")or die(mysqli_error($koneksi4));
			if($versi == '1') {
				$tblrl = $dbMaster == 'def_acc' ? 'rl2021_v1' : 'RL2021_V1';
				mysqli_query($koneksi4, "INSERT INTO RL2021 SELECT * FROM $dbMaster.$tblrl")or die(mysqli_error($koneksi4));
			} else if($versi == '2') {
				$tblrl = $dbMaster == 'def_acc' ? 'rl2021_v2' : 'RL2021_V2';
				mysqli_query($koneksi4, "INSERT INTO RL2021 SELECT * FROM $dbMaster.$tblrl")or die(mysqli_error($koneksi4));
			} else if($versi == '3') {
				$tblrl = $dbMaster == 'def_acc' ? 'rl2021_v3' : 'RL2021_V3';
				mysqli_query($koneksi4, "INSERT INTO RL2021 SELECT * FROM $dbMaster.$tblrl")or die(mysqli_error($koneksi4));
			}
		}
		else if($table != 'TBAYAR' AND $table != 'ACNO' AND $table != 'ACMASTER' AND $table != 'ACTRANS' AND $table != 'BS2021' AND $table != 'RL2021'){
			mysqli_query($koneksi4, "INSERT INTO `$table` SELECT * FROM $dbMaster.`$table1`") or die(mysqli_error($koneksi4));
		}

	}

	//masukkan username,email,pass owner kedalam tb sysuser
	$usid = strtoupper(substr($dbName,0,1)).'01';
	$username = substr($_SESSION['custName'],0,20);
	$email = $_SESSION['custEmail'];
	$sCs = mysql_query("SELECT password_app FROM cust where email = '$email'") or die (mysql_error());
	$data = mysql_fetch_array($sCs);

	$password = $data['password_app'];	
	$level = ($_SESSION['custLevel']=='A') ? '1' : '2';

	mysqli_query($koneksi4, "INSERT INTO SYSUSER (USID, `PASSWORD`, USERNAME, EMAIL, USLEVEL, IDPT, DOE, TOE, LOGINFLAG, AKTIF, AKSESCABANG) VALUES ('$usid', '$password', '$username', '$email', '$level', '01', '".date('Y-m-d H:i:s')."', '".date('H:i:s')."', '0', 'Y', '01')") or die(mysqli_error($koneksi4));
	
	//NEW QUERY
	mysqli_query($koneksi4, "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, DOE, LOE, DEO) SELECT '".$usid."', S1.LADD, S2.LAPD, S2.URUT, NOW(), NOW(), '".$usid."' FROM SYSMENUGROUP S1 JOIN SYSMENU S2 ON S1.LADD = S2.LADD WHERE UPPER(S1.GRID) = '".strtoupper($namaversi)."'") or die(mysqli_error($koneksi4));
	
	$qCB = "SELECT name, address, phone_no, npwp_no FROM company 
			WHERE id = '".$companyId."'";
	$rCB = mysql_query($qCB) or die(mysql_error());
	$dCB = mysql_fetch_array($rCB);
	$nama_cabang = $dCB['name']; 
	$alamat_cabang = $dCB['address'];
	$telp_cabang = $dCB['phone_no'];
	$npwp_cabang = $dCB['npwp_no'];
	
	mysqli_query($koneksi4, "INSERT INTO CABANG (KODE, NAMA, ALAMAT1, ALAMAT2, TELP, NPWP, GCB, DOE, DEO, AKTIF) VALUES ('01', '".$nama_cabang."', '".$alamat_cabang."', '', '".$telp_cabang."', '".$npwp_cabang."', '', '".date('Y-m-d H:i:s')."', '".$usid."', 'Y')") or die(mysqli_error($koneksi4));
	
	$tabel_bs = "BS".$tahuns;
	$tabel_rl = "RL".$tahuns;
	
	mysqli_query($koneksi4, "ALTER TABLE BS2021 RENAME ".$tabel_bs."") or die(mysqli_error($koneksi4));
	
	mysqli_query($koneksi4, "ALTER TABLE RL2021 RENAME ".$tabel_rl."") or die(mysqli_error($koneksi4));
	
	mysqli_query($koneksi4, "UPDATE SYSDATA SET NAMA = '".$periodes."' WHERE KODE IN ('000','001')") or die(mysqli_error($koneksi4));

	mysqli_query($koneksi4, "UPDATE SYSDATA SET NAMA = '".$periodBegin."' WHERE KODE IN ('011')") or die(mysqli_error($koneksi4));
	
	//mysqli_query($koneksi4, "UPDATE SYSDATA SET NAMA = 'NYN' WHERE KODE IN ('010')") or die(mysqli_error($koneksi4));
			
	//koneksi database default periode def_accyymm
	$koneksi2 = mysqli_connect($hostname2,$username2, $password2, 'def_accyymm') or die(mysqli_connect_error());

	/*buat database user_accyymm*/
	$query = 'CREATE DATABASE '.$dbName2;
	mysqli_query($connCreateDB, $query) or die(mysqli_error($connCreateDB));
	
	$koneksi5 = mysqli_connect($hostname2,$username2,$password2,$dbName2) or die(mysqli_connect_error()); //buat koneksi baru dengan database yang telah dibuat

	//copy struktur database dari def_accyymm;
	$sql = mysqli_query($koneksi2,"SHOW TABLES");
	while($tables = mysqli_fetch_array($sql)){
		$table = $tables[0];
		$table = strtoupper($table);
		$table1 = strtolower($table);

		$tb = mysqli_fetch_array(mysqli_query($koneksi2, "SHOW CREATE TABLE $table1"));
		$create = str_replace("`$table1`","`$table`",$tb[1]);
		mysqli_query($koneksi5, "$create")or die(mysqli_error($koneksi5));		
	}

	//New Condition (01/04/2021)
	$tblacno = $dbMaster == 'def_acc' ? 'acno' : 'ACNO';
	mysqli_query($koneksi5, "TRUNCATE TABLE $dbMaster.$tblacno")or die(mysqli_error($koneksi5));
	$dbMasterPer = 'def_accyymm';
	if($versi == '1') {
		$tblAcnoPer = $dbMasterPer == 'def_accyymm' ? 'acno_v1' : 'ACNO_V1';
		mysqli_query($koneksi5, "INSERT INTO ACNO SELECT * FROM $dbMasterPer.$tblAcnoPer")or die(mysqli_error($koneksi5));
	} else if($versi == '2') {
		$tblAcnoPer = $dbMasterPer == 'def_accyymm' ? 'acno_v2' : 'ACNO_V2';
		mysqli_query($koneksi5, "INSERT INTO ACNO SELECT * FROM $dbMasterPer.$tblAcnoPer")or die(mysqli_error($koneksi5));
	} else if($versi == '3' && $acno == 'Y') {
		$tblAcnoPer = $dbMasterPer == 'def_accyymm' ? 'acno_v3' : 'ACNO_V3';
		mysqli_query($koneksi5, "INSERT INTO ACNO SELECT * FROM $dbMasterPer.$tblAcnoPer")or die(mysqli_error($koneksi5));
	}
	
}

function sendEmail($email, $message, $subject, $username, $password, $name, $inv_id=null, $lampiran=null){
	$mail = new PHPMailer(true);		

	try {
				
		$mail->SMTPDebug = 0;
		//$mail->isSMTP();
		$mail->Host 		= 'siscomonline.co.id';
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $username; //'no-reply@siscomonline.co.id';
		$mail->Password 	= $password; //'siscomnoplayoke515';
		$mail->SMTPSecure   = 'ssl';
		$mail->Port 		= 465;
		
		$mail->setFrom($username, $name);
		
		if (is_array($email)) {
            foreach ($email as $key => $value) {
                $mail->addAddress($value);
            }
        }
        else{
            $mail->addAddress($email);    
        }

		if ($lampiran != null) {
			$mail->addAttachment($lampiran);
		}
		
		$mail->Charset = 'utf-8';
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;
		// $mail->AltBody //Body Alternatif untuk nonHTML client

		$mail->send();
		$_SESSION['success'] = 'Your email has been sent.';
	  } catch (Exception $e) {
		  $_SESSION['error'] = 'Mailer error: '.$e->getMessage(); //'Mailer error:{$mail->ErrorInfo}';
	  }  
}

// function getUrlContent($url) {
//     $parts = parse_url($url);
//     $host = $parts['host'];
//     $ch = curl_init();
//     $header = array('GET /1575051 HTTP/1.1',
//         "Host: {$host}",
//         'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,/;q=0.8',
//         'Accept-Language:en-US,en;q=0.8',
//         'Cache-Control:max-age=0',
//         'Connection:keep-alive',
//         'Host:adfoc.us',
//         'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
//     );

//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//     $result = curl_exec($ch);
//     curl_close($ch);
//     return $result;
// }

function url_get_content($url) {
	if (!function_exists('curl_init')) {
		die('CURL is not installed! :(');
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
?>