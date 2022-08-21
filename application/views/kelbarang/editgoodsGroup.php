<?php
$data = json_decode(json_encode($pageInfo), True);
$kode = 1;
$nama = '';
$inisial = '';
$hd = '';
$aktif = '';

$count = count($data['goodsGroupInfo']);
// $count2 = count($data['goodsGroupCount2']); 
if($count>0) {
    for($i=0;$i<$count;$i++)
    {
        $kode = $data['goodsGroupInfo'][$i]['KODE'];
        $nama = $data['goodsGroupInfo'][$i]['NAMA'];
        $inisial = $data['goodsGroupInfo'][$i]['INISIAL'];
    } 
    $kodes = sprintf("%03d", $kode);
} else if($count == 0) {
    $kodes = sprintf("%03d", $kode);
}
// print_r($data['goodsGroupInfo']);

// if($count2>0)
// {
//     for($j=0;$j<$count2;$j++)
//     {
//         $kode = $data['goodsGroupInfo2'][$j]['KODE'];
//         $nama = $data['goodsGroupInfo2'][$j]['NAMA'];
//         $inisial = $data['goodsGroupInfo2'][$j]['INISIAL'];
//         $hd = $data['goodsGroupInfo2'][$j]['HD'];
//         $aktif = $data['goodsGroupInfo2'][$j]['AKTIF'];
//     }                 
// }
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
        <div class="col-lg-6">
            <h5 class="ms-2"><i class="fa fa-cube fa-fw"></i>  <?php echo $page_title ?></h5>
        </div>
        <hr>
    </div>
    <div class="row px-2 mt-3">
        <div class="col-lg-6 col-sm-8">
            <div class="row">
                <div class="col-lg-6">
                    <label for="kode">Kode</label>
                    <input type="text" class="form-control" name="kode" id="kode" maxlength="3" value="<?= $kodes ?>" autocomplete="off" onkeyup="ChangeCase(this)" onKeyPress="return isNumberAlphaDotKey(event)" readonly>
                </div>
                <div class="col-lg-5">
                    <label for="kode">Status</label>
                    <div class="row">
                        <div>
                            <input type="checkbox" id="aktif" <?php echo $cek; ?> name="aktif"> Aktif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-6">
                    <label for="inisial">Inisial</label>
                    <input type="text" class="form-control" name="inisial" id="inisial" value="<?= $inisial ?>" maxlength="10" autocomplete="off" onkeyup="ChangeCase(this)" onKeyPress="return isNumberAlphaDotKey(event)" placeholder="01.">
                </div>
                <div class="col-lg-4"> 
                    <label for="tipe">Tipe *</label>
                    <select class="form-control required" id="tipe" name="tipe">
                        <option value="1"<?php if($hd==1){echo "selected=selected";} ?>>Detail</option>    
                        <option value="0"<?php if($hd==0){echo "selected=selected";} ?>>Header</option>        
                    </select>
                </div>
            </div>
            <div class="row mt-2">
            <div class="col-lg-10">
                <label for="nama">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" value="<?= $nama ?>" maxlength="30" autocomplete="off" onkeyup="ChangeCase(this)" onKeyPress="return isNumberAlphaDotKey(event)" placeholder="Nama kelompok barang">
                </div>
            </div>
            <button class="btn btn-primary mt-3" id="simpan">Simpan</button>
            
            <a href="<?= base_url('goodsGroupListing') ?>" id="batal" class="btn btn-warning mt-3"><i class="fa fa-times"></i></a>
        </div>
    </div>
</div>
