<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{
    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($usid, $password)
    {
		$this->db->select('a.USID, a.PASSWORD, a.USERNAME,  
		a.USERLEVEL, b.LVLNAME, a.EMAIL, a.BAHASA,
		a.USERTYPE,	a.IMAGE');
		$this->db->from('SYSUSER AS a');
		$this->db->join('SYSLEVEL AS b','b.LVLID = a.USERLEVEL');
		if(stristr($usid, '@') == TRUE) {
			$this->db->where('LOWER(a.EMAIL)', strtolower($usid)); 
		} else {
			$this->db->where('LOWER(a.USID)', strtolower($usid));
		}
		$query = $this->db->get();
		$user = $query->result();
		if(!empty($user)){
			if(stristr($usid, '@') == TRUE) {
				$password = $user[0]->EMAIL;
				// $password = $usid.$password;
			} else {
				$password = $password;
				// echo $password;
			}
			// echo $password . ' | '; 
			// print_r($user[0]->PASSWORD);
			// echo password_verify($password, $user[0]->PASSWORD); 
			// die();
			
	        if(password_verify($password, $user[0]->PASSWORD)){
				// print_r($user); die();
				return $user;
	        } else {
				return array();
	        }
	    } else {
	        return array();
	    }
    }
	
	function loginAdmin($email, $password, $maxLvl)
    {
		$this->db->select('a.USID, a.PASSWORD, a.USLEVEL, a.EMAIL');
        $this->db->from('SYSUSER AS a');
		if(stristr($email, '@') == TRUE) {
			$this->db->where('LOWER(a.EMAIL)', strtolower($email)); 
		} else {
			$this->db->where('LOWER(a.USID)', strtolower($email));
		}
		$this->db->where('a.USLEVEL <=', $maxLvl);
        $query = $this->db->get();     
        $user = $query->result();
		
		if(!empty($user)){
			if(stristr($email, '@') == TRUE) {
				$password = $email.$password;
			} else {
				$password = $user[0]->EMAIL.$password;
			}
       
            if(password_verify($password, $user[0]->PASSWORD)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    {
        $this->db->select('USID');
        $this->db->where('EMAIL', $email);
        $query = $this->db->get('SYSUSER');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $result = $this->db->insert('tbl_reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('userId, email, name');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', array('password'=>getHashedPassword($password)));
        $this->db->delete('tbl_reset_password', array('email'=>$email));
    }
	
	function loginUser($insert)
	{
		$this->db->trans_start();
        $this->db->insert('SYSLOGIN', $insert);
       
		//$insert_id = $this->db->insert_id();	//Untuk mendapatkan last id (catatan: autonumber)
		$insert_id = $this->db->affected_rows();		
		
        $this->db->trans_complete();
		
		if($insert_id > 0){
			return $insert_id;
		} else {
			return FALSE;
		}
	}
	
	function getTotalActive($usid, $superuser)
	{		
		$query = $this->db->query("SELECT DISTINCT USID FROM SYSLOGIN 
									WHERE USID <> '".$usid."'
									AND USID NOT IN ".$superuser."");
		$hasil = $query->result();
		$result = $query->num_rows();
		
		return $result;
	}
	
	function getTotalActiveNonSuper($usid)
	{		
		$query = $this->db->query("SELECT DISTINCT USID FROM SYSLOGIN 
									WHERE USID <> '".$usid."'");
		$hasil = $query->result();
		$result = $query->num_rows();
		
		return $result;
	}
	
	function getLoginInfo($userId)
	{
		$strQry = "SELECT * FROM SYSLOGIN WHERE USID LIKE '%".$userId."%' ORDER BY END_TIME DESC LIMIT 1";
		$query = $this->db->query($strQry);
        //$query = $this->db->get();
        
        return $query->result();
	}
	
	function checkLogin($userId)
	{
		$strQry = "SELECT USID FROM SYSLOGIN WHERE USID LIKE '%".$userId."%' ORDER BY END_TIME DESC LIMIT 1";
		$query = $this->db->query($strQry);
		$hasil = $query->result();
		$result = $query->num_rows();
		
		return $result;
	}
	
	function updateLogin($userId, $endTime, $waitingTime, $ip)
    {   
		$endTime      = date('Y-m-d H:i:s', $endTime);
		$waitingTime  = date('Y-m-d H:i:s', $waitingTime);
	
		$qry = "UPDATE SYSLOGIN 
				SET END_TIME = '$endTime', WAITING_TIME = '$waitingTime', IP = '$ip' 
				WHERE USID = '$userId'";
		$query = $this->db->query($qry); 
       	
		return $query;
    }

    function updateWaitingTime($userId, $no)
    {	
		$db1 = $this->session->userdata('dbDef');
		$qry = "UPDATE $db1.SYSLOGIN 
				SET WAITING_TIME = DATE_ADD(NOW(), INTERVAL '$no' MINUTE)
				WHERE USID = '$userId'";
		$query = $this->db->query($qry); 
       	
		return $query;
    }
	
	function updateLoginUser($userId, $endTime)
    {   
		$endTime = date('Y-m-d H:i:s', $endTime);
		
		$qry = "UPDATE SYSLOGIN 
				SET END_TIME = '".$endTime."'  
				WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
       	
		return $query;
    }
	
	function deleteLoginUser($userId)
    {   
		$qry = "DELETE FROM SYSLOGIN WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
	}
	
	function deleteLogin($userId, $ip)
    {   
		$qry = "DELETE FROM SYSLOGIN WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
       	
		$qry = "DELETE FROM SYSLOGIN WHERE IP = '".$ip."' AND USID <> '".$userId."'";
		$query = $this->db->query($qry);
		
		return $query;
    }
	
	function deleteLoginByUserTime($userId, $endTime)
    {   
		$qry = "DELETE FROM SYSLOGIN WHERE USID = '".$userId."' 
				AND END_TIME < '".$endTime."'";
		$query = $this->db->query($qry); 
		
		return $query;
    }
	
	function deleteLoginByDate($endDate)
    {   
		$qry = "DELETE FROM SYSLOGIN WHERE DATE(END_TIME) < '".$endDate."'";
		$query = $this->db->query($qry); 
		
		return $query;
    }
	
	function checkDb($dbname)
	{
		$query = $this->db->query("SHOW DATABASES LIKE '".$dbname."'");
		$hasil = $query->result();
		$result = count($hasil);
		// var_dump($hasil); echo "dbname <br>";
		
		return $result;
	}

	function checkDbsampai($sampaidbname)
	{
		$query = $this->db->query("SHOW DATABASES LIKE '".$sampaidbname."'");
		$hasil = $query->result();
		$result = count($hasil);
		// var_dump($hasil); die();
		
		return $result;
	}

	function checkDbPeriode($dbname, $sampaidbname)
	{
		$query = $this->db->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name BETWEEN '".$dbname."' AND '".$sampaidbname."' ORDER BY schema_name ASC");
		$result = $query->result();
		// var_dump($result);
		
		return $result;
		
	}

	function checkTB($dbname,$tbname)
	{
		$query = $this->db->query("SHOW TABLES FROM $dbname LIKE '$tbname'");
		$hasil = $query->result();
		$result = $query->num_rows();
	
		return $result;
	}
	
	function unlockUser($id)
	{
		$qry = "DELETE FROM SYSLOGIN WHERE USID = '".$id."'";
		$this->db->query($qry);
		
		return TRUE;
	}

	function getLoginFlag($email)
	{
		$qry = "SELECT LOGINFLAG FROM SYSUSER ";
		if(stristr($email, '@') == TRUE) {
			$qry .= "WHERE LOWER(EMAIL) = '".$email."'";
		} else {
			$qry .= "WHERE LOWER(USID) = '".$email."'";
		}
		$query = $this->db->query($qry);
		$result = $query->result_array();
		 
		return $result;
	}

	function updateLoginSysUser($userId, $value)
    { 
		$qry = "UPDATE SYSUSER 
				SET LOGINFLAG = '".$value."'  
				WHERE USID = '".$userId."'";
		$query = $this->db->query($qry); 
       	
		return $query;
    }

    function getCabangDef($kode='01')
	{
		$qry = "SELECT NAMA FROM CABANG
				WHERE KODE = '$kode'";
		$query = $this->db->query($qry);
		$result = $query->result_array();
		// echo $result[0]['NAMA'];
		// print_r($result);
		return $result[0]['NAMA'];
	}

	function checkSysuser($email){
		
		$qry = "SELECT USID FROM SYSUSER
				WHERE USID = '$email' OR EMAIL = '$email'";
		$query = $this->db->query($qry);
        //echo $this->db->last_query();

        if ($query->num_rows() > 0){
             return true;
        } else {
             return false;
       	}
	}
	
}
?>
