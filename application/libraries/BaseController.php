<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class BaseController extends CI_Controller {
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $roleText = '';
	protected $langId = '';
	
	protected $global = array ();
	
	public function __construct()
	{
		parent::__construct();
		if (isset($_SESSION['dbDef'])) {
			$this->db->database = $_SESSION['dbDef'];
		}
		// echo $this->db->database;
		// print_r($_SESSION);
	}
	/**
	 * Takes mixed data and optionally a status code, then creates the response
	 *
	 * @access public
	 * @param array|NULL $data
	 *        	Data to output to the user
	 *        	running the script; otherwise, exit
	 */
	public function response($data = NULL) {
		$this->output->set_status_header ( 200 )->set_content_type ( 'application/json', 'utf-8' )->set_output ( json_encode ( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) )->_display ();
		exit ();
	}
	
	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn() {
		$isLoggedIn = $this->session->userdata ( 'isLoggedIn' );
		
		if (! isset ( $isLoggedIn ) || $isLoggedIn != TRUE) {
			redirect ( 'login' );
		} else {
			$this->role = $this->session->userdata ( 'role' );
			$this->loginId = $this->session->userdata ( 'userId' );
			$this->name = $this->session->userdata ( 'name' );
			$this->roleText = $this->session->userdata ( 'roleText' );
			$this->langId = $this->session->userdata ( 'langId' );
			
			$this->global ['name'] = $this->name;
			$this->global ['role'] = $this->role;
			$this->global ['role_text'] = $this->roleText;
			$this->global ['langId'] = $this->langId;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isAdmin() {
		if ($this->role == '') {
			return true;
		} else {
			return false;
		}
		return false;
	}
	
	/**
	 * This function is used to check form transaction status (open/close)
	 */
	function isOpen() {
		if ($this->isOpen != FALSE) {
			return true;
		} else {
			return false;
		}
		return true;
	}
	
	/**
	 * This function is used to check the access
	 */
	function isTicketter() {
		//if ($this->role != ROLE_ADMIN || $this->role != ROLE_MANAGER) {
		//	return true;
		//} else {
		//	return false;
		//}
		return true;
	}
	
	/**
	 * This function is used to load the set of views
	 */
	function loadThis() {
		$this->global ['page_title'] = 'SISERP : Access Denied';
		
		$this->load->view ( 'inc/header', $this->global );
		$this->load->view ( 'access' );
		$this->load->view ( 'inc/footer' );
	}
	
	/**
	 * This function is used to logged out user from system
	 */
	/*function logout() {
		$this->session->sess_destroy ();
		
		redirect ( 'login' );
	}*/

	/**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */
	
	function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL, $clickMenu = TRUE){
		// echo 'YGY';
		// print_r($pageInfo);
		// die();
		$header = BASE_DIR."application/views/templates/header.php";
		if ($clickMenu == FALSE) {
			$nav = BASE_DIR."application/views/templates/nav_disabled.php";
		} else {
			$nav = BASE_DIR."application/views/templates/sidebar.php";
		}
		$topbar = BASE_DIR."application/views/templates/topbar.php";
		$footer = BASE_DIR."application/views/templates/footer.php";
		$scripts = BASE_DIR."application/views/templates/scripts.php";

		$content = BASE_DIR."application/views/".$viewName.".php";
		$content_view_script = BASE_DIR."application/views/".$viewName."_script.php";
		if(file_exists($content_view_script)){
			$content_script = $content_view_script;
		} else {
			$content_script = BASE_DIR."application/views/content_script.php";
		}
		
		/*---------------- PHP Custom Scripts ---------
		
		YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC. */
		
		$data = json_decode(json_encode($headerInfo), True);
		
		$page_title = $data['page_title'];
		$active = $pageInfo['active'];
		
		/* ---------------- END PHP Custom Scripts ------------- */
		
		include($header);

		if($page_title != 'Lock Screen') {
			if($page_title != '' and $page_title != 'Error 404' and $page_title != 'Error 500') {
				$count = substr_count($page_title,"|");
				$titles = explode("|",trim($page_title));
				if($count < 1) {
					$page_nav[trim($titles[0])]["active"] = true;
				} else if($count < 2) {
					$page_nav[trim($titles[0])]["sub"][trim($titles[1])]["active"] = true;
					$breadcrumbs[trim($titles[0])] = "";
				} else if($count < 3) {
					$page_nav[trim($titles[0])]["sub"][trim($titles[1])]["sub"][trim($titles[2])]["active"] = true;
					$breadcrumbs[trim($titles[1])] = "";
				} else if($count < 4) {
					$page_nav[trim($titles[0])]["sub"][trim($titles[1])]["sub"][trim($titles[2])]["sub"][trim($titles[3])]["active"] = true;
					$breadcrumbs[trim($titles[2])] = "";
				} 
			}
			include($nav);
		}
		?>
       
		<!-- ==========================CONTENT STARTS HERE ========================== -->
		
		<!-- Right Panel -->
		<div id="right-panel" class="right-panel">

			<?php 
			include($topbar);
			?>
			
			<!-- MAIN CONTENT -->
			
            <div class="content">
				
			<?php 
				include($content);  
			?>
            <?php
				if($page_title != 'Lock Screen') {
			?>
			</div>
			<!-- END MAIN CONTENT -->
			<?php
				};
			?>
            
			<!-- /#right-panel -->
			
			
			<!-- FOOTER -->
			<?php
		include($footer);
		?>
		<!-- END FOOTER -->
	</div>
	<?php 
	include($scripts); 
	//include content scripts
	include($content_script);
	?>
	
	<!-- ==========================CONTENT ENDS HERE ========================== -->
	
	</body>
	</html>
		<?php 
			//include required scripts
			// include($scripts); 
			// //include content scripts
			// include($content_script);
			//include footer
			// include($analytics); 
	}
	
	
	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link
	 * @param {number} $count : This is page count
	 * @param {number} $perPage : This is records per page limit
	 * @return {mixed} $result : This is array of records and pagination data
	 */
	function paginationCompress($link, $count, $perPage) {
		$this->load->library ( 'pagination' );
	
		$config ['base_url'] = base_url () . $link;
		$config ['total_rows'] = $count;
		$config ['uri_segment'] = SEGMENT;
		$config ['per_page'] = $perPage;
		$config ['num_links'] = NUM_LINKS;
		$config ['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-end">';
		$config ['full_tag_close'] = '</ul></nav>';
		// $config ['first_tag_open'] = '<li class="page-item disabled">';
		// $config ['first_link'] = '<a class="page-link">First</a>';
		// $config ['first_tag_close'] = '</li>';
		$config ['prev_link'] = '<a class="page-link">Previous</a>';
		$config ['prev_tag_open'] = '<li class="page-item">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_link'] = '<a class="page-link" href="#">Next</a>';
		$config ['next_tag_open'] = '<li class="page-item">';
		$config ['next_tag_close'] = '</li>';
		$config ['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">1</a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="page-item"><a class="page-link" href="#">2</a>';
		$config ['num_tag_close'] = '</a></li>';
		// $config ['last_tag_open'] = '<li class="page-item">';
		// $config ['last_link'] = '<a class="page-link" href="#">Last</a>';
		// $config ['last_tag_close'] = '</li>';
	
		$this->pagination->initialize ( $config );
		$page = $config ['per_page'];
		$segment = $this->uri->segment ( SEGMENT );
		
		return array (
				"page" => $page,
				"segment" => $segment
		);
	}
	
	
}