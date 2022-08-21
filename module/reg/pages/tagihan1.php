<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?=strtoupper($data['name'])." (Faktur Proforma $data[inv_no])";?> 
        </div>
        <div class="panel-body" id="tagihan">
            <p>Kami Telah Menerima Informasi Pendaftaran Database ini, dan sedang menunggu proses pembayaran.</p><br>
            <p>Jumlah yang harus dibayarkan 
                <span class="nominal">Rp <?=$grand_total;?></span><br>
                <span class="keterangan">*Total yang dibayar sudah termasuk ppn</span>
            </p><br>
            <p>Silahkan lakukan transfer ke rekening berikut.</p>
    <?php 
        while ($dbank = mysql_fetch_array($queryBank)) {
    ?>	
            <p><strong><?=$dbank['nama_bank'];?><br>
                No. Rek:&nbsp;<?=$dbank['bank_no'];?><br>
                A/N: <?=$dbank['namanasabah'];?></strong><br>
            </p>
    <?php
        }
    ?>
		<!-- <p> -->
		<!--<a href="https://<?=$host;?>/siserp/module/reg/pages/account.php" class="btn btn-success" id="back">Kembali ke Siscom Online</a><br>-->
		<a href="<?=$abs;?>/reg/pages/account.php" class="btn btn-success" id="back">Kembali ke Siscom Online</a><br>
        <?php 
            $none = $paid_off == 'N' ? 'block' : 'none';
        ?>
		<!--<a href="https://<?=$host;?>/siserp/module/reg/pages/tagihan.php?invoice=<?=$data['inv_no']?>" class="btn btn-link pull-left" id="back" style="display: <?=$none?>">Ubah Metode Pembayaran</a>-->
		<a href="<?=abs;?>/reg/pages/tagihan.php?invoice=<?=$data['inv_no']?>" class="btn btn-link pull-left" id="back" style="display: <?=$none?>">Ubah Metode Pembayaran</a>

		<!-- </p> -->
        </div>
    </div>
</div>