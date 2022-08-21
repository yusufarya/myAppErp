<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time',0);

require APPPATH . '/libraries/BaseController.php';

class Mpersediaan extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mpersediaan_model');
        $this->load->model('User_model');
        $this->isLoggedIn();
    }

    function unitListing()
    {
        $data['active'] = 'PERSEDIAAN';

        if($this->input->post('submit') != NULL) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $tanda = $this->security->xss_clean($this->input->post('tanda'));
            $searchStat = $this->security->xss_clean($this->input->post('stat'));
            $searchHal = $this->security->xss_clean($this->input->post('hal'));
            $searchOrder = $this->security->xss_clean($this->input->post('order'));
            $this->session->set_userdata(array("search"=>$searchText, "tanda"=>$tanda, "hal"=>$searchHal, "order"=>$searchOrder, "stat"=>$searchStat));
            redirect('unitListing');
        } else {
            if(($this->session->userdata("search") != NULL || $this->session->userdata("stat") != NULL || $this->session->userdata("hal") > PER_PAGE || $this->session->userdata("order") != NULL) && $this->session->userdata("tanda") == 'unit') {
                $searchText = $this->session->userdata('search');
                // $searchStat = 'Y';
                $searchStat = $this->session->userdata('stat');
                $searchHal = $this->session->userdata('hal');
                $searchOrder = $this->session->userdata('order');
            } else {
                $searchText = '';
                $searchStat = 'Y';
                $searchHal = PER_PAGE;
                $searchOrder = 'NAMA';
            }
        }
        
        $data['searchText'] = $searchText;
        $data['searchStat'] = $searchStat;
        $data['searchHal'] = $searchHal;
        $data['searchOrder'] = $searchOrder;
        
        $this->load->library('pagination');
        
        $count = $this->Mpersediaan_model->unitListingCount($searchText, $searchStat, $searchOrder);
        
        $returns = $this->paginationCompress ( "unitListing/", $count, $searchHal);
        
        $data['unitRecords'] = $this->Mpersediaan_model->unitListing($searchText, $searchStat, $searchOrder, $returns["page"], $returns["segment"]);
    
        $this->global['page_title'] = 'Persediaan | Master Satuan';

        $this->loadViews("unit/unit", $this->global, $data, NULL, TRUE);
    }
    function addUnitForm()
    {
        $kode = strtoupper($this->input->post('kode'));
        $nama = strtoupper($this->input->post('nama'));
        $aktif = $this->input->post('aktif');
        
        $data['unitInfo'] = array(array('KODE'=>$kode,'NAMA'=>$nama,'AKTIF'=>$aktif));
        
        $data['active'] = 'PERSEDIAAN';
        $this->global['page_title'] = 'Tambah Master Satuan';

        $this->loadViews("unit/addunit", $this->global, $data, NULL, TRUE);
    }
    function addUnit() 
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('kode', 'Kode', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('aktif', 'Aktif', 'trim');

        if ($this->form_validation->run() == false) {
            $this->addUnitForm();
        } else {
            $kode = strtoupper($this->input->post('kode'));
			$nama = strtoupper($this->input->post('nama'));
			$aktif = $this->input->post('aktif');
			
			if($aktif == '')
			{
				$aktif = 'N';
			}
			else
			{
				$aktif = 'Y';
			}
            
            $usid = $this->session->userdata('userId');
			
			$unitInfo = array('KODE'=>$kode, 'NAMA'=>$nama, 'AKTIF'=>$aktif, 'USID'=>$usid, 'CREATED_AT'=>date('Y-m-d H:i:s'), 'UPDATE_AT'=>date('Y-m-d H:i:s'));
            // print_r($unitInfo); 
									
			$result = $this->Mpersediaan_model->addUnit($unitInfo);
							
			$desc = 'Tambah Satuan '.$kode;
							
			$loginfo = array('LOGUSER'=>$usid, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
			
			if($result > 0)
			{ 
				$this->User_model->addLog($loginfo); 
				$this->session->set_flashdata('message', '
                <div class="row px-2 mt-3">
                    <div class="alert alert-success py-2 alert-dismissible fade show" role="alert">
                    Kode satuan berhasil ditambahkan.
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
                $status = array('status' => 'success', 'data' => $result);
			} else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger py-2">Kode Satuan gagal disimpan</div>');
                $status = array('status' => 'failed');
			}
			
			echo json_encode($status);
        }

    }
    function editUnitForm($kode = NULL)
    { 
        if($kode == null)
        {
            $this->editUnitForm();
        }
        
        // $kode = urldecode($kode);
        
        $data['unitInfo'] = $this->Mpersediaan_model->getUnitInfoByKode($kode);
        $data['kode'] = $kode;
        $data['active'] = 'PERSEDIAAN';
        
        $this->global['page_title'] = 'Persediaan | Edit Master Satuan';
        
        $this->loadViews("unit/editUnit", $this->global, $data, NULL, TRUE);
    }
    function editUnit() {
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('kode','Kode','trim|required|exact_length[3]');
        $this->form_validation->set_rules('aktif','Aktif','trim');
        $this->form_validation->set_rules('nama','Nama','trim|required|max_length[10]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->editUnitForm($kode);
        }
        else
        {
            $where = $this->input->post('where');
            $kode = $this->input->post('kode');
            $nama = strtoupper($this->input->post('nama'));
            $aktif = $this->input->post('aktif');
            if($aktif == 'on')
            {
                $aktif = 'Y';
            } else {
                $aktif = 'N';
            }
            $usid = $this->session->userdata('userId');
            
            $unitInfo = array('KODE'=>$kode, 'NAMA'=>ucwords($nama), 'USID'=>$usid, 'UPDATE_AT'=>date('Y-m-d H:i:s'), 'AKTIF'=>$aktif);		
            $result = $this->Mpersediaan_model->editUnit($unitInfo, $where); 
            
            $desc = 'Ubah Satuan '.$kode; 
            $loginfo = array('LOGUSER'=>$usid, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
            
            if($result == TRUE)
			{
				$this->User_model->addLog($loginfo); 
				$this->session->set_flashdata('message', '
                <div class="row px-2 mt-3">
                    <div class="alert alert-success py-2 alert-dismissible fade show" role="alert">
                    Kode satuan berhasil disimpan.
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
                $status = array('status' => 'success', 'data' => $result);
			} else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger py-2">Kode Satuan gagal disimpan</div>');
                $status = array('status' => 'failed');
			}
            echo json_encode($status);
        }
    }

    function deleteUnit($kode)
    {
		$delete = $this->Mpersediaan_model->deleteUnit($kode);
		
		$this->load->model('User_model');

		$ip = $this->User_model->getClientIP();
						
		$desc = 'Hapus Satuan '.$kode;
						
		$loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
		
		$this->User_model->addLog($loginfo);

        $this->session->set_flashdata('message', '
        <div class="row px-2 mt-3">
            <div class="alert alert-success py-2 alert-dismissible fade show" role="alert">
            '.$kode.' satuan berhasil dihapus.
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
		echo $delete;
		// redirect('unitListing');
    }
    
    function goodsListing()
    {
        $filter = "";
        $kelompok_barang = "";
        $tipe = "";
        $merek = "";
        $suppname = "";
        $tanda = "goods";

        $data['kelompok_barang'] = $kelompok_barang;
        $data['tipe'] = $tipe;
        $data['merek'] = $merek; 
        
        if($this->input->post('submit') != NULL) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $tanda = $this->security->xss_clean($this->input->post('tanda'));
            $searchStat = $this->security->xss_clean($this->input->post('stat'));
            $searchHal = $this->security->xss_clean($this->input->post('hal'));
            $searchOrder = $this->security->xss_clean($this->input->post('order'));
            $this->session->set_userdata(array("search"=>$searchText, "tanda"=>$tanda, "hal"=>$searchHal, "stat"=>$searchStat, "order"=>$searchOrder));
            // redirect('goodsListing');
        } else {
            if(($this->session->userdata("search") != NULL || $this->session->userdata("hal") > PER_PAGE) && $this->session->userdata("tanda") == 'goods' || ($this->session->userdata('order') != 'NAMA' && $this->session->userdata('order'))) {
                $searchText = $this->session->userdata('search');
                $searchStat = 'Y';
                $searchHal = $this->session->userdata('hal');
                $searchOrder = $this->session->userdata('order');
            } else {
                $searchText = '';
                $searchStat = 'Y';
                $searchOrder = 'NAMA';
                $searchHal = PER_PAGE;
            }
        }
        
        $data['searchText'] = $searchText;
        $data['searchStat'] = $searchStat;
        $data['searchHal'] = $searchHal;
        $data['searchOrder'] = $searchOrder;
        
        $this->load->library('pagination');
        
        $count = $this->Mpersediaan_model->goodsListingCount($searchText, $searchStat, $filter);
        
        $returns = $this->paginationCompress ( "goodsListing/", $count, $searchHal );
        
        $data['goodsRecords'] = $this->Mpersediaan_model->goodsListing($searchText, $searchStat, $searchOrder, $returns["page"], $returns["segment"], $filter);

        // print_r(nl2br($data['goodsRecords'])); die;
        
        $this->load->model('Utility_model');
        
        $tableName = 'prod1';

        $data['merekInfo'] = $this->Mpersediaan_model->getMerekInfo();
        
        // $data['tableInfo'] = $this->Utility_model->getSystabelInfo($tableName);
        
        $data['active'] = 'PERSEDIAAN';

        $this->global['page_title'] = 'Persediaan | Master Barang';

        $this->loadViews("barang/goods", $this->global, $data, NULL, TRUE);
    
    }
    function addGoodsForm()
    {
        $kode = strtoupper($this->input->post('kode'));
        $nama = strtoupper($this->input->post('nama'));
        $aktif = $this->input->post('aktif');
        
        $data['unitInfo'] = array(array('KODE'=>$kode,'NAMA'=>$nama,'AKTIF'=>$aktif));
        
        $data['active'] = 'PERSEDIAAN';
        $this->global['page_title'] = 'Tambah Master BARANG';

        $this->loadViews("barang/addgoods", $this->global, $data, NULL, TRUE);
    }

    function goodsGroupListing()
    { 
        if($this->input->post('submit') != NULL) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $tanda = $this->security->xss_clean($this->input->post('tanda'));
            $searchStat = $this->security->xss_clean($this->input->post('stat'));
            $searchOrder = $this->security->xss_clean($this->input->post('order'));
            $searchHal = $this->security->xss_clean($this->input->post('hal'));
            $this->session->set_userdata(array("search"=>$searchText, "tanda"=>$tanda, "hal"=>$searchHal, "order"=>$searchOrder, "stat"=>$searchStat));
            redirect('goodsGroupListing');
        } else {
            if(($this->session->userdata("search") != NULL || $this->session->userdata("hal") > PER_PAGE || $this->session->userdata("order") != NULL) && $this->session->userdata("tanda") == 'goodsGroup') {
                $searchText = $this->session->userdata('search');
                $searchStat = 'Y';
                $searchHal = $this->session->userdata('hal');
                $searchOrder = $this->session->userdata('order');
            } else {
                $searchText = '';
                $searchStat = 'Y';
                $searchHal = PER_PAGE;
                $searchOrder = 'INISIAL';
            }
        }
        
        $data['searchText'] = $searchText;
        $data['searchStat'] = $searchStat; 
        $data['searchHal'] = $searchHal;
        $data['searchOrder'] = $searchOrder;
        
        $this->load->library('pagination');
        
        $count = $this->Mpersediaan_model->goodsGroupListingCount($searchText, $searchStat, $searchOrder);
        
        $returns = $this->paginationCompress ( "goodsGroupListing/", $count, $searchHal);
        
        $data['goodsGroupRecords'] = $this->Mpersediaan_model->goodsGroupListing($searchText, $searchStat, $searchOrder, $returns["page"], $returns["segment"]);
        
        $this->load->model('Utility_model'); 
        
        $data['logsInfo'] = array();
        $data['active'] = 'PERSEDIAAN';
        
        $this->global['page_title'] = 'Persediaan | Kelompok Barang';
        
        $this->loadViews("kelbarang/goodsGroup", $this->global, $data, NULL, TRUE);
    }
    
    function addGoodsGroupForm()
    {  
        $data['active'] = 'PERSEDIAAN';
        $kode = ucwords(strtoupper($this->input->post('kode')));
        $nama = ucwords(strtoupper($this->input->post('nama')));
        $inisial = ucwords(strtoupper($this->input->post('inisial')));
        $hd = $this->input->post('hd');
        $aktif = $this->input->post('aktif');
        
        $this->load->model('Mpersediaan_model');
        
        $data['goodsGroupCount'] = $this->Mpersediaan_model->getGoodsGroupCount();
        $data['goodsGroupCount2'] = array(array('KODE'=>$kode, 'NAMA'=>$nama, 'INISIAL'=>$inisial, 'HD'=>$hd, 'AKTIF'=>$aktif, 'USID'=>$this->loginId, 'CREATED_AT'=>date('Y-m-d H:i:s'), 'UPDATED_AT'=>date('Y-m-d H:i:s'))); 
        
        $this->load->model('User_model');
        
        $this->global['page_title'] = 'Persediaan | Tambah Kelompok Barang';

        $this->loadViews("kelbarang/addgoodsGroup", $this->global, $data, NULL, TRUE);
    }

    function addGoodsGroup_(){

        $kode = ucwords(strtoupper($this->input->post('kode'))); 
        $nama = ucwords(strtoupper($this->input->post('nama')));
        $inisial = ucwords(strtoupper($this->input->post('inisial')));
        $tipe = $this->input->post('tipe');
        $aktif = $this->input->post('aktif');
        
        if($aktif == '')
        {
            $aktif = 'N';
        }
        else
        {
            $aktif = 'Y';
        }
            
        $goodsGroupInfo = array('KODE'=>$kode, 'NAMA'=>$nama, 'INISIAL'=>$inisial, 'HD'=>$tipe, 'AKTIF'=>$aktif, 'USID'=>$this->loginId, 'CREATED_AT'=>date('Y-m-d H:i:s'), 'UPDATED_AT'=>date('Y-m-d H:i:s')); 
        // print_r($goodsGroupInfo);
        $this->load->model('Mpersediaan_model'); 

        $result = $this->Mpersediaan_model->addGoodsGroup($goodsGroupInfo);
        
        $this->load->model('User_model');

        $ip = $this->User_model->getClientIP(); 
                        
        $desc = 'Tambah Kelompok Barang '.$kode;

        $loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
        
        if($result > 0)
        {
            $this->User_model->addLog($loginfo); 
            echo json_encode(['success'=>'Kelompok Barang berhasil dibuat']);
        }
        else
        {
            $this->session->set_flashdata('message', 'Kelompok barang gagal dibuat');
            echo json_encode(['errors'=>'Kelompok Barang gagal dibuat']);
        }
			//echo '{}';
    }

    function editGoodsGroupForm($kode)
    {
        $data['active'] = 'PERSEDIAAN';
        if($kode == null)
        {
            redirect('goodsGroupListing');
        }
        
        $data['goodsGroupInfo'] = $this->Mpersediaan_model->getGoodsGroupInfo($kode);
        
        $this->global['page_title'] = 'Persediaan | Ubah Data Kelompok Barang';
        
        $this->loadViews("kelbarang/editgoodsGroup", $this->global, $data, NULL, FALSE);
    }

    function editGoodsGroup_()
    {
        $kode = $this->input->post('kode');
			
        $inisials = ucwords(strtoupper($this->input->post('inisial')));
        $result = $this->Mpersediaan_model->cekInisial($inisials);
        
        $nama = ucwords(strtoupper($this->input->post('nama')));
        $hd = $this->input->post('tipe');
        $aktif = $this->input->post('aktif');
        
        if($aktif == '')
        {
            $aktif = 'N';
        }
        else
        {
            $aktif = 'Y';
        }
        
        $goodsGroupInfo = array('AKTIF'=>$aktif, 'NAMA'=>$nama, 'INISIAL'=>$inisials, 'HD'=>$hd, 'AKTIF'=>$aktif, 'USID'=>$this->loginId, 'UPDATED_AT'=>date('Y-m-d H:i:s'));

        $result = $this->Mpersediaan_model->editGoodsGroup($goodsGroupInfo, $kode);
        
        $this->load->model('User_model');

        $ip = $this->User_model->getClientIP();
                        
        $desc = 'Ubah Kelompok Barang '.$kode;
                        
        $loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
        
        if($result == true)
        {
            $this->User_model->addLog($loginfo);
            $this->Mpersediaan_model->updateGoodsByCol('INGROUP', $inisials, 'GROUP', $kode);
            
            $this->session->set_flashdata('success', 'Kelompok Barang berhasil diubah');
            echo json_encode(['success'=>'Kelompok Barang berhasil diubah']);
        }
        else
        {
            $this->session->set_flashdata('error', 'Kelompok Barang gagal diubah');
            echo json_encode(['errors'=>'Kelompok Barang gagal diubah']);
        }
        
        // redirect('goodsGroupListing');
    }

    function deletegoodsGroup($kode)
    {
		$delete = $this->Mpersediaan_model->deleteGoodsGroup($kode);
		
		$this->load->model('User_model');

		$ip = $this->User_model->getClientIP();
						
		$desc = 'Hapus Kelompok Barang '.$kode;
						
		$loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
		
		$this->User_model->addLog($loginfo);

        $this->session->set_flashdata('message', '
        <div class="row px-2 mt-3">
            <div class="alert alert-success py-2 alert-dismissible fade show" role="alert">
            '.$kode.' satuan berhasil dihapus.
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
		echo $delete;
		// redirect('unitListing');
    }

    function brandListing()
    {   
        $data['active'] = 'PERSEDIAAN';
        //$searchOrder = 'NAMA';
        if($this->input->post('submit') != NULL) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $tanda = $this->security->xss_clean($this->input->post('tanda'));
            $searchStat = $this->security->xss_clean($this->input->post('stat'));
            $searchHal = $this->security->xss_clean($this->input->post('hal'));
            $searchOrder = $this->security->xss_clean($this->input->post('order'));
            $this->session->set_userdata(array("search"=>$searchText, "tanda"=>$tanda, "hal"=>$searchHal, "stat"=>$searchStat, "order"=>$searchOrder));
            // redirect('brandListing');
        } else {
            if(($this->session->userdata("search") != NULL || $this->session->userdata("hal") > PER_PAGE) && $this->session->userdata("tanda") == 'brand' || ($this->session->userdata('order') != 'NAMA' && $this->session->userdata('order'))) {
                $searchText = $this->session->userdata('search');
                $searchStat = 'Y';
                $searchHal = $this->session->userdata('hal');
                $searchOrder = $this->session->userdata('order');
                $searchOrder = 'NAMA';
            } else {
                $searchText = '';
                $searchStat = 'Y';
                $searchHal = PER_PAGE;
                $searchOrder = 'NAMA';
            }
        }
        
        $data['searchText'] = $searchText;
        $data['searchStat'] = $searchStat;
        $data['searchHal'] 	= $searchHal;
        $data['searchOrder']= $searchOrder;
        
        $this->load->library('pagination');
        
        $count = $this->Mpersediaan_model->brandListingCount($searchText, $searchStat, $searchOrder);
        
        $returns = $this->paginationCompress ( "brandListing/", $count, $searchHal);
        
        $data['brandRecords'] = $this->Mpersediaan_model->brandListing($searchText, $searchStat, $searchOrder, $returns["page"], $returns["segment"]);
        
        $this->load->model('Utility_model'); 
        
        $this->global['page_title'] = 'Persediaan | Master Brand';
        
        $this->loadViews("brand/brand", $this->global, $data, NULL, TRUE);
    }

    function addbrandForm()
    {
        $data['active'] = 'PERSEDIAAN';
        
        $this->load->model('Mpersediaan_model');

        $kode = ucwords(strtoupper($this->input->post('kode')));
        $nama = ucwords(strtoupper($this->input->post('nama')));
        $aktif = $this->input->post('aktif');
            
        $data['brandCount'] = array(array('KODE'=>$kode,'NAMA'=>$nama, 'AKTIF'=>$aktif, 'USID'=>$this->loginId, 'CREATED_AT'=>date('Y-m-d H:i:s'), 'UPDATED_AT'=>date('Y-m-d H:i:s')));
        
        $this->load->model('User_model'); 

        $this->global['page_title'] = 'Persediaan | Master Brand';

        $this->loadViews("brand/addBrand", $this->global, $data, NULL, FALSE);
    }

    function addbrand()
    {
        $kode = ucwords(strtoupper($this->input->post('kode'))); 
        $nama = ucwords(strtoupper($this->input->post('nama')));
        $aktif = $this->input->post('aktif');
        
        if($aktif == '')
        {
            $aktif = 'N';
        }
        else
        {
            $aktif = 'Y';
        }
            
        $brandInfo = array('KODE'=>$kode, 'NAMA'=>$nama, 'AKTIF'=>$aktif, 'USID'=>$this->loginId, 'UPDATED_AT'=>date('Y-m-d H:i:s'));
        
        $this->load->model('Mpersediaan_model');
                            
        $result = $this->Mpersediaan_model->addBrand($brandInfo,$kode); 
        
        $this->load->model('User_model');

        $ip = $this->User_model->getClientIP();
                        
        $desc = 'Tambah Merek '.$kode;
                        
        $loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
        
        if($result == true)
        {
            $this->User_model->addLog($loginfo);
            $this->session->set_flashdata('success', 'Master Brand Berhasil Ditambah');
            $this->session->unset_userdata('search');
            echo json_encode(['success'=>'Master Brand Berhasil Ditambah']);
        }
        else
        {
            $this->session->set_flashdata('error', 'Master Brand Gagal Ditambah');
            echo json_encode(['error'=>'Master Brand Gagal Ditambah']);
        } 
        // redirect($redirect);	
    }

    function editbrandForm($kode = NULL)
    { 
        $data['active'] = 'PERSEDIAAN';

        if($kode == null) {
            redirect('brandListing');
        } 
        $kode = urldecode($kode);

        $this->load->model('Mpersediaan_model');
        
        $data['brandInfo'] = $this->Mpersediaan_model->getBrandInfo($kode);
        $data['kode'] = $kode;
        
        $this->global['page_title'] = 'Persediaan | Master Brand';
        
        $this->loadViews("brand/editbrand", $this->global, $data, NULL, FALSE);
    }

    function editbrand()
    {
        $kode = ucwords(strtoupper($this->input->post('kode')));
        $name = ucwords(strtoupper($this->input->post('nama')));
        
        $aktif = $this->input->post('aktif');
        
        if($aktif == '')
        {
            $aktif = 'N';
        }
        else
        {
            $aktif = 'Y';
        }

        $brandInfo = array('NAMA'=>$name, 'AKTIF'=>$aktif, 'UPDATED_AT'=>date('Y-m-d H:i:s'));
        
        $result = $this->Mpersediaan_model->editBrand($brandInfo, $kode);
    
        $this->load->model('User_model');

        $ip = $this->User_model->getClientIP();
        
        $desc = 'Ubah Merek '.$kode;
        
        $loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
        
        if($result == true)
        {
            $this->User_model->addLog($loginfo);
            $this->session->set_flashdata('success', 'Master Brand Berhasil Diubah');
            echo json_encode(['success'=>'Master Brand Berhasil Diubah']);
        }
        else
        {
            $this->session->set_flashdata('error', 'Master Brand Gagal Diubah');
            echo json_encode(['error'=>'Master Brand Gagal Diubah']);
        } 

    }

    function deleteBrand($kode)
    {
		$this->Mpersediaan_model->deleteBrand($kode);
		
		$this->load->model('User_model');

		$ip = $this->User_model->getClientIP();
						
		$desc = 'Hapus Merek '.$kode;
						
		$loginfo = array('LOGUSER'=>$this->loginId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
		
		$this->User_model->addLog($loginfo);
		
		redirect('brandListing');
    }
}