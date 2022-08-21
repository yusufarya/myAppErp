<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
	/**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
		$this->db->select('BaseTbl.USID, BaseTbl.EMAIL, BaseTbl.USERNAME, BaseTbl.USERLEVEL, BaseTbl.GAMBAR');
        $this->db->from('SYSUSER as BaseTbl');
        $likeCriteria = "BaseTbl.AKTIF = 'Y' ";
        if(!empty($searchText)) {
            $likeCriteria .= "AND (BaseTbl.EMAIL LIKE '%".$searchText."%'
                            	OR BaseTbl.USERNAME LIKE '%".$searchText."%'
								OR BaseTbl.USERLEVEL LIKE '%".$searchText."%'
								OR BaseTbl.USID LIKE '%".$searchText."%')";
            //$this->db->where($likeCriteria);
        }
        $this->db->where($likeCriteria);
        $query = $this->db->get();
       
        return count($query->result());
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */ 
	function userListing($searchText = '', $searchOrder = '', $page, $segment)
    {	
		$qry = "SELECT BaseTbl.USID, BaseTbl.EMAIL, BaseTbl.USERNAME, BaseTbl.USERLEVEL, BaseTbl.GROUP, 
				BaseTbl.GCB, BaseTbl.GGD, BaseTbl BaseTb, BaseTbl.JAMAWAL, BaseTbl.JAMAKHIR, 
				BaseTbl.STRUK, BaseTbl.DESCRIPTION, BaseTbl.SALDOKAS, BaseTbl.DC, BaseTbl.USERTYPE, BaseTbl.GAMBAR,
				c.NAMA AS NAMACABANG, l.IP 
			   	FROM SYSUSER BaseTbl 
				LEFT JOIN CABANG c ON c.KODE = BaseTbl
				LEFT JOIN SYSLOGIN l ON l.USID = BaseTbl.USID 
				WHERE BaseTbl.AKTIF = 'Y' ";
		if(!empty($searchText))
		{
			$qry .= "AND (BaseTbl.EMAIL LIKE '%".$searchText."%'
                            	OR BaseTbl.USERNAME LIKE '%".$searchText."%'
								OR BaseTbl.USERLEVEL LIKE '%".$searchText."%'
								OR BaseTbl.USID LIKE '%".$searchText."%' 
								OR BaseTbl.GROUP LIKE '%".$searchText."%'
								OR BaseTbl.GCB LIKE '%".$searchText."%' 
								OR BaseTbl.GGD LIKE '%".$searchText."%' 
								OR BaseTblLIKE '%".$searchText."%' 
								OR BaseTb LIKE '%".$searchText."%' 
								OR BaseTbl.EXP LIKE '%".$searchText."%' 
								OR BaseTbl.JAMAWAL LIKE '%".$searchText."%' 
								OR BaseTbl.JAMAKHIR LIKE '%".$searchText."%' 
								OR BaseTbl.STRUK LIKE '%".$searchText."%' 
								OR BaseTbl.DESCRIPTION LIKE '%".$searchText."%' 
								OR BaseTbl.SALDOKAS LIKE '%".$searchText."%' 
								OR BaseTbl.USERTYPE LIKE '%".$searchText."%' 
								OR BaseTbl.GAMBAR LIKE '%".$searchText."%' 
								OR c.NAMA LIKE '%".$searchText."%') ";
		}
		
		if($segment != '')
		{
			$offset = $segment;
		}
		else
		{
			$offset = '0';
		}
		if (!empty($searchOrder)) {
			$qry .= " ORDER BY ".$searchOrder." ASC";
		}
		$qry .= " LIMIT ".$offset.", ".$page;
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
      	return $result;
    }
	
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addUser($userInfo, $roleId, $userId)
    {
        $this->db->trans_start();
        $this->db->insert('SYSUSER', $userInfo);
		
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		$qry = "SELECT LADD, LAPD, URUT FROM SYSMENU WHERE LEVEL >= ".$roleId." AND URUT > 0";
		$query = $this->db->query($qry);   
        $result = $query->result(); 
		
		foreach($result as $row)
		{
			$ladd = $row->LADD;
			$lapd = $row->LAPD;
			$urut = $row->URUT;
			$qry = "INSERT INTO SYSMENUUSER(USID, LADD, LAPD, URUT) VALUES ('".$userId."', '".$ladd."', '".$lapd."', '".$urut."')";
			$query = $this->db->query($qry); 
		}
		 
        return $query;
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		} 

        //return $insert_id;
    }
	
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
		$this->db->select('USID, USERNAME, EMAIL, PASSWORD, USERLEVEL, GCB, GGD, DESCRIPTION, USERTYPE, IMAGE, AKSESCABANG, AKSESGUDANG');
        $this->db->from('SYSUSER');
        $this->db->where('USID', $userId);
        $query = $this->db->get();
        return $query->result();
    }

    function getGdbyUser($userId)
    {
		$qry = "SELECT AKSESGUDANG 
				FROM SYSUSER WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
		
		$row = $query->row();   
		if($row != null){
			$kdGd = $row->AKSESGUDANG;
		} else {
			$kdGd = '';
		}
		
		return $kdGd;
    }
	
    function getGdByCb($cabang)
    {
    	$qry = "SELECT KODE, NAMA, CB FROM GUDANG WHERE CB IN ($cabang)";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

	function getUsidInfo($usid = '')
	{
		$qry = "SELECT USID, USERNAME, EMAIL, PASSWORD, USERLEVEL, OTOR, `GROUP`, GCB, GGD,
		 		EXP, JAMAWAL, JAMAKHIR, STRUK, `DESCRIPTION`, SALDOKAS, DC, USERTYPE, GAMBAR FROM SYSUSER ";
		if(!empty($usid)) {
			$qry .= "WHERE USID = '".$usid."' ";
		}
		$qry .= "ORDER BY USERNAME";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('USID', $userId);
        $this->db->update('SYSUSER', $userInfo);
        
        return TRUE;
    }
    
	/**
     * This function is used to delete user to system
     * @return number $insert_id : This is last inserted id
     */

	function getUSID($userId){
		$this->db->select('USID');
        $this->db->from('SYSUSER');
        $this->db->where('USID', $userId);
        $query = $this->db->get();
        $result = $query->result();
		
		return $result;
	}

	function editUSID($userId, $userIdN, $userIdN2){
		$deo = $this->loginId;

		if($userIdN2 == '') {
			$qry = "INSERT INTO SYSUSER (USID, PASSWORD, USERLEVEL, `GROUP`, USERNAME, EMAIL, DESCRIPTION, GAMBAR, NAMAGAMBAR, FLAG, ID1, ID2, ID3, DOE, TOE, LOE, DEO, SIGN, USERTYPE, SALESM, EXP, JAMAWAL, JAMAKHIR, GCB, GGD, OTOR, STRUK, SALDOKAS, DC, BAHASA, LOGINFLAG, AKTIF, AKSESCABANG, AKSESGUDANG) (SELECT '$userIdN', PASSWORD, USERLEVEL, `GROUP`, USERNAME, EMAIL, DESCRIPTION, GAMBAR, NAMAGAMBAR, FLAG, ID1, ID2, ID3, NOW(), TOE, LOE, '$deo', SIGN, USERTYPE, SALESM, EXP, JAMAWAL, JAMAKHIR, GCB, GGD, OTOR, STRUK, SALDOKAS, DC, BAHASA, LOGINFLAG, AKTIF, AKSESCABANG, AKSESGUDANG 
				FROM SYSUSER
				WHERE USID = '$userId')";
		} else {
			$qry = "INSERT INTO SYSUSER (USID, PASSWORD, USERLEVEL, `GROUP`, USERNAME, EMAIL, DESCRIPTION, GAMBAR, NAMAGAMBAR, FLAG, ID1, ID2, ID3, DOE, TOE, LOE, DEO, SIGN, USERTYPE, SALESM, EXP, JAMAWAL, JAMAKHIR, GCB, GGD, OTOR, STRUK, SALDOKAS, DC, BAHASA, LOGINFLAG, AKTIF, AKSESCABANG, AKSESGUDANG) (SELECT '$userIdN', PASSWORD, USERLEVEL, `GROUP`, '$userIdN2', EMAIL, DESCRIPTION, GAMBAR, NAMAGAMBAR, FLAG, ID1, ID2, ID3, NOW(), TOE, LOE, '$deo', SIGN, USERTYPE, SALESM, EXP, JAMAWAL, JAMAKHIR, GCB, GGD, OTOR, STRUK, SALDOKAS, DC, BAHASA, LOGINFLAG, AKTIF, AKSESCABANG, AKSESGUDANG  
				FROM SYSUSER
				WHERE USID = '$userId')";
		}
		$query = $this->db->query($qry);

		$qry = "SELECT USID FROM SYSUSER WHERE USID = '$userIdN'";
		$query2 = $this->db->query($qry);

		if($query2->num_rows() > 0){

			$aktif = array('AKTIF' => 'N');
			$this->db->where('USID', $userId);
        	$this->db->update('SYSUSER', $aktif);			

			$qry = "INSERT INTO SYSMENUUSER (USID, LADD, LAPD, URUT, SETDEFAULT, DOE, LOE, DEO)
					(SELECT '$userIdN', LADD, LAPD, URUT, SETDEFAULT, NOW(), LOE, '$deo' 
					FROM SYSMENUUSER WHERE USID = '$userId')";
			$query = $this->db->query($qry);

			return true;
		} else {
			return false;	
		}

	}

	function deleteUser($userId)
    {
		$jenis = 'M. User';
		$db2 = $this->session->userdata('dbBulan');
		$db1 = $this->session->userdata('dbDef');
		$qry = "INSERT INTO ".$db2.".HAPUS (NOMOR, NAMA, NOXX, ID1, DOE, JENIS) 
				SELECT USID, USERNAME, 'SYSUSER', '".$this->loginId."', '".date('Y-m-d H:i:s')."', '".$jenis."' 
				FROM ".$db1.".SYSUSER WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
		//$qry = "DELETE FROM ".$db1.".SYSUSER WHERE USID = '".$userId."'";
		$qry = "UPDATE ".$db1.".SYSUSER SET AKTIF = 'N' WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
		//$qry = "DELETE FROM ".$db1.".SYSMENUUSER WHERE USID = '".$userId."'";
		//$query = $this->db->query($qry); 
       	
		return $query;
    }
	
    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('USID, PASSWORD');
        $this->db->where('USID', $userId);        
        $query = $this->db->get('SYSUSER');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->PASSWORD)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('USID', $userId);
        $this->db->update('SYSUSER', $userInfo);
        
        return $this->db->affected_rows();
    }
	
	 /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
	function deleteLoginUser($hasil)
	{
		$DB = $this->load->database('default',TRUE);
		$DB->trans_start();
		$DB->delete('SYSLOGIN',$hasil);
		$DB->trans_complete();
	}
	
	function addLog($loginfo)
    {
		/*$DB2 = $this->load->database('db2',TRUE);
        $DB2->trans_start();
        $DB2->insert('SYSLOG', $loginfo);
		$insert_id = $DB2->affected_rows();		
        $DB2->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}*/

        $db2 = $this->session->userdata('dbBulan');
		$arrkeys = '';
		$arrvalues = '';
		foreach($loginfo as $keys=>$values){
			$arrkeys .= "`".$keys."`".",";
			$arrvalues .= "'".$values."',"; 
		}
		$arrkeys = substr($arrkeys,0,strlen($arrkeys)-1);
		$arrvalues = substr($arrvalues,0,strlen($arrvalues)-1);
		$qry = "INSERT INTO ".$db2.".SYSLOG (".$arrkeys.") VALUES (".$arrvalues.")";
		$query = $this->db->query($qry);
		
		$insert_id = $this->db->insert_id();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
    }	
	
	/**
     * This function is used to get the log listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function logListingCount($searchText = '')
    {
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT d2.LOGID, d2.LOGUSER, d1.USERNAME, d2.LOGIP, d2.LOGDATE, d2.LOGDESC
			   	FROM ".$db2.".SYSLOG d2 LEFT JOIN SYSUSER d1 ON d2.LOGUSER = d1.USID ";
		if(!empty($searchText))
		{
			if ($this->session->userdata('pageLogin') == 'admin' || $this->session->userdata('pageLogin') == 'dev') {
				$qry .= " WHERE d2.LOGUSER LIKE '%".$searchText."%' OR d1.USERNAME LIKE '%".$searchText."%' OR d2.LOGDESC LIKE '%".$searchText."%' ";
			} else {
				$qry .= " WHERE d2.LOGUSER IN (SELECT USID FROM SYSUSER) AND d1.USERNAME LIKE '%".$searchText."%' OR d2.LOGDESC LIKE '%".$searchText."%' ";
			}
		}
		
		$query = $this->db->query($qry);
		
        return count($query->result());
    }
	
	/**
     * This function is used to get the log listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
	function logListing($searchText = '', $page, $segment)
    {	
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT d2.LOGID, d2.LOGUSER, d1.USERNAME, d2.LOGIP, d2.LOGDATE, d2.LOGDESC 
			   		FROM ".$db2.".SYSLOG d2 LEFT JOIN SYSUSER d1 ON d2.LOGUSER = d1.USID ";
		if(!empty($searchText))
		{
			if ($this->session->userdata('pageLogin') == 'admin' || $this->session->userdata('pageLogin') == 'dev') {
				$qry .= " WHERE d2.LOGUSER LIKE '%".$searchText."%' OR d1.USERNAME LIKE '%".$searchText."%' OR d2.LOGDESC LIKE '%".$searchText."%' ";
			} else {
				$qry .= " WHERE d2.LOGUSER IN (SELECT USID FROM SYSUSER) AND d1.USERNAME LIKE '%".$searchText."%' OR d2.LOGDESC LIKE '%".$searchText."%' ";
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
		
		$qry .= " ORDER BY d2.LOGDATE DESC LIMIT ".$offset.", ".$page;
		
       	$query = $this->db->query($qry);   
        $result = $query->result(); 
		
      	return $result;
    }

    function getLogDesc($desc = '')
	{
		$db2 = $this->session->userdata('dbBulan');
		$qry = "SELECT * FROM ".$db2.".SYSLOG ";
		if(!empty($desc)) {
			$qry .= "WHERE LOGDESC LIKE '%".$desc."%'";
		}
		$qry .= "ORDER BY LOGDATE DESC LIMIT 1";
		$query = $this->db->query($qry);
		$result = $query->result();
		
		return $result;
	}
	
	/**
     * This function is used to check whether user id is already exist or not
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkUserExists($userId)
    {
        $this->db->select("USID");
        $this->db->from("SYSUSER");
        $this->db->where("USID", $userId);   
        $query = $this->db->get();
		
        return $query->result();
    }
	
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("EMAIL");
        $this->db->from("SYSUSER");
        $this->db->where("EMAIL", $email);   
        if($userId != 0){
            $this->db->where("USID !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }

    function getTotalUser()
	{		
		$query = $this->db->query("SELECT * FROM SYSUSER WHERE AKTIF = 'Y'");
		$hasil = $query->result();
		$result = $query->num_rows();
		
		return $result;
	}
	
	/**
     * This function is used to get user IP Address
     * @return array $result : This is result of the query
     */
	function getClientIP()
    {
		$ipadress = '';
		
		$baseURL = $_SERVER['HTTP_HOST'];
		if($baseURL == 'localhost' || $baseURL == '127.0.0.1') {
			$ipadress = getHostByName(getHostName());
		} else {	
			if(isset($_SERVER['HTTP_CLIENT_IP']))
				$ipadress = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ipadress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipadress = $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['REMOTE_ADDR']))
				$ipadress = $_SERVER['REMOTE_ADDR'];
			else
				$ipadress = 'UNKNOWN';
		}

		$ipadress = trim(substr($ipadress, 0, 20));

        return $ipadress;
    }
	
    /**
     * This function is used to get browser user
     * @return array $result : This is result of the query
     */
	function getBrowser() 
	{
	    $browser = '';
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) 
	        $browser = 'Netscape';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
	        $browser = 'Firefox';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
	        $browser = 'Chrome';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
	        $browser = 'Opera';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
	        $browser = 'Internet Explorer';
	    else
	        $browser = 'Other';
	    return $browser;
	}

	/**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('LVLID, LVLNAME');
        $this->db->from('SYSLEVEL');
        $this->db->where('LVLID !=', 0);
        $query = $this->db->get();
        
        return $query->result();
    }
	
	function getProfile($profile)
	{
		$qry = "SELECT USID, USERNAME, USERLEVEL, EMAIL, GAMBAR FROM
		 		SYSUSER WHERE USID = '".$profile."'";		 
		$query = $this->db->query($qry);
		$result = $query->result_array();
		 
		return $result;
	}
	
	function getUsidByEmail($email)
	{
		$qry = "SELECT USID FROM SYSUSER WHERE EMAIL = '".$email."' AND AKTIF = 'Y'";
		$query = $this->db->query($qry);
        $result = $query->result_array();
		 
		return $result;
	}

	function getSession($type)
    {
		
		if($type == 'userId') {
			$result =  $this->session->userdata('userId');
		}
		
        return $result;
    }

}