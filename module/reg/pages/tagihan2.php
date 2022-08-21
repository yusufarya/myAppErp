<?php 
	// $dbname = $_GET['dbname'];
	$npwp = '';
	$order_id = '';
	$totUser1 = 0;
	$totUser2 = 0;
	$totUser3 = 0;
	$verId = '';
	//echo $_SESSION['custEmail'];
    //jika belum ada tagihan
	if (isset($_GET['dbname']) && isset($_GET['versi'])) {
		// ambil informasi kontak 
		$dbname = $_GET['dbname'];
		//ambil data database user
		$sql = mysql_query("SELECT cs.*, c.*, COUNT(cs.order_id) AS jumlah_user FROM cust_order cs JOIN company c on cs.company_id=c.id WHERE cs.dbname='$dbname' GROUP BY cs.dbname") or die(mysql_error());
		$info = mysql_fetch_array($sql);
		$date = date('d-M-Y', strtotime($info['end_date']));
        $order_id = $info['order_id'];
		$begin_date = $info['begin_date'];
		$end_date = $info['end_date'];
		$used = $info['used'];
		$db_date = $info['db_date'];
		$created_on = $info['created_on'];
		$beginPeriod = $info['begin_date'];

		//Jenis Paket Database
		$qr2 = "SELECT p.id AS package_id,
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type    
				FROM package p 
				JOIN subscribe s ON p.subscribe_id = s.id 
				WHERE p.package_type IN ('1','3') AND p.ver_id = '$_GET[versi]' 
				AND s.stsrec = 'A'";
		$sql = mysql_query($qr2);

		//$sql = mysql_query("SELECT *, p.id as package_id FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id where ver_id=$_GET[versi] AND package_type=1 AND s.stsrec = 'A'");
		
		//ambil harga paket tambah user
		if($created_on < '2021-02-01') {
			$qr3 = "SELECT *, p.id AS package_id   
					FROM `package` p JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.ver_id = '$verId' AND p.package_type IN ('1','3') 
					AND begin_date <= CURDATE() AND end_date >= CURDATE() 
					AND s.stsrec = 'A'";	
		} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2021-11-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2021-11-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$_GET[versi]' 
					AND s.stsrec = 'A'";
		} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2022-01-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '2022-01-01'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$_GET[versi]' 
					AND s.stsrec = 'A'";
		} else {
			$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE()  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$_GET[versi]' 
					AND s.stsrec = 'A'";
		}
		$sql2 = mysql_query($qr3);
		//$sql2 = mysql_query("SELECT * FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id WHERE p.ver_id=$_GET[versi] 
		//AND p.package_type=2 AND p.stsrec = 'A'");
		$hargaU = mysql_fetch_array($sql2);
		$user = $info['jumlah_user']-1;
		$hargaUser =  $user* $hargaU['price'];
		
		$display = "block"; 
		$action = 'simpaninvoice';
		$required = "required";
	}
    //jika sudah ada invoice
	else if (isset($_GET['invoice'])) {
		$required = "";
		$strqry = "SELECT iv.id, iv.inv_no, iv.inv_date, iv.due_date, ivd.order_id, co.dbname, co.company_id, 
                    ivd.begin_date, ivd.end_date, co.used, iv.discount, iv.total_amount, iv.ppn, iv.discount,
                    iv.initial_amount, ivd.amount, ivd.package_id AS paket, p.package_type, iv.voucher_code, 
                    s.value_month, p.subscribe_id, co.db_date, co.created_on, pt.name AS nama_paket, 
                    co.total_branch, ivd.subscribe_times             
                    FROM invoice iv 
                    LEFT JOIN invoice_detail ivd ON ivd.inv_id = iv.id 
                    LEFT JOIN cust_order co ON co.order_id = ivd.order_id 
                    LEFT JOIN package p ON p.id = ivd.package_id 
                    LEFT JOIN package_type pt ON pt.id = p.package_type 
                    LEFT JOIN subscribe s on s.id = p.subscribe_id					
					WHERE iv.inv_no = '$_GET[invoice]'";
		$sql = mysql_query($strqry);
		$db = mysql_fetch_array($sql);
		$inv_id = $db['id'];
		$dbname = $db['dbname'];
		$company = $db['company_id'];
		$order_id = $db['order_id'];
		$begin_date = $db['begin_date'];
		$end_date = $db['end_date'];
		$package_type_inv = $db['package_type'];
		$nama_paket = $db['nama_paket'];
		$add_branch = $db['total_branch'] - 1;
		$used = $db['used'];
        $discount = $db['discount'];
		$inv_date = $db['inv_date'];
		$due_date = $db['due_date'];
		$begin_date = $db['begin_date'];
		$end_date = $db['end_date'];
        $voucher_code = $db['voucher_code'];
        $subscribeID = $db['subscribe_id'];
        $subscribe_times = $db['subscribe_times'];
        $none = 'none';
        $disabled1 = '';
        if($voucher_code != NULL){
            $disabled1 = 'disabled';
            $none = '';
        }
        $ppn = $db['ppn'];
        $ppn_value = ($db['ppn']/($db['total_amount']-$db['discount']))*100;
        $ppn_percent = 11;

        $beginPeriod = $db['begin_date'];
        $db_date = $db['db_date'];
        $created_on = $db['created_on'];

        if($package_type_inv == '5') {
        	$item = 'Cabang';
        } else {
        	$item = 'User';
        }

        //ambil data Tambahan User
        $sqluser = mysql_query("SELECT inv.id, inv.inv_no, invd.id AS invd_id, invd.order_id, co.cust_id, c.email, p.package_type
					            FROM `invoice` inv
					            LEFT JOIN invoice_detail invd on invd.inv_id = inv.id
					            LEFT JOIN cust_order co on co.order_id = invd.order_id
					            LEFT JOIN cust c on c.id = co.cust_id
					            LEFT JOIN package p on p.id = invd.package_id
					            WHERE inv.inv_no = '$_GET[invoice]' and p.package_type = '2'") or die (mysql_error());
        $jumlah_user = mysql_num_rows($sqluser);
       	
		//ambil data database user
		$totUser1 = 0;
		$totUser2 = 0;
		$totUser3 = 0;
		$verId = '';
		$strcnt = "SELECT iv.inv_no, cs.company_id, cs.ver_id, v.name AS ver_name,
					p.package_type, cs.package_id, p.subscribe_id,  
					COUNT(cs.order_id) AS jumlah_user FROM cust_order cs 
					LEFT JOIN company co ON cs.company_id = co.id 
					LEFT JOIN invoice_detail id ON id.order_id = cs.order_id 
					LEFT JOIN invoice iv ON iv.id = id.inv_id 
					LEFT JOIN package p ON p.id = cs.package_id 
					LEFT JOIN version v ON v.id = cs.ver_id 
					WHERE cs.dbname = '$dbname' 
					AND iv.inv_no = '$_GET[invoice]'  
					GROUP BY iv.inv_no, cs.dbname, cs.order_id, p.package_type ";
		$sql = mysql_query($strcnt) or die(mysql_error());
		//$info = mysql_fetch_array($sql);
		while ($info = mysql_fetch_array($sql)) {
			if($info['package_type'] == 1 or $info['package_type'] == 3) {
				$totUser1 = 1;
			} else if($info['package_type'] == 2) {
				$totUser2 = $jumlah_user;
			} else {
				$totUser3 = $info['jumlah_user'];
			}
			$verId = $info['ver_id'];
			$verName = $info['ver_name'];
			// $subscribeID = $info['subscribe_id'];
		}
		
		$packageDisabled = '';
		$panelType = 'panel-info';
		if($totUser1 == 0) {
			if($totUser2 > 0) {
				$packageDisabled = 'disabled';
				$panelType = 'panel-danger';
			} else {
				$packageDisabled = '';
				$panelType = 'panel-info';
			}
		}

		if($package_type_inv == 5) {
			$panelType = 'panel-danger';
		}
		
		$date = date('d-M-Y', strtotime($end_date)); //date('d-M-Y', strtotime($info['end_date']));

		//ambil data database company
		$sql = mysql_query("SELECT * FROM `company` co WHERE id = '$company'");
		$co = mysql_fetch_array($sql);
		$company_name = $co['name'];
		$company_phone = $co['phone_no'];
		$npwp = $co['npwp_no'];
		
		//Jenis Paket 1 Database + 1 User
		/*if($created_on < '2021-02-01') {
			$qr2 = "SELECT *, p.id AS package_id   
					FROM `package` p JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.ver_id = '$verId' AND p.package_type IN ('1','3') 
					AND begin_date <= CURDATE() AND end_date >= CURDATE() 
					AND s.stsrec = 'A'";	
		} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			$qr2 = "SELECT p.id AS package_id, s.id AS subscribe_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '$created_on'   
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '$created_on' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type IN ('1','3') AND p.ver_id = '$verId' 
	 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
					AND s.stsrec = 'A'";
		} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			$qr2 = "SELECT p.id AS package_id, s.id AS subscribe_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '$created_on'   
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '$created_on' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type IN ('1','3') AND p.ver_id = '$verId' 
	 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
					AND s.stsrec = 'A'";
		} else {
			/*$qr2 = "SELECT p.id AS package_id, s.id AS subscribe_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type IN ('1','3') AND p.ver_id = '$verId' 
	 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
					AND s.stsrec = 'A'";*/
			/*$qr2 = "SELECT p.id AS package_id, s.id AS subscribe_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type IN ('1','3') AND p.ver_id = '$verId' 
	 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
					AND s.stsrec = 'A'";*/
		//}
		$qr2 = "SELECT p.id AS package_id, s.id AS subscribe_id,
				(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
				(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
				ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
				FROM package p 
				JOIN subscribe s ON p.subscribe_id = s.id 
				WHERE p.package_type IN ('1','3') AND p.ver_id = '$verId' 
 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
				AND s.stsrec = 'A'";
		$sql1 = mysql_query($qr2);

		//Jenis Paket Tambah User 
		/*if($created_on < '2021-02-01') {
			$qr3 = "SELECT p.id AS package_id, p.price FROM `package` p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND  p.ver_id = '$verId' 
					AND s.stsrec = 'A'";
		} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
			$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '$created_on'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '$created_on'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type     
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
					AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
					AND s.stsrec = 'A'";
		} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
			$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '$created_on'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '$created_on'  
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type     
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
					AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
					AND s.stsrec = 'A'";
		} else {
			/*$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type     
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
					AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
					AND s.stsrec = 'A'";*/
			/*$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2022-01-01' AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type     
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
					AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
					AND s.stsrec = 'A'";*/
		//}
		//Jenis Paket Tambah User 
		$qr3 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type     
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '2' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
					AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
					AND s.stsrec = 'A'";
		$sql2 = mysql_query($qr3);	   
		$row2 = mysql_fetch_array($sql2);
		$harga2 = $row2['price'];

		if($dbname == 'db') {	//Rotary Created: 2020-09-08
			$harga2 = '100000';
		}

		//Jenis Paket Tambah Cabang 
		$qr5 = "SELECT p.id AS package_id,
					(SELECT pr.begin_date FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS begin_date, 
					(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
					ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type     
					FROM package p 
					JOIN subscribe s ON p.subscribe_id = s.id 
					WHERE p.package_type = '5' AND p.ver_id = '$verId' AND p.subscribe_id = '$subscribeID' 
					AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE() 
					AND s.stsrec = 'A'";
		$sql5 = mysql_query($qr5);	   
		$row5 = mysql_fetch_array($sql5);
		$harga5 = $row5['price'];

		if($dbname == 'db') {	//Rotary Created: 2020-09-08
			$harga5 = '18000';
		}
		
		$hargaUser = $totUser2 * $row2['price'];
		$display = "none";	
		if($package_type_inv == '1' || $package_type_inv == '3') {
			$action = 'editinvoice';
		} else if($package_type_inv == '2' || $package_type_inv == '5') {
			$action = 'saveinvoice';
		}
	}
?>
<div class="container">
    <form id="infotagihan" enctype="multipart/form-data" method="post" action="action.php">
        <div class="row" id="kontak" style="display: <?=$display;?>">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    Informasi Kontak
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Nama
                        </label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?=$_SESSION['custName'];?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Nama Perusahaan
                        </label>
                        <input type="text" name="perusahaan" id="perusahaan" class="form-control" value="<?=$company_name;?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Handphone
                        </label>
                        <input type="text" name="handphone" id="handphone" class="form-control" value="<?=$company_phone;?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Email
                        </label>
                        <input type="text" name="email" id="email" class="form-control" value="<?=$_SESSION['custEmail'];?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Nama Database
                        </label>
                        <input type="text" name="dbname" id="dbname" class="form-control" value="<?=$dbname;?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Upload KTP
                        </label>
                        <input type="file" name="ktp" id="ktp" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <a class="pull-right" href="#" id="ubah" style="font-size: 14px"> Ubah</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="row" id="faktur">
            <div class="panel panel-primary">
                <div class="panel-heading" style="text-align: left; padding-left: 30px">
                    <?=$_GET['invoice']?>
                </div>
                <div class="panel-body">                  
                    <div class="form-group">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label class="control-label col-md-3 col-lg-3 col-sm-3" style="margin-left: -10px; padding: 5px;">
								Tanggal Tagihan
							</label>
							<input type="text" class="form-control" value="<?=date("d-M-Y",strtotime($inv_date))?>" style="border:none; background-color:transparent !important; text-decoration:none" readonly>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label class="control-label col-md-3 col-lg-3 col-sm-3" style="margin-left: -10px; padding: 5px;">
								Jatuh Tempo
							</label>
							<input type="text" class="form-control" value="<?=date("d-M-Y",strtotime($due_date))?>" style="border:none; background-color:transparent !important; text-decoration:none" readonly>
						</div>
					</div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6">
							<label class="control-label col-md-3 col-lg-3 col-sm-3" style="margin-left: -10px; padding: 5px;">
								Database
							</label>
							<input type="text" class="form-control" value="<?=$dbname?>" style="border:none; background-color:transparent !important; text-decoration:none" readonly>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label class="control-label col-md-3 col-lg-3 col-sm-3" style="margin-left: -10px; padding: 5px;">
								Versi
							</label>
							<input type="text" class="form-control" value="<?=$verName;?>" style="border:none; background-color:transparent !important; text-decoration:none" readonly>
						</div>
					</div>
					<div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6">
							<label class="control-label col-md-3 col-lg-3 col-sm-3" style="margin-left: -10px; padding: 5px;">
								Awal Periode
							</label>
							<input type="text" class="form-control" value="<?=date("d-M-Y",strtotime($begin_date))?>" style="border:none; background-color:transparent !important; text-decoration:none" readonly>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label class="control-label col-md-3 col-lg-3 col-sm-3" style="margin-left: -10px; padding: 5px;">
								Akhir Periode
							</label>
							<input type="text" class="form-control" value="<?=date("d-M-Y",strtotime($end_date))?>" style="border:none; background-color:transparent !important; text-decoration:none" readonly>
						</div>
					</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel <?=$panelType?>">
                <div class="panel-heading" style="text-align: left; padding-left: 30px">
                    Basic Package
                </div>
                <div class="panel-body">
                    <div id="radioPackage" class="col-lg-6 col-md-6 col-sm-6">
                    <?php 
                        $i=1;
                        while ($row = mysql_fetch_array($sql1)) {
                            $checked ='';
                            $subtotal = '000.000';
                            $harga = '000.000';
                            $total = '000.000';
                            $ppn = '000.000';
							$subscribe_name = '';
                            $value_month = $row['value_month'];
                            $subsc_id = '1';

                            if (isset($_GET['invoice'])) {
                                $checked = ($db['paket']==$row['package_id']) ? 'checked':'';
								if($checked == 'checked') {
									$subscribe_name = $row['name'];
								}

                                $subtotal = number_format($db['amount'],2, ',', '.');
                                $harga = $totUser1 * $db['amount'];
                                if($package_type_inv == 5) {
                                	$harga = 0;
                            	}
                                $total = number_format($db['total_amount']+$db['ppn']-$db['discount'],2, ',', '.');
                                $ppn = $db['ppn'];
                                $subsc_id = $row['subscribe_id'];
                            }
                            $value = $row['value'];
                            $tgl = date('d-M-Y', strtotime("+$value days", strtotime($begin_date)));
							$tgl2 = date('Y-m-d', strtotime("+$value days", strtotime($begin_date)));
							
							// get harga tambah user
							/*if($created_on < '2021-02-01') {
								$sqlUsr = "SELECT p.id AS package_id, p.price FROM `package` p 
											JOIN subscribe s ON p.subscribe_id = s.id 
											WHERE p.package_type = '2' AND  p.ver_id = '$verId' 
											AND s.stsrec = 'A'";
							} else if($created_on >= '2021-02-01' and $created_on < '2021-11-01') {
								$sqlUsr = "SELECT p.id AS package_id, s.id AS subscribe_id,
											(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-02-01' AND pr.begin_date < '".$created_on."'   
											ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
											FROM package p 
											JOIN subscribe s ON p.subscribe_id = s.id 
											WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.id = '$subsc_id' 
							 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
											AND s.stsrec = 'A'";
							} else if($created_on >= '2021-11-01' and $created_on < '2022-01-01') {
								$sqlUsr = "SELECT p.id AS package_id, s.id AS subscribe_id,
											(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date < '".$created_on."'    
											ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
											FROM package p 
											JOIN subscribe s ON p.subscribe_id = s.id 
											WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.id = '$subsc_id' 
							 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
											AND s.stsrec = 'A'";
							} else {
								/*$sqlUsr = "SELECT p.id AS package_id, s.id AS subscribe_id,
											(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= '$beginPeriod' 
											ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
											FROM package p 
											JOIN subscribe s ON p.subscribe_id = s.id 
											WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.id = '$subsc_id' 
							 				AND s.begin_date <= '$beginPeriod' AND s.end_date >= '$beginPeriod'
											AND s.stsrec = 'A'";*/
								/*$sqlUsr = "SELECT p.id AS package_id, s.id AS subscribe_id,
											(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date >= '2021-11-01' AND pr.begin_date <= CURDATE() 
											ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
											FROM package p 
											JOIN subscribe s ON p.subscribe_id = s.id 
											WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.id = '$subsc_id' 
							 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
											AND s.stsrec = 'A'";*/
							//}
							// get harga tambah user
							$sqlUsr = "SELECT p.id AS package_id, s.id AS subscribe_id,
										(SELECT pr.price FROM package_price pr WHERE pr.package_id = p.id AND pr.begin_date <= CURDATE() 
										ORDER BY pr.begin_date DESC LIMIT 1) AS price, s.name, s.description, s.value, s.value_month, p.package_type   
										FROM package p 
										JOIN subscribe s ON p.subscribe_id = s.id 
										WHERE p.package_type = '2' AND p.ver_id = '$verId' AND s.id = '$subsc_id' 
						 				AND s.begin_date <= CURDATE() AND s.end_date >= CURDATE()
										AND s.stsrec = 'A'";
							$qrU = mysql_query($sqlUsr);
							$rowUsr = mysql_fetch_array($qrU);
							$harga2 = $rowUsr['price'];
							$hargaUser2 = $totUser2 * $rowUsr['price'];			
                    ?>
                        <label>
                        <input type="radio" id="package" name="package" value="<?=$row['package_id'];?>" onclick="hitung('<?=$row['price'];?>', '<?=$tgl;?>', '<?=$row['value'];?>', '<?=$value_month?>', '<?=$harga2?>', '<?=$totUser2?>', '<?=$row['package_type'];?>', '<?=$row['name'];?>')" <?=$checked;?> <?=$packageDisabled;?>>
                            <strong><?=$row['name'];?> @ Rp. <?=number_format($row['price'], 2,',','.');?></strong>
                        </label><br> <!-- <span><?=$value_month?></span> -->
                        <small>*<?=$row['description'];?></small><br><br>
                    <?php
                        }
                    ?>	
                    </div>
                    <!-- <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="form-group">
                            <label>
                            <input type="radio" name="paket" id="aktivasi" value="harga normal"> Gunakan Kode Aktivasi
                            </label><br>
                            <input type="text" name="kode_aktivasi" id="kode_aktivasi" class="form-control" placeholder="Masukkan Kode Aktivasi" disabled>
                        </div>                     
                    </div> -->
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="form-group">
                            <label>
                            <input type="checkbox" name="voucher" id="voucher" value="harga normal"> Gunakan Voucher
                            </label><br>
                            <input type="text" name="kode_voucher" id="kode_voucher" class="form-control" placeholder="Masukkan Kode Voucher" autocomplete="off" disabled>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                    	<div class="form-group">
                        	<button type="button" class="btn btn-info btn-sm" style="margin-top: 26px;margin-left: -70px;" data-toggle="modal" data-target="#modalVoucher"><i class="fa fa-info"></i> Info</button>
                    	</div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3" style="padding: 35px 0px; display: <?=$none?>">
                        <div class="form-group">
                            <label>
                                Kode Voucher :&emsp;<span id="voucherAwal"><?=$voucher_code;?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading" style="text-align: left; padding-left: 30px">
                    Rincian Tagihan
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td colspan="2" width="70%"><b>Paket Dasar</b>&emsp;&emsp;&emsp;&nbsp; <span id="nmPaket"><?=$subscribe_name?></span></td>
                                <td align="right">IDR</td>
                                <td align="right" id="harga"><?=number_format($harga,2, ',','.');?></td>
                            </tr>
                            <?php if($package_type_inv == '5') { ?>
	                            <tr>
	                                <td colspan="2" width="70%"><b><?=$nama_paket?></b>&emsp;&emsp;&nbsp; <span id="totuser"><?=$add_branch;?></span> <?=$item?> @ Rp. <span id="hargaUser"><?=number_format($harga5, 2,',','.');?></span>&nbsp;x&nbsp;
	                                    <span id="bulan"><?=$subscribe_times?></span>&nbsp;Bulan
	                                </td>
	                                <td align="right">IDR</td>
	                                <td align="right" id="harga-totuser"><?=number_format($add_branch * $harga5 * $subscribe_times, 2, ',','.');?></td>
	                            </tr>
                            <?php } else { ?>
	                            <tr>
	                                <td colspan="2" width="70%"><b><?=$nama_paket?></b>&emsp;&emsp;&nbsp; <span id="totuser"><?=$totUser2;?></span> <?=$item?> @ Rp. <span id="hargaUser"><?=number_format($harga2, 2,',','.');?></span>&nbsp;x&nbsp;
	                                    <span id="bulan"><?=$subscribe_times?></span>&nbsp;Bulan
	                                </td>
	                                <td align="right">IDR</td>
	                                <td align="right" id="harga-totuser"><?=number_format($totUser2 * $harga2 * $subscribe_times, 2, ',','.');?></td>
	                            </tr>
                        	<?php } ?>
                            <tr>
                                <td rowspan="5">
                                    <?php while ($data = mysql_fetch_array($sqluser)) {
                                        $invd_id = $data['invd_id'];
                                        if($totUser2 != 0){
                                    ?>  
                                        <div class="tags" style="border: 0px; width: auto;">
                                            <span class="tag"><?=$data['email'];?><button type="button" class="close" title="Hapus tagihan user!" value="" onclick="del_tag(this, '<?=$invd_id?>')">×</button></span>
                                        </div>
                                    <?php 
                                        }
                                    } ?>
                                </td>
                                <td align="right" width="20%">Potongan Voucher</td>
                                <td align="right">IDR</td>
                                <td align="right" id="discount">- <?=number_format($discount, 2, ',','.')?></td>
                            </tr>
                            <tr>
                                <td align="right" width="20%">Sub Total</td>
                                <td align="right">IDR</td>
                                <td align="right" id="subtotal"><?=number_format(($harga + ($hargaUser * $subscribe_times) - $discount), 2, ',','.');?></td>
                            </tr>
                            <tr>
                                <td align="right" width="20%">PPN <?=$ppn_value?>%</td>
                                <td align="right">IDR</td>
                                <td align="right" id="ppn"><?=number_format($ppn, 2,',','.');?></td>
                            </tr>
                            <tr>
                                <td align="right" width="20%"><strong>Total</strong></td>
                                <td align="right"><strong>IDR</strong></td>
                                <td align="right" id="totalharga"><strong><?=$total;?></strong></td>
                            </tr>
                            <tr>
                                <td align="right" width="20%"><strong>Aktif s/d </strong></td>
                                <td align="right"></td>
                                <td align="right" id="end_date"><?=$date;?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" style="display: <?=$display;?>">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Faktur Pajak
                </div>
                <div class="panel-body">
                    <div class="checkbox">
                        <label class="control-label">Apakah Anda membutuhkan faktur pajak?&emsp;</label>
                        <label><input type="checkbox" name="faktur" id="faktur" value="Y">Ya</label>	
                    </div>
                    <div id="form-faktur" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-lg-3 col-sm-3">
                                NPWP
                            </label>
							<input type="text" name="npwp" id="npwp" class="form-control" placeholder="NPWP" value="<?=$npwp?>" />
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-lg-3 col-sm-3">
                                Jenis Faktur
                            </label>
                        
                            <select class="form-control" name="jenisfaktur">
                                <option value="">-- Pilih Jenis Faktur --</option>
                        <?php 
                            $sql = mysql_query("select * from taxpayer_type") or die(mysql_error());
                            while ($row = mysql_fetch_array($sql)) {
                        ?>
                                <option value="<?=$row['id'];?>"><?=$row['name'];?></option>
                        <?php
                            }
                        ?>                     
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-lg-3 col-sm-3">
                                Upload NPWP
                            </label>
                            <input type="file" name="npwp" id="npwp" class="form-control">
                        </div>						
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="amount" id="amount" value="<?=($harga + $hargaUser);?>">
        <!-- <input type="hidden" name="send_voucher" id="send_voucher"> -->
        <input type="hidden" name="action" value="<?=$action;?>">
        <input type="hidden" name="activate" id="activate">
        <input type="hidden" name="pajak" id="pajak" value="<?=$ppn;?>">
        <input type="hidden" name="order_id" value="<?=$order_id;?>">
        <input type="hidden" name="begin_date" value="<?=$begin_date;?>">
        <input type="hidden" name="end_date" id="val_end_date" value="<?=$date;?>">
        <input type="hidden" name="used" value="<?=$used;?>">
        <input type="hidden" name="jmlh_hari" id="jmlh_hari" value="<?=$value;?>">
        <input type="hidden" name="val_discount" id="val_discount" value="<?=$discount;?>">
		<input type="hidden" name="totuser1" value="<?=$totUser1;?>">
		<input type="hidden" name="totuser2" value="<?=$totUser2;?>">
        <input type="hidden" name="inv_id" id="inv_id" value="<?=$inv_id?>">
        <input type="hidden" name="subscribe_times" id="subscribe_times" value="<?=$subscribe_times?>">
        <input type="hidden" name="package_type" id="package_type" value="<?=$package_type_inv?>">
        <input type="hidden" name="add_branch" id="add_branch" value="<?=$add_branch?>">

        <?php if (isset($_GET['invoice'])): ?>
                    <input type="hidden" name="invoice" id="invoice" value="<?=$_GET['invoice'];?>">
        <?php endif ?>
    
        <div class="row" style="text-align: center;padding-bottom: 100px;">
            <div class="row">
                <div class="checkbox">
                    <label style="color: white"><input type="checkbox" name="term" id="term">&nbsp;Saya Telah Membaca dan Setuju dengan <a href="https://<?=$_SERVER['HTTP_HOST'];?>/siscomweb/info#syaratketentuan" target="_blank"><font color="yellow">Syarat dan Ketentuan yang Berlaku</font></a> pada layanan ini</label>	
                    
                </div>
            </div>
            
            <div class="row pull-right">
                <button class="btn btn-success btn-lg" id="aktifkan" disabled>Simpan</button>&emsp;
                <a href="info_tagihan.php?dbname=<?=$dbname;?>" class="btn btn-warning btn-lg" >Kembali</a>
            </div>
        </div>
    
    </form>
</div>

<div class="modal fade" id="modalVoucher" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Daftar Voucher</h4>
        </div>
        <div class="modal-body" style="max-height: 500px;overflow-y: scroll;">
          <!-- <input id="voucher1" type="text" value="JSADHJS"> <button type="button" class="btn btn-info btn-sm" style="margin-top: -3px;" onclick="copyVoucher('#voucher1')"> Copy</button> -->
            <div class="row">
          		<?php 
                    $sql = mysql_query("SELECT id,name,begin_date,expired_date,description FROM voucher WHERE stsrec = 'A' ORDER BY begin_date, expired_date") or die(mysql_error());
                    while ($row = mysql_fetch_array($sql)) {
                    	$idVoucher = '#'.$row['id'];
                    	$begin_date = date('d-M-Y', strtotime($row['begin_date']));
                    	$expired_date = date('d-M-Y', strtotime($row['expired_date']));
                ?>
                		<div class="panel" style="border: 1px solid #eaeaea;margin: 10px 30px;padding: 20px; position: relative;box-shadow: 0 1px 5px #eaeaea;">
                            <!-- <input id="<?=$row['id']?>" value="<?=$row['name']?>" type="text" readonly=""> -->
                            <h6 style="color: gray;">Kode &emsp;&emsp;&emsp;&nbsp;:&emsp;<?=$row['name']?></h6>
						    <h6 style="color:gray">Tgl berlaku &nbsp;&nbsp;:&emsp;<?=$begin_date?> s/d <?=$expired_date?></h6>
							<button type="button" class="btn btn-info btn-sm" style="float: right;position: absolute;right: 20px;top: 35%;" onclick="copyVoucher(`<?=$idVoucher?>`)"> Copy</button>
							<h6 style="color: gray;">Deskripsi &emsp;&nbsp;:&emsp;<?=$row['description']?></h6>
						</div>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div>

<script type="text/javascript">
	function copyVoucher(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).val()).select();
		document.execCommand("copy");
		$temp.remove();
	}
</script>