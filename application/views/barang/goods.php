<?php
	$data = json_decode(json_encode($pageInfo), True);

    $role      = $this->session->userdata('role');
    
    $countmerek = count($data['merekInfo']);
	
	if(isset($_POST['searchText']) && $_POST['searchText'] != ''){
		$searchText = $_POST['searchText'];
	} else {
		$searchText = $data['searchText'];
	}
	
	if(isset($_POST['searchStat']) && $_POST['searchStat'] != ''){
		$searchStat = $_POST['searchStat'];
	} else {
		$searchStat = $data['searchStat'];
	}

	if(isset($_POST['searchHal']) && $_POST['searchHal'] != ''){
		$searchHal = $_POST['searchHal'];
	} else {
		$searchHal = $data['searchHal'];
	}
    
    if(isset($_POST['searchOrder']) && $_POST['searchOrder'] != ''){
        $searchOrder = $_POST['searchOrder'];
    } else {
        $searchOrder = $data['searchOrder'];
    }
	
    $filter = '';
	$countall = $this->Mpersediaan_model->goodsListingCount($searchText, $searchStat, $filter);
	$count = count($data['goodsRecords']);
	
	$sessionClass = 'GOODS';
	
    $UserLevel = $this->session->userdata('role');

    $cekImport = $this->Utility_model->getSysDataValue("036");
    // $namaImport = $cekImport[0]['NAMA'];

    $arr['TIPE'][4] = 'TRACKING';

    $CI =&get_instance();
    $CI->load->model('Utility_model');
    $sysData = $CI->Utility_model->getSysDataValue('003');
    // $sysNama = $sysData[0]['NAMA'];
    // $producCost = substr($sysNama, 3, 1);

    //Update SYSMENU untuk keperluan Cara Mulai
    $uri_string = $this->uri->uri_string();
    if($count > 0) {
        $qry = "UPDATE SYSMENU SET FLAG = 'Y' WHERE LINK = '".$uri_string."'";
        $this->db->query($qry); 
    }

?>
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-4">
            <h5 class="ms-2"><?php echo $page_title ?></h5>
            <hr>
        </div> 
    </div>
    <?php echo $this->session->flashdata('message') ?>
    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Ketik sesuatu.." aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-warning" type="button" id="button-addon2">Cari</button>
            </div>
        </div>
        <div class="col-lg-4 ms-auto text-right">
            <a href='<?php echo base_url('addGoodsForm') ?>' class="btn btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="row px-2 mt-3">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>KODE</th>
                    <th>GROUP</th>
                    <th>INISIAL</th>
                    <th>NAMA</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no =1;
                foreach ($data['goodsRecords'] as $g) { ?>
                    <tr>
                        <td><?= $g['KODE'] ?></td>
                        <td><?= $g['GROUP'] ?></td>
                        <td><?= $g['INISIAL'] ?></td>
                        <td><?= $g['NAMA'] ?></td>
                        <td style="text-align: right;">
                            <a href="<?= base_url('editUnitForm/') . $g['KODE'] ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></a>
                            <button class="btn btn-danger btn-sm" onclick="HapusSat('<?= $g['KODE'] ?>')" >
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>