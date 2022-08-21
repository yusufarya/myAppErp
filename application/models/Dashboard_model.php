<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
	
	function getPeriodOfMonth($mingguKe)
	{
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$tglawalbulan = 1;
		$tgl_awal = $tglawalbulan;
		$tglawal = $tahun.'-'.sprintf("%02d", $bulan).'-'.sprintf("%02d", $tgl_awal);
		$tglakhirbulan = date("t", strtotime($tglawal));
		
		$arrPeriod = '';
		$this->load->library('Commonfunction');
		for($i=1;$i<=6;$i++)
		{
			$hari_awal = $this->commonfunction->namahari($tglawal,'nilai');
			switch($hari_awal){
				case '0' : $tgl_akhir = $tgl_awal + 6; break;
				case '1' : $tgl_akhir = $tgl_awal + 5; break;
				case '2' : $tgl_akhir = $tgl_awal + 4; break;
				case '3' : $tgl_akhir = $tgl_awal + 3; break;
				case '4' : $tgl_akhir = $tgl_awal + 2; break;
				case '5' : $tgl_akhir = $tgl_awal + 1; break;
				case '6' : $tgl_akhir = $tgl_awal + 0; break;
			}
			if($tgl_akhir > $tglakhirbulan) {
				$tgl_akhir = $tglakhirbulan;
			}
			if($i == $mingguKe) {
				$this->session->set_userdata('mingguKe', $i);
				$this->session->set_userdata('tglAwal', $tgl_awal);
				$this->session->set_userdata('tglAkhir', $tgl_akhir);
			}
			$period = $i.':'.$tgl_awal.':'.$tgl_akhir;
			$arrPeriod .= $period.'|';
			$tgl_awal = $tgl_akhir + 1;	
			$tglawal = $tahun.'-'.sprintf("%02d", $bulan).'-'.sprintf("%02d", $tgl_awal);
		}
	
		$arrPeriod = substr($arrPeriod,0,strlen($arrPeriod)-1);
		
		return $arrPeriod;
	}
	function getTotalPenjualanBarang($limit)
    {
		$db = $this->session->userdata('dbDef');
        $db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT DT.GROUP, DT.BARANG, PROD1.NAMA, 
				(SUM(DT.QTY*(DT.HARGA+DT.BIAYA+DT.TAXN+DT.LAIN-DT.DISCN))/1000) AS TOTJUAL 
				FROM ".$db2.".JLDT DT, ".$db.".PROD1 PROD1
				WHERE PROD1.GROUP=DT.GROUP AND PROD1.KODE=DT.BARANG
				GROUP BY DT.GROUP, DT.BARANG
				ORDER BY TOTJUAL DESC LIMIT ".$limit."";
		$query = $this->db->query($qry);   
        $result = $query->result();  
        
        return $result;
    }
	
	function getTotalPenjualanSalesman($limit)
    {
		$db = $this->session->userdata('dbDef');
        $db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT SALESM.NAMA, SUM(DT.QTY*(DT.HARGA+BIAYA+TAXN+LAIN)-DT.DISCN) AS TOTJUAL 
				FROM ".$db2.".JLDT DT, ".$db.".SALESM SALESM
				WHERE SALESM.KODE=DT.SALESM
				GROUP BY DT.SALESM
				ORDER BY TOTJUAL DESC LIMIT ".$limit."";
		$query = $this->db->query($qry);   
        $result = $query->result();  
        
        return $result;
    }
	
	function getTotalPenjualanNetto($tipe)
    {
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$tgl = '01'.'-'.$bulan.'-'.$tahun;
		$akhir = date("t", strtotime($tgl));
		
		$db = $this->session->userdata('dbDef');
        $db2 = $this->session->userdata('dbBulan');
		
		$awal = $this->session->userdata('tglAwal');
		$akhir = $this->session->userdata('tglAkhir');
		
		if($tipe == 'D') {	//Daily
			$total = 0;
			$arrJual = '';
			//for($i=1;$i<=$akhir;$i++)
			for($i=$awal;$i<=$akhir;$i++)
			{
				$qry = "SELECT (SUM(HD.HRGNET)/1000) AS HRGNET 
						FROM ".$db2.".JLHD HD 
						WHERE DAY(HD.TANGGAL) = '".$i."' 
						GROUP BY DATE(HD.TANGGAL)";
				$query = $this->db->query($qry);   
				$result = $query->result();  
				$row = $query->row(); 
				if($query->num_rows() > 0) {
					$val = $row->HRGNET;
				} else {
					$val = 0;
				}
				$arrJual .= $val.',';
				$total = $total + $val;
			}
			$arrJual = '['.substr($arrJual,0,strlen($arrJual)-1).']';
			
			return $arrJual.'|'.$total;
		} else if($tipe == 'M') {	//Monthly
			//$qry = "SELECT (SUM(DT.QTY*(DT.HARGA+DT.BIAYA+DT.TAXN+DT.LAIN)-DT.DISCN)/1000) AS HRGNET  
			//		FROM ".$db2.".JLDT DT WHERE DT.NOMOR IN (SELECT NOMOR FROM ".$db2.".JLHD)";
			$qry = "SELECT (SUM(HRGNET*KURS)/1000) AS HRGNET FROM ".$db2.".JLHD"; 
			$query = $this->db->query($qry);   
			$result = $query->result();  
			
			return $result;
		}
    }
	
	function getTotalBayarHutang()
    {
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$tgl = '01'.'-'.$bulan.'-'.$tahun;
		$akhir = date("t", strtotime($tgl));
		
		$db = $this->session->userdata('dbDef');
        $db2 = $this->session->userdata('dbBulan');
		
		$awal = $this->session->userdata('tglAwal');
		$akhir = $this->session->userdata('tglAkhir');
		
		$total = 0;
		$arrHutang = '';
		//for($i=1;$i<=$akhir;$i++)
		for($i=$awal;$i<=$akhir;$i++)
		{
			$qry = "SELECT (SUM(PPT.BAYAR)/1000) AS BAYAR   
					FROM ".$db2.".PHTGDT PPT 
					WHERE DAY(PPT.TANGGAL) = '".$i."'
					GROUP BY DATE(PPT.TANGGAL)";
			$query = $this->db->query($qry);   
			$result = $query->result();  
			$row = $query->row(); 
			if($query->num_rows() > 0) {
				$val = $row->BAYAR;
			} else {
				$val = 0;
			}
			$arrHutang .= $val.',';
			$total = $total + $val;
		}
		$arrHutang = '['.substr($arrHutang,0,strlen($arrHutang)-1).']';
        
        return $arrHutang.'|'.$total;
    }

    function getTotalLabaRugi()
    {
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$tgl = '01'.'-'.$bulan.'-'.$tahun;
		$akhir = date("t", strtotime($tgl));
		
		$db = $this->session->userdata('dbDef');
        $db2 = $this->session->userdata('dbBulan');
		
		$awal = $this->session->userdata('tglAwal');
		$akhir = $this->session->userdata('tglAkhir');
		
		$total = 0;
		$arrLabaRugi = '';
		//for($i=1;$i<=$akhir;$i++)
		for($i=$awal;$i<=$akhir;$i++)
		{
			$qry = "SELECT TANGGAL, SUM(QTY*(HARGA-DISCN+BIAYA+TAXN)-FIFO) AS LABARUGI 
					FROM ".$db2.".PROD3
					WHERE DAY(TANGGAL) = '".$i."' AND
					LEFT(NOMOR,2) IN('SI')
					GROUP BY TANGGAL 
					ORDER BY TANGGAL";
			$query = $this->db->query($qry);   
			$result = $query->result();  
			$row = $query->row(); 
			if($query->num_rows() > 0) {
				$val = $row->LABARUGI;
			} else {
				$val = 0;
			}
			$arrLabaRugi .= $val.',';
			$total = $total + $val;
		}
		$arrLabaRugi = '['.substr($arrLabaRugi,0,strlen($arrLabaRugi)-1).']';
        
        return $arrLabaRugi.'|'.$total;
    }
	
	function getTotalTerimaPiutang()
    {
		$bulan = $this->session->userdata('curMonth');
		$tahun = $this->session->userdata('curYear');
		$tgl = '01'.'-'.$bulan.'-'.$tahun;
		$akhir = date("t", strtotime($tgl));
		
		$db = $this->session->userdata('dbDef');
        $db2 = $this->session->userdata('dbBulan');
		
		$awal = $this->session->userdata('tglAwal');
		$akhir = $this->session->userdata('tglAkhir');
		
		$total = 0;
		$arrPiutang = '';
		//for($i=1;$i<=$akhir;$i++)
		for($i=$awal;$i<=$akhir;$i++)
		{
			$qry = "SELECT (SUM(PPT.BAYAR)/1000) AS BAYAR   
					FROM ".$db2.".PPTGDT PPT 
					WHERE DAY(PPT.TANGGAL) = '".$i."'
					GROUP BY DATE(PPT.TANGGAL)";
			$query = $this->db->query($qry);   
			$result = $query->result();  
			$row = $query->row(); 
			if($query->num_rows() > 0) {
				$val = $row->BAYAR;
			} else {
				$val = 0;
			}
			$arrPiutang .= $val.',';
			$total = $total + $val;
		}
		$arrPiutang = '['.substr($arrPiutang,0,strlen($arrPiutang)-1).']';
        
        return $arrPiutang.'|'.$total;
    }

    function getImgCabang($kode)
    {
    	$this->db->select('GAMBAR');
    	$this->db->from('CABANG');
    	$this->db->where('KODE', $kode);

    	$query = $this->db->get();

    	return $query->result();
    }

    function getLogsInfo()
    {
		$db2 = $this->session->userdata('dbBulan');

		$qry = "SELECT d2.LOGID, d2.LOGUSER, d1.USERNAME, d2.LOGIP, d2.LOGDATE, d2.LOGDESC 
			   		FROM ".$db2.".SYSLOG d2 LEFT JOIN SYSUSER d1 ON d2.LOGUSER = d1.USID ";
		if(!empty($searchText))
		{
			if ($this->session->userdata('pageLogin') == 'admin' || $this->session->userdata('pageLogin') == 'dev') {
				
			} else {
				$qry .= " WHERE d2.LOGUSER IN (SELECT USID FROM SYSUSER) AND d2.LOGUSER = '".$usid."' ";
			}
		}

		$qry .= "ORDER BY d2.LOGDATE DESC LIMIT 5";
		
    	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
      	return $result;
    }
}

  