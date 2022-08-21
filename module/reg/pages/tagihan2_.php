<?php 
	// $dbname = $_GET['dbname'];
	$npwp = '';
	$order_id = '';
	
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
		
		//Jenis Paket Database
		$sql = mysql_query("SELECT *, p.id as package_id FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id where ver_id=$_GET[versi] AND package_type=1 AND s.stsrec = 'A'");	   
		
		//ambil harga paket tambah user
		$sql2 = mysql_query("SELECT * FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id WHERE p.ver_id=$_GET[versi] 
		AND p.package_type=2 AND p.stsrec = 'A'");
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
		$sql = mysql_query("SELECT invoice.*, invd.package_id AS paket, invd.order_id, 
							invd.amount, 
							co.dbname, co.company_id, co.ver_id, co.package_id, 
							invd.begin_date, invd.end_date, invd.expired_date, co.used 
							FROM `invoice` 
							JOIN invoice_detail invd ON invoice.id=invd.inv_id 
        					JOIN cust_order co ON invd.order_id=co.order_id
    						WHERE invoice.inv_no='$_GET[invoice]'");
		$db = mysql_fetch_array($sql);
		$dbname = $db['dbname'];
		$company = $db['company_id'];
		$order_id = $db['order_id'];
		$begin_date = $db['begin_date'];
		$end_date = $db['end_date'];
		$used = $db['used'];
        $discount = $db['discount'];
		
        //ambil package type
        $sqlp = mysql_query("SELECT invd.package_id, p.package_type
                        FROM `invoice` inv
                        LEFT JOIN invoice_detail invd on invd.inv_id = inv.id
                        LEFT JOIN package p on p.id = invd.package_id
                        WHERE inv.inv_no = '$_GET[invoice]'") or die(mysql_error());
        $dataP = mysql_fetch_array($sqlp);
        $pkg_id = $dataP['package_id'];

        //ambil data database user
        $sql = mysql_query("select *, count(cs.order_id) AS jumlah_user FROM cust_order cs JOIN company ON cs.company_id=company.id WHERE cs.dbname='$dbname' and package_id='$pkg_id' GROUP BY cs.dbname") or die(mysql_error());
        $info = mysql_fetch_array($sql);
        $date = date('d-M-Y', strtotime($end_date)); //date('d-M-Y', strtotime($info['end_date']));
        // print_r($info);
		//ambil data database company
		$sql = mysql_query("SELECT * FROM `company` co WHERE id=$company");
		$co = mysql_fetch_array($sql);
		$npwp = $co['npwp_no'];
		
		//Jenis Paket Database
		$sql = mysql_query("SELECT *, p.id AS package_id FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id WHERE  ver_id= '$info[ver_id]' AND package_type=1 AND s.stsrec = 'A'") or die(mysql_error());	   
        // echo("SELECT *, p.id AS package_id FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id WHERE  ver_id= '$info[ver_id]' AND package_type=1 AND s.stsrec = 'A'");

		//ambil harga paket tambah user
		// $sql2 = mysql_query("SELECT * FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id where ver_id=$info[ver_id] and package_type=2 and s.stsrec = 'A'");
		// $hargaU = mysql_fetch_array($sql2);
        $sql2 = mysql_query("SELECT * FROM `package` p JOIN subscribe s ON p.subscribe_id=s.id where ver_id='$info[ver_id]' and package_type=2 and s.stsrec = 'A'") or die (mysql_error());
        $hargaU = mysql_fetch_array($sql2);
		$user = $info['jumlah_user'];
		$hargaUser =  $user* $hargaU['price'];
		$display = "none";	
		$action = 'editinvoice';
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
                        <input type="text" name="perusahaan" id="perusahaan" class="form-control" value="<?=$info['name'];?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-lg-3 col-sm-3">
                            Handphone
                        </label>
                        <input type="text" name="handphone" id="handphone" class="form-control" value="<?=$info['phone_no'];?>" readonly>
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
        <div class="row">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    Paket Aktivasi Database
                </div>
                <div class="panel-body">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                    <?php 
                        $i=1;
                        while ($row = mysql_fetch_array($sql)) {
                            $checked ='';
                            $subtotal = '000.000';
                            $harga = '000.000';
                            $total = '000.000';
                            $ppn = '000.000';
                            if (isset($_GET['invoice'])) {
                                $checked = ($db['paket']==$row['package_id']) ? 'checked':'';
                                $subtotal =number_format($db['amount'],2, ',', '.');
                                $harga = $db['amount'];
                                $total = number_format($db['total_amount'],2, ',', '.');
                                //$ppn = $harga * 10 / 100;
                                $ppn = $harga * 11 / 100;
                            }
                            $value = $row['value'];
                            $tgl = date('d-M-Y', strtotime("+$value days", strtotime($begin_date)));
							$tgl2 = date('Y-m-d', strtotime("+$value days", strtotime($begin_date)));
                    ?>
                        <label>
                        <input type="radio" name="package" value="<?=$row['package_id'];?>" onclick="hitung('<?=$row['price'];?>', '<?=$tgl;?>', '<?=$row['value'];?>')" <?=$checked;?>>
                            <strong><?=$row['name'];?> @ Rp. <?=number_format($row['price'], 2,',','.');?></strong>
                        </label><br>
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
                            <input type="checkbox" name="voucher" id="voucher" value="harga normal" > Gunakan Voucher
                            </label><br>
                            <input type="text" name="kode_voucher" id="kode_voucher" class="form-control" placeholder="Masukkan Kode Aktivasi" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    Rincian
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="70%">Paket Dasar : 1 Database + 1 User</td>
                                <td align="right">IDR</td>
                                <td align="right" id="harga"><?=number_format($harga,2, ',','.');?></td>
                            </tr>
                            <tr>
                                <td width="70%">Tambahan Akses User: <?=$user;?> User</td>
                                <td align="right">IDR</td>
                                <td align="right" id="hargaUser"><?=number_format($hargaUser, 2, ',','.');?></td>
                            </tr>
                            <tr>
                                <!-- <td colspan="3" align="right" id="voucher"><a>Gunakan Voucher</a></td> -->
                                <td align="right" width="70%">Discount</td>
                                <td align="right">IDR</td>
                                <td align="right" id="discount"><?=number_format($discount, 2, ',','.')?></td>
                            </tr>
                            <tr>
                                <td align="right" width="70%">Sub Total</td>
                                <td align="right">IDR</td>
                                <td align="right" id="subtotal"><?=$subtotal;?></td>
                            </tr>
                            <tr>
                                <td align="right" width="70%">PPN 10%</td>
                                <td align="right">IDR</td>
                                <td align="right" id="ppn"><?=number_format($ppn, 2,',','.');?></td>
                            </tr>
                            <tr>
                                <td align="right" width="70%"><strong>Total Biaya Aktivasi</strong></td>
                                <td align="right"><strong>IDR</strong></td>
                                <td align="right" id="totalharga"><strong><?=$total;?></strong></td>
                            </tr>
                            <tr>
                                <td align="right" width="70%"><strong>Aktif s/d </strong></td>
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
        <input type="hidden" name="amount" id="amount">
        <!-- <input type="hidden" name="send_voucher" id="send_voucher"> -->
        <input type="hidden" name="action" value="<?=$action;?>">
        <input type="hidden" name="activate" id="activate">
        <input type="hidden" name="pajak" id="pajak">
        <input type="hidden" name="order_id" value="<?=$order_id;?>">
        <input type="hidden" name="begin_date" value="<?=$begin_date;?>">
        <input type="hidden" name="end_date" id="val_end_date" value="<?=$date;?>">
        <input type="hidden" name="used" value="<?=$used;?>">
        <input type="hidden" name="jmlh_hari" id="jmlh_hari" value="<?=$value;?>">
        <input type="hidden" name="val_discount" id="val_discount" value="<?=$discount;?>">

        <?php if (isset($_GET['invoice'])): ?>
                    <input type="hidden" name="invoice" id="invoice" value="<?=$_GET['invoice'];?>">
        <?php endif ?>
    
        <div class="row" style="text-align: center;padding-bottom: 100px;">
            <div class="row">
                <div class="checkbox">
                    <label><input type="checkbox" name="term" id="term">&nbsp;Saya Telah Membaca dan Setuju dengan <a href="https://<?=$_SERVER['HTTP_HOST'];?>/siscomweb/info#syaratketentuan" target="_blank">Syarat dan Ketentuan yang Berlaku</a> pada layanan ini</label>	
                    
                </div>
            </div>
            
            <div class="row pull-right">
                <a href="info_tagihan.php?dbname=<?=$dbname;?>" class="btn btn-warning btn-lg" >Kembali</a>&emsp;
                <button class="btn btn-success btn-lg" id="aktifkan" disabled>Aktifkan</button>
            </div>
        </div>
    
    </form>
</div>