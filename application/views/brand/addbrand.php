<?php
$data = json_decode(json_encode($pageInfo), True);

$kode = '';
$name = '';
$aktif = '';

$count = count($data['brandCount']); 

if($count>0)
{
    for($i=0;$i<$count;$i++)
    {
        $kode = $data['brandCount'][$i]['KODE'];
		$name = $data['brandCount'][$i]['NAMA'];
		$aktif = $data['brandCount'][$i]['AKTIF'];
    }
}
if($aktif == 'N')
{
	$cek = '';
}
else
{
	$cek = 'checked';
}
?>
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-4">
            <h5 class="ms-2"><i class="fa fa-cube fa-fw"></i>  <?php echo $page_title ?></h5>
        </div>
        <hr>
    </div>
    <div class="row px-2 mt-3">
        <div class="col-lg-6 col-sm-8">
            <div class="row">
                <div class="col-lg-6">
                    <label for="kode">Kode</label>
                    <input type="text" class="form-control" name="kode" id="kode" maxlength="3" autocomplete="off" onkeyup="ChangeCase(this)" onKeyPress="return isNumberAlphaDotKey(event)">
                </div>
                <div class="col-lg-4">
                    <label for="kode">Contoh</label>
                    <input type="text" class="form-control" value="001" readonly autocomplete="off">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-10">
                    <label for="nama">Nama Merek</label>
                    <input type="text" class="form-control" name="nama" id="nama" maxlength="10" autocomplete="off" onkeyup="ChangeCase(this)" onKeyPress="return isNumberAlphaDotKey(event)">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mt-3">
                    <input type="checkbox" id="aktif" <?php echo $cek; ?> name="aktif">
                    <i></i> Aktif
                </div>
            </div>
            <button class="btn btn-primary mt-5" id="simpan">Simpan</button>
            <a href="<?= base_url('unitListing') ?>" id="batal" class="btn btn-warning mt-5"><i class="fa fa-times"></i></a>
        </div>
    </div>
</div>