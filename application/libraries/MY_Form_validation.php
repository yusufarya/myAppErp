<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	function __construct($config = array()){
		parent::__construct($config);
	}
	
	public function unique_code($str, $field)
	{
		$fld = explode(".",$field);
		$tbl = $fld[0];
		$col = $fld[1];
		
		$check = $this->CI->db->get_where($tbl, array($col => $str), 1);
		
		if($check->num_rows() > 0) {
			$this->set_message('unique_code','Bagian Kode harus unik.');
			
			return false;
		}
		
		return true;
	}
		
}

  