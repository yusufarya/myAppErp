<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpersediaan_model extends CI_Model
{
	
	function getSatuanInfo()
	{
		$qry = "SELECT KODE, NAMA FROM SATUAN WHERE AKTIF = 'Y' ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getProd1InfoByGroupKode($kode, $group)
	{
		$qry = "SELECT NAMA, INGROUP, INISIAL, SAT  
				FROM PROD1 
				WHERE `KODE` = '$kode' AND `GROUP` = '$group'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	function getSalesPriceGroupInfoo($nomor)
	{
		$qry = "SELECT NOMOR, CB, NAMA, INGROUP FROM STDJUALHD WHERE NOMOR = ".$nomor."";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getCabangUser($usid)
	{
		$qry = "SELECT IDPT AS CABANG, CABANG.NAMA AS NAMACABANG FROM SYSUSER S JOIN CABANG ON S.IDPT=CABANG.KODE WHERE S.USID='$usid'";
		$query = $this->db->query($qry);
		$result = $query->row_array();
		return $result;
	}

	function cekGudang($kodeGd)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT GUDANG FROM $db2.WARE1 WHERE GUDANG='".$kodeGd."'";
		$query = $this->db->query($qry);
		$result = $query->num_rows();
		return $result;
	}

	function getAktif($kode)
	{
		$qry = "SELECT AKTIF FROM `GROUP` WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$row = $query->row(); 
				
		$aktif = $row->AKTIF;
		
        return array('aktif' => $aktif);
	}
	
	function getSalesPriceGroupInfoD($nomor)
	{
		$qry = "SELECT DT.GROUP, DT.BARANG, DT.LAMA, DT.BARU, DT.DISCD, P.NAMA, P.INGROUP     
				FROM STDJUALDT DT 
				LEFT JOIN PROD1 P ON P.GROUP = DT.GROUP AND P.KODE = DT.BARANG 
				WHERE DT.NOMOR = ".$nomor." 
				ORDER BY P.NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	function getUnitInfoByKode($kode)
    {
		$qry = "SELECT KODE, NAMA, AKTIF FROM SATUAN WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getUnitInfoByNama($nama)
    {
		$qry = "SELECT KODE, NAMA, AKTIF FROM SATUAN WHERE NAMA = '".$nama."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function cekUnitExistProd1($kode)
	{
		$qry = "SELECT SAT FROM PROD1
				WHERE SAT = '".$kode."' ";
		$query = $this->db->query($qry);   
        $result = $query->num_rows();
		
		return $result;
	}
	
	function cekGroupExistProd1($kode)
	{
		$qry = "SELECT `GROUP` FROM PROD1
				WHERE `GROUP` = '".$kode."' ";
		$query = $this->db->query($qry);   
        $result = $query->num_rows();
		
		return $result;
	}
	
	function cekBrandExists($kode)
	{
		$qry = "SELECT INISIAL FROM PROD1 WHERE MEREK = '".$kode."'";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
	}
	
	function cekWhExistTrx($kode, $tabel)
	{
		$qry = "SELECT GUDANG FROM ".$tabel." 
				WHERE GUDANG = '".$kode."' ";
		$query = $this->db->query($qry);   
        $result = $query->num_rows();
		
		return $result;
	}
	
	function cekNamaExistProd1($nama)
	{
		$qry = "SELECT NAMA FROM PROD1
				WHERE NAMA = '".$nama."' ";
		$query = $this->db->query($qry);   
        $result = $query->num_rows();
		
		return $result;
	}
	
    function unitListingCount($searchText = '', $searchStat = '', $searchOrder = '')
    {
        $this->db->select('BaseTbl.*');
        $this->db->from('SATUAN as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchOrder)) {
            $this->db->order_by($searchOrder, "ASC");
        }
        $query = $this->db->get();
        return count($query->result());
    }
	
	function unitListing($searchText = '', $searchStat = '', $searchOrder = '', $page, $segment)
    {	
		$this->db->select('BaseTbl.*');
        $this->db->from('SATUAN as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchOrder)) {
            $this->db->order_by('BaseTbl.'.$searchOrder, "ASC");
        }
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();  
		 
        return $result; 
    }
	
	function addUnit($unitInfo)
    {
        $this->db->trans_start();
        $this->db->insert('SATUAN', $unitInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }
	
	function editUnit($unitInfo, $kode)
    {
		$this->db->where('KODE', $kode);
        if($this->db->update('SATUAN', $unitInfo)){
			return TRUE;
		}else{
			return false;
		}
        
        return TRUE;
    }
	
	function editUnitIP($kode)
    {
        $qry = "UPDATE SATUAN SET IP = '' WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }
	
	function deleteUnit($kode)
	{
		$jenis = 'M. Satuan';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS (CABANG, NOMOR, NAMA, NOXX, USID, UPDATE_AT, JENIS) 
				SELECT '01', KODE, NAMA, 'SATUAN', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' 
				FROM ".$db1.".SATUAN WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".SATUAN WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry); 
       	return $query;
	}
	
	function ambilKodeGoodsGroup()
    {
    	$kode = "000";	
		$qry = "SELECT KODE FROM `GROUP` ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry); 
		
		$row = $query->row();   
		if($row != null){
			$kode = $row->KODE;
		}
		
		return array('kode'=>$kode);
    }

	function goodsGroupListingCount($searchText = '', $searchStat = '', $searchOrder = '')
    {
		$qry = "SELECT * FROM `GROUP` ";
		if(!empty($searchText))
		{
			$qry .= "WHERE KODE LIKE '%".$searchText."%' OR NAMA LIKE '%".$searchText."%' OR INISIAL LIKE '%".$searchText."%' ";
			if(!empty($searchStat)) {
            	$qry .= "AND AKTIF = '".$searchStat."' "; 
        	}
		} else {
			if(!empty($searchStat)) {
            	$qry .= "WHERE AKTIF = '".$searchStat."' "; 
        	}
		}
		if(!empty($searchOrder)) {
			$qry .= "ORDER BY ".$searchOrder." ASC"; 
        }
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function goodsGroupListing($searchText = '', $searchStat = '', $searchOrder = '', $page, $segment)
    {	
		$qry = "SELECT * FROM `GROUP`";
		if(!empty($searchText))
		{
			$qry .= " WHERE KODE LIKE '%".$searchText."%' OR NAMA LIKE '%".$searchText."%' OR INISIAL LIKE '%".$searchText."%' ";
			if(!empty($searchStat)) {
            	$qry .= " AND AKTIF = '".$searchStat."' "; 
        	}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " WHERE AKTIF = '".$searchStat."' "; 
        	}
		}	
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		if(!empty($searchOrder)) {
			$qry .= "ORDER BY ".$searchOrder." ASC LIMIT ".$offset.", ".$page; 
        }
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
       	return $result;
    }

    function goodsGroupListing2($searchText = '', $searchStat = '', $searchOrder = '', $page)
    {	
		$qry = "SELECT * FROM `GROUP`";
		if(!empty($searchText))
		{
			$qry .= " WHERE KODE LIKE '%".$searchText."%' OR NAMA LIKE '%".$searchText."%' OR INISIAL LIKE '%".$searchText."%' ";
			if(!empty($searchStat)) {
            	$qry .= " AND AKTIF = '".$searchStat."' "; 
        	}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " WHERE AKTIF = '".$searchStat."' "; 
        	}
		}	
		if(!empty($searchOrder)) {
			$qry .= "ORDER BY ".$searchOrder." ASC LIMIT ".$page." "; 
        }
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
       	return $result;
    }

    function getGoodsGroupInisial($inisial)
    {
    	$qry = "SELECT INISIAL, NAMA, AKTIF FROM `GROUP` WHERE INISIAL = '".$inisial."' ";
    	$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }

    function getGoodsGroupNama($nama)
    {
    	$qry = "SELECT INISIAL, NAMA, AKTIF FROM `GROUP` WHERE NAMA = '".$nama."' ";
    	$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }
	
    function getGoodsGroupInfoAll()
    {	
		$qry = "SELECT KODE, NAMA, INISIAL, AKTIF, HD, SAT, STDBELI, STDJUAL, MINJUAL FROM `GROUP` WHERE AKTIF = 'Y' ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }

    function getGoodsGroupInfoAllCopy()
    {	
		$qry = "SELECT G.KODE, G.NAMA, G.INISIAL, G.AKTIF, G.HD, G.SAT, G.STDBELI, G.STDJUAL, G.MINJUAL 
				FROM `GROUP` AS G 
				LEFT JOIN PROD1 AS P1 ON P1.GROUP = G.KODE 
				WHERE G.AKTIF = 'Y' 
				AND P1.GROUP = G.KODE
				AND P1.FLAGPOS = 'Y' 
				GROUP BY G.KODE 
				ORDER BY G.NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }

	function getGoodsGroupInfo($kode)
    {	
		$qry = "SELECT KODE, NAMA, INISIAL, AKTIF, HD, SAT FROM `GROUP` 
				WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }
	
	function getGoodsGroupInfoByKode($kode)
    {	
		$qry = "SELECT KODE, NAMA, INISIAL, AKTIF, HD, SAT, STDBELI, STDJUAL, MINJUAL FROM `GROUP` 
				WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
	
		return $result;
    }

    function getGoodsGroupInfoByInisial($inisial)
    {	
		$qry = "SELECT KODE, NAMA, INISIAL, AKTIF FROM `GROUP` WHERE INISIAL ='".$inisial."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
    function getGoodsGroupInfoByNama($nama)
    {	
		$qry = "SELECT KODE, NAMA, INISIAL, AKTIF, HD FROM `GROUP` WHERE NAMA ='".$nama."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

	function getGoodsGroupInfoEdit($kode, $ip)
    {	
		$qry = "SELECT KODE, NAMA, INISIAL, AKTIF, HD , SAT, STDBELI, STDJUAL, MINJUAL, IP FROM `GROUP` WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		//VERIFY IP EDIT
		if($result[0]['IP'] == $ip || $result[0]['IP'] == ''){
			return $result;
		}else{
			return false;
		}
    }
	
	function getGoodsGroupCount()
    {	
		$qry = "SELECT KODE FROM `GROUP` 
				WHERE KODE REGEXP '^[0-9]+$'
				AND LENGTH(KODE) = 3
				ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function updateGoodsGroupIP($kode,$ip){
		$qry = "UPDATE `GROUP` SET IP = '".$ip."' WHERE KODE = '".$kode."'";
		if($this->db->query($qry)){
			return true;
		}else{
			return false;
		}
	}
	
	function updateGoodsByCol($col, $colValue, $key, $keyValue){
		$qry = "UPDATE PRODUK SET `".$col."` = '".$colValue."' WHERE `".$key."` = '".$keyValue."'";
		if($this->db->query($qry)){
			return true;
		}else{
			return false;
		}
	}
	
	function addGoodsGroup($goodsGroupInfo)
    {
		
        $this->db->insert('GROUP', $goodsGroupInfo);
       
		// //$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();	
		// echo($this->db->last_query());
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }	
	
	function editGoodsGroup($goodsGroupInfo, $kode)
    {
        $this->db->where('KODE', $kode);
        if($this->db->update('GROUP', $goodsGroupInfo)){
			return TRUE;
		}else{
			return false;
		}
    }
	
	function editGoodsGroupP($goodsGroupInfo2, $kode1)
    {
        $this->db->where('KODE', $kode1);
        $this->db->update('GROUP', $goodsGroupInfo2);
        
        return TRUE;
    }
	
	function addGoodsGroupKode($kode,$kode1)
	{
		$qry = "UPDATE `GROUP` SET KODE = '".$kode."' WHERE KODE = '".$kode1."'";
		$this->db->query($qry);
	}
	
	function editGoodsGroupIP($kode)
    {
        $qry = "UPDATE `GROUP` SET IP = '' WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }
	
	function getGoodsGroupCountByIP($ip)
    {	
		$qry = "SELECT KODE   
				FROM `GROUP` WHERE IP = '".$ip."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getGoodsGroupCountIP($ip)
    {	
		$qry = "SELECT COUNT(KODE) AS CNT 
				FROM `GROUP` 
				WHERE IP = '".$ip."' ";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getGoodsGroupLastKode(){
		$qry = "SELECT KODE FROM `GROUP` 
				WHERE KODE REGEXP '^[0-9]+$'
				AND LENGTH(KODE) = 3
				ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}
	
	function deleteGoodsGroup($kode)
	{
		$jenis = 'M. Kelompok Barang';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef'); 
		$qry = "INSERT INTO ".$db2.".HAPUS(CABANG, NOMOR, NAMA, NOXX, USID, UPDATE_AT, JENIS) SELECT '01', KODE, NAMA, 'GROUP', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' FROM ".$db1.".`GROUP` WHERE KODE='".$kode."'";
		// echo $qry;
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".GROUP WHERE KODE='".$kode."'";
		$query = $this->db->query($qry); 
       	return $query;
	}
	
	function cekInisial($inisials)
    {
        $qry = "SELECT INISIAL FROM `GROUP` WHERE INISIAL = '".$inisials."'";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
    }
	
    function ambilKodeBrand()
    {
    	$kode = "000";	
		$qry = "SELECT KODE FROM MEREK ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry); 
		
		$row = $query->row();   
		if($row != null){
			$kode = $row->KODE;
		}
		
		return array('kode'=>$kode);
    }

	function getMerekInfo()
	{
		$qry = "SELECT KODE, NAMA FROM MEREK WHERE AKTIF = 'Y' ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getBrandCountIP($ip)
    {	
		$qry = "SELECT COUNT(KODE) AS CNT 
				FROM MEREK 
				WHERE IP = '".$ip."' ";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getBrandInfo($kode)
    {	
		$qry = "SELECT KODE, NAMA, AKTIF FROM MEREK WHERE KODE ='".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getBrandInfoByNama($nama)
    {	
		$qry = "SELECT KODE, NAMA, AKTIF FROM MEREK WHERE NAMA ='".$nama."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function cekKodeExists($kode)
	{
		$qry = "SELECT KODE FROM MEREK WHERE KODE = ".$kode."";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
	}

	function getBrandCountByIP($ip)
    {	
		$qry = "SELECT KODE   
				FROM MEREK WHERE IP = '".$ip."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getBrandCount()
    {	
		$qry = "SELECT KODE FROM MEREK 
				WHERE KODE REGEXP '^[0-9]+$'
				AND LENGTH(KODE) = 3
				ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function cekdoe($kode)
	{
		$qry = "SELECT DOE FROM MEREK WHERE KODE='".$kode."'";
		$query = $this->db->query($qry);
		$row = $query->row(); 
				
		if($row->DOE == NULL)
		{
			$result = 0;
		}
		else
		{
			$result = 1;
		}
		
        return $result;
	}
	
	function brandListingCount($searchText = '', $searchStat = '', $searchOrder = '')
    {
        $this->db->select('BaseTbl.*');
        $this->db->from('MEREK as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%'
								OR BaseTbl.AKTIF LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchOrder)) {
            $this->db->order_by('BaseTbl.'.$searchOrder,'ASC');
        }
        $query = $this->db->get();
        return count($query->result());
    }
	
	function brandListing($searchText = '', $searchStat = '', $searchOrder = '', $page, $segment)
    {	
		$this->db->select('BaseTbl.*');
        $this->db->from('MEREK as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%'
								OR BaseTbl.AKTIF LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchOrder)) {
            $this->db->order_by('BaseTbl.'.$searchOrder,'ASC');
        }
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();  
        return $result; 
    }
    
    function brandListing2($searchText = '', $searchStat = '', $searchOrder, $page='')
    {	
		$this->db->select('BaseTbl.*');
        $this->db->from('MEREK as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%'
								OR BaseTbl.AKTIF LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchOrder)) {
            $this->db->order_by('BaseTbl.'.$searchOrder,'ASC');
        }
        // $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result_array();
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";
        return $result; 
    }
	
	function addBrand($brandInfo)
    {
        $this->db->trans_start();
        $this->db->insert('MEREK', $brandInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }

    function addBrandKode($kode,$kode1)
	{
		$qry = "UPDATE MEREK SET KODE ='".$kode."' WHERE KODE ='".$kode1."'";
		$this->db->query($qry);
	}
	
	function editBrand($brandInfo, $kode)
    {
        $this->db->where('KODE', $kode);
        $this->db->update('MEREK', $brandInfo);

        return TRUE;
    }
	
	function editBrandD($brandInfo2, $kode1)
    {
        $this->db->where('KODE', $kode1);
        $this->db->update('MEREK', $brandInfo2);

        return TRUE;
    }
	
	function editBrandIP($kode)
    {
        $qry = "UPDATE MEREK SET IP = '' WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }
	
	function deleteBrand($kode)
	{
		$jenis = 'M. Brand';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS(CABANG, NOMOR, NAMA, NOXX, USID, UPDATE_AT, JENIS) SELECT '01', KODE, NAMA, 'MEREK', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' FROM ".$db1.".MEREK WHERE KODE='".$kode."'";
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".MEREK WHERE AKTIF='".$kode."'";
		$query = $this->db->query($qry); 
       	return $query;
	}

	function cekProdukExists($kode)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT PRODUK FROM ".$db2.".JLHD WHERE PRODUK = '".$kode."'";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
	}

	function ambilKodeProduk()
    {
    	$kode = "000";	
		$qry = "SELECT KODE FROM PRODUK ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry); 
		
		$row = $query->row();   
		if($row != null){
			$kode = $row->KODE;
		}
		
		return array('kode'=>$kode);
    }

	function getKodeInfo()
	{
		$qry = "SELECT KODE, NAMA FROM PRODUK WHERE AKTIF = 'Y' ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getProdukCountIP($ip)
    {	
		$qry = "SELECT COUNT(KODE) AS CNT 
				FROM PRODUK  
				WHERE IP = '".$ip."' ";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getProdukInfo($kode)
    {	
		$qry = "SELECT KODE, NAMA, AKTIF FROM PRODUK WHERE KODE ='".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getProdukInfoByNama($nama)
    {	
		$qry = "SELECT KODE, NAMA, AKTIF FROM PRODUK WHERE NAMA ='".$nama."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function cekKodeProdukExists($kode)
	{
		$qry = "SELECT KODE FROM PRODUK WHERE KODE = ".$kode."";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
	}

	function getProdukCountByIP($ip)
    {	
		$qry = "SELECT KODE   
				FROM PRODUK WHERE IP = '".$ip."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getProdukCount()
    {	
		$qry = "SELECT KODE FROM PRODUK  
				WHERE KODE REGEXP '^[0-9]+$'
				AND LENGTH(KODE) = 3
				ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function cekdoeProduk($kode)
	{
		$qry = "SELECT DOE FROM PRODUK WHERE KODE='".$kode."'";
		$query = $this->db->query($qry);
		$row = $query->row(); 
				
		if($row->DOE == NULL)
		{
			$result = 0;
		}
		else
		{
			$result = 1;
		}
		
        return $result;
	}
	
	function produkListingCount($searchText = '', $searchStat = '')
    {
        $this->db->select('BaseTbl.*');
        $this->db->from('PRODUK as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%'
								OR BaseTbl.AKTIF LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        return count($query->result());
    }
	
	function produkListing($searchText = '', $searchStat = '', $searchOrder = '', $page, $segment)
    {	
		$this->db->select('BaseTbl.*');
        $this->db->from('PRODUK as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.KODE LIKE '%".$searchText."%'
                            	OR BaseTbl.NAMA LIKE '%".$searchText."%'
								OR BaseTbl.AKTIF LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchStat)) {
            $likeCriteria = "(BaseTbl.AKTIF = '".$searchStat."')";
            $this->db->where($likeCriteria);
        }
		if(!empty($searchOrder)) {
            $this->db->order_by('BaseTbl.'.$searchOrder,'ASC');
        }
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();  
        return $result; 
    }
	
	function addProduk($produkInfo)
    {
        $this->db->trans_start();
        $this->db->insert('PRODUK', $produkInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }

    function addProdukKode($kode,$kode1)
	{
		$qry = "UPDATE PRODUK SET KODE ='".$kode."' WHERE KODE ='".$kode1."'";
		$this->db->query($qry);
	}
	
	function editProduk($produkInfo, $kode)
    {
        $this->db->where('KODE', $kode);
        $this->db->update('PRODUK', $produkInfo);

        return TRUE;
    }
	
	function editProdukD($produkInfo2, $kode1)
    {
        $this->db->where('KODE', $kode1);
        $this->db->update('PRODUK', $brandInfo2);

        return TRUE;
    }
	
	function editProdukIP($kode)
    {
        $qry = "UPDATE PRODUK SET IP = '' WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }
	
	function deleteProduk($kode)
	{
		$jenis = 'M. Brand';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS(NOMOR, NAMA, NOXX, ID1, DOE, JENIS) SELECT KODE, NAMA, 'MEREK', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' FROM ".$db1.".PRODUK WHERE KODE='".$kode."'";
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".PRODUK WHERE KODE='".$kode."'";
		$query = $this->db->query($qry); 
       	return $query;
	}

    function updateUkuran($ukuranInfo, $nourut, $barang, $group)
    {
		// $db2 = $this->session->userdata('dbBulan');
		$arrstr = '';
		foreach($ukuranInfo as $keys=>$values){ 
			$arrstr .= "`".$keys."`"." = "."'".$values."',";
		}
		$arrstr = substr($arrstr,0,strlen($arrstr)-1);		
		$qry = "UPDATE UKURAN SET ".$arrstr." 
				WHERE NOURUT = '".$nourut."' AND BARANG = '".$barang."' AND `GROUP`='".$group."'";
		$query = $this->db->query($qry);
				
		return TRUE;
    }

    function updateProd1($prod1Info, $barang, $group)
    {
		$db2 = $this->session->userdata('dbBulan');
		$arrstr = '';
		foreach($prod1Info as $keys=>$values){ 
			$arrstr .= "`".$keys."`"." = "."'".$values."',";
		}
		$arrstr = substr($arrstr,0,strlen($arrstr)-1);		
		$qry = "UPDATE PROD1 SET ".$arrstr." 
				WHERE KODE = '".$barang."' AND `GROUP`='".$group."'";
		$query = $this->db->query($qry);

		// echo($this->db->last_query());''
				
		return TRUE;
    }
	
	function cekdoeWh($kode)
	{
		$qry = "SELECT DOE FROM GUDANG WHERE KODE='".$kode."'";
		$query = $this->db->query($qry);
		$row = $query->row(); 
				
		if($row->DOE == NULL)
		{
			$result = 0;
		}
		else
		{
			$result = 1;
		}
		
        return $result;
	}
	
	function getBarInfo()
	{
		$qry = "SELECT KODE, `GROUP`, INISIAL, NAMA, SAT, STDJUAL, TIPE, BARCODE 
				FROM PROD1 WHERE `GROUP` IS NOT NULL
				ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getGoodsInfoAll()
    {	
		$qry = "SELECT * FROM `PROD1` WHERE AKTIF = 'Y'";
		$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }

	function getGoodsInfoByKode($kode)
	{
		$qry = "SELECT KODE, `GROUP`, INISIAL, NAMA, SAT, STDBELI, STDJUAL, TIPE, GAMBAR 
				FROM PROD1 WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}
	
	function getGoodsInfoByInisial($inisial)
	{
		$qry = "SELECT KODE, `GROUP`, INISIAL, NAMA, SAT, STDBELI, STDJUAL, TIPE, GAMBAR, BARCODE 
				FROM PROD1 WHERE INISIAL = '".$inisial."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}

	function getGoodsInfoByInisialCopy($kode,$group)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT IFNULL(BL.HARGA,0) AS HARGA, BL.VENDOR, BL.LOKASI, BL.TANGGAL, V.NAMA AS SUPP, P.NAMA, P.BARCODE, P.INISIAL, P.KODE, P.`GROUP`, P.STDJUAL  
			FROM PROD1 AS P
			LEFT JOIN $db2.BLDT AS BL ON (P.`GROUP`,P.KODE) = (BL.`GROUP`,BL.BARANG) 
			LEFT JOIN VENDOR AS V ON (V.KODE,V.LOKASI) = (BL.VENDOR,BL.LOKASI) 
			WHERE P.KODE = '".$kode."' AND P.`GROUP` = '".$group."' 
			ORDER BY BL.NOMOR DESC LIMIT 1
		";
		// echo $qry;
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}

	function getGoodsInfoByNama($nama)
    {	
		$qry = "SELECT `GROUP`, KODE, NAMA, AKTIF FROM `PROD1` WHERE NAMA ='".$nama."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function goodsListingCount($searchText = '', $searchStat = '', $filter = '')
    {
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$cab = $this->session->userdata('curBranch');
			
		$db1 = $_SESSION['dbname'];
		$db2 = $_SESSION['dbname'].substr($tahun,2,2).$bulan;

		// akses gd
		$this->load->model('User_model');
		$userId = $this->session->userdata('userId');
		$data['userInfo'] = $this->User_model->getUserInfo($userId);
		$data['userInfo'] = json_decode(json_encode($data['userInfo']), True);

		$aksesGd = '';
		$arrGd = '';
		$subFilter = '';	
		if(count($data['userInfo']) > 0 ) {

			if ($data['userInfo'][0]['AKSESGUDANG'] != '') {
				if($arrGd == ''){
					$countwh = 0;
					$whInfo = explode(' ', $data['userInfo'][0]['AKSESGUDANG']);
					$countwh = count($whInfo);
					if($countwh>0)
		            {
		            	for($i=0;$i<$countwh;$i++)
		                {
		                	$arrGd .= "'".$whInfo[$i]."',";
		                }
		            }
				}
			} else {

				$countwh = 0;
				$this->load->model('Mpersediaan_model');
				$data['whInfo'] = $this->Mpersediaan_model->getWhInfoCopy('',$cab);
				$whInfo = json_decode(json_encode($data['whInfo']), True);
				$countwh = count($whInfo);
				if($countwh>0)
	            {
	            	for($i=0;$i<$countwh;$i++)
	                {
	                	$arrGd .= "'".$whInfo[$i]['KODE']."',";
	                }
	            }
			}

		}

		$aksesGd = $arrGd != '' ? substr($arrGd, 0, strlen($arrGd)-1) : '';

		if(!empty($aksesGd))
		{
			$subFilter .= " AND W.GUDANG IN (".$aksesGd.") ";
		}
		// end akses gd
		
		$qry = "SELECT P1.*, S.NAMA AS SATNAME, M.NAMA AS MEREKNAME, G.NAMA AS NamaGroup, 
				(SELECT IFNULL(SUM(IFNULL(STOK / P1.HTG,0)),0) FROM $db2.WARE1 W
				LEFT JOIN $db1.GUDANG GD ON GD.KODE = W.GUDANG 
				WHERE W.GROUP = P1.GROUP AND W.BARANG = P1.KODE
				AND GD.CB LIKE '%".$cab."%' ".$subFilter.") AS STOK1 
				FROM $db1.PRODUK P1 LEFT JOIN $db1.GROUP G ON G.KODE = P1.GROUP 
				LEFT JOIN $db1.SATUAN S ON S.KODE = P1.SAT 
				LEFT JOIN $db1.MEREK M ON M.KODE = P1.MEREK ";
		if(!empty($searchText))
		{
			$qry .= " WHERE P1.INISIAL LIKE '%".$searchText."%' OR P1.NAMA LIKE '%".$searchText."%' OR P1.BARCODE LIKE '%".$searchText."%' 
			OR P1.TIPE LIKE '%".$searchText."%' OR S.NAMA LIKE '%".$searchText."%' OR M.NAMA LIKE '%".$searchText."%' 
			OR P1.AKTIF LIKE '%".$searchText."%' OR `GROUP` LIKE '%".$searchText."%'";
			if(!empty($searchStat)) {
            	$qry .= " AND P1.AKTIF = '".$searchStat."' "; 
        	}
        	if($filter != ''){
				$qry .= $filter;
			}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " WHERE P1.AKTIF = '".$searchStat."' "; 

            	if($filter != ''){
					$qry .= $filter;
				}
        	}
		}
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function goodsListing($searchText = '', $searchStat = '', $searchOrder='', $page, $segment, $filter = '')
    {
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$cab = $this->session->userdata('curBranch');
			
		$db1 = $_SESSION['dbname'];
		$db2 = $_SESSION['dbname'].substr($tahun,2,2).$bulan;

		// akses gd
		$this->load->model('User_model');
		$userId = $this->session->userdata('userId');
		$data['userInfo'] = $this->User_model->getUserInfo($userId);
		$data['userInfo'] = json_decode(json_encode($data['userInfo']), True);

		$aksesGd = '';
		$arrGd = '';
		$subFilter = '';	
		if(count($data['userInfo']) > 0 ) {

			if ($data['userInfo'][0]['AKSESGUDANG'] != '') {
				if($arrGd == ''){
					$countwh = 0;
					$whInfo = explode(' ', $data['userInfo'][0]['AKSESGUDANG']);
					$countwh = count($whInfo);
					if($countwh>0)
		            {
		            	for($i=0;$i<$countwh;$i++)
		                {
		                	$arrGd .= "'".$whInfo[$i]."',";
		                }
		            }
				}
			} else {

				$countwh = 0;
				$this->load->model('Mpersediaan_model');
				$data['whInfo'] = $this->Mpersediaan_model->getWhInfoCopy('',$cab);
				$whInfo = json_decode(json_encode($data['whInfo']), True);
				$countwh = count($whInfo);
				if($countwh>0)
	            {
	            	for($i=0;$i<$countwh;$i++)
	                {
	                	$arrGd .= "'".$whInfo[$i]['KODE']."',";
	                }
	            }
			}

		}

		$aksesGd = $arrGd != '' ? substr($arrGd, 0, strlen($arrGd)-1) : '';

		if(!empty($aksesGd))
		{
			$subFilter .= " AND W.GUDANG IN (".$aksesGd.") ";
		}
		// end akses gd
			
		$qry = "SELECT P1.*, S.NAMA AS SATNAME, M.NAMA AS MEREKNAME, G.NAMA AS NamaGroup, 
				(SELECT IFNULL(SUM(IFNULL(STOK / P1.HTG,0)),0) FROM $db2.WARE1 W
				LEFT JOIN $db1.GUDANG GD ON GD.KODE = W.GUDANG 
				WHERE W.GROUP = P1.GROUP AND W.BARANG = P1.KODE
				AND GD.CB LIKE '%".$cab."%' ".$subFilter.") AS STOK1 
				FROM $db1.PRODUK P1 
				LEFT JOIN $db1.GROUP G ON G.KODE = P1.GROUP 
				LEFT JOIN $db1.SATUAN S ON S.KODE = P1.SAT 
				LEFT JOIN $db1.MEREK M ON M.KODE = P1.MEREK ";

		if(!empty($searchText))
		{
			if(!empty($searchStat)) {
				$qry .= " WHERE ( P1.INISIAL LIKE '%".$searchText."%' OR P1.NAMA LIKE '%".$searchText."%' OR P1.BARCODE LIKE '%".$searchText."%' 
				OR P1.TIPE LIKE '%".$searchText."%' OR S.NAMA LIKE '%".$searchText."%' OR M.NAMA LIKE '%".$searchText."%' 
				OR P1.AKTIF LIKE '%".$searchText."%' OR `GROUP` LIKE '%".$searchText."%' ) AND P1.AKTIF = '".$searchStat."' ";
        	}
        	else{
				$qry .= " WHERE P1.INISIAL LIKE '%".$searchText."%' OR P1.NAMA LIKE '%".$searchText."%' OR P1.BARCODE LIKE '%".$searchText."%' 
				OR P1.TIPE LIKE '%".$searchText."%' OR S.NAMA LIKE '%".$searchText."%' OR M.NAMA LIKE '%".$searchText."%' 
				OR P1.AKTIF LIKE '%".$searchText."%' OR `GROUP` LIKE '%".$searchText."%'";
        	}
			
        	if($filter != ''){
				$qry .= $filter;
			}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " WHERE P1.AKTIF = '".$searchStat."' "; 

            	if($filter != ''){
					$qry .= $filter;
				}
        	}
		}
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		if($searchOrder == "DOE") {
           $qry .= " ORDER BY P1.".$searchOrder." ASC ";
        } elseif ($searchOrder == "LOE") {
        	$qry .= " ORDER BY P1.".$searchOrder." ASC ";
        } elseif ($searchOrder == "KODE") {
        	$qry .= " ORDER BY P1.".$searchOrder." ASC ";
        } elseif ($searchOrder == "NAMA") {
        	$qry .= " ORDER BY P1.".$searchOrder." ASC ";
        } else {
        	$qry .= " ";
        }

		if ($page != '') {
			$qry .= "LIMIT ".$offset.", ".$page;
		}
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
		// echo $qry; die()
       	return $result;
    }

    function cekStatusBarang($kode,$group){
    	$where = array(
    		'BARANG' => $kode,
    		'GROUP' => $group
    	);

    	$db2 = $this->session->userdata('dbBulan');
    	$this->db->where($where);
    	$this->db->from($db2.".PROD3");
    	return $this->db->count_all_results();
    }

    function getCountFormulaBarang($kode,$group){
    	$where = array(
    		'BARANG' => $kode,
    		'GROUP' => $group
    	);

    	$this->db->where($where);
    	$this->db->from("FORMULA");
    	return $this->db->count_all_results();
    }
	
	function getGoodsInfo($inisial)
    {	
		$qry = "SELECT P.KODE, P.NAMA, P.GROUP, P.JENIS, P.INISIAL, P.BARCODE, P.SAT, 
				P.MEREK, P.AKTIF, P.TIPE, P.STDBELI, P.STDJUAL, P.MINJUAL, P.MINSTOK, 
				P.MAXSTOK, P.PAKAI, P.SETHRG, P.INGROUP, P.GAMBAR, G.INISIAL AS INGROUP1, P.STOK  
				FROM PROD1 P 
				JOIN `GROUP` G ON G.KODE = P.GROUP 
				WHERE P.INISIAL = '".$inisial."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getGoodsInfoByKodeGroup($group,$kode)
    {	
		$qry = "SELECT P.KODE, P.NAMA, P.GROUP, P.JENIS, P.INISIAL, P.BARCODE, P.SAT, 
				P.MEREK, P.AKTIF, P.TIPE, P.STDBELI, P.STDJUAL, P.MINJUAL, P.MINSTOK, 
				P.MAXSTOK, P.PAKAI, P.SETHRG, P.INGROUP, P.GAMBAR, P.FLAGPOS, 
				G.INISIAL AS INGROUP1, P.STOK  
				FROM PROD1 P 
				JOIN `GROUP` G ON G.KODE = P.GROUP 
				WHERE P.GROUP = '".$group."' 
				AND P.KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getUkurInfo($kode, $group)
    {
    	// $db2 = $this->session->userdata('dbBulan');	
		$qry = "SELECT NOURUT, PAK, QTY, STDBELI, STDJUAL, MINJUAL  
				FROM UKURAN 
				WHERE BARANG = '".$kode."' AND `GROUP` = '".$group."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getStokInfo($kode, $group)
    {
    	$db2 = $this->session->userdata('dbBulan');	
		$qry = "SELECT SUM(IFNULL(STOK,0)) AS stokWare1 FROM $db2.WARE1 W 
				WHERE W.GROUP = '".$group."' AND W.BARANG = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function getUkuranInfoBuatDitarik()
    {
    	// $db2 = $this->session->userdata('dbBulan');	
    	
    	// if ($periode != '') {
    	// 	$db2 = $periode;
    	// }
    	
		$qry = "SELECT BARANG, `GROUP`, SATUAN, HTG, NOURUT, PAK, QTY, STDBELI, STDJUAL, MINJUAL  
				FROM UKURAN";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getGoodsCount()
    {	
		$qry = "SELECT KODE FROM PROD1 ORDER BY KODE ASC";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getGoodsInisial($inisial = '')
	{
		$qry = "SELECT INISIAL, `GROUP`, KODE, NAMA FROM PROD1 ";
		if(!empty($inisial)) {
			$qry .= "WHERE INISIAL = '".$inisial."' ";
		}
		$qry .= "ORDER BY INISIAL";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getBarangInfo2($start, $limit)
	{
		$qry = "SELECT KODE, `GROUP`, HTG, INISIAL, NAMA, SAT, TIPE, STDBELI, STDJUAL, GAMBAR FROM PROD1 
				WHERE `GROUP` IS NOT NULL AND `AKTIF` = 'Y' AND FLAGPOS = 'Y' 
				ORDER BY NAMA ASC LIMIT ".$start.", ".$limit." ";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getBarangInfo()
	{
		$qry = "SELECT KODE, `GROUP`, HTG, INISIAL, NAMA, SAT, TIPE, STDBELI, STDJUAL, GAMBAR FROM PROD1 
				WHERE `GROUP` IS NOT NULL AND `AKTIF` = 'Y' AND FLAGPOS = 'Y' ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getBarangByNama($nabar)
	{
		$qry = "SELECT KODE, `GROUP`, HTG, INISIAL, NAMA, SAT, TIPE, STDBELI FROM PROD1 
				WHERE `GROUP` IS NOT NULL AND `AKTIF` = 'Y' AND NAMA = '$nabar' ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getBarangByGroupKode($group, $kode)
	{
		$qry = "SELECT P.KODE, P.GROUP, P.HTG, P.INISIAL, P.NAMA, P.SAT, P.TIPE, P.STDBELI,
				U.PAK, U.HTG AS HGPAK 
				FROM PROD1 P 
				LEFT JOIN UKURAN U ON P.GROUP = U.GROUP AND P.KODE = U.BARANG AND P.SAT = U.SATUAN 
				WHERE P.GROUP IS NOT NULL AND P.GROUP = '$group' AND P.KODE = '$kode'";
		$query = $this->db->query($qry);

		$row = $query->row();   
		
		$tipe = $row->TIPE;
		$htg = $row->HTG;
		$ptg = $row->HTG;
		$pak = $row->PAK;
		$hgpak = $row->HGPAK;
		
		return array('tipe'=>$tipe, 'htg'=>$htg, 'ptg'=>$ptg, 'pak'=>$pak, 'hgpak'=>$hgpak);
	}
	
	function getGroupInfo()
	{
		$qry = "SELECT KODE, NAMA FROM `GROUP`";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getGroup()
	{
		$qry = "SELECT * FROM `GROUP` WHERE AKTIF='Y'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	
	function getGroupInfoH()
	{
		$qry = "SELECT KODE, NAMA FROM `GROUP` WHERE HD = 0";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	function getGroup_($Group){
		$cSql = "SELECT * FROM `GROUP` WHERE `KODE` = '$Group' ";
		$result = $this->db->query($cSql)->result_array();
		$rsX = array();
		foreach($result as $key => $row){
			foreach($row as $key => $value){
				$rsX[$key] = $value;
			}
			
		}
		return $rsX;
	}
	function getBarang_($Group,$Barang){
		$cSql = "SELECT * FROM PROD1 WHERE `GROUP`=$Group AND KODE=$Barang";
		$result = $this->db->query($cSql)->result_array();
		$rsX = array();
		foreach($result as $key => $row){
			foreach($row as $key => $value){
				$rsX[$key] = $value;
			}
			
		}
		return $rsX;
	}

	// function addGoods($goodsInfo)
 //    {
 //        $this->db->trans_start();
 //        $this->db->insert('PROD1', $goodsInfo);
       
	// 	$insert_id = $this->db->affected_rows();		
		
 //        $this->db->trans_complete();
		
	// 	if($insert_id > 0){
	// 		return $insert_id;
	// 	} else {
	// 		return FALSE;
	// 	}
 //    }

    function addGoods($goodsInfo)
    {
		$db2 = $this->session->userdata('dbBulan');
		$arrkeys = '';
		$arrvalues = '';
		foreach($goodsInfo as $keys=>$values){
			$arrkeys .= "`".$keys."`".",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		$qry = "INSERT INTO PROD1 (".$arrkeys.") VALUES (".$arrvalues.")";
		$query = $this->db->query($qry);
				
		$insert_id = $this->db->insert_id();
				
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }

    function addGoodsImport($goodsImportInfo)
    {
		$db2 = $this->session->userdata('dbBulan');
		$arrkeys = '';
		$arrvalues = '';
		foreach($goodsImportInfo as $keys=>$values){
			$arrkeys .= "`".$keys."`".",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		$qry = "INSERT INTO IMPORTPROD1 (".$arrkeys.") VALUES (".$arrvalues.")";
		$query = $this->db->query($qry);
				
		$insert_id = $this->db->insert_id();
				
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }


    function getSatDetailInfo($sat)
    {	
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT KODE, NAMA FROM SATUAN WHERE KODE != '".$sat."'";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }

    function getSatFromUkur($barang, $group)
    {	
		// $db2 = $this->session->userdata('dbBulan');
		// if ($periode != '') {
		// 	$db2 = $periode;
		// }
		
		$qry = "SELECT PAK FROM UKURAN WHERE BARANG = '".$barang."' AND `GROUP` = '".$group."' ";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }
	
	function cekNamanyaLah($nama)
    {	
		$qry = "SELECT NAMA FROM PROD1 WHERE NAMA = '".$nama."' ";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }
	
    function cekInisialnyaLah($inisial)
    {	
		$qry = "SELECT INISIAL FROM PROD1 WHERE INISIAL = '".$inisial."' ";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }

   	function cekBarcodenyaLah($barcode)
    {	
		$qry = "SELECT BARCODE, INISIAL FROM PROD1 WHERE BARCODE = '".$barcode."' ";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }
	
    function cekSudahAdaGoodsBelum($barang, $group)
    {	
		$qry = "SELECT TIPE FROM PROD1 WHERE KODE = '".$barang."' AND `GROUP` = '".$group."' ";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }

	function getKodeBarang($kel)
	{
		$qry = "SELECT KODE FROM PROD1 
				WHERE `GROUP` = '".$kel."' 
				AND KODE REGEXP '^[0-9]+$'  
				ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}

	function ambilKodeBarang($kel)
    {
    	$kode = "00000";	
		$qry = "SELECT KODE FROM `PROD1` WHERE `GROUP` = '".$kel."' AND KODE REGEXP '^[0-9]+$' ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry); 
		
		$row = $query->row();   
		if($row != null){
			$kode = $row->KODE;
		}
		
		return array('kode'=>$kode);
    }
	
	function editGoods($goodsInfo, $inisial1)
    {
        $this->db->where('INISIAL', $inisial1);
        $this->db->update('PROD1', $goodsInfo);
        
        return TRUE;
    }
	
	function editGoodsIP($kode)
    {
        $qry = "UPDATE PROD1 SET IP = NULL WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }

    function editIP($table, $kode)
    {
    	// print_r($table);
        $qry = "UPDATE `$table` SET IP = NULL WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }

    function updateHtgProd1($htg, $stdjual, $stdbeli, $barang, $group)
    {
        $qry = "UPDATE PROD1 SET HTG = ".$htg.", STDJUAL = ".$stdjual.", STDBELI = ".$stdbeli." WHERE KODE = '".$barang."' AND `GROUP`= '".$group."'";
		$this->db->query($qry); 
		
		return TRUE;
    }

    function updateHtgUkuran($barang, $group, $qty)
    {
    	// $db2 = $this->session->userdata('dbBulan');

		$qry = "UPDATE UKURAN SET HTG = ".$qty." / QTY WHERE BARANG = '".$barang."' AND `GROUP`= '".$group."'";
		$this->db->query($qry); 
		
		return TRUE;
    }

    function getSatProd1($barang, $group)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT SAT, HTG FROM PROD1 WHERE KODE = '".$barang."' AND `GROUP` = '".$group."'";
		$query = $this->db->query($qry);
		$row = $query->row();
		
		$sat = $row->SAT;

		$htg = $row->HTG;

        return array('sat'=>$sat, 'htg'=>$htg);
	}

	function getGroupKodeByIngroupProd1($ingroup)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT `GROUP`, `KODE` FROM PROD1 WHERE `INGROUP` = '".$ingroup."' ";
		$query = $this->db->query($qry);
		$row = $query->row();
		
		$group = $row->GROUP;

		$kode = $row->KODE;

        return array('grup'=>$group, 'kode'=>$kode);
	}

	 function getLastQtyUkuranD($barang, $group)
	{
		// $db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT QTY FROM UKURAN WHERE BARANG = '".$barang."' AND `GROUP` = '".$group."' ORDER BY NOURUT DESC";
		$query = $this->db->query($qry);
		$row = $query->row();
		
		$qty = $row->QTY;

        return array('qty'=>$qty);
	}

    function getLastQtyUkuran($barang, $group, $sat=NULL)
	{
		// $db2 = $this->session->userdata('dbBulan');
		$satuan = $sat ?  " AND PAK='".$sat."'" : "";

		$qry = "SELECT QTY, HTG, STDBELI, STDJUAL FROM UKURAN WHERE BARANG = '".$barang."' AND `GROUP` = '".$group."' $satuan ";
		if ($sat == NULL) {
			$qry .= "ORDER BY NOURUT DESC LIMIT 1";
		}
		$query = $this->db->query($qry);
		$row = $query->row();
		
		$htg = $row->HTG;

		$stdbeli = $row->STDBELI;

		$stdjual = $row->STDJUAL;

		$qty = $row->QTY;

        return array('htg'=>$htg, 'stdbeli'=>$stdbeli, 'stdjual'=>$stdjual, 'qty'=>$qty);
	}
	
	function deleteGoods($id)
	{
		$jenis = 'M. Barang';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS(CABANG, NOMOR, NAMA, NOXX, ID1, DOE, JENIS) SELECT '01', KODE, NAMA, 'PROD1', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' FROM ".$db1.".PROD1 WHERE INISIAL='".$id."'";
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".PROD1 WHERE INISIAL='".$id."'";
		$query = $this->db->query($qry); 
       	return $query;
	}

	function deleteUkuranDiListing($kode, $group)
	{
		// $db2 = $this->session->userdata('dbBulan'); 
		$qry = "DELETE FROM UKURAN WHERE BARANG ='".$kode."' AND `GROUP` = '".$group."'";
		$query = $this->db->query($qry); 
       	return $query;
	}

	function getFormulaInfo($group, $kode)
    {
		$qry = "SELECT P.NAMA AS NAMABARANG, P.GROUP, P.KODE 
				FROM PROD1 P  
				WHERE P.GROUP = '".$group."' AND P.KODE = '".$kode."'";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }

    function getFormulaDetailInfo($group, $kode)
	{
		$qry = "SELECT F.*, P.NAMA AS NAMABARANG 
				FROM FORMULA F 
				JOIN PROD1 P ON P.GROUP = F.GROUP AND P.KODE = F.BARANG 
				WHERE F.GROUP = '".$group."' AND F.BARANG = '".$kode."' AND P.TIPE <> 1";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
	}

	function cekFormula($group, $barang, $group1, $barang1)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT * FROM FORMULA WHERE 
				`GROUP` = '$group' AND BARANG = '$barang' AND 
				GROUP1 = '$group1' AND BARANG1 = '$barang1'";
		$query = $this->db->query($qry);
		$result = $query->result_array(); 
		
        return $result;	
	}

	function addFormula($formulaInfo)
    {
       	$db2 = $this->session->userdata('dbBulan');
		$arrkeys = '';
		$arrvalues = '';
		foreach($formulaInfo as $keys=>$values){
			$arrkeys .= "`".$keys."`".",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		$qry = "INSERT INTO FORMULA (".$arrkeys.") VALUES (".$arrvalues.")";
		$query = $this->db->query($qry);
				
		$insert_id = $this->db->insert_id();
				
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }

	function editFormula($formulaInfo, $group, $barang, $no)
    {
		$db2 = $this->session->userdata('dbBulan');
		$arrstr = '';
		foreach($formulaInfo as $keys=>$values){ 
			$arrstr .= "`".$keys."`"." = "."'".$values."',";
		}
		$arrstr = substr($arrstr,0,strlen($arrstr)-1);		
		$qry = "UPDATE FORMULA SET ".$arrstr." 
				WHERE `GROUP` = '".$group."' AND BARANG = '".$barang."' AND KODE = '".$no."'";
		$query = $this->db->query($qry);

		return TRUE;
    }
	
	function deleteFormula($kode, $group, $barang)
	{
		// $db2 = $this->session->userdata('dbBulan'); 
		$qry = "DELETE FROM FORMULA WHERE BARANG1 ='".$barang."' AND GROUP1 = '".$group."' AND KODE = '".$kode."'";
		$query = $this->db->query($qry); 
       	return $query;
	}

	function getWhInfo2($wh = '', $array=false)
	{
		$curBranch = $this->session->userdata('curBranch');

		$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE NAMA IS NOT NULL AND CB='".$curBranch."'";
		if(!empty($wh)) {
			$qry .= "AND KODE = '".$wh."' ";
		}
		$qry .= "ORDER BY NAMA";
		$query = $this->db->query($qry);

		if ($array) {			
			$result = $query->result_array();
		}else{
			$result = $query->result();			
		}
		
		return $result;
	}

	function getWhInfo($wh = '', $array=false)
	{
		$curBranch = $this->session->userdata('curBranch');

		$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE AKTIF = 'Y' AND NAMA IS NOT NULL AND CB='".$curBranch."'";
		if(!empty($wh)) {
			$qry .= "AND KODE = '".$wh."' ";
		}
		$qry .= "ORDER BY NAMA";
		$query = $this->db->query($qry);

		if ($array) {			
			$result = $query->result_array();
		}else{
			$result = $query->result();			
		}
		
		return $result;
	}

	function getWhInfoCopy($wh = '', $cab)
	{
		$curBranch = $this->session->userdata('curBranch');

		if($cab == '00'){
			$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE NAMA IS NOT NULL ";
		} else {
			$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE NAMA IS NOT NULL AND CB='".$cab."'";
		}
		if(!empty($wh)) {
			$qry .= "AND KODE = '".$wh."' ";
		}
		$qry .= "ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getWhAllInfo($wh = '', $array=false)
	{
		$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE NAMA IS NOT NULL ";
		if(!empty($wh)) {
			$qry .= "AND KODE = '".$wh."' ";
		}
		$qry .= "ORDER BY NAMA";
		$query = $this->db->query($qry);

		if ($array) {			
			$result = $query->result_array();
		}else{
			$result = $query->result();			
		}
		
		return $result;
	}

	function getWhAllInfo1($wh = '', $array=false)
	{
		var_dump($wh);
		$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE NAMA IS NOT NULL ";
		if($wh != '') {
			$qry .= "AND KODE IN ".$wh." ";
		} else {	
			$qry .= "";
		}
		$qry .= "ORDER BY KODE";
		$query = $this->db->query($qry);

		if ($array) {			
			$result = $query->result_array();
		}else{
			$result = $query->result();			
		}
		
		return $result;
	}

	function getWhAllInfoCopy($array=false)
	{
		$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE NAMA IS NOT NULL ";
		
		$qry .= "ORDER BY NAMA";
		$query = $this->db->query($qry);

		if ($array) {			
			$result = $query->result_array();
		}else{
			$result = $query->result();			
		}
		
		return $result;
	}

	function addUkuran($ukuranInfo)
    {
		// $db2 = $this->session->userdata('dbBulan');
		$arrkeys = '';
		$arrvalues = '';
		foreach($ukuranInfo as $keys=>$values){
			$arrkeys .= "`".$keys."`".",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		$qry = "INSERT INTO UKURAN (".$arrkeys.") VALUES (".$arrvalues.")";
		$query = $this->db->query($qry);
				
		$insert_id = $this->db->insert_id();
				
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }
	
    function ambilKodeWh()
    {
    	$kode = "00000";	
		$qry = "SELECT KODE FROM GUDANG ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry); 
		
		$row = $query->row();   
		if($row != null){
			$kode = $row->KODE;
		}
		
		return array('kode'=>$kode);
    }

	function getWhInfoByKode($kode)
    {	
		$qry = "SELECT KODE, NAMA, ALAMAT1, ALAMAT2, TELP, OTORISASI, CB, GCB, AKTIF, GROUPGUDANG 
				FROM GUDANG WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

    function countWhProd1($kode)
    {
    	$db2 = $this->session->userdata('dbBulan');
    	$qry = "SELECT GUDANG
				FROM $db2.WARE1 WHERE GUDANG = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getWhCountByIP($ip)
    {	
		$qry = "SELECT KODE   
				FROM GUDANG WHERE IP = '".$ip."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getWhCountIP($ip)
    {	
		$qry = "SELECT COUNT(KODE) AS CNT 
				FROM GUDANG 
				WHERE IP = '".$ip."' ";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }
	
	function getWhCount()
    {	
		$qry = "SELECT KODE FROM GUDANG 
				WHERE KODE REGEXP '^[0-9]+$'
				AND LENGTH(KODE) = 5
				ORDER BY KODE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
    }

	function whListingCount($searchText = '', $searchStat = '')
    {
		$curBranch = $this->session->userdata('curBranch');
		$qry = "SELECT G.*, C.NAMA AS NAMACABANG 
				FROM GUDANG G
				JOIN CABANG C ON C.KODE = G.CB WHERE G.CB = '$curBranch' ";
		if(!empty($searchText))
		{
			$qry .= " AND G.KODE LIKE '%".$searchText."%' OR G.NAMA LIKE '%".$searchText."%' OR G.ALAMAT1 LIKE '%".$searchText."%' OR G.TELP LIKE '%".$searchText."%' OR G.AKTIF LIKE '%".$searchText."%' OR G.GROUPGUDANG LIKE '%".$searchText."%' OR G.OTORISASI LIKE '%".$searchText."%'";
			if(!empty($searchStat)) {
            	$qry .= " AND G.AKTIF = '".$searchStat."' "; 
        	}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " AND G.AKTIF = '".$searchStat."' "; 
        	}
		}
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function whListing($searchText = '', $searchStat = '', $searchOrder = '', $page, $segment)
    {	
		$curBranch = $this->session->userdata('curBranch');
		$qry = "SELECT G.*, C.NAMA AS NAMACABANG 
				FROM GUDANG G
				JOIN CABANG C ON C.KODE = G.CB WHERE G.CB = '$curBranch' ";
		if(!empty($searchText))
		{
			$qry .= " AND G.KODE LIKE '%".$searchText."%' OR G.NAMA LIKE '%".$searchText."%' OR G.ALAMAT1 LIKE '%".$searchText."%' OR G.TELP LIKE '%".$searchText."%' OR G.AKTIF LIKE '%".$searchText."%' OR G.GROUPGUDANG LIKE '%".$searchText."%' OR G.OTORISASI LIKE '%".$searchText."%'";
			if(!empty($searchStat)) {
            	$qry .= " AND G.AKTIF = '".$searchStat."' "; 
        	}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " AND G.AKTIF = '".$searchStat."' "; 
        	}
		}
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		if(!empty($searchOrder)) {
            	$qry .= " ORDER BY G.".$searchOrder." ASC"; 
    	}
		$qry .= " LIMIT ".$offset.", ".$page;
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
       return $result;
    }

    function whListing2($searchText = '', $searchStat = '', $searchOrder = '', $page)
    {	
		$curBranch = $this->session->userdata('curBranch');
		$qry = "SELECT G.*, C.NAMA AS NAMACABANG 
				FROM GUDANG G
				JOIN CABANG C ON C.KODE = G.CB WHERE G.CB = '$curBranch' ";
		if(!empty($searchText))
		{
			$qry .= " AND G.KODE LIKE '%".$searchText."%' OR G.NAMA LIKE '%".$searchText."%' OR G.ALAMAT1 LIKE '%".$searchText."%' OR G.TELP LIKE '%".$searchText."%' OR G.AKTIF LIKE '%".$searchText."%' OR G.GROUPGUDANG LIKE '%".$searchText."%' OR G.OTORISASI LIKE '%".$searchText."%'";
			if(!empty($searchStat)) {
            	$qry .= " AND G.AKTIF = '".$searchStat."' "; 
        	}
		} else {
			if(!empty($searchStat)) {
            	$qry .= " AND G.AKTIF = '".$searchStat."' "; 
        	}
		}
		
		if(!empty($searchOrder)) {
            	$qry .= " ORDER BY G.".$searchOrder." ASC"; 
    	}
		$qry .= " LIMIT ".$page;
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
       return $result;
    }
	
	function addWh($whInfo)
    {
        $this->db->trans_start();
        $this->db->insert('GUDANG', $whInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }
	
	function addWhKode($kode,$kode1)
	{
		$qry = "UPDATE GUDANG SET KODE = '".$kode."' WHERE KODE = '".$kode1."'";
		$this->db->query($qry);
	}
	
	function editWh($whInfo,$kode)
    {
        $this->db->where('KODE', $kode);
        $this->db->update('GUDANG', $whInfo);
        
        return TRUE;
    }
	
	function editWhK($whInfo2,$kode1)
    {
        $this->db->where('KODE', $kode1);
        $this->db->update('GUDANG', $whInfo2);
        
        return TRUE;
    }
	
	function editWhIP($kode)
    {
        $qry = "UPDATE GUDANG SET IP = '' WHERE KODE = '".$kode."'";
		$this->db->query($qry); 
		
		return TRUE;
    }
	
	function deleteWh($kode)
	{
		$jenis = 'M. Gudang';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS(CB, CABANG, NOMOR, NAMA, NOXX, ID1, DOE, JENIS) SELECT CB, '01', KODE, NAMA, 'GUDANG', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' FROM ".$db1.".GUDANG WHERE KODE='".$kode."'";
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".GUDANG WHERE KODE='".$kode."' ";
		$query = $this->db->query($qry); 
       	return $query;
	}
		
	function salesPriceGroupListingCount($searchText = '')
    {	
    	$curBranch = $this->session->userdata('curBranch');
		$qry = "SELECT A.*, C.NAMA AS NAMACABANG,
				CONCAT(G.INISIAL,' - ',G.NAMA) AS INGROUP  
				FROM STDJUALHD A
				LEFT JOIN CABANG C ON C.KODE = A.CB 
				LEFT JOIN `GROUP` G ON G.INISIAL = A.INGROUP 
				WHERE A.CB = '$curBranch' ";
		if(!empty($searchText))
		{
			$qry .= "AND (A.KODE LIKE '%".$searchText."%' OR NOMOR LIKE '%".$searchText."%' 
						OR A.NAMA LIKE '%".$searchText."%' OR G.NAMA LIKE '%".$searchText."%') ";
		} 
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function salesPriceGroupListing($searchText = '', $page, $segment)
    {	
    	$curBranch = $this->session->userdata('curBranch');
		$qry = $qry = "SELECT A.*, C.NAMA AS NAMACABANG,
						CONCAT(G.INISIAL,' - ',G.NAMA) AS INGROUP  
						FROM STDJUALHD A
						LEFT JOIN CABANG C ON C.KODE = A.CB 
						LEFT JOIN `GROUP` G ON G.INISIAL = A.INGROUP 
						WHERE A.CB = '$curBranch' ";
		if(!empty($searchText))
		{
			$qry .= "AND (A.KODE LIKE '%".$searchText."%' OR NOMOR LIKE '%".$searchText."%' 
						OR A.NAMA LIKE '%".$searchText."%' OR G.NAMA LIKE '%".$searchText."%') ";
		} 
		$query = $this->db->query($qry);
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		
		$qry .= " GROUP BY A.KODE ORDER BY A.KODE ASC LIMIT ".$offset.", ".$page;
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 

       	return $result;
    }
	
	function getDebeCeerWare1($gd, $group, $barang)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT DEBE, CEER, AWAL FROM ".$db2.".WARE1 WHERE GUDANG = ".$gd." AND `GROUP` = ".$group." AND BARANG = ".$barang."";
		$query = $this->db->query($qry);
		$row = $query->row(); 
				
		$debe = $row->DEBE;
		
		$ceer = $row->CEER;
		
		$awal = $row->AWAL;
		
        return array('debe' => $debe, 'ceer' => $ceer, 'awal' => $awal);
	}

	function addstokjual($kel){
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT IFNULL(SUM(PROD3.QTY),0) AS STOK
				FROM PROD1, $db2.PROD3 
				WHERE PROD1.INGROUP = '$kel' AND PROD1.`GROUP` = PROD3.`GROUP` AND PROD1.KODE = PROD3.BARANG AND PROD3.FLAG <= 5";
		$query = $this->db->query($qry);
		$result = $query->result_array();

		return $result;
	}
	
	function getKodeStdJual($nomor)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT KODE FROM STDJUALHD WHERE NOMOR = ".$nomor."";
		$query = $this->db->query($qry);
		$row = $query->row(); 
				
		$kode = $row->KODE;
		
        return array('kode' => $kode);
	}
	
	function getSalesPriceGroupTable()
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT NOMOR, NAMA FROM STDJUALHD
				ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	function getSalesPriceGroupInfo()
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT KODE AS BARANG, NAMA, STDJUAL, `GROUP`, INGROUP 
				FROM PROD1  
				GROUP BY KODE, NAMA, STDJUAL 
				ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}

	function getStokBarang($group, $barang)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT P1.NAMA, SUM(CASE WHEN P3.FLAG<=5 THEN P3.QTY ELSE -P3.QTY END) AS JUMLAHSTOK, 
				P3.CB, P3.GROUP, P1.INGROUP   
				FROM $db2.PROD3 P3  
				LEFT JOIN PROD1 P1 ON P1.GROUP = P3.GROUP AND P1.KODE = P3.BARANG
				WHERE P3.BARANG = '$barang' AND P3.GROUP = '$group' 
				GROUP BY P3.BARANG, P3.GROUP, P1.NAMA  
				ORDER BY P1.NAMA";
		$query = $this->db->query($qry);
		$result = $query->row_array();
		
		return $result;
	}
	
	function getStokBarangInisial($ingroup)
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT P1.NAMA, SUM(CASE WHEN P.FLAG<=5 THEN P.QTY ELSE -P.QTY END) AS JUMLAHSTOK, 
				P.CB, P.GROUP, P1.INGROUP  
				FROM $db2.PROD3 P 
  				LEFT JOIN PROD1 P1 ON P1.GROUP = P.GROUP AND P1.KODE = P.BARANG   
				WHERE P1.INGROUP LIKE '%$ingroup%' 
				GROUP BY P1.GROUP, P1.KODE, P1.NAMA 
				ORDER BY P1.NAMA";
		$query = $this->db->query($qry);
		$result = $query->row_array();
		//echo $qry;
		return $result;
	}

	// function getSalesPriceGroupInfo()
	// {
	// 	$db2 = $this->session->userdata('dbBulan');
	// 	$qry = "SELECT tab1.CB, tab2.BARANG, P1.NAMA, P1.STDJUAL, SUM(tab2.STOK) AS STOK, P1.`GROUP` FROM GUDANG tab1
	// 			JOIN ".$db2.".WARE1 tab2 on tab1.KODE = tab2.GUDANG
	// 			JOIN PROD1 P1 ON P1.KODE = tab2.BARANG
	// 			GROUP BY  tab1.CB, tab2.BARANG, P1.NAMA, P1.STDJUAL
	// 			ORDER BY tab1.CB, P1.NAMA";
	// 	$query = $this->db->query($qry);
	// 	$result = $query->result();
		
	// 	return $result;
	// }
	
	function addStdjualDt($stdjualDtInfo)
	{
		$db2 = $this->session->userdata('dbBulan');
		$arrkeys = '';
		$arrvalues = '';
		foreach($stdjualDtInfo as $keys=>$values){
			$arrkeys .= "`".$keys."`".",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		$qry = "INSERT INTO STDJUALDT (".$arrkeys.") VALUES (".$arrvalues.")";
		$query = $this->db->query($qry);
				
		$insert_id = $this->db->insert_id();
				
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
	}
	
	function updateStdJualDt($stdJualDtInfo, $nomor, $kodebarang, $group)
    {
       	$db2 = $this->session->userdata('dbBulan');
		$arrstr = '';
		foreach($stdJualDtInfo as $keys=>$values){ 
			$arrstr .= "`".$keys."`"." = "."'".$values."',";
		}
		$arrstr = substr($arrstr,0,strlen($arrstr)-1);		
		$qry = "UPDATE STDJUALDT SET ".$arrstr." 
				WHERE NOMOR = ".$nomor." AND BARANG = ".$kodebarang." AND `GROUP` = ".$group."";
		$query = $this->db->query($qry);
				
		return TRUE;
    }

    function updateStdJualHD($nomor, $data)
    {
    	$this->db->where($nomor);
       	$this->db->update('STDJUALHD',$data);
				
		return TRUE;
    }
	
	function deleteStdJualDt($nomor, $kodebarang)
	{
		$db2 = $this->session->userdata('dbBulan');
		
		$qry = "DELETE FROM STDJUALDT WHERE NOMOR = ".$nomor." AND BARANG = ".$kodebarang."";
		$query = $this->db->query($qry);
	
		return $query;
	}

	function cancelUkuran($nourut, $group, $barang)
	{
		// $db2 = $this->session->userdata('dbBulan');
		
		$qry = "DELETE FROM UKURAN WHERE BARANG = ".$barang." AND `GROUP` = ".$group." AND NOURUT = ".$nourut."";
		$query = $this->db->query($qry);
	
		return $query;
	}

	function deleteUkuran($nourut, $group, $barang)
	{
		// $db2 = $this->session->userdata('dbBulan');
		
		$qry = "DELETE FROM UKURAN WHERE BARANG = ".$barang." AND `GROUP` = ".$group." AND NOURUT = ".$nourut."";
		$query = $this->db->query($qry);

		$qrys = "UPDATE UKURAN SET NOURUT = NOURUT-1 WHERE NOURUT > ".$nourut." AND BARANG = ".$barang." AND `GROUP` = ".$group.""; 
		$query = $this->db->query($qrys);
		//$query = $DB2->query($qrys);
		
		$qryss = "UPDATE UKURAN SET NOURUT = RIGHT(CONCAT('000',NOURUT),3) WHERE LENGTH(NOURUT) < 3";
       	$query = $this->db->query($qryss);
	
		return $query;
	}

	function deleteUkuranItung1($group, $barang)
	{
		// $db2 = $this->session->userdata('dbBulan');
		
		$qry = "DELETE FROM UKURAN WHERE BARANG = ".$barang." AND `GROUP` = ".$group."";
		$query = $this->db->query($qry);

		$qryS = "DELETE FROM PROD1 WHERE KODE = ".$barang." AND `GROUP` = ".$group."";
		$query = $this->db->query($qryS);
	
		return $query;
	}
	
	function delSalesPriceGroup($nomor)
	{
		$jenis = 'P. Kelompok Std. Harga Jual';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS (NOMOR, NAMA, NOXX, ID1, DOE, JENIS) 
				SELECT NOMOR, NOMOR, NOMOR, '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' 
				FROM ".$db1.".STDJUALHD WHERE KODE = '".$nomor."'";
		$query = $this->db->query($qry);   
		$qrys = "DELETE FROM ".$db1.".STDJUALHD WHERE KODE='".$nomor."'";
		$query = $this->db->query($qrys);
		$qryss = "DELETE FROM ".$db1.".STDJUALDT WHERE KODE='".$nomor."'";
		$query = $this->db->query($qryss); 
       	return $query;
	}
	
	function hapusSalesPriceGroupIp($nomor)
	{
		$qry = "UPDATE STDJUALHD SET IP = NULL
				WHERE NOMOR = '".$nomor."'";
		$query = $this->db->query($qry);
				
		return TRUE;
	}

	function cek_dt_gudang($barang, $group, $gudang = ''){
		$db2 = $this->session->userdata('dbBulan');
		$curBranch = $this->session->userdata('curBranch');

		$gd = $gudang != '' ? " AND W.GUDANG = '$gudang'" : "";
		$data = "SELECT 
				G.KODE, G.NAMA, SUM(W.STOK) / P.HTG AS STOK, P.SAT, P.HTG
			FROM $db2.WARE1 W 
				LEFT JOIN GUDANG G ON G.KODE = W.GUDANG
				LEFT JOIN PROD1 P ON CONCAT(P.`GROUP`,P.KODE) = CONCAT(W.`GROUP`,W.BARANG)
			WHERE 
				W.BARANG = '$barang'
				AND W.GROUP = '$group' AND CB='".$curBranch."'
				$gd
			GROUP BY G.KODE
			ORDER BY G.NAMA";
		$result = $this->db->query($data)->result_array();

		return $result;
	}

	function cek_dt_gudang2($barang, $group, $gudang = '', $tgl){
		$db2 = $this->session->userdata('dbBulan');
		$curBranch = $this->session->userdata('curBranch');
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$mm = date('m');
		$yy = date('Y');
		$now = date('Y-m-d');

		if((sprintf("%02d", $bulan) == sprintf("%02d", $mm)) and (sprintf("%04d", $tahun) == sprintf("%04d", $yy)))
		{
			if($tgl < $now) {
				$tgl = $tgl.' 23:59:59';
			} else {
				$tgl = date('Y-m-d H:i:s');
			}
		} else {
			$tgl = $tgl.' 23:59:59';
		}

		$gd = $gudang != '' ? " AND P3.GUDANG = '$gudang'" : "";
		/*$data = "SELECT G.KODE, G.NAMA, SUM(CASE WHEN P3.FLAG <=5 THEN P3.QTY/P.HTG ELSE -P3.QTY/P.HTG END) AS STOK, 
					P.SAT, P.HTG, P3.TANGGAL
					FROM $db2.PROD3 P3
					LEFT JOIN GUDANG G ON G.KODE = P3.GUDANG
					LEFT JOIN PROD1 P ON CONCAT(P.`GROUP`,P.KODE) = CONCAT(P3.`GROUP`,P3.BARANG)
					WHERE
					P3.BARANG = '$barang'
					AND P3.GROUP = '$group' AND P3.TANGGAL <= '$tgl' AND P3.CB = '".$curBranch."'
					$gd
					GROUP BY G.KODE
					ORDER BY G.NAMA";*/
		$data = "SELECT G.KODE, G.NAMA, SUM(CASE WHEN P3.FLAG <=5 THEN P3.QTY/P.HTG ELSE -P3.QTY/P.HTG END) AS STOK, 
					P.SAT, P.HTG, P3.TANGGAL
					FROM $db2.PROD3 P3
					LEFT JOIN GUDANG G ON G.KODE = P3.GUDANG
					LEFT JOIN PROD1 P ON CONCAT(P.`GROUP`,P.KODE) = CONCAT(P3.`GROUP`,P3.BARANG)
					WHERE
					P3.BARANG = '$barang'
					AND P3.GROUP = '$group' AND P3.TANGGAL <= '$tgl' AND G.CB = '".$curBranch."' 
					$gd
					GROUP BY G.KODE
					ORDER BY G.NAMA";
		$result = $this->db->query($data)->result_array();

		return $result;
	}

	function getLogImportGoods()
    {	
		$qry = "SELECT * FROM `IMPORTPROD1`";
		$query = $this->db->query($qry);
		$result = $query->result();
	
		return $result;
    }
    
    function getAllGudang(){
		$cSql = "SELECT KODE FROM GUDANG";
		$query = $this->db->query($cSql);
		$result = $query->result();
		return $result;
	}

}

  