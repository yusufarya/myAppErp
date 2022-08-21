<?php 
	session_start();
	$host=$_SERVER['SERVER_NAME'];
	include 'includes/style.php';
	require_once '../includes/koneksi2.php';
	
	if (isset($_GET['dbname'])) {
		$dbname = $_GET['dbname'];
		
		$queryBank = mysql_query("SELECT A.*, A.id AS id_bank, A.bank_account AS namanasabah, B.name AS nama_bank, B.img FROM `contact_bank` A LEFT JOIN bank B ON B.code = A.bank_code WHERE view = 1 AND A.stsrec= 'A' ORDER BY A.id ASC") or die(mysql_error());

		$query = mysql_query("SELECT cust.name, inv.inv_no, inv.ppn, inv.paid_off, co.dbname, inv.total_amount,inv.paid_date, inv.initial_amount, inv.discount, (inv.total_amount + inv.ppn + inv.initial_amount - inv.discount) AS grand_total 
					FROM `invoice` inv 
					JOIN cust on inv.cust_id=cust.id 
	    			JOIN invoice_detail invd on invd.inv_id=inv.id 
	    			JOIN cust_order co ON co.order_id=invd.order_id 
	    		 	where co.dbname='$dbname' and co.active='A' and inv.stsrec='A' order by inv_date desc limit 1") or die(mysql_error());

	    $data = mysql_fetch_array($query);
	    $paid_date = date('d-m-Y', strtotime($data['paid_date']));
	    $grand_total = number_format($data['grand_total'],2, ',','.');
	    $paid_off = $data['paid_off'];
	}
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>SISCOM ONLINE</title>

		<!-- form element -->
		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!-- form widget -->
		<meta name="description" content="and Validation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="assets/css/select2.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="assets/js/ace-extra.min.js"></script>
		<!-- <script src="js/jquery.js"></script> -->

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			.panel-heading{
				font-size: 18px;
				text-align: center;
				padding: 10px auto;

			}
			#tagihan{
				font-size: 16px;
				text-align: center;
				padding: 40px !important;
			}
			span.nominal{
				font-size: 30px;
				font-weight: bold;
			}
			span.keterangan{
				font-size: 12px;
				font-weight: lighter;
			}
			.container{
				margin-top: 30px;
			}
			.control-label{
				text-align:left !important;
			}
			.form-control{
				width: 70% !important;
			}
			.table tr,td{
				border:0 !important;
			}
			#voucher a{
				/*font-style: italic;*/
				font-weight: 600;
			}
			.navbar {
				background-color: #ffffff00;
				box-shadow:none !important;
			}
			.ace-nav>li.light-blue>a {
			    background-color: #62a8d130;
			}
			
		</style>

	</head>

	<body class="main-container">
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<!--<a href="account.php"><img class="pos_img" src="../img/LOGO-SISCOM.png" style="max-height: 60px"></a>-->
							<a href="account.php"><img class="pos_img" src="<?=$abs?>/reg/img/LOGO-SISCOM.png" style="max-height: 60px"></a>
						</small>
					</a>

					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>

						<img src="assets/images/avatars/avatar4.png" />
					</button>

					<!-- <button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>
					</button> -->
				</div>

				<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">
						<?php include 'notification.php'; ?>

						<li class="light-blue dropdown-modal" style="height: 60px">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="height: 60px">
								<img class="nav-user-photo" src="assets/images/avatars/avatar4.png" alt="Jason's Photo" style="margin-top: 10px" />
								<span class="user-info">
									<small style="margin-top: 10px">Welcome,</small>
									<?php echo $_SESSION['custName'];  ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!--<li>
									<a href="#">
										<i class="ace-icon fa  fa-credit-card"></i>
										Penagihan
									</a>
								</li>-->
								
								<li>
									<a href="ubah_profil.php">
										<i class="ace-icon fa fa-user"></i>
										Profil
									</a>
								</li>
								
								<li>
									<a href="ubah_password.php">
										<i class="ace-icon fa fa-key"></i>
										Ubah Password
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="../logout.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
		

		<?php 
			if (isset($_GET['versi']) || isset($_GET['invoice'])) {
				require_once "tagihan2.php";
			}else{
				require_once "tagihan1.php";
			}
		?>

		<script src="assets/js/jquery-2.1.4.min.js"></script>
		
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<script src="assets/js/wizard.min.js"></script>
		<script src="assets/js/jquery.validate.min.js"></script>
		<script src="assets/js/jquery-additional-methods.min.js"></script>
		<script src="assets/js/bootbox.js"></script>
		<script src="assets/js/jquery.maskedinput.min.js"></script>
		<script src="assets/js/select2.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>
		<script src="assets/sweetalert/sweetalert2.all.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			// $('#back').on('click', function(evt){
			// 	evt.preventDefault();

			// 	window.location.href = "http://<?=$host;?>/siserp/module/reg/pages/account.php";
			// })
			
			//tampung id invoice_detail user
			var invd_id = [];

			$(document).ready(function(){
				$('#radioPackage').find('input[type=radio]').each(function(index){
					if($(this).prop('checked') == true){
						$(this).click()
					}
				})
			})

			$('#ubah').on('click', function(){
				if($(this).text()=='Ubah'){
					$(this).text('Batal')
					$('#kontak input').removeAttr('readonly');	
				}else{
					$(this).text('Ubah')
					$('#kontak input').attr('readonly', 'true');	
				}
				
			});

			$('#faktur').on('click', function(){
				var checked = $('#faktur').is(':checked');
				if (checked) {
					$('#form-faktur').show();
					$('#form-faktur input').prop('required', true);
				}else{
					$('#form-faktur').hide();
				}
			})

			$(document).on('click', function(){
				var aktivasi = $('#aktivasi').is(':checked');
				if (aktivasi) {
					$('#kode_aktivasi').prop('disabled', false);
				}else{
					$('#kode_aktivasi').prop('disabled', true);
				}

				var aktivasi2 = $('#voucher').is(':checked');
				if (aktivasi2) {
					$('#kode_voucher').prop('disabled', false);
				}else{
					$('#kode_voucher').prop('disabled', true);

					$('#kode_voucher').val('');
					$('#discount').text('- '+convertToRupiah(0))
					$('#val_discount').val(0);

					harga = $('#harga').text().replace(/[^\d,]+/g,'').replace(',','.');
					let hargauser = parseInt($('#hargaUser').text().replace(/[^\d,]+/g,'').replace(',','.'));
					let totuser = $('#totuser').text()
					var value_month = parseInt($('#bulan').text().replace(/[^\d,]+/g,'').replace(',','.'));
					var nmPaket = $('#nmPaket').text();
					//hitung(harga,'','',value_month,hargatotuser,totuser);
					hitung(harga, '', '', value_month, hargauser, totuser, '1', nmPaket, '');
				}

				/* jika tombol persyaratan tidak diceklis atau paket tidak dipilih maka tombol aktifkan di disabled*/
				var totuser2 = $('input[name=totuser2]').val()

				//if ($('#term').is(':checked') && $('input[name=package]').is(':checked') || $('#term').is(':checked') && totuser2 !=0) {
				if ($('#term').is(':checked')) {
					$('#aktifkan').prop('disabled', false);
				}else{
					$('#aktifkan').prop('disabled', true);
				}
				
			});

			$('input[name=paket]').on('click', function(){
				var aktivasi = $('input[name=paket]:checked').val();
			});

			$('#kode_voucher').on('focusout', function(){
				harga = $('#harga').text().replace(/[^\d,]+/g,'').replace(',','.');
				let hargatotuser = parseInt($('#hargaUser').text().replace(/[^\d,]+/g,'').replace(',','.'));
				let totuser = $('#totuser').text()
				var value_month = parseInt($('#bulan').text().replace(/[^\d,]+/g,'').replace(',','.'));
				var nmPaket = $('#nmPaket').text();
				//hitung(harga,'','',value_month,hargatotuser,totuser);
				//hitung(harga, '', '', value_month, hargauser, totuser, '1', nmPaket);
				hitung(harga, '', '', value_month, hargatotuser, totuser, '1', nmPaket, '');
			});

			$('#kode_aktivasi').on('change', function(){
				kode_aktivasi = $(this).val();
				
				setTimeout(function() {
					aktifasi = getJson('action.php', {kode_aktivasi:kode_aktivasi, action:'ambilactivate', dbname:'<?=$dbname;?>'});
					if(aktifasi.status == 'success'){
						value = aktifasi.data.value;
						end_date = aktifasi.end_date;
						$('#activate').val(value);
						$('#end_date').text(end_date);
						$('#val_end_date').val(end_date);		
					}
					else{
						Swal.fire({
							title : 'Warning',
							html : '<h4>Kode aktivasi salah atau masa aktif kode sudah habis.</h4>',
							type : 'warning',
							position: 'top',
							confirmButtonColor : '#3085d6',
						})
					}	
				}, 2000);	
			});

			function hitung(harga, end_date, jmlh_hari, value_month, harga_user, totuser, package_type, subscribe_name){
				// alert(totuser)
				let kode_voucher = $('#kode_voucher').val();
				let harga1 = parseInt(harga);
					harga_user = parseInt(harga_user);
					totuser = parseInt(totuser);
				let voucherAwal = $('#voucherAwal').text();
				let tipe_paket = package_type;
				let nama_paket = subscribe_name;
				let	persen_ppn = 11;
				
					value_month = parseInt(value_month);
				//alert(value_month);
				hargatotuser = isNaN(value_month) ? totuser * harga_user * 1 : totuser * harga_user * value_month;
				let total_amount = hargatotuser+harga1;
				// alert(hargatotuser)
				
				let voucher = getJson('action.php', {kode_voucher:kode_voucher, action:'ambilvoucher', jenis:'voucherbaru'});
				let minimum_amount = voucher.data.minimum_amount;
				let harga_voucher = 0;
				if (kode_voucher != '' && $('#voucher').is(':checked')) {
					if(voucher.status == 'success' && total_amount >= minimum_amount && total_amount >= voucher.data.value){
						harga_voucher = voucher.data.value;
						$('#discount').text('- '+convertToRupiah(harga_voucher))
						$('#val_discount').val(harga_voucher);					
						/*if(tipe_paket == '3'){
							Swal.fire({
								title : 'Warning',
								html : "<h4>Voucher tidak berlaku untuk paket yang dipilih</h4>",
								type : 'warning',
								position: 'top',
								showConfirmButton : false,
								timer: 2000
							})
							setTimeout(function() {
								$('input[name="voucher"]').removeAttr('checked');
								href:location.reload();
							},2000)
						} else {
							harga_voucher = voucher.data.value;
							$('#discount').text('- '+convertToRupiah(harga_voucher))
							$('#val_discount').val(harga_voucher);
						}*/
					}
					else if(voucher.status == 'success' && total_amount<voucher.data.value){
						Swal.fire({
							title : 'Warning',
							html : "<h4 style='color: red'>Potongan Voucher Rp."+convertToRupiah(voucher.data.value)+"-,</h4> <h5>Voucher berlaku jika Tagihan lebih besar dari voucher.</h5>",
							type : 'warning',
							position: 'top',
							showConfirmButton : false,
							timer: 3000
						})
						setTimeout(function() {
							$('#discount').text('0');
							href:location.reload();
						},3000)
					}
					else if(voucher.status == 'success' && total_amount < minimum_amount){
						Swal.fire({
							title : 'Warning',
							html : "<h4>Voucher berlaku jika minimum belanja Rp."+convertToRupiah(minimum_amount)+"-,</h4>",
							type : 'warning',
							position: 'top',
							showConfirmButton : false,
							timer: 2000
						})
						setTimeout(function() {
							$('#discount').text('0');
							href:location.reload();
						},2000)
					}
					else{
						Swal.fire({
							title : 'Warning',
							html : "<h4>Kode voucher salah atau periode voucher sudah berakhir</h4>",
							type : 'warning',
							position: 'top',
							showConfirmButton : false,
							timer: 2000
						})
						setTimeout(function() {
							$('#discount').text('0');
							href:location.reload();
						},2000)
					}
					
				}
				else if(!$('#voucher').is(':checked') && voucherAwal != ''){
					
					voucher = getJson('action.php', {kode_voucher:voucherAwal, action:'ambilvoucher', jenis:'voucherlama'});

					harga_voucher = voucher.data.value;
					$('#discount').text('- '+convertToRupiah(harga_voucher))
					$('#val_discount').val(harga_voucher);

					console.log('harga lama:', harga_voucher);

				}
				else{
					harga_voucher = 0;
				}
				
				// if (subtotal <= 0) {
				// 	subtotal = 0;
				// }

				if (total_amount <= 0) {
					total_amount = 0;
				}
					
				let subtotal = hargatotuser+harga1-harga_voucher;

				//let pajak = subtotal*(10/100);
				let pajak = subtotal*(persen_ppn/100);
				let totalharga = pajak+subtotal;

				if(!isNaN(value_month)) {
					$('#bulan').text(value_month);
				}
				$('#harga-totuser').text(convertToRupiah(hargatotuser));
				$('#hargaUser').text(convertToRupiah(parseInt(harga_user)));
				$('#harga').text(convertToRupiah(harga1));
				$('#subtotal').text(convertToRupiah(subtotal));
				$('#ppn').text(convertToRupiah(pajak));
				$('#totalharga strong').text(convertToRupiah(totalharga));
				$('#amount').val(total_amount);
				$('#pajak').val(pajak);

				if (end_date != '') {
					$('#end_date').text(end_date);
					$('#val_end_date').val(end_date);			
					$('#jmlh_hari').val(jmlh_hari);				
				}
				
				$('#nmPaket').text(nama_paket);
				
			}

			function convertToRupiah(angka){
				var rupiah = new Intl.NumberFormat('id',{style:'decimal', minimumFractionDigits:2, maximumFractionDigits:2}).format(angka);

				return rupiah;
			}

			function getJson(url, data){
				return JSON.parse(
						$.ajax({
							type 	: 'post',
							url 	: url,
							data 	: data,
							dataType: 'json',
							global	:false,
							async	:false,
							success : function(msg){

							}
						}).responseText
					)
			}

			function del_tag(e, id){
				var jumlah = parseInt($('span.tag').length) - 1;

				var hargauser = parseInt($('#hargaUser').text().replace(/[^\d,]+/g,'').replace(',','.'));
				var hargatotuser = parseInt($('#harga-totuser').text().replace(/[^\d,]+/g,'').replace(',','.'));
				var value_month = parseInt($('#bulan').text().replace(/[^\d,]+/g,'').replace(',','.'));
				var totalhargaUser = hargatotuser*jumlah*value_month;
				var nmPaket = $('#nmPaket').text();

				invd_id.push(id);
				var idUser = invd_id.join(',');

				$('#totuser').text(jumlah);
				// $('#hargaUser').text(convertToRupiah(totalhargaUser));

				harga = $('#harga').text().replace(/[^\d,]+/g,'').replace(',','.');
				//hitung(harga, '', '', value_month, totalhargaUser, jumlah);
				hitung(harga, '', '', value_month, hargauser, jumlah, '1', nmPaket);
				
				$(e).parent().remove();
				$('#invd_id').val(idUser);
			}
		</script>
	</body>
</html>
