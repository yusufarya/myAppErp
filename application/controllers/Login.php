<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
		$this->load->model('User_model');
		// $this->load->library('Mathcaptcha');
		// $this->mathcaptcha->init();
    }

	public function index()
	{
		$this->isLoggedIn();
	}

	function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
		
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
			$this->load->view('login');
        }
        else
        {
            redirect('/dashboard');
        }
    }

	public function loginMe()
	{
		$this->load->library('form_validation');
		
        $this->form_validation->set_rules('email', 'Email', 'required|max_length[100]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
		
		if($this->form_validation->run() == FALSE)
        {
            $this->index();
        } else {
			$usid = $this->input->post('email');
        	$password = $this->input->post('password');
			
			$tahun = $this->input->post('year');
			$bulan = $this->input->post('month');

			$cabangDef = $this->Login_model->getCabangDef();
		
			if($bulan == 1) {
				$tahun1 = $tahun - 1;
				$bulan1 = 12; 
			} else {
				$tahun1 = $tahun;
				$bulan1 = $bulan - 1;
			}
			$tahuns = substr($tahun, -2);
			$tahuns1 = substr($tahun1, -2);
			
			$sessionTemp = array('usidT'=>'', 'passT'=>'');
			$this->session->set_userdata($sessionTemp);
				
			$supId = '';
			$supName = '';
			
			$supers = explode(',',SUPERUSER);
			$cntSuper = count($supers);
			$superId = '';
			$superName = '';
			$superPw = '';
			$superKey = '';
			$superPwEncrypt = '';
			$grupSuperKey = '';
			$grupSuperPw = '';
			$arrSuperId = [];
			$arrSuperName = [];
			$arrSuperKey = [];

			for($a=0;$a<$cntSuper;$a++)
			{
				$super = explode('|',$supers[$a]);
				$superId .= "'".$super[0]."',";
				$superName .= "'".$super[1]."',";
				// $superKey .= "'".$super[2]."',";
				if(strtolower($usid) == strtolower($super[0])) {
					$supId = $super[0];
					$supName = $super[1];
					$supKey = $super[2];
				}
				array_push($arrSuperId,$super[0]);
				array_push($arrSuperName,$super[1]);
				// array_push($arrSuperKey,$super[2]);
			}
			$grupSuperId = substr($superId,0,strlen($superId)-1);
			$grupSuperName = substr($superName,0,strlen($superName)-1);
			$grupSuperKey = substr($superKey,0,strlen($superKey)-1);
			$superUserId = '('.$grupSuperId.')';
			$superUserPw = '('.$grupSuperPw.')';

			ob_start();
			system('ipconfig /all');
			$mycom = ob_get_contents();
			ob_clean();
			$findme = 'Physical';
			$pmac = strpos($mycom, $findme);
			$mac = substr($mycom,($pmac+36),17);
			
			//$dbDef = INIT_DB_NAME.'_'.'acc';
			//$dbPer = INIT_DB_NAME.'_'.'acc'.$tahuns.$bulan;
			//$dbPerB = INIT_DB_NAME.'_'.'acc'.$tahuns1.$bulan1;
			
			$dbDef = INIT_DB_NAME;
			$dbPer = INIT_DB_NAME.$tahuns.$bulan;
			$dbPerB = INIT_DB_NAME.$tahuns1.$bulan1;
			
			
			$this->load->model('User_model');
			$ip = $this->User_model->getClientIP();
			
			if($bulan == '01')
			{
				$bulans = 'Januari';
			}
			else if($bulan == '02')
			{
				$bulans = 'Februari';
			}
			else if($bulan == '03')
			{
				$bulans = 'Maret';
			}
			else if($bulan == '04')
			{
				$bulans = 'April';
			}
			else if($bulan == '05')
			{
				$bulans = 'Mei';
			}
			else if($bulan == '06')
			{
				$bulans = 'Juni';
			}
			else if($bulan == '07')
			{
				$bulans = 'Juli';
			}
			else if($bulan == '08')
			{
				$bulans = 'Agustus';
			}
			else if($bulan == '09')
			{
				$bulans = 'September';
			}
			else if($bulan == '10')
			{
				$bulans = 'Oktober';
			}
			else if($bulan == '11')
			{
				$bulans = 'November';
			}
			else if($bulan == '12')
			{
				$bulans = 'Desember';
			}
			else
			{
				$bulans = '';
			}
			
			$mac_client = '';
			$this->load->model('Utility_model');
			$totBranch = $this->Utility_model->getTotalBranch();

			$now = date('Y-m-d H:i:s'); 
			$curDate = date('Y-m-d'); 
			$endDate = date('Y-m-d', strtotime($curDate. ' - '.EXPIRED_LOGIN.' days')); 
			$this->Login_model->deleteLoginByDate($endDate); 
			$cur_time = $_SERVER['REQUEST_TIME'];

			$totUser = $this->Login_model->getTotalActive($usid, $superUserId);
			
			if($supId == '') {	//Bukan Super User
				$limitUser = TOT_ACTIVE_USER;
				$loginCheck = $this->Login_model->checkLogin($usid);
				if($loginCheck > 0) {
					$resLogin = $this->Login_model->getLoginInfo($usid);
					foreach ($resLogin as $resLog)
					{
						$waiting_time = $resLog->WAITING_TIME;
					} 
				} else {
					$waiting_time = $now;
				}
			} else {
				$limitUser = $totUser + 100;
				$loginCheck = 0;
				$waiting_time = $now;
			}
			
			$diff 	    = strtotime($waiting_time) - $cur_time;
			$diff_menit = round($diff / 60);
			$diff_jam 	= round($diff / 3600);
			$diff_hari  = round($diff / 86400);
			$selisih    = '';

			if($diff_hari > 0) {
				$selisih = $diff_hari.' hari ';
			}
			if($diff_jam > 0) {
				$selisih .= $diff_jam.' jam ';
			}
			if($diff_menit > 0) {
				$selisih .= $diff_menit.' menit ';
			}

			if($loginCheck > 0 and ($cur_time <= strtotime($waiting_time))) {
				
				$this->session->set_userdata('message', 'User '.$usid.' masih aktif, silakan tunggu '.trim($selisih).' lagi !');
				redirect('loginMe');
				// echo json_encode(array('status'=>'failed','error'=>'User '.$usid.' masih aktif, silakan tunggu '.trim($selisih).' lagi !'));
				// return;
			
			} else if($totBranch > TOT_BRANCH) {
				
				echo json_encode(array('status'=>'failed','error'=>'Total Cabang sudah melewati batas.<br>  Hubungi SISCOM !'));
				return;
			
			} else if($totUser >= $limitUser and $supId != '') {
				
				echo json_encode(array('status'=>'failed','error'=>'Total User Aktif sudah melewati batas.<br>  Hubungi SISCOM !'));
				return;
			
			} else {
				$this->Login_model->deleteLoginUser($usid);
				
				$dbcheck = $this->Login_model->checkDb($dbPer);

				if($dbcheck==0)
				{
					
					$sessionTemp = array('usidT'=>$usid, 'passT'=>$password);
					$this->session->set_userdata($sessionTemp);

					$this->session->set_flashdata('message', '<div class="alert alert-danger py-1" role="alert">Periode database tidak ada.</div>');
					redirect('loginMe');
					// echo json_encode(array('status'=>'failed','error'=>'Database periode '.$bulans.' '.$tahun.' tidak ada'));
					// return;
				} else {
					//Captcha
					$ceklogin = 'Y';
					if($ceklogin != 'Y') {
						
						// //$this->session->set_flashdata('error', 'Captcha salah!');
						
						// $sessionTemp = array('usidT'=>$usid, 'passT'=>$password);
						// $this->session->set_userdata($sessionTemp);
						
						// //redirect('/login');

						// echo json_encode(array('status'=>'failed','error'=>'Captcha salah!'));
						// return;
					
					} else { 
						//JIKA SUPADMIN
						$supEmail = strtoupper($usid);
						$flag = '';
						$supflag = FALSE;
						if(in_array($supEmail, $arrSuperId)){
							$key = array_search($supEmail,$arrSuperId);
							// print_r($key);
							
							//sistem pw baru
							// require_once APPPATH.'libraries/GoogleAuthenticatorPHP/GoogleAuthenticator.php';
							
							$ga = new PHPGangsta_GoogleAuthenticator();
							$secret = $arrSuperKey[$key]; 
							
							$checkResult = $ga->verifyCode($secret, $password, 10);
							
							if ($checkResult) {
								$supflag = TRUE;
								$result = array(array('USID' => $supEmail, 'PASSWORD' => '', 
								'USERNAME' => $arrSuperName[$key], 'USLEVEL' => '0', 'LVLNAME' => 'LEVEL 0', 
								'EMAIL' => '', 'BAHASA' => 'id', 'USERTYPE' => ''));
							} else {
								$supflag = FALSE; 
								echo json_encode(array('status'=>'failed','error'=>'User ID atau Password salah'));
								return;
							}
						}else{
							$result = $this->Login_model->loginMe($usid, $password);
							// echo ' = PAS = ';
							// print_r($result); die();
						}
					}

					if(isset($result) && count($result) > 0)
					{						
						$awal = $_SERVER['REQUEST_TIME'];
						$durasi = SESSION_TIME;
						$akhir = $awal + $durasi;
						$tunggu = $awal + WAITING_TIME;
						$uniqueId = md5(uniqid(rand(), TRUE));
						$this->load->model('User_model');
						$browser = $this->User_model->getBrowser();
						
						foreach ($result as $res)
						{
							// echo 'a d a';
							$data = [
								'USID' => $res->USID,
								'START_TIME' => date('Y-m-d H:i:s', $awal),
								'END_TIME' => date('Y-m-d H:i:s', $akhir),
								'WAITING_TIME' => date('Y-m-d H:i:s', $tunggu),
								'FLAG' => $uniqueId,
								'IP' => $ip,
								'BROWSER' => $browser
							];
								
							$this->Login_model->loginUser($data);
							$this->Login_model->updateLoginSysUser($res->USID, '1');
									
							switch($bulan) {
								case '01': 
									$namaBulan = 'Jan';
									break;
								case '02': 
									$namaBulan = 'Feb';
									break;
								case '03': 
									$namaBulan = 'Mar';
									break;
								case '04': 
									$namaBulan = 'Apr';
									break;
								case '05': 
									$namaBulan = 'Mei';
									break;
								case '06': 
									$namaBulan = 'Jun';
									break;
								case '07': 
									$namaBulan = 'Jul';
									break;
								case '08': 
									$namaBulan = 'Agt';
									break;
								case '09': 
									$namaBulan = 'Sep';
									break;
								case '10': 
									$namaBulan = 'Okt';
									break;
								case '11': 
									$namaBulan = 'Nov';
									break;	
								case '12': 
									$namaBulan = 'Des';
									break;	
							}

							$mingguKe = 1;
							if($tahun == date('Y') && $bulan == date('m')) {
								$mingguKe = dateToWeek(date('Y-m-d'));
							}
							// print_r($res); die();
							
							$sessionArr = array('userId'=>$res->USID,                    
									'role'=>$res->USERLEVEL,
									'roleText'=>$res->LVLNAME,
									// 'group'=>$res->GROUP,
									'userType'=>$res->USERTYPE,
									'name'=>$res->USERNAME,
									'userEmail'=>$res->EMAIL,
									'langId'=>$res->BAHASA,
									'dbDef'=>$dbDef,
									'cabangDef'=>$cabangDef,
									'dbBulan'=>$dbPer,
									'dbBulanB'=>$dbPerB,
									'curYear'=>$tahun,
									'curMonth'=>$bulan,
									'curMonthName'=>$namaBulan,
									// 'curBranch'=>$res['IDPT'],
									// 'curWh'=>$res['IDGD'],
									'curModule'=>1,
									'lastLogin'=>$awal,
									'expiredLogin'=>$akhir,
									'mySessionId'=>$uniqueId,
									'versi' => APPVER,
									'macServer'=>$mac,
									'macClient'=>$mac_client,
									'isOpen'=> FALSE,
									'isLoggedIn' => TRUE,
									'supflag' => $supflag,
									'supArray' => $superUserId,
									'dbname' => INIT_DB_NAME,
									'companyname' => COMPANY_NAME,
									'begindate' => '',
									'enddate' => '',
									'used' => '',
									'pageLogin' => 'login',
									'userImage'=> $res->IMAGE,
									'ip'=> $ip,
									'browser'=> $browser,
									'mingguKe'=>$mingguKe
							);

							$this->session->set_userdata($sessionArr);
							
							$db1 = $this->session->userdata('dbDef');
							$db2 = $this->session->userdata('dbBulan'); 
							//UPDATE DATABASE
							// require_once("Updatedb.php"); 
							// echo json_encode(array('status'=>'success'));
							// return; 
							redirect('dashboard');
						}
					} else { 
						echo json_encode(array('status'=>'failed','error'=>'User ID atau Password salah')); 
						$this->session->set_flashdata('message', '<div class="alert alert-danger py-1" role="alert">User ID atau Password salah. </div>');
						redirect('loginMe');
					}
				}
			}

			// $sysuser = $this->db->get_where('sysuser', ['USID' => $usid])->row_array();

			// if ($sysuser) {
			// 	if ($sysuser['ACTIVE'] == 'Y') {
			// 		if (password_verify($password, $sysuser['PASSWORD'])) {
			// 			$data = [
			// 				'USID' => $sysuser['USID'],
			// 				'USERLEVEL' => $sysuser['USERLEVEL']
			// 			];
			// 			$this->session->set_userdata($data);
			// 			redirect('dashboard');
			// 		} else {
			// 			$this->session->set_flashdata('message', '<div class="alert alert-danger py-1" role="alert">Wrong password!</div>');
			// 			redirect('loginMe');
			// 		}
			// 	} else {
			// 		$this->session->set_flashdata('message', '<div class="alert alert-danger py-1" role="alert">Sorry! your account is not activated!</div>');
			// 		redirect('loginMe');
			// 	}
			// } else {
			// 	$this->session->set_flashdata('message', '<div class="alert alert-danger py-1" role="alert">Sorry! your email is not registered!</div>');
			// 	redirect('loginMe');
			// }
		}
	}

	function logout()
	{	
		//DELETE FILES @ application\libraries\reportico\projects\siserp FOLDER
		//$this->load->model('Utility_model');
		//$directory =  DOC_FILE_ERP."/application/libraries/Reportico/projects/siserp/var/";
		//$this->Utility_model->delete_files($directory, $this->loginId, 'xml');
		
		$data = $this->loginId = $this->session->userdata('userId');
		
		$hasil = array('USID'=>$data);
				
		$this->User_model->deleteLoginUser($hasil);

		$this->load->model('Login_model');
		$this->Login_model->updateLoginSysUser($this->session->userdata('userId'), '0');
		
		if ($this->session->userdata('pageLogin') == 'login') {
			$this->session->sess_destroy();
			redirect('login');
		} else if ($this->session->userdata('pageLogin') == 'admin') {
			$this->session->sess_destroy();
			ob_start();
			//$host = $_SERVER['HTTP_HOST'];
			$url = base_url().'cloudadmin';
			header("Location: $url");
			exit;
		} else if ($this->session->userdata('pageLogin') == 'account') {
			$this->session->sess_destroy();
			ob_start();
			//$host = $_SERVER['HTTP_HOST'];
			$url = base_url().'module/reg/pages/account.php';
			header("Location: $url");
			exit;
		} else if ($this->session->userdata('pageLogin') == 'dev') {
			$this->session->sess_destroy();
			ob_start();
			//$host = $_SERVER['HTTP_HOST'];
			$url = base_url().'cloudtest';
			header("Location: $url");
			exit;
		}
	}
}
