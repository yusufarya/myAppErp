<?php 
$goodsGroupRecords = $pageInfo['goodsGroupRecords'];
?>
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-4">
            <h5 class="ms-2"><i class="fa fa-cube fa-fw"></i> <?php echo $page_title ?></h5><hr>
        </div>
    </div>
    <?php echo $this->session->flashdata('message') ?>
    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Ketik sesuatu.." aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-warning" type="button" id="button-addon2">Cari</button>
            </div>
        </div>  
        <div class="col-lg-4 ms-auto text-right">
            <a href='<?php echo base_url('addGoodsGroupForm') ?>' class="btn btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="row px-2 mt-3"> 
        <div class="col lg-12">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="text-align:left; width:75px;">KODE</th>
                        <th style="text-align:left; width:115px;">INISIAL</th>
                        <th>NAMA</th>
                        <th style="text-align:left; width:75px;">TIPE</th>
                        <th style="text-align:center; width:55px;">AKTIF</th>
                        <th style="text-align:right; width:105px;">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($goodsGroupRecords as $gg) {  
                        $namabrg = '';
                        if ($gg->HD == '1') {
                            $namabrg = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $gg->NAMA;
                        } else {
                            $namabrg = $gg->NAMA;
                        }
                    ?>
                        <tr>    
                            <td><?= $gg->KODE ?></td>
                            <td><?= $gg->INISIAL ?></td>
                            <td><?= $namabrg ?></td>
                            <?php 
                            $tipe = '';
                            if($gg->HD == '0'){
                                $tipe = 'Header';
                            } else if ($gg->HD == '1') {
                                $tipe = 'Detail';
                            }
                            ?>
                            <td style="text-align:left;"><?= $tipe ?></td>
                            <td style="text-align: center;"><?= $gg->AKTIF ?></td>
                            <td style="text-align: right;">
                                <a href="<?= base_url('editGoodsGroupForm/') . $gg->KODE ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></a>
                                <button class="btn btn-danger btn-sm" onclick="HapusgoodsGroup('<?= $gg->KODE ?>')" >
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->pagination->create_links(); ?>
        </div>
    </div>
</div>