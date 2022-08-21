<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time',-1);
ini_set('memory_limit','256M');

class Utility_model extends CI_Model
{
	
	function checkExistValue($col, $db, $tbl, $val)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "SELECT COUNT(*) AS CNTREC 
				FROM ".$table." 
				WHERE `".$col."` = '".$val."'";
		$query = $this->db->query($qry);   
        $result = $query->result();
		
       	return $result;	
	}

	function checkExistValue2($col, $col2, $db, $tbl, $val, $val2)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else if($db == '') {
			$db2 = '';
			$table = $tbl;
		} else if($db == 'def') {
			$db2 = 'def_acc';
			$table = $db2.'.'.$tbl;
		}
		
		$qry = "SELECT COUNT(*) AS CNTREC 
				FROM ".$table." 
				WHERE `".$col."` = '".$val."' 
				AND `".$col2."` = '".$val2."' ";
		$query = $this->db->query($qry);   
        $result = $query->result();
		
       	return $result;	
	}
	
	function getCountRec($table)
	{	
		$qry = "SELECT COUNT(*) AS CNTREC 
				FROM ".$table."";
		$query = $this->db->query($qry);   
        $result = $query->result();
		
		echo $result;
        //return $result;	
	}

	function getLastNoAntri($col, $db, $tbl, $left, $code = '', $tanggal)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "SELECT CAST(LEFT(".$col.",".$left.") AS INTEGER) AS LASTANTRI, CAST(LEFT(".$col.",11) AS INTEGER) AS RES 
				FROM ".$table." 
				WHERE TANGGAL = '".$tanggal."'"; 		
		// if(!empty($code))
		// {
		// 	$qry .= "WHERE LEFT(NOMOR,2) = '".$code."' ";
		// }
		
		$qry .="ORDER BY ".$col." DESC LIMIT 1 ";
		
		$query = $this->db->query($qry);   
        $result = $query->result_array();
		
        return $result;	
	}
	
	function getLastNo($col, $db, $tbl, $right, $code = '')
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "SELECT CAST(RIGHT(".$col.",".$right.") AS INTEGER) AS LASTNO 
				FROM ".$table." "; 		
		if(!empty($code))
		{
			$qry .= "WHERE LEFT(NOMOR,2) = '".$code."' ";
		}
		
		$qry .="ORDER BY ".$col." DESC LIMIT 1 ";
		
		$query = $this->db->query($qry);   
        $result = $query->result_array();
		
        return $result;	
	}
	
	function getLastNoCab($col, $db, $tbl, $right, $cab, $code='')
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "SELECT CAST(RIGHT(".$col.",".$right.") AS INTEGER) AS LASTNOCAB, NOREF AS LASTNOREF
				FROM ".$table." 
				WHERE CB = '".$cab."' ";
		if(!empty($code))
		{
			$qry .= "AND LEFT(NOMOR,2) = '".$code."' ";
		}
		$qry .= "ORDER BY ".$col." DESC LIMIT 1";
		$query = $this->db->query($qry);
		// var_dump($qry); die();
        $result = $query->result_array();
    	
        return $result;	
	}

	function getLastNoCabTx($col, $db, $tbl, $right, $cab, $cabke, $code='')
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "SELECT CAST(RIGHT(".$col.",".$right.") AS INTEGER) AS LASTNOCAB 
				FROM ".$table." 
				WHERE CB = '".$cab."' ";
		// $qry = "SELECT CAST(RIGHT(".$col.",".$right.") AS INTEGER) AS LASTNOCAB 
		// 		FROM ".$table." 
		// 		WHERE CBTX = '".$cab."' AND CB = '".$cabke."' ";
		if(!empty($code))
		{
			$qry .= "AND LEFT(NOMOR,2) = '".$code."' ";
		}
		$qry .= "ORDER BY ".$col." DESC LIMIT 1";
		$query = $this->db->query($qry);   
        $result = $query->result_array();
    	
        return $result;	
	}
   
   	function addHeaderInfo($headerInfo, $table, $col, $value, $cab, $valuecab)
	{
		$arrkeys = '';
		$arrvalues = '';
		foreach($headerInfo as $keys=>$values){
			$arrkeys .= $keys.",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		
		$qrySelect = "SELECT ".$col." FROM ".$table." WHERE ".$col." = '".$value."'";
		$querySelect = $this->db->query($qrySelect);
		$num = $querySelect->num_rows();
		
		if($valuecab != '') {
			$qrySelectCb = "SELECT NOMORCB FROM ".$table." 
							WHERE NOMORCB = '".$valuecab."'
							AND CB = '".$cab."'";
			$querySelectCb = $this->db->query($qrySelectCb);
			$numCb = $querySelectCb->num_rows();
		} else {
			$numCb = 0;	
		}
		
		if($num < 1 and $numCb < 1) {
			$qry = "INSERT INTO ".$table." (".$arrkeys.") VALUES (".$arrvalues.")";
			$query = $this->db->query($qry);
			
			//$insert_id = $this->db->insert_id();
			$insert_id = $this->db->affected_rows();
			
			if($insert_id > 0){
				return $insert_id;
			} else {                                    
				return FALSE;
			}
		} else {
			return FALSE;	
		}
	}

	function addHeaderInfoTx($headerInfo, $table, $col, $value, $cab, $cabke, $valuecab)
	{
		$arrkeys = '';
		$arrvalues = '';
		foreach($headerInfo as $keys=>$values){
			$arrkeys .= $keys.",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		
		$qrySelect = "SELECT ".$col." FROM ".$table." WHERE ".$col." = '".$value."'";
		$querySelect = $this->db->query($qrySelect);
		$num = $querySelect->num_rows();
		
		if($valuecab != '') {
			$qrySelectCb = "SELECT NOMORCB FROM ".$table." 
							WHERE NOMORCB = '".$valuecab."'
							AND CB = '".$cabke."' AND CBTX = '".$cab."'";
			$querySelectCb = $this->db->query($qrySelectCb);
			$numCb = $querySelectCb->num_rows();
		} else {
			$numCb = 0;	
		}
		
		if($num < 1 and $numCb < 1) {
			$qry = "INSERT INTO ".$table." (".$arrkeys.") VALUES (".$arrvalues.")";
			$query = $this->db->query($qry);
			
			//$insert_id = $this->db->insert_id();
			$insert_id = $this->db->affected_rows();
			
			if($insert_id > 0){
				return $insert_id;
			} else {                                    
				return FALSE;
			}
		} else {
			return FALSE;	
		}
	}
	
	function editHeaderInfo($headerInfo, $table, $col, $val)
	{
		$arrstr = '';
		foreach($headerInfo as $keys=>$values){ 
			$arrstr .= "`".$keys."`"." = "."'".$values."',";
		}
		$arrstr = substr($arrstr,0,strlen($arrstr)-1);		
		$qry = "UPDATE ".$table." SET ".$arrstr." 
				WHERE ".$col." = '".$val."'";
		$query = $this->db->query($qry);
				
		return TRUE;
	}
	
	function eraseIp($col, $value, $db, $tbl)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "UPDATE ".$table." SET IP = '' WHERE ".$col." = '".$value."'";
		$this->db->query($qry);
		
		return TRUE;
	}
	
	function unlockData($id, $col, $db, $tbl)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "UPDATE ".$table." SET IP = '' WHERE ".$col." = '".$id."'";
		$this->db->query($qry);
		
		return TRUE;
	}
	
	function unlockDataAll($db, $tbl)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db = $this->session->userdata('dbDef');
			$table = $db.'.'.$tbl;
		}
		
		$qry = "UPDATE ".$table." SET IP = '' WHERE IP <> ''";
		$this->db->query($qry);
		
		return TRUE;
	}
	
	function getSystabelInfo($tableName)
	{
		$qry = "SELECT * FROM SYSTABEL 
				WHERE NMTABEL = '".$tableName."'";
		$query = $this->db->query($qry); 
		$result = $query->result_array();
		
		return $result;
	}
	
	function getTableName($db)
	{
		$qry = "SELECT UPPER(TABLE_NAME) AS TABLE_NAME  
				FROM information_schema.tables
				WHERE table_schema = '$db'";
		$query = $this->db->query($qry); 
		$result = $query->result_array();
		
		return $result;
	}
	
	function updateSysmenu()
	{
		$db = $this->session->userdata('dbDef');
		$qry = "SELECT NAMA, KET, KET2 
				FROM ".$db.".SYSMENU  
				WHERE NAMA LIKE '%?%' OR KET LIKE '%?%' OR KET2 LIKE '%?%'";
		$query = $this->db->query($qry);
		if ($query->num_rows() > 0) {		
			foreach ($query->result() as $row)
			{
				$nama = $row->NAMA;
				$ket = $row->KET;
				$ket2 = $row->KET2;
				$_nama = str_replace('?','',$nama);
				$_ket = str_replace('?','',$ket);
				$_ket2 = str_replace('?','',$ket2);
				
				$qry2 = "UPDATE ".$db.".SYSMENU  
							SET NAMA = '".$_nama."' 
							WHERE NAMA = '".$nama."'";
				$query2 = $this->db->query($qry2);
				
				$qry2 = "UPDATE ".$db.".SYSMENU 
							SET KET = '".$_ket."' 
							WHERE KET = '".$ket."'";
				$query2 = $this->db->query($qry2);
				
				$qry2 = "UPDATE ".$db.".SYSMENU  
							SET KET2 = '".$_ket2."' 
							WHERE KET2 = '".$ket2."'";
				$query2 = $this->db->query($qry2);
			}
		}
		
		return $query;
	}
	
	function viewSystabel()
	{
		$db = $this->session->userdata('dbDef');
		$namatabel = '';
		$qry = "SELECT DISTINCT NMTABEL, COUNT(NMTABEL) AS CNT    
				FROM ".$db.".SYSTABEL 
				GROUP BY NMTABEL    
				ORDER BY NMTABEL";
		$query = $this->db->query($qry);
		if ($query->num_rows() > 0) {		
			foreach ($query->result() as $row)
			{
				$namatabel = $row->NMTABEL;
				$totkolom = $row->CNT;
				$this->Utility_model->updateSystabel($namatabel, $totkolom);
			}
		}
		
		return $query;
	}
	
	function updateSystabel($namatabel, $totkolom)
	{
		$db = $this->session->userdata('dbDef');
		$qry = "SELECT NMKOLOM, URUT 
				FROM ".$db.".SYSTABEL   
				WHERE URUT = 0 
				AND NMTABEL = '".$namatabel."'";
		$query = $this->db->query($qry);
		if ($query->num_rows() > 0) {		
			for ($i=1;$i<=$totkolom; $i++) {				
				$qry2 = "UPDATE ".$db.".SYSTABEL   
							SET URUT = ".$i." 
							WHERE NMTABEL = '".$namatabel."'";
				$query2 = $this->db->query($qry2);				
			}
		}
		
		return $query;
	}
	
	function create_db($db) 
	{
		$servername = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$conn = new mysqli($servername, $username, $password);
		if ($conn->connect_error) {
			//die("Koneksi gagal: ".$conn->connect_error);
			$res = '2';
		} else {	
			$sql = "CREATE DATABASE ".$db."";
			if ($conn->query($sql) === TRUE) {
				$res = '1';
			} else {
				$res = '0';
			}
		}
		
		$conn->close();
		
		return $res;
	}
	
	function tabledb_exists($database, $tablename) 
	{
		$qry = "SELECT * FROM information_schema.tables
				WHERE table_schema = '".$database."' 
				AND table_name = '".$tablename."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $query->result();
	}
	
	function column_exists($database, $tablename, $columnname) 
	{	
		$qry = "SELECT * FROM information_schema.columns
				WHERE table_schema = '".$database."' 
				AND table_name = '".$tablename."'
				AND column_name = '".$columnname."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $query->result();
	}
	
	function keys_exists($database, $tablename, $keysname) 
	{
		$table = $database.'.'.$tablename;
		
		$qry = "SHOW KEYS FROM ".$table." 
				WHERE UPPER(key_name) = '".strtoupper($keysname)."'";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $query->result();
	}
	
	function exec_syntax($syntax) 
	{	
		$servername = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
				
		$conn = new mysqli($servername, $username, $password);
		if ($conn->connect_error) {
			die("Koneksi gagal: ".$conn->connect_error);
		} else {	
			$sql = $syntax;
			$this->db->query($sql);
		}
		
		$conn->close();
	}
	
	function create_column($database, $tablename, $columnname, $columntype, $defaultvalue, $afterbefore, $columnold) 
	{	
		$servername = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$table = $database.".".$tablename;
		
		//ALTER TABLE `custom` ADD `PAKAI` CHAR(1) NULL DEFAULT 'Y' AFTER `AKTIF`;
		$conn = new mysqli($servername, $username, $password);
		if ($conn->connect_error) {
			die("Koneksi gagal: ".$conn->connect_error);
		} else {	
			if($defaultvalue == '') {
				$sql = "ALTER TABLE ".$table." ADD `".$columnname."` ".$columntype." NULL ".$afterbefore." `".$columnold."`";
			} else {
				$sql = "ALTER TABLE ".$table." ADD `".$columnname."` ".$columntype." NULL DEFAULT '".$defaultvalue."' ".$afterbefore." `".$columnold."`";
			}
			$this->db->query($sql);
		}
		
		$conn->close();
	}
	
	function copy_table($dbfrom, $dbto, $tablename) 
	{
		$tablefrom = $dbfrom.'.'.$tablename;
		$tableto = $dbto.'.'.$tablename;
		
		$servername = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$conn = new mysqli($servername, $username, $password);
		if ($conn->connect_error) {
			//die("Koneksi gagal: ".$conn->connect_error);
			$res = '2';
		} else {	
			$sql = "CREATE TABLE ".$tableto." LIKE ".$tablefrom."";
			if ($conn->query($sql) === TRUE) {
				$res = '1';
			} else {
				$res = '0';
			}
		}
		
		$conn->close();
		
		return $res;
	}
	
	function copy_tableBsRl($dbname, $tblFrom, $tblTo) 
	{
		$tablefrom = $dbname.'.'.$tblFrom;
		$tableto = $dbname.'.'.$tblTo;
		
		$servername = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$conn = new mysqli($servername, $username, $password);
		if ($conn->connect_error) {
			//die("Koneksi gagal: ".$conn->connect_error);
			$res = '2';
		} else {	
			$sql = "CREATE TABLE ".$tableto." LIKE ".$tablefrom."";
			if ($conn->query($sql) === TRUE) {
				$res = '1';
			} else {
				$res = '0';
			}
		}
		
		$conn->close();
		
		return $res;
	}
	
	function copy_fieldBsRl($dbname, $tblFrom, $tblTo) 
	{
		$tablefrom = $dbname.'.'.$tblFrom;
		$tableto = $dbname.'.'.$tblTo;
		
		try {
			$qry = "INSERT INTO $tableto SELECT * FROM $tblFrom";
			$query = $this->db->query($qry);
			
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	function menuSearchListing($user, $search){
		$qry = "SELECT REPLACE(SM.KET,'&','') AS KET,SM.LINK FROM SYSMENUUSER SMU 
				JOIN SYSMENU SM ON SMU.LADD = SM.LADD 
				WHERE SMU.USID = '".$user."' ";
		if(!empty($search)) {
			$qry .= "AND REPLACE(SM.KET,'&','') LIKE '%".$search."%' ";
		}
		$qry .= "AND SM.LAPD != 0 
				AND SM.LINK IS NOT NULL
				ORDER BY REPLACE(SM.KET,'&','')";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}
	
	function getSysLevelInfo()
    {
		$this->db->select('LVLID, LVLNAME');
        $this->db->from('SYSLEVEL');
		$this->db->order_by('LVLID');
        $query = $this->db->get();
		
        return $query->result();
    }
	
	function getCabangInfo($cab = '')
	{
		$qry = "SELECT KODE, NAMA FROM CABANG ";
		if(!empty($cab)) {
			$qry .= "WHERE KODE = '".$cab."'";
		}
		$qry .= " GROUP BY KODE ORDER BY NAMA";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	function getSysDataValue($kode)
	{
		$qry = "SELECT NAMA FROM SYSDATA
				WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}
	
	function updSysDataValue($kode,$nilai)
	{
		$qry = "UPDATE SYSDATA
				SET NAMA = '".$nilai."' 
				WHERE KODE = '".$kode."'";
		$query = $this->db->query($qry);
	}
	
	function getAcnoInfo()
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT KODE, NAMA, LEVEL FROM ".$db2.".ACNO ORDER BY KODE";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	function getPk($db, $tbl, $nofak)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		
		$qry = "SELECT PK FROM ".$tabel." WHERE NOMOR = '".$nofak."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
	}
	
	function getTotalBranch()
	{
		$query = $this->db->query("SELECT DISTINCT KODE FROM CABANG WHERE AKTIF = 'Y'");
		$hasil = $query->result();
		$result = $query->num_rows();
		
		return $result;
	}
	
	function getTableInfo($nama_tabel)
    {
		$qry = "SELECT * FROM SYSTABEL 
				WHERE NMTABEL = '".$nama_tabel."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
	
		return $result;
	}
	
	function grupMenuListingCount($searchText = '')
    {
		$qry = "SELECT COUNT(S1.GRID) 
				FROM SYSMENUGROUP S1 ";
		if(!empty($searchText))
		{
			$qry .= "WHERE S1.GRID LIKE '%".$searchText."%' ";
		}
		$qry .= "GROUP BY S1.GRID";
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function grupMenuListing($searchText = '', $page, $segment)
    {	
		$qry = "SELECT TBL.GRID, TBL.JML FROM (
				SELECT S1.GRID, COUNT(S1.LADD) AS JML  
				FROM SYSMENUGROUP S1 ";
		if(!empty($searchText))
		{
			$qry .= "WHERE S1.GRID LIKE '%".$searchText."%' ";
		}
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		
		$qry .= "GROUP BY S1.GRID) TBL  
				ORDER BY TBL.GRID ASC LIMIT ".$offset.", ".$page;
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
      	return $result;
    }
	
	function addGrupMenu($dataInfo)
	{
		$this->db->trans_start();
        $this->db->insert('SYSMENUGROUP', $dataInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
	}
	
	function deleteGrupMenu($id)
	{
		$qry = "DELETE FROM SYSMENUGROUP WHERE GRID='".$id."'";
		$query = $this->db->query($qry);
       	return $query;
	}
	
	function getUserMenuInfo($usid, $role)
    {
		$arrMenu = '';
		$i = -1;
		$qry1 = "SELECT DISTINCT s1.LADD, s1.URUT, s2.LINK, 
					s2.ICON, s1.LAPD, s2.LEVEL, s2.KET, s3.F4, s3.F5, s3.F6
					FROM SYSMENUUSER s1
					JOIN SYSMENU s2 ON s2.LADD = s1.LADD
					LEFT JOIN SYSPRIVILEGES s3 ON s3.USID = S1.USID AND s3.LADD = s1.LADD
					WHERE s2.LEVEL >= ".$role." 
					AND s1.USID = '".$usid."' 
					AND s1.LAPD = 0 AND s1.URUT > 0
					AND LEFT(s2.KET,1) <> '-'
					ORDER BY s1.URUT asc";
		$main_menu = $this->db->query($qry1);
		if ($main_menu->num_rows() > 0) {
			foreach ($main_menu->result() as $main) {
				$i = $i + 1;
				$arrMenu[$i] = sprintf("%02d",$main->URUT).".".str_replace('&','',$main->KET)."|".$main->LADD."|".$main->F4."|".$main->F5."|".$main->F6;
				$qry2 = "SELECT DISTINCT s1.LADD, s1.URUT, s2.LINK, 
							s2.ICON, s1.LAPD, s2.LEVEL, s2.KET, s3.F4, s3.F5, s3.F6
							FROM SYSMENUUSER s1
							JOIN SYSMENU s2 ON s2.LADD = s1.LADD
							LEFT JOIN SYSPRIVILEGES s3 ON s3.USID = S1.USID AND s3.LADD = s1.LADD
							WHERE s2.LEVEL >= ".$role." 
							AND s1.USID = '".$usid."' 
							AND s1.LAPD = ".$main->LADD." AND s1.URUT > 0
							AND LEFT(s2.KET,1) <> '-'
							ORDER BY s1.URUT asc";
				$sub_menu = $this->db->query($qry2);
				if ($sub_menu->num_rows() > 0) {
					foreach ($sub_menu->result() as $sub) {
						$i = $i + 1;
						$arrMenu[$i] = "&nbsp;&nbsp;&nbsp;".sprintf("%02d",$main->URUT).".".sprintf("%02d",$sub->URUT).".".str_replace('&','',$sub->KET)."|".$sub->LADD."|".$sub->F4."|".$sub->F5."|".$sub->F6;
						$qry3 = "SELECT DISTINCT s1.LADD, s1.URUT, s2.LINK, 
									s2.ICON, s1.LAPD, s2.LEVEL, s2.KET, s3.F4, s3.F5, s3.F6
									FROM SYSMENUUSER s1
									JOIN SYSMENU s2 ON s2.LADD = s1.LADD
									LEFT JOIN SYSPRIVILEGES s3 ON s3.USID = S1.USID AND s3.LADD = s1.LADD
									WHERE s2.LEVEL >= ".$role." 
									AND s1.USID = '".$usid."' 
									AND s1.LAPD = ".$sub->LADD." AND s1.URUT > 0
									AND LEFT(s2.KET,1) <> '-'
									ORDER BY s1.URUT asc";
						$sub_menu2 = $this->db->query($qry3);
						if ($sub_menu2->num_rows() > 0) {
							foreach ($sub_menu2->result() as $sub2) {
								$i = $i + 1;
								$arrMenu[$i] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".sprintf("%02d",$main->URUT).".".sprintf("%02d",$sub->URUT).".".sprintf("%02d",$sub2->URUT).".".str_replace('&','',$sub2->KET)."|".$sub2->LADD."|".$sub2->F4."|".$sub2->F5."|".$sub2->F6;					
							}
						} else {
						
						}
					}
				} else {
					$i = $i + 1;
					$arrMenu[$i] = "&nbsp;&nbsp;&nbsp;".sprintf("%02d",$main->URUT).".".sprintf("%02d",$sub->URUT).".".str_replace('&','',$sub->KET)."|".$sub->LADD."|".$sub2->F4."|".$sub2->F5."|".$sub2->F6;
				}
			}
		}
		
		return $arrMenu;
		//die(print_r($arrMenu));
	}
	
	function getUserCustomMenuInfo($usid){
		$query = "SELECT SYSPRIVILEGES.LADD,sysmenu.NAMA,SYSPRIVILEGES.F4,SYSPRIVILEGES.F5,SYSPRIVILEGES.F6 FROM SYSPRIVILEGES LEFT JOIN SYSMENU ON SYSPRIVILEGES.LADD = SYSMENU.LADD WHERE USID = '".$usid."'";
		$result = $this->db->query($query);
		$result = $query->num_rows();
	}
		
	function userMenuListingCount($searchText = '')
    {
		$qry = "SELECT COUNT(S1.USID) 
				FROM SYSMENUUSER S1 
				LEFT JOIN SYSUSER S2 ON S2.USID = S1.USID 
				WHERE S1.USID IN (SELECT USID FROM SYSUSER WHERE AKTIF = 'Y')";
		if(!empty($searchText))
		{
			$qry .= "AND S2.USERNAME LIKE '%".$searchText."%' OR S1.USID LIKE '%".$searchText."%' ";
		}
		$qry .= "GROUP BY S1.USID";
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function userMenuListing($searchText = '', $page, $segment)
    {	
		$qry = "SELECT TBL.USID, TBL.USERNAME, TBL.USLEVEL,
				TBL.JML FROM (
				SELECT S1.USID, S2.USERNAME, S2.USLEVEL,
				COUNT(S1.LADD) AS JML  
				FROM SYSMENUUSER S1 
				LEFT JOIN SYSUSER S2 ON S2.USID = S1.USID 
				WHERE S1.USID IN (SELECT USID FROM SYSUSER WHERE AKTIF = 'Y') ";
		if(!empty($searchText))
		{
			$qry .= "AND S2.USERNAME LIKE '%".$searchText."%' OR S1.USID LIKE '%".$searchText."%' ";
		}
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		
		$qry .= "GROUP BY S1.USID, S2.USERNAME, S2.USLEVEL) TBL  
				ORDER BY TBL.USID ASC LIMIT ".$offset.", ".$page;
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
      	return $result;
    }
	
	function addUserMenu($dataInfo)
	{
		$this->db->trans_start();
        $this->db->insert('SYSMENUUSER', $dataInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
	}
	
	function deleteUserMenu($id)
	{
		$qry = "DELETE FROM SYSMENUUSER WHERE USID = '".$id."'";
		$query = $this->db->query($qry);
       	return $query;
	}
	
	function menuListingCount($searchText = '')
    {	
		$qry = "SELECT NAMA, KET, LEVEL, `GROUP`, LADD, REP 
				FROM SYSMENU 
				WHERE LEVEL >= 0 AND URUT > 0 AND LEFT(KET,1) <> '-' ";
		if(!empty($searchText))
		{
			$qry .= "AND (NAMA LIKE '%".$searchText."%'
					  OR KET LIKE '%".$searchText."%'
					  OR `GROUP` LIKE '%".$searchText."%'
					  OR LADD LIKE '%".$searchText."%'
					  OR REP LIKE '%".$searchText."%') ";
		} 
		
		$query = $this->db->query($qry);
		
        return count($query->result());		
    }
    
	function menuListing($searchText = '', $page, $segment)
    {			
		$arrMenu = '';
		$i = -1;
		$qry1 = "SELECT DISTINCT s2.NAMA, s2.LADD, s2.URUT, s2.LINK, 
					s2.ICON, s2.LAPD, s2.LEVEL, s2.KET, s2.GROUP, s2.REP 
					FROM SYSMENU s2 
					WHERE s2.LEVEL >= 0 AND s2.LAPD = 0 AND s2.URUT > 0 
					AND LEFT(s2.KET,1) <> '-' 
					ORDER BY s2.URUT asc";
		$main_menu = $this->db->query($qry1);
		if ($main_menu->num_rows() > 0) {
			foreach ($main_menu->result() as $main) {
				$i = $i + 1;
				$arrMenu[$i] = sprintf("%02d",$main->URUT).".|".str_replace('&','',$main->NAMA)."|".$main->LADD."|".$main->KET."|".$main->LEVEL."|".$main->URUT."|".$main->REP."|".$main->LINK;
				$qry2 = "SELECT DISTINCT s2.NAMA, s2.LADD, s2.URUT, s2.LINK, 
							s2.ICON, s2.LAPD, s2.LEVEL, s2.KET, s2.GROUP, s2.REP 
							FROM SYSMENU s2 
							WHERE s2.LEVEL >= 0 AND s2.LAPD = ".$main->LADD." AND s2.URUT > 0 
							AND LEFT(s2.KET,1) <> '-' 
							ORDER BY s2.URUT ASC";
				$sub_menu = $this->db->query($qry2);
				if ($sub_menu->num_rows() > 0) {
					foreach ($sub_menu->result() as $sub) {
						$i = $i + 1;
						$arrMenu[$i] = "	".sprintf("%02d",$main->URUT).".".sprintf("%02d",$sub->URUT).".|".str_replace('&','',$sub->NAMA)."|".$sub->LADD."|".$sub->KET."|".$sub->LEVEL."|".$sub->URUT."|".$sub->REP."|".$sub->LINK;
						$qry3 = "SELECT DISTINCT s2.NAMA, s2.LADD, s2.URUT, s2.LINK, 
									s2.ICON, s2.LAPD, s2.LEVEL, s2.KET, s2.GROUP, s2.REP 
									FROM SYSMENU s2 
									WHERE s2.LEVEL >= 0 AND s2.LAPD = ".$sub->LADD."  
									AND LEFT(s2.KET,1) <> '-' 
									ORDER BY s2.URUT ASC";
						$sub_menu2 = $this->db->query($qry3);
						if ($sub_menu2->num_rows() > 0) {
							foreach ($sub_menu2->result() as $sub2) {
								$i = $i + 1;
								$arrMenu[$i] = "		".sprintf("%02d",$main->URUT).".".sprintf("%02d",$sub->URUT).".".sprintf("%02d",$sub2->URUT).".|".str_replace('&','',$sub2->NAMA)."|".$sub2->LADD."|".$sub2->KET."|".$sub2->LEVEL."|".$sub2->URUT."|".$sub2->REP."|".$sub2->LINK;					
							}
						} else {
						
						}
					}
				} else {
					$i = $i + 1;
					$arrMenu[$i] = "	".sprintf("%02d",$main->URUT).".".sprintf("%02d",$sub->URUT).".".str_replace('&','',$main->NAMA)."|".$sub->LADD."|".$sub->KET."|".$sub->LEVEL."|".$sub->URUT."|".$sub->REP."|".$sub2->LINK;
				}
			}
		}
		
		return $arrMenu;		
	}
	
	function getLastMenuUrut($lapd){
		$query = "SELECT MAX(URUT) AS URUT FROM SYSMENU WHERE LAPD = '".$lapd."'";
		$result = $this->db->query($query);
		$results = $result->row();
		return $results;
	}
	
	function getLastMenuLADD(){
		$query = "SELECT MAX(LADD) AS LADD FROM SYSMENU";
		$result = $this->db->query($query);
		$results = $result->row();
		return $results;
	}
	
	function editMenu($dataInfo,$index)
    {	
		$this->db->where('LADD', $index);
        $this->db->update('SYSMENU', $dataInfo);
        
        return TRUE;
	}
	
	function addMenu($dataInfo)
	{
		$this->db->trans_start();
        $this->db->insert('SYSMENU', $dataInfo);
       	
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
	}
	
	function deleteMenu($index)
    {	
		$jenis = 'U. Setting Menu';
		$db2 = $this->session->userdata('dbBulan');
		$qry = "INSERT INTO ".$db2.".HAPUS(NOMOR, NAMA, NOXX, DOE, JENIS) SELECT LADD, NAMA, 'SETTING_MENU', '".date('Y-m-d H:i:s')."', '".$jenis."' FROM ".$db1.".SYSMENU WHERE LADD= ".$index." ";
		$query = $this->db->query($qry);   
		$qry = "DELETE FROM ".$db1.".SYSMENU WHERE LADD=".$index." ";
		$query = $this->db->query($qry); 
       	return $query;
	}
	
	function tableListingCount($searchText = '')
    {
		$qry = "SELECT COUNT(S1.NMTABEL) 
				FROM SYSTABEL S1 
				WHERE NVIEW = 'Y' ";
		if(!empty($searchText))
		{
			$qry .= "AND S1.NMTABEL LIKE '%".$searchText."%' OR S1.KETTABEL LIKE '%".$searchText."%' ";
		}
		$qry .= "GROUP BY S1.NMTABEL";
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	function tableListing($searchText = '', $page, $segment)
    {	
		$qry = "SELECT TBL.NMTABEL, TBL.KETTABEL, TBL.JML FROM (
				SELECT S1.NMTABEL, S1.KETTABEL, COUNT(S1.NMKOLOM) AS JML  
				FROM SYSTABEL S1 
				WHERE NVIEW = 'Y' ";
		if(!empty($searchText))
		{
			$qry .= "AND S1.NMTABEL LIKE '%".$searchText."%' OR S1.KETTABEL LIKE '%".$searchText."%' ";
		}
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		
		$qry .= "GROUP BY S1.NMTABEL) TBL  
				ORDER BY TBL.NMTABEL ASC LIMIT ".$offset.", ".$page;
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
      	return $result;
    }
	
	function getTableInfoEdit($ntabel, $nkolom)
    {	
		$qry = "SELECT NMTABEL, NMKOLOM, NMHEADER, 
				NTAMPIL, NDEFAULT, NFIXED   
				FROM SYSTABEL 
				WHERE NMTABEL = '".$ntabel."' 
				AND NMKOLOM = '".$nkolom."'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		
		return $result;
    }
	
	function editTableInfo($tableInfo, $namaTabel)
    {
        $this->db->where('NMTABEL', $namaTabel);
        if($this->db->update('SYSTABEL', $tableInfo)){
			return TRUE;
		}else{
			return false;
		}
    }
	
	function editTable($tableInfo, $ntabel, $nkolom)
    {
        $this->db->where('NMTABEL', $ntabel);
		$this->db->where('NMKOLOM', $nkolom);
        if($this->db->update('SYSTABEL', $tableInfo)){
			return TRUE;
		}else{
			return false;
		}
    }
	
	function delete_files($dir, $usid, $ext)
	{
		$files = $dir.'*_'.$usid.'_*.'.$ext;
		$folders = glob($files);
		foreach($folders as $file){
			if(is_file($file))
			unlink($file);	//delete file
		}
	}
	
	function tableResetFixedAll()
    {
		$qry = "UPDATE SYSTABEL SET NTAMPIL = NFIXED, NDEFAULT = NFIXED";
		$query = $this->db->query($qry);
       	
		return $query;
    }
	
	function tableResetFixed($namaTabel)
    {
		$qry = "UPDATE SYSTABEL SET NTAMPIL = NFIXED, NDEFAULT = NFIXED  
				WHERE NMTABEL = '".$namaTabel."'";
		$query = $this->db->query($qry);
       	
		return $query;
    }
	
	function getDefaultInfo()
    {
		$this->db->select('KODE, NAMA');
        $this->db->from('SYSDATA');
		$this->db->order_by('KODE');
        $query = $this->db->get();
        return $query->result();
    }
	
	function itungdata()
    {
        $qry = "SELECT KODE FROM SYSDATA";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
    }
	
	function cekNoxx($nomor, $tabel)
	{
		$qry = "SELECT NOXX FROM ".$tabel." 
				WHERE NOXX = '".$nomor."' ";
		$query = $this->db->query($qry);   
        $result = $query->num_rows();
		
		return $result;
	}
	
	function getCountOfTable($tabel, $col, $val)
    {
        $qry = "SELECT * FROM ".$tabel."  
				WHERE ".$col." = '".$val."'";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
    }
	
	function hapusIp($dbname, $table, $colname, $value)
	{
		$tablename = $dbname.'.'.$table;
		$qry = "UPDATE ".$tablename." SET IP = NULL WHERE ".$colname." = '".$value."'";
		$this->db->query($qry);
		
		return TRUE;
	}
	
	function addDefaultSetting($defaultInfo)
    {
        $this->db->trans_start();
        $this->db->insert('SYSDATA', $defaultInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }
	
	function editDefaultSetting($defaultInfo, $kode)
    {
        $this->db->where('KODE', $kode);
        $this->db->update('SYSDATA', $defaultInfo);
        
        return TRUE;
    }
	
	function itungdatauser($usid)
    {
        $qry = "SELECT NAMA FROM SYSDATAUSER WHERE USID = '".$usid."'";
		$query = $this->db->query($qry);   
        $result = $query->num_rows(); 
        
        return $result;
    }
	
	function addUserMenuDefault($userDefaultInfo)
    {
        $this->db->trans_start();
        $this->db->insert('SYSDATAUSER', $userDefaultInfo);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }
	
	function editUserMenuDefault($userDefaultInfo, $usid, $kode)
    {
        $this->db->where('USID', $usid);
		$this->db->where('KODE', $kode);
        $this->db->update('SYSDATAUSER', $userDefaultInfo);
        
        return TRUE;
    }
	
	function getUserDefaultInfo($usid)
    {
		$this->db->select('KODE, NAMA');
        $this->db->from('SYSDATAUSER');
		$this->db->where('USID',$usid);
        $query = $this->db->get();
        return $query->result();
    }
	
	function dataRelease()
    {
		$db = $this->session->userdata('dbDef');
		$qry = "SELECT UPPER(TABLE_NAME) AS TABLE_NAME FROM information_schema.tables 
				WHERE TABLE_SCHEMA = '".$db."' 
				ORDER BY TABLE_NAME";
		$query = $this->db->query($qry);
		foreach ($query->result() as $row)
    	{
    	    $table = $db.'.'.$row->TABLE_NAME;
			if($row->TABLE_NAME != 'SYSBAHASA' and $row->TABLE_NAME != 'SYSDATA' 
				and $row->TABLE_NAME != 'SYSDATAUSER' and $row->TABLE_NAME != 'SYSLEVEL' 
				and $row->TABLE_NAME != 'SYSMENU' and $row->TABLE_NAME != 'SYSMENUGROUP' 
				and $row->TABLE_NAME != 'SYSMENUUSER' and $row->TABLE_NAME != 'SYSMODUL' 
				and $row->TABLE_NAME != 'SYSPRIVILEGES' and $row->TABLE_NAME != 'SYSTABEL' 
				and $row->TABLE_NAME != 'SYSUSER' and $row->TABLE_NAME != 'CABANG'
				and $row->TABLE_NAME != 'ACANALISIS' and $row->TABLE_NAME != 'ACNO'
				and $row->TABLE_NAME != 'ACMASTER' and $row->TABLE_NAME != 'ACTRANS') {  
					$qry11 = "TRUNCATE TABLE ".$table."";
					$query11 = $this->db->query($qry11);
			} else {
				if(strtoupper($row->TABLE_NAME) == 'SYSUSER') {
					$qry11 = "DELETE FROM ".$db.".SYSUSER WHERE USID NOT IN ('111')";
					$query11 = $this->db->query($qry11);
				} else if(strtoupper($row->TABLE_NAME) == 'SYSMENUGROUP') {
					$qry11 = "DELETE FROM ".$db.".SYSMENUGROUP WHERE GRID NOT IN ('ADMIN')";
					$query11 = $this->db->query($qry11);
				} else if(strtoupper($row->TABLE_NAME) == 'SYSMENUUSER') {
					$qry11 = "DELETE FROM ".$db.".SYSMENUUSER WHERE USID NOT IN ('111')";
					$query11 = $this->db->query($qry11);
				} 
			}
   		}
		
		$db2 = $this->session->userdata('dbBulan');
		$qry2 = "SELECT UPPER(TABLE_NAME) AS TABLE_NAME FROM information_schema.tables 
				WHERE TABLE_SCHEMA = '".$db2."' 
				ORDER BY TABLE_NAME";
		$query2 = $this->db->query($qry2);
		foreach ($query2->result() as $row2)
    	{
    	    $table2 = $db2.'.'.$row2->TABLE_NAME;
			$qry21 = "TRUNCATE TABLE ".$table2."";
			$query21 = $this->db->query($qry21);
   		}
		
       	return $query;
	}
	
	function checkSysPrivileges($usid,$id)
	{
		$qry = "SELECT * FROM SYSPRIVILEGES WHERE USID = '".$usid."' AND LADD = '".$id."'";
		/*$this->db->select('USID');
        $this->db->from('SYSPRIVILEGES');
		$this->db->where('USID', $usid);
		$this->db->where('LADD', $id);*/
		$query = $this->db->query($qry);   
        $result = $query->num_rows();
		        
        return $result;
        //return $query->result();
    }
	
	function updateSysPrivileges($usid,$id,$sysPrivilegesInfo)
	{
		$this->db->where('USID', $usid);
		$this->db->where('LADD', $id);
        $this->db->update('SYSPRIVILEGES', $sysPrivilegesInfo);
        
        return TRUE;
	}
	
	function addSysPrivileges($sysPrivilegesInfo){
		$this->db->trans_start();
        $this->db->insert('SYSPRIVILEGES', $sysPrivilegesInfo);
		
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
	}
	
	function backup($host, $user, $pass, $name, $tables, $path, $init) 
	{		
		$return = '';
		
		$conn = new mysqli($host, $user, $pass, $name);
		if ($conn->connect_error) {
			die("Koneksi gagal: ".$conn->connect_error);
		} else {
			if($tables == '')
			{	
				$tables = array();
				$qry = "SELECT UPPER(TABLE_NAME) AS TABLE_NAME  
						FROM INFORMATION_SCHEMA.TABLES  
						WHERE TABLE_SCHEMA = '".$name."'  
						ORDER BY TABLE_NAME";
				$query = $this->db->query($qry);
				$result = $query->result_array();
				foreach($result as $keys=>$values){
					$tables[] = $result[$keys]['TABLE_NAME']; 
				}
				
				foreach($tables as $idx=>$value)
				{
					$table = $tables[$idx];
					$num_fields = 0;
					$arrkeys = '';
					$arrvalues = '';
					$qry2 = 'SELECT * FROM '.$name.'.'.$table;
					$query2 = $this->db->query($qry2);
					$result2 = $query2->result_array();
					$num_fields = $query2->num_rows();
					
					$return .= 'DROP TABLE '.$name.'.'.$table.';';
					$return .= "\n\n";
					$qry3 = 'SHOW CREATE TABLE '.$name.'.'.$table;
					$query3 = $this->db->query($qry3);
					$result3 = $query3->result_array();
					$row2 = $result3[0]['Create Table'];
					$return .= "-- \n";
					$return .= "-- Table structure for table `".$table."`\n";
					$return .= "-- \n";
					$return .= "\n\n".$row2.";\n\n";
					
					$qry4 = 'SHOW COLUMNS FROM '.$name.'.'.$table;
					$query4 = $this->db->query($qry4);
					$result4 = $query4->result_array();
					$rowcnt4 = $query4->num_rows();
					
					$field = '(';
					foreach($result4 as $keys4=>$values4){ 
						$field .= $result4[$keys4]['Field'].","; 
					}
					$field = substr($field,0,strlen($field)-1).')';
					
					if($num_fields > 0) {
										
						foreach($result2 as $row=>$innerArray) {
							foreach($innerArray as $keys2=>$values2) {
								$arrkeys .= "`".$keys2."`,";
								$arrvalues .= "'".$values2."',";  
							}
						}
						$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
						$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
						
						$return .= 'INSERT INTO '.$name.'.'.$table.' ('.$arrkeys.') VALUES (';
						$return .= $arrvalues;
						$return .= ");";
					} 
					
					/*$qry5 = "SELECT a.TABLE_SCHEMA, a.TABLE_NAME, a.CONSTRAINT_TYPE, a.CONSTRAINT_NAME, 
							(SELECT GROUP_CONCAT(b.COLUMN_NAME SEPARATOR ',') 
								FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE b 
								WHERE b.TABLE_SCHEMA = a.TABLE_SCHEMA AND b.TABLE_NAME = a.TABLE_NAME 
								AND b.CONSTRAINT_NAME = a.CONSTRAINT_NAME 
								GROUP BY b.CONSTRAINT_NAME) AS COLUMN_NAME 
							FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS a 
							WHERE a.TABLE_SCHEMA = '".$name."' 
							AND a.TABLE_NAME = '".$table."'";
					$query5 = $this->db->query($qry5);
					$result5 = $query5->result_array();
					$rowcnt5 = $query5->num_rows();
					
					if($rowcnt5 > 0) {
					
						$return .= "-- \n";
						$return .= "-- Indexes for table `".$table."`\n";
						$return .= "-- \n";
						$return .= "\n";
						$return .= "ALTER TABLE `".$table."`\n";
						
						foreach($result5 as $row5) {
							if($row5['CONSTRAINT_TYPE'] == 'PRIMARY KEY') {
								$return .= "ADD PRIMARY KEY (`".$row5['COLUMN_NAME']."`)\n";
							} else if($row5['CONSTRAINT_TYPE'] == 'UNIQUE') {
								$return .= "ADD UNIQUE KEY `".$row5['CONSTRAINT_NAME']."` (`".$row5['COLUMN_NAME']."`)\n";
							} 
						}
	
					}*/
					
					$return .= "\n";
				
				}
				
				$backupName = 'db-backup-'.$name.'-'.$init.'.sql';
				$filename = $path.'/'.$backupName;
				$handle = fopen($filename,'w+');
				fwrite($handle, $return);
				fclose($handle);
			} else {
				
			}
		}
		
		return $return;
	}
	
	function backupData($dbname, $dbname2)
	{
	// 	/* BACKUP DATABASE SIS_ACC */
	// 	$this->db->database = $dbname;		
	// 	$tables = $this->db->list_tables();

	// 	$folder = str_replace( '\\', '/',FCPATH."$dbname");
	// 	mkdir($folder);
		
	// 	foreach ($tables as $key => $table) {
	// 		$path = $folder.'/'.$table.".csv";			
	// 		$query = "select * from `$table` into outfile '$path' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";	
	// 		$this->db->query($query);
	// 	}
	// 	/* AKHIR BACKUP DATABASE SIS_ACC */
		
	// 	/* BACKUP DATABASE SIS_ACC0912 */
	// 	$this->db->database = $dbname2;
	// 	$tables2 = $this->db->list_tables();
	// 	// echo $dbname;
	// 	$folder = str_replace( '\\', '/',FCPATH."$dbname2");
	// 	mkdir($folder);
		
	// 	foreach ($tables2 as $key => $table) {
	// 		$path = $folder.'/'.$table.".csv";			
	// 		$query = "select * from `$table` into outfile '$path' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";	
	// 		$this->db->query($query);
	// 	}
	// 	/* AKHIR BACKUP DATABASE SIS_ACC0912 */
	
	// 	$dbnames = [$dbname, $dbname2];
	// 	$tables=[$tables, $tables2];
	// 	$data = array(
	// 		"dbname" => $dbnames,
	// 		"table" => $tables
	// 	);

	// 	return $data;
	}

	function backupDataCopy($dbname, $dbname2, $dbname3)
	{
		// $dbnames = [];
		$dbnames = [];
		$arrTbl = [];
	 	/* BACKUP DATABASE SIS_ACC */
	 	$this->db->database = $dbname;		
	 	$tableM = $this->db->list_tables();
	 	$folder = str_replace( '\\', '/',FCPATH."$dbname");
	// 	// mkdir($folder);
		if (!file_exists($folder)) {
			$permit = 0755;
		    mkdir($folder, $permit, true);
	//        	// mkdir("backup/db/" . $folder, 0755, TRUE);
 		} 
		foreach ($tableM as $key => $table) {
			$path = $folder.'/'.$table.".csv";			
			$query = "select * from `$table` into outfile '$path' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";
			$this->db->query($query);
		}
 	// }

		$query = $this->db->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name BETWEEN '".$dbname2."' AND '".$dbname3."' ORDER BY schema_name ASC");
		$result = $query->result();
		$countdb = count($result);
		$dbs = '';
		for ($i=0; $i < $countdb; $i++) { 

			$dbper = $result[$i]->schema_name;
			// // $this->db->database = $dbname2;
			$this->db->database = $dbper;
			$tables2 = $this->db->list_tables();
			$folder = str_replace( '\\', '/',FCPATH."$dbper");
			print_r($folder);
			mkdir($folder);
			if (!file_exists($folder)) {
		        $permit = 0755;
			    mkdir($folder, $permit, true);
			}
			
			foreach ($tables2 as $key => $table) {
				$path = $folder.'/'.$table.".csv";
				$query = "select * from `$table` into outfile '$path' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";	
				$this->db->query($query);
			}
			array_push($dbnames, $dbper);
			array_push($arrTbl, $tables2);
		}
		for ($i=0; $i < count($dbnames); $i++) { 
			$dbsampai = $dbnames;
			$arrTbl = $arrTbl;
		}
		// $dbnames = [$dbname, $dbsampai];
		// $tables=[$tables, $arrTbl];
		$dbnames = $dbsampai;
		array_push($dbnames, $dbname);
		$tables= $arrTbl;
		array_push($tables, $tableM);

		$data = array(
			"dbname" => $dbnames,
			"table" => $tables
		);
		// die();
		return $data;
		
		// $query = $this->db->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name BETWEEN '".$dbname."' AND '".$dbsampai."' ORDER BY schema_name ASC");
		// $query = $query->result();
		// return $query;
		// $result = $query->num_rows();

		// $this->db->database = $db;
		// $tables2 = $this->db->list_tables();
		// $folder = str_replace( '\\', '/',FCPATH."$db");
		// if (!file_exists($folder)) {
	 //        mkdir($folder, 0777, TRUE);
		// }
		// foreach ($tables2 as $key => $table) {
		// 	$path = $folder.'/'.$table.".csv";			
		// 	$query = "select * from `$table` into outfile '$path' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";
		// 	$this->db->query($query);
		// }

		/* BACKUP DATABASE SIS_ACC0912 */
		
		// echo "======= = DATA = ============= <br>";
		// var_dump($data);
		// die();
	}

	function restoreData($databases,$path2){
		$tables2 = array();

		foreach ($databases as $key => $db) {

			$this->db->database = $db;
			$tables = $this->db->list_tables();
			// echo $dbname;

			$folder = str_replace( '\\','/', $path2."/$db");
			
			foreach ($tables as $key => $table) {
				// hapus data yang lama
				$this->db->truncate($table);

				/* Isi dengan data yang terakhir di backup */
				$path = $folder.'/'.$table.".csv";			
				$query = "load data infile '$path' into table `$table` fields terminated by ',' enclosed by '\"' lines terminated by '\\n'";

				$this->db->query($query);
			}	
			$tables2[] = $tables;
		}
		
		return $tables2;
	}

	function restoreDataSql($dbname, $path)
	{
		$this->db->database = $dbname;
		$dbSql = file_get_contents($path);
		$string_query = rtrim($dbSql, "\n;");
		$array_query = explode(";", $string_query);
		
		$tables = $this->db->list_tables();
		foreach ($tables as $key => $table) {
			// hapus data yang lama
			$this->db->query("drop table `$table`");
		}

		/* Restore Data Backup */
		foreach ($array_query as $key => $query) {
			$this->db->query($query);
		}
	}

	function restore($host, $user, $pass, $name, $tables, $path, $file)
	{	
		$return = '';
		
		$conn = new mysqli($host, $user, $pass, $name);
		if ($conn->connect_error) {
			die("Koneksi gagal: ".$conn->connect_error);
		} else {
		
			$nama_file = $file['name'];
			$ukrn_file = $file['size'];
			$tmp_file = $file['tmp_name'];
			
			if($nama_file == '')
			{
				echo "Fatal Error";
			}
			else 
			{
				$alamatfile = $rest_dir.$nama_file;
				$templine = array();
				
				if(move_uploaded_file($tmp_file, $alamatfile))
				{
					$templine = '';
					$lines = file($alamatfile);
					
					foreach($lines as $line)
					{
						if(substr($line,0,2) == '--' || $line == '')
							continue;
							
						$templine .= $line;
						
						if(substr(trim($line),-1,1) == ';')
						{
							mysql_query($templine) or print('Query gagal \'<strong>' . $templine . '\': '. 
							mysql_error(). '<br /><br />');
						}
					}
					echo "<center>Berhasil Restore Database.</center>";
				
				} else {
				
					echo "Proses upload gagal, kode error = ".$file['error'];
					
				}
			}
		}
	}
	
	function hitung_ulang($db2)
    {
		if($jenis == 'v1') {
			$qry = "UPDATE ".$tbl." SET POSJ = 'V' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vo') {
			$qry = "UPDATE ".$tbl." SET POSJ = '' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vt') {
			
		} else if($jenis == 'va') {
			
		}
		$query = $this->db->query($qry);

       	return $query;
	}
	
	function sn_index($db2)
    {
		if($jenis == 'v1') {
			$qry = "UPDATE ".$tbl." SET POSJ = 'V' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vo') {
			$qry = "UPDATE ".$tbl." SET POSJ = '' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vt') {
			
		} else if($jenis == 'va') {
			
		}
		$query = $this->db->query($qry);

       	return $query;
	}
	
	function hitung_hpp($db2)
    {
		if($jenis == 'v1') {
			$qry = "UPDATE ".$tbl." SET POSJ = 'V' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vo') {
			$qry = "UPDATE ".$tbl." SET POSJ = '' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vt') {
			
		} else if($jenis == 'va') {
			
		}
		$query = $this->db->query($qry);

       	return $query;
	}
	
	function dataValidation($inisial, $tbl, $jenis, $tglawal, $tglakhir)
    {
		if($jenis == 'v1') {
			$qry = "UPDATE ".$tbl." SET POSJ = 'V' 
					WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
					AND TANGGAL >= '".$tglawal."' 
					AND TANGGAL <= '".$tglakhir."'";
		} else if($jenis == 'vo') {
			if($inisial != 'gl') {
				$qry = "UPDATE ".$tbl." SET POSJ = '' 
						WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
						AND TANGGAL >= '".$tglawal."' 
						AND TANGGAL <= '".$tglakhir."'
						AND IFNULL(ACC,'') = ''";
			} else {
				$qry = "UPDATE ".$tbl." SET POSJ = '' 
						WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
						AND TANGGAL >= '".$tglawal."' 
						AND TANGGAL <= '".$tglakhir."'
						AND IFNULL(FLAG,'') = ''";
			}
		} else if($jenis == 'vt') {
			
		} else if($jenis == 'va') {
			$this->load->model('Akunting_model');
			$this->Akunting_model->delGldataByDate($tglawal,$tglakhir,$inisial);
			$this->Akunting_model->delThdByDate($tglawal,$tglakhir,$inisial);
			$this->Akunting_model->delTrxByDate($tglawal,$tglakhir,$inisial);
			if($inisial != 'gl') {
				$qry = "UPDATE ".$tbl." SET ACC = '' 
						WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
						AND TANGGAL >= '".$tglawal."' 
						AND TANGGAL <= '".$tglakhir."'";
			} else {
				$qry = "UPDATE ".$tbl." SET FLAG = '' 
						WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."' 
						AND TANGGAL >= '".$tglawal."' 
						AND TANGGAL <= '".$tglakhir."'";
			}
		}
		$query = $this->db->query($qry);
		
       	return $query;
	}

	function dataValidationAll($inisial, $tbl)
    {		
		$qry = "UPDATE ".$tbl." SET POSJ = 'V', ACC = ''  
				WHERE LEFT(NOMOR,2) = '".strtoupper($inisial)."'";
		$query = $this->db->query($qry);
		
       	return $query;
	}
	
	function ubahStatus_model($table='', $data=NULL, $where=NULL){
    	$db2 = $this->session->userdata('dbBulan');

		$qry = "SHOW TABLES FROM `$db2` LIKE '$table'";
		$query = $this->db->query($qry);
		$cekDbBulan = $query->result_array();

		$db2 = count($cekDbBulan) > 0 ? $db2.'.' : '';

    	$this->db->where($where);
    	$this->db->update($db2.$table, $data);

    	return TRUE;
    }

    function BLIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT BLDT.*, BLIM.IMEI FROM ".$db2.".BLDT, ".$db2.".BLIM 
					WHERE BLDT.NOMOR = BLIM.NOMOR AND BLDT.NOKEY = BLIM.NOKEY AND 
		 			BLDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CBXX."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TGL."','".$IM->VENDOR."','".$IM->LOKASI."','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",".$IM->DISCN.",".$IM->BIAYA.",
								".$IM->TAXN.",".$IM->LAIN.",'0','0','0','3','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function RBLIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT RBLDT.*, RBLIM.IMEI FROM ".$db2.".RBLDT, ".$db2.".RBLIM 
					WHERE RBLDT.NOMOR = RBLIM.NOMOR AND RBLDT.NOKEY = RBLIM.NOKEY AND 
		 			RBLDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CBXX."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TGL."','".$IM->VENDOR."','".$IM->LOKASI."','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								".$IM->TAXN.",".$IM->LAIN.",'0','0','0','8','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function JLIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT JLDT.*, JLIM.IMEI FROM ".$db2.".JLDT, ".$db2.".JLIM 
					WHERE JLDT.NOMOR = JLIM.NOMOR AND JLDT.NOKEY = JLIM.NOKEY AND 
		 			JLDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CBXX."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TGL."','','','".$IM->CUSTOM."',
								'".$IM->WILAYAH."','".$IM->SALESM."','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",".$IM->DISCN.",".$IM->BIAYA.",
								".$IM->TAXN.",".$IM->LAIN.",'0','0',".$IM->RATA.",'7','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function RJLIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT RJLDT.*, RJLIM.IMEI FROM ".$db2.".RJLDT, ".$db2.".RJLIM 
					WHERE RJLDT.NOMOR = RJLIM.NOMOR AND RJLDT.NOKEY = RJLIM.NOKEY AND 
		 			RJLDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CBXX."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TGL."','','','".$IM->CUSTOM."',
								'".$IM->WILAYAH."','".$IM->SALESM."','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								".$IM->TAXN.",".$IM->LAIN.",'0','0','0','4','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
		
			}	
		}	
		
		return $hasil;
	}

	function TXIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT TXDT.*, TXIM.IMEI FROM ".$db2.".TXDT, ".$db2.".TXIM 
					WHERE TXDT.NOMOR = TXIM.NOMOR AND TXDT.NOKEY = TXIM.NOKEY AND 
		 			TXDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {

				$this->load->model('General_model');
				$getGudang = $this->General_model->getGudangInfo($IM->PINDAH);
				$getGudang2 = $this->General_model->getGudangInfo($IM->GUDANG);

				$cabangke = count($getGudang) > 0 ? $getGudang[0]['CB'] : 'error';
				$cabangdari = count($getGudang2) > 0 ? $getGudang2[0]['CB'] : 'error';
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$cabangdari."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$cabangke."','".$IM->NOMOR."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TANGGAL."','','','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','RP', 1,".$IM->HARGA.",'0','0',
								'0','0','0','0','0','6','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}

				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->PINDAH."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$cabangke."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$cabangdari."','".$IM->NOMOR."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TANGGAL."','','','',
								'','','".$IM->PINDAH."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','RP', 1,".$IM->HARGA.",'0','0',
								'0','0','0','0','0','2','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function AJUS_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		/*$cSQL = "SELECT * FROM ".$db2.".AJUS 
					WHERE AJUS.CABANG = '".$cb."' ";*/
		$cSQL = "SELECT XPDT.*, XPIM.IMEI FROM ".$db2.".XPDT, ".$db2.".XPIM 
					WHERE XPDT.NOMOR = XPIM.NOMOR AND XPDT.NOKEY = XPIM.NOKEY AND 
		 			XPDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','',
								'','','','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TGL."','','','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								'0','0','0','0','0','".$IM->FLAG."','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function RVIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT RVDT.*, RVIM.IMEI FROM ".$db2.".RVDT, ".$db2.".RVIM 
					WHERE RVDT.NOMOR = RVIM.NOMOR AND RVDT.NOKEY = RVIM.NOKEY AND 
		 			RVDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CABANG."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','',
								'".$IM->TANGGAL."','".$IM->TGL."','".$IM->VENDOR."','".$IM->LOKASI."','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->LAMA.",'0','0',
								'0','0','0','0',".$IM->LAMA.",'8','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CABANG."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','',
								'".$IM->TANGGAL."','".$IM->TGL."','".$IM->VENDOR."','".$IM->LOKASI."','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->BARU.",'0','0',
								'0','0','0','0',".$IM->BARU.",'3','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
					
				}
		
			}	
		}	
		
		return $hasil;
	}

	function TBJ_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT * FROM ".$db2.".TUKARJ 
					WHERE TUKARJ.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."',
								'','".$IM->CABANG."','".$IM->NOXX."','','".$IM->NOREF."','',
								'".$IM->TANGGAL."','".$IM->TANGGAL."','','','".$IM->CUSTOM."',
								'".$IM->WILAYAH."','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."',
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								'0','0','0','0','0','5','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";

					$this->db->query($cSQL);
				
				}

				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEIBARU."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."',
								'','".$IM->CABANG."','".$IM->NOXX."','','".$IM->NOREF."','',
								'".$IM->TANGGAL."','".$IM->TANGGAL."','','','".$IM->CUSTOM."',
								'".$IM->WILAYAH."','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."',
								'".$IM->IMEIBARU."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								'0','0','0','0','0','9','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function TBB_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT * FROM ".$db2.".TUKARB 
					WHERE TUKARB.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."',
								'','".$IM->CABANG."','".$IM->NOXX."','','".$IM->NOREF."','',
								'".$IM->TANGGAL."','".$IM->TANGGAL."','".$IM->VENDOR."','".$IM->LOKASI."','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."',
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								'0','0','0','0','0','9','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}

				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEIBARU."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."',
								'','".$IM->CABANG."','".$IM->NOXX."','','".$IM->NOREF."','',
								'".$IM->TANGGAL."','".$IM->TANGGAL."','".$IM->VENDOR."','".$IM->LOKASI."','',
								'','','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEIBARU."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",'0','0',
								'0','0','0','0','0','5','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

	function TPIM_($db,$db2) {
		$hasil = true ;

		$cb = $this->session->userdata('curBranch');

		$cSQL = "SELECT TPDT.*, TPIM.IMEI FROM ".$db2.".TPDT, ".$db2.".TPIM 
					WHERE TPDT.NOMOR = TPIM.NOMOR AND TPDT.NOKEY = TPIM.NOKEY AND 
		 			TPDT.CABANG = '".$cb."' ";
		$rsIM = $this->db->query($cSQL);
		
		if ($rsIM->num_rows() > 0) {
			foreach ($rsIM->result() as $IM) {
				
				$cSQL = "SELECT * FROM ".$db2.".PROD2 WHERE NOMOR = '".$IM->NOMOR."' AND 
							`IMEI` = '".$IM->IMEI."' AND `GROUP` = '".$IM->GROUP."' AND 
							BARANG = '".$IM->BARANG."' AND GUDANG = '".$IM->GUDANG."' ";  
				$rsPROD2 = $this->db->query($cSQL);
				
				if ($rsPROD2->num_rows() <= 0) {

					$cSQL = "INSERT INTO ".$db2.".PROD2 (
								CABANG,CB,NOMOR,NOMORCB,NOKEY,CBXX,NOXX,NOSUB,NOREF,REF,TANGGAL,TGL, 
								VENDOR,LOKASI,CUSTOM,WILAYAH,SALESM,GUDANG,`GROUP`,BARANG,IMEI,
								UANG,KURS,HARGA,DISCN,BIAYA,TAXN,LAIN,STOK,RATA,FIFO,FLAG,DOE,TOE,LOE,DEO,
								`SIGN`,FLAGSRV,NAMACUSTOM,PIN,HP,TOKO,RUSAK,CS,TK,LENGKAP,REKAN,JENIS,STATUS,
								STS,GARANSI,KONFIRMASI,NMKONFIRMASI,TGLEXP,NOBATCH)
								SELECT  
								'".$IM->CABANG."','".$IM->CB."','".$IM->NOMOR."','".$IM->NOMORCB."','".$IM->NOKEY."',
								'".$IM->CBXX."','".$IM->NOXX."','".$IM->NOSUB."','".$IM->NOREF."','' ,
								'".$IM->TANGGAL."','".$IM->TGL."','','','".$IM->CUSTOM."',
								'".$IM->WILAYAH."','".$IM->SALESM."','".$IM->GUDANG."','".$IM->GROUP."','".$IM->BARANG."' ,
								'".$IM->IMEI."','".$IM->UANG."',".$IM->KURS.",".$IM->HARGA.",".$IM->DISCN.",".$IM->BIAYA.",
								".$IM->TAXN.",".$IM->LAIN.",'0','0','0','7','".$IM->DOE."','".$IM->TOE."', '".$IM->LOE."',
								'".$IM->DEO."','','','','','','','','','','','','','','','','','',NOW(),''";
					$this->db->query($cSQL);
				
				}
		
			}	
		}	
		
		return $hasil;
	}

    // Fungsi untuk melakukan proses upload file excel
	function upload_file_xls($filename){
		$this->load->library('upload'); // Load librari upload
		
		$config['upload_path'] = './excel/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '2048';
		$config['overwrite'] = true;
		$config['file_name'] = $filename;
	
		$this->upload->initialize($config); // Load konfigurasi uploadnya
		if($this->upload->do_upload('file')){ // Lakukan upload dan Cek jika proses upload berhasil
			// Jika berhasil :
			$return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
			return $return;
		}else{
			// Jika gagal :
			$return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
			return $return;
		}
	}
	
	// Buat sebuah fungsi untuk melakukan insert lebih dari 1 data
	function insert_multiple_xls($tablename, $data)
	{
		if(count($data) > 0)
        {
            for($i=0;$i<count($data);$i++)
            {
            	$arrkeys = '';
				$arrvalues = '';
            	foreach($data[$i] as $keys=>$values){
            		$arrkeys .= "`".$keys."`".",";
					$arrvalues .= "'".$values."',";
            	}
            	$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
				$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
				$qry = "INSERT INTO `".$tablename."` (".$arrkeys.") VALUES (".$arrvalues.")";
				// echo $qry;
				$query = $this->db->query($qry);
			}
						
			$insert_id = $this->db->insert_id();
						
			if($insert_id > 0){
				return $insert_id;
			} else {
				return FALSE;
			}
        }
	}

	function insert_multiple_xls_copy($tablename, $data)
	{

		$db2 = $this->session->userdata('dbBulan');

		if(count($data) > 0)
        {

            for($i=0;$i<count($data);$i++)
            {
            	$arrkeys = '';
				$arrvalues = '';
            	foreach($data[$i] as $keys=>$values){
            		$arrkeys .= "`".$keys."`".",";
					$arrvalues .= "'".$values."',";
            	}
            	$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
				$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
				$qry = "INSERT INTO `".$db2."`.BLHD (".$arrkeys.") VALUES (".$arrvalues.")";
				// echo $qry;
				$query = $this->db->query($qry);
			}
						
			$insert_id = $this->db->insert_id();
						
			if($insert_id > 0){
				return $insert_id;
			} else {
				return FALSE;
			}
        }
	}

	function insert_multiple_xls2($tablename, $data)
	{
		$db2 = $this->session->userdata('dbBulan');
		$table = $db2.'.'.$tablename;
		if(count($data) > 0)
        {
            for($i=0;$i<count($data);$i++)
            {
            	$arrkeys = '';
				$arrvalues = '';
            	foreach($data[$i] as $keys=>$values){
            		$arrkeys .= "`".$keys."`".",";
					$arrvalues .= "'".$values."',";
            	}
            	$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
				$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
				$qry = "INSERT INTO ".$table." (".$arrkeys.") VALUES (".$arrvalues.")";
				// echo $qry;
				$query = $this->db->query($qry);
			}
						
			$insert_id = $this->db->insert_id();
						
			if($insert_id > 0){
				return $insert_id;
			} else {
				return FALSE;
			}
        }
	}

	function truncateTable($db, $tbl)
	{
		if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}

		$qry = "TRUNCATE TABLE ".$table."";
		$query = $this->db->query($qry);
	}
	
	function getSysdata($kode)
    {
    	try {
			$this->db->where('KODE', $kode);
			$this->db->select('KODE, NAMA');
	        $this->db->from('SYSDATA');
			$this->db->order_by('KODE');
	        $query = $this->db->get();
	        return $query->result();
    		
    	} catch (Exception $e) {
    		return false;
    	}
    }
    
    function getDefaultLvlOtor($kode)
    {
		$qry = "SELECT NAMA 
				FROM SYSDATA WHERE KODE = $kode";
		$query = $this->db->query($qry);   
		$result = $query->result_array();
				
		return $result;
    }

    function getNprt($tbl,$db,$nomor){
    	if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		try {
			$this->db->where('NOMOR', $nomor);
			$this->db->select('NPRT');
	        $this->db->from($table);
	        $query = $this->db->get();
	        return $query->result();
    		
    	} catch (Exception $e) {
    		return false;
    	}
    }

    function updateNprt($tbl,$db,$nomor,$data){
    	if($db == '2') {
			$db2 = $this->session->userdata('dbBulan');
			$table = $db2.'.'.$tbl;
		} else {
			$db2 = '';
			$table = $tbl;
		}
		try {
	    	$this->db->where('NOMOR', $nomor);
	        $this->db->update($table, $data);
			return true;
		} catch (Exception $e) {
			return false;
		}
    }
	
}

  