<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Commonfunction
{
	
	private $ci;
   
    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function namahari($tanggal,$val) {
		//format $tanggal YYYY-MM-DD
		$tgl = substr($tanggal,8,2);
		$bln = substr($tanggal,5,2);
		$thn = substr($tanggal,0,4);
		$info = date('w',mktime(0,0,0,$bln,$tgl,$thn));
		if($val == 'nilai') {
			return $info;
		} else if($val == 'nama') {
			switch($info){
				case '0' : return "Minggu"; break;
				case '1' : return "Senin"; break;
				case '2' : return "Selasa"; break;
				case '3' : return "Rabu"; break;
				case '4' : return "Kamis"; break;
				case '5' : return "Jumat"; break;
				case '6' : return "Sabtu"; break;
			}
		}
	}
	
	public function weekNumberOfMonth($date) {
		$tgl = date_parse($date);
		$tanggal = $tgl['day'];
		$bulan = $tgl['month'];
		$tahun = $tgl['year'];
		
		//tanggal 1 tiap bulan
		$tanggalAwalBulan = mktime(0,0,0,$bulan,1,$tahun);
		$tglMingguSebelum = (int)date('W',$tanggalAwalBulan);
		
		//tanggal sekarang
		$tanggalYangDicari = mktime(0,0,0,$bulan,$tanggal,$tahun);
		$tglMingguYangDicari = (int)date('W',$tanggalYangDicari);
		
		$mingguKe = $tglMingguYangDicari - $tglMingguSebelum + 1;
		
		return $mingguKe.'|'.$tglMingguSebelum.'|'.$tglMingguYangDicari;
	}
        
}

?>