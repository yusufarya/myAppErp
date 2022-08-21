<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This function is used to print the content of any data
 */
function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * This function used to get the CI instance
 */
if(!function_exists('get_instance'))
{
    function get_instance()
    {
        $CI = &get_instance();
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if(!function_exists('getHashedPassword'))
{
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if(!function_exists('verifyHashedPassword'))
{
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}

/**
 * This method used to get current browser agent
 */
if(!function_exists('getBrowserAgent'))
{
    function getBrowserAgent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');

        $agent = '';

        if ($CI->agent->is_browser())
        {
            $agent = $CI->agent->browser().' '.$CI->agent->version();
        }
        else if ($CI->agent->is_robot())
        {
            $agent = $CI->agent->robot();
        }
        else if ($CI->agent->is_mobile())
        {
            $agent = $CI->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }

        return $agent;
    }
}

if(!function_exists('setProtocol'))
{
    function setProtocol()
    {
        $CI = &get_instance();
                    
        $CI->load->library('email');
        
        $config['protocol'] = PROTOCOL;
        $config['mailpath'] = MAIL_PATH;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['smtp_user'] = SMTP_USER;
        $config['smtp_pass'] = SMTP_PASS;
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        
        $CI->email->initialize($config);
        
        return $CI;
    }
}

if(!function_exists('emailConfig'))
{
    function emailConfig()
    {
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['mailpath'] = MAIL_PATH;
        $config['charset'] = 'UTF-8';
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
    }
}

if(!function_exists('resetPasswordEmail'))
{
    function resetPasswordEmail($detail)
    {
        $data["data"] = $detail;
        // pre($detail);
        // die;
        
        $CI = setProtocol();        
        
        $CI->email->from(EMAIL_FROM, FROM_NAME);
        $CI->email->subject("Reset Password");
        $CI->email->message($CI->load->view('email/resetPassword', $data, TRUE));
        $CI->email->to($detail["email"]);
        $status = $CI->email->send();
        
        return $status;
    }
}

if(!function_exists('setFlashData'))
{
    function setFlashData($status, $flashMsg)
    {
        $CI = get_instance();
        $CI->session->set_flashdata($status, $flashMsg);
    }
}

function ubahstatus_helper($table, $id='NOMOR')
{
    $CI =& get_instance();
    $CI->load->model('Utility_model');
	$CI->load->model('Akunting_model');
    $CI->load->model('User_model');

    $post = $CI->input->post(NULL, TRUE);

    $desc = '';

    if (isset($post['nomor'])) {

        $nomor = $post['nomor'];
        $where = array($id=>$nomor);
        
        //print_r($post);
        if (isset($post['acc']) && $post['acc'] != NULL) {
            $acc = $post['acc']!='NULL'? $post['acc'] : NULL;

            $data = array("ACC"=>$acc);
            $CI->Utility_model->ubahStatus_model($table, $data, $where);
            
			if($acc != 'V') {
				$CI->Akunting_model->delGldataByNomor($nomor);
				$CI->Akunting_model->delThdByNomor($nomor);
				$CI->Akunting_model->delTrxByNomor($nomor);
				$CI->Akunting_model->calcCcdata();
				$CI->Akunting_model->calcTbdata();

                $desc = 'Buka Validasi Accounting Nomor : '.$nomor;
			} else if($acc == 'V') {
				switch(substr($nomor,0,2))
				{
					case "RO": $CI->Akunting_model->addJurnalRO($nomor); break;
					case "PI": $CI->Akunting_model->addJurnalPI($nomor); break;
					case "PR": $CI->Akunting_model->addJurnalPR($nomor); break;
					case "DO": $CI->Akunting_model->addJurnalDO($nomor); break;
					case "SI": $CI->Akunting_model->addJurnalSI($nomor); break;
					case "SR": $CI->Akunting_model->addJurnalSR($nomor); break;
					case "XP": $CI->Akunting_model->addJurnalXP($nomor); break;
					case "P3": $CI->Akunting_model->addJurnalP3($nomor); break;
					case "P1": $CI->Akunting_model->addJurnalP1($nomor); break;
					case "PD": $CI->Akunting_model->addJurnalPD($nomor); break;
					case "DP": $CI->Akunting_model->addJurnalDP($nomor); break;
                    case "DB": $CI->Akunting_model->addPostingDB($nomor); break;
					case "KR": $CI->Akunting_model->addPostingCR($nomor); break;
					case "BG": $CI->Akunting_model->addJurnalBG($nomor); break;
					default: break;
				}

                $desc = 'Validasi Accounting Nomor : '.$nomor;
            }
        }
        else if (isset($post['flag']) && $post['flag'] != NULL) {
            $flag = $post['flag']!='NULL'? $post['flag'] : NULL;

            $data = array("FLAG"=>$flag);
            $CI->Utility_model->ubahStatus_model($table, $data, $where);

            if($flag != 'P') {
                $CI->Akunting_model->delGldataByNomor($nomor);
                $CI->Akunting_model->calcCcdata();
                $CI->Akunting_model->calcTbdata();

                $datainfo = array("POSJ"=>'V', "FLAG"=>'');

                $CI->Akunting_model->editThd($datainfo, $nomor);
                $CI->Akunting_model->editTrxByNomor($datainfo, $nomor);

                $desc = 'Buka Validasi Accounting Nomor : '.$nomor;
            }
            else if($flag == 'P') {
                $datainfo = array("POSJ"=>'P', "FLAG"=>'P');

                $CI->Akunting_model->editThd($datainfo, $nomor);
                $CI->Akunting_model->editTrxByNomor($datainfo, $nomor);

                switch(substr($nomor,0,2))
                {
                    case "GL": $CI->Akunting_model->addJurnalGL($nomor); break;
                    default: break;
                }

                $desc = 'Validasi Accounting Nomor : '.$nomor;
            }
        }
        else if ($post['postj'] != NULL) {
            $postj = $post['postj']!='NULL'? $post['postj'] : NULL;

            if($postj != 'V') {
                $desc = 'Buka Validasi Nomor : '.$nomor;
            } else if($postj == 'V') {
                $desc = 'Validasi Nomor : '.$nomor;
            }

            $data = array("POSJ"=>$postj);
            $CI->Utility_model->ubahStatus_model($table, $data, $where);
        }  

        $userId = $CI->User_model->getSession('userId');  
        $ip = $CI->User_model->getClientIP();                          
        $loginfo = array('LOGUSER'=>$userId, 'LOGIP'=>$ip, 'LOGDATE'=>date('Y-m-d H:i:s'), 'LOGDESC'=>$desc);
        $CI->User_model->addLog($loginfo); 
		 
    }  
}

if(!function_exists('GetDirectorySize')){
    function GetDirectorySize($path){
        $file_size = 0;
        $path = realpath($path);
        if($path!==false && $path!='' && file_exists($path)){
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                $file_size += $object->getSize();
            }
        }
        return $file_size;
    }
}

if (!function_exists('default_img')) {
    function default_img($text = null, $nama_file, $dbname, $folder)
    {   
        if($text != null)
        {
            if($folder == 'product')
            {
                if (!is_dir('./images/'.$dbname.'/product')) {
                    mkdir('./images/'.$dbname.'/product', 0777, TRUE);
                }
                $path = 'images/'.$dbname.'/product/'.$nama_file.'.png';

            } else if($folder == 'salesm'){
                if (!is_dir('./images/'.$dbname.'/salesm')) {
                    mkdir('./images/'.$dbname.'/salesm', 0777, TRUE);
                }
                $path = 'images/'.$dbname.'/salesm/'.$nama_file.'.png';
            } else if($folder == 'meja'){
                if (!is_dir('./images/'.$dbname.'/meja')) {
                    mkdir('./images/'.$dbname.'/meja', 0777, TRUE);
                }
                $path = 'images/'.$dbname.'/meja/'.$nama_file.'.png';
            }
            $image = imagecreate(200, 200);
            $font_size = 86;
            $font = 'assets/fonts/arial.ttf';

            imagecolorallocate($image, 128, 128, 128);

            $textcolor = imagecolorallocate($image, 169, 169, 169);

            $image_width = imagesx($image);  
            $image_height = imagesy($image);

            // Get Bounding Box Size
            $text_box = imagettfbbox($font_size,0,$font,$text);

            // Get your Text Width and Height
            $text_width = $text_box[2]-$text_box[0];
            $text_height = $text_box[7]-$text_box[1];

            // Calculate coordinates of the text
            $x = ($image_width/2) - ($text_width/2);
            $y = ($image_height/2) - ($text_height/2);

            imagettftext($image, $font_size, 0, $x, $y, $textcolor, $font, $text);
            imagepng($image, $path);
            imagedestroy($image);

            return $path;
        }
    }
}

function upload_image($table, $kolom = 'KODE', $kode, $dbname)
{
    $_this =& get_instance();
    $_this->load->library('upload');
    $_this->load->model('M_upload');
    
    if($table == 'SALESM')
    {
        $config['upload_path'] = './images/'.$dbname.'/salesm'; //path folder
        if (!is_dir('./images/'.$dbname.'/salesm')) {
            mkdir('./images/'.$dbname.'/salesm', 0777, TRUE);
        }
    } else if ($table == 'CUSTOM'){
        $config['upload_path'] = './images/'.$dbname.'/custom'; //path folder
        if (!is_dir('./images/'.$dbname.'/custom')) {
            mkdir('./images/'.$dbname.'/custom', 0777, TRUE);
        }
    } else if ($table == 'VENDOR'){
        $config['upload_path'] = './images/'.$dbname.'/vendor'; //path folder
        if (!is_dir('./images/'.$dbname.'/vendor')) {
            mkdir('./images/'.$dbname.'/vendor', 0777, TRUE);
        }
    } else if ($table == 'CABANG'){
        $config['upload_path'] = './images/'.$dbname.'/cabang'; //path folder
        if (!is_dir('./images/'.$dbname.'/cabang')) {
            mkdir('./images/'.$dbname.'/cabang', 0777, TRUE);
        }
    } else if ($table == 'SYSUSER'){
        $config['upload_path'] = './images/'.$dbname.'/user'; //path folder
        if (!is_dir('./images/'.$dbname.'/user')) {
            mkdir('./images/'.$dbname.'/user', 0777, TRUE);
        }
    } else if ($table == 'MEJA'){
        $config['upload_path'] = './images/'.$dbname.'/meja'; //path folder
        if (!is_dir('./images/'.$dbname.'/meja')) {
            mkdir('./images/'.$dbname.'/meja', 0777, TRUE);
        }
    } else if ($table == 'TBAYAR'){
        $config['upload_path'] = './images/'.$dbname.'/tipe_bayar'; //path folder
        if (!is_dir('./images/'.$dbname.'/tipe_bayar')) {
            mkdir('./images/'.$dbname.'/tipe_bayar', 0777, TRUE);
        }
    }

    $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
    $config['encrypt_name'] = TRUE; //Enkripsi nama yang terupload

    $_this->upload->initialize($config);
    
    $kolom = $_this->input->post('colx');
    $tabel = $_this->input->post('tablex');
    $nilai = $_this->input->post('valx');
    $form = $_this->input->post('formx');
    $fotoOld = $_this->input->post('fotoOld');
    $strTable = strtolower($table);

    // print_r($_FILES);
  	echo $_this->upload->display_errors('<p>','</p>');
    if(!empty($_FILES['filefoto']['name'])){
        if ($_this->upload->do_upload('filefoto')){
            $gbr = $_this->upload->data();
            //Compress Image
            $config['image_library'] = 'gd2';
            $config['source_image'] = './images/'.$dbname.'/'.$strTable.'/'.$gbr['file_name'];
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;
            $config['quality'] = '50%';
            $config['width'] = 400;
            $config['height'] = 400;
            $config['new_image'] = './images/'.$dbname.'/'.$strTable.'/'.$gbr['file_name'];
            $_this->load->library('image_lib', $config);
            $_this->image_lib->resize();
            
            if($fotoOld != '') {
                @unlink('./images/comp/'.$fotoOld);
            }
            
            // $kolom = 'KODE';
            // $tabel = 'CABANG';

            $gambar = $gbr['file_name'];
            $_this->M_upload->simpan_upload($gambar, $tabel, $kolom, $kode);
            echo "<br> <center> Image berhasil diupload.";
            
            // redirect('/'.$form.'/'.$nilai,'refresh');
        } else {
            // $_this->session->set_flashdata('upImg', 'Gagal upload image!');
        }

    }else{
        if($fotoOld == 'empthy'){
            $gambar = '';
            $_this->M_upload->simpan_upload($gambar, $tabel, $kolom, $kode);
            // echo "<br> <br> <br> <center> Image Kosong.";
        }
        // echo "<br> <br> <br> <center> Image gagal diupload silahkan mengulang kembali.";
        
        // redirect('/'.$form.'/'.$nilai,'refresh');
    }
}

function edit_image($table)
{
    $_this =& get_instance();
    
    $_this->load->model('M_upload');
    $_this->load->library('upload');
    
    $config['upload_path'] = './images/comp'; //path folder
    $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
    $config['encrypt_name'] = TRUE; //Enkripsi nama yang terupload

    $_this->upload->initialize($config);
    
    $kolom = $_this->input->post('colx');
    $tabel = $_this->input->post('tablex');
    $nilai = $_this->input->post('valx');
    $form = $_this->input->post('formx');
    $fotoOld = $_this->input->post('fotoOld');
    
    if(!empty($_FILES['filefoto']['name'])){

        if ($_this->upload->do_upload('filefoto')){
            $gbr = $_this->upload->data();
            //Compress Image
            $config['image_library'] = 'gd2';
            $config['source_image'] = './images/comp'.$gbr['file_name'];
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = FALSE;
            $config['quality'] = '50%';
            $config['width'] = 400;
            $config['height'] = 'auto';
            $config['new_image'] = './images/comp'.$gbr['file_name'];
            $_this->load->library('image_lib', $config);
            $_this->image_lib->resize();
            
            if($fotoOld != '') {
                @unlink('./images/comp/'.$fotoOld);
            }
            
            $gambar = $gbr['file_name'];
            $_this->M_upload->simpan_upload($gambar, $tabel, $kolom, $nilai);
            echo "<br> <center> Image berhasil diupload.";
            // echo ($gambar);
            // redirect('/'.$form.'/'.$nilai,'refresh');
        }
                 
    }else{
        if($fotoOld == 'empthy'){
            $gambar = '';
            $_this->M_upload->simpan_upload($gambar, $tabel, $kolom, $kode);
            echo "<br> <br> <br> <center> Image Kosong.";
        }
        echo "<br> <br> <br> <center> Image gagal diupload silahkan mengulang kembali.";
        
        // redirect('/'.$form.'/'.$nilai,'refresh');

    }
}

function renderPdf($nama_dokumen,$html)
{
    ini_set("memory_limit","-1");
    
    include_once(APPPATH.'third_party/mpdf/content/modules/mPDF/MPDF61/mpdf.php');
    $mpdf = new mPDF('utf-8','A4');

    $mpdf->WriteHTML($html);
    $mpdf->Output($nama_dokumen.".pdf",'D');
    // exit;
}

function FormatPeriod_($cKey)
{
    return Left($cKey, 2)."/".Right($cKey, 4);
}

function Left($Str,$Len)
{
    return substr($Str,0,$Len);
}

function Right($Str,$Len)
{
    return substr($Str,-$Len);
}

function Mid($Str,$Start,$Len)
{
    return substr($Str,$Start,$Len);   
}

function SetPeriod_($cKey)
{
    return Left($cKey, 2).Right($cKey, 4);
}

function SetAcc_($cKey)
{
   return Left($cKey, 4).Right($cKey, 4);
}

function FormatAcc_($cKey){
   return Left($cKey, 4).".".Right($cKey, 4);
}

function FormatDate_($cDate,$cFormat){
    return date($cFormat,strtotime($cDate));
}

function FormatNo_($cKey){
   return Left($cKey, 2)."-".Mid($cKey, 2, 4)."/".Mid($cKey, 6, 2)."-".Right($cKey, 4);
}

function SetNo_($cKey){
   return Left($cKey, 2).Mid($cKey, 3, 4).Mid($cKey, 8, 2).Right($cKey, 4);
}

function FormatCurrency_($cKey){
    return number_format($cKey,DEC_PRC);
}

function FormatMoney_($cKey){
    if($cKey>=0)
        return number_format($cKey,DEC_PRC);
    else
        return '('.number_format(abs($cKey),DEC_PRC).')';       
        
}

function Space_($nLen){
    return str_repeat("&nbsp;", $nLen);
}

function CekDatabase_($dbName){
    $CI = get_instance();
    $row =  $CI->db->query("SHOW DATABASES LIKE '%$dbName%'")->num_rows();
    if($row>0)
        return true;
    else
        return false;
}

function Sort_($array, $on, $order=SORT_ASC){

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function Find_($arrData,$colName,$strToFind){

    $result = in_array($strToFind,array_column($arrData, $colName));

    return $result;

}

function replace_mychar($str){
    $string = str_replace(['\'','"'],'`',$str);
    return $string;
}

function blank_to_zero($str){
    $zero = $str && $str != 'NaN' && $str != 'null' && $str != 'NULL' && $str != 'undefined' && $str != 'Undefined' ? $str : 0;
    return $zero;
}

function getWhOption($aksesGd, $dataGudang, $dataWh)
{
    $j = 0;
    for ($i=0; $i < count($dataWh); $i++) { 
        if (in_array($dataGudang[$i]['KODE'], $aksesGd)) {
            $j += 1;
        }
        else{
            unset($dataGudang[$i]);
        }
    }

    if($j > 0){
            // print_r($dataGudang);
        return $dataGudang;
    }
    // print_r($dataWh);
    return $dataWh;
}

function cekStokExcel($barang, $group, $gd, $tanggal)
{
    $CI =& get_instance();
    $CI->load->model('Persediaan_model');
    
    $queryStokMasuk = $CI->Persediaan_model->getStokMasuk($barang, $group, $gd, $tanggal);

    $stokmasuk = $queryStokMasuk['stokmasuk'];

    $queryStokKeluar = $CI->Persediaan_model->getStokKeluar($barang, $group, $gd, $tanggal);

    $stokkeluar = $queryStokKeluar['stokkeluar'];

    $hasilpengecekan = ($stokmasuk - $stokkeluar);

    return $hasilpengecekan;
}

function dateToWeek($qDate){
    $dt = strtotime($qDate);
    $day  = date('j',$dt);
    $month = date('m',$dt);
    $year = date('Y',$dt);
    $totalDays = date('t',$dt);
    $weekCnt = 1;
    $retWeek = 0;
    for($i=1;$i<=$totalDays;$i++) {
        $curDay = date("N", mktime(0,0,0,$month,$i,$year));
        if($curDay==7) {
            if($i==$day) {
                $retWeek = $weekCnt+1;
            }
            $weekCnt++;
        } else {
            if($i==$day) {
                $retWeek = $weekCnt;
            }
        }
    }
    return $retWeek;
}

?>