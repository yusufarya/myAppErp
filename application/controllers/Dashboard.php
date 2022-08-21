<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . '/libraries/BaseController.php';
require_once(APPPATH.'/libraries/BaseController.php');
// echo __ROOT__;

class Dashboard extends BaseController {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('Dashboard_model'); 
		$this->load->library('Commonfunction', 'BaseController');
		// $this->isLoggedIn();
    }
    
    
    public function index()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
		
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->session->set_flashdata('message', '<div class="alert alert-danger py-1" role="alert">Maaf, anda belum login</div>');
			$this->load->view('login');
        }
        else
        {
            $data['logsInfo'] = $this->Dashboard_model->getLogsInfo();
            $data['active'] = 'DASHBOARD';
    
            $this->global['page_title'] = 'Dashboard';
                    
            $this->loadViews("dashboard", $this->global, $data, NULL, TRUE);
        }
    }
}