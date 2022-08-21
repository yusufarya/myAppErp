function isNumberKey(evt) 
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	// Added to allow decimal, period, or delete
	//if (charCode == 110 || charCode == 190 || charCode == 46)
	//	return true;
	
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	
	return true;
}

function getJSON(url, data){
	
  	return JSON.parse($.ajax({
    	url:url,
    	type:'post',
    	dataType: 'json',
    	data:data,
    	global:false,
    	async:false,
    	success:function(msg){
    	}
  	}).responseText);
}

function getUrlVars(){
    
    var vars = [], key;
    var host = window.location.hostname;
    var path = window.location.pathname;
    var hash = window.location.href.substring(window.location.href.indexOf('#')+1);
    hash = hash.slice(hash.indexOf('?')+1).split('&');

    for (var i = 0; i < hash.length; i++) {
      key = hash[i].split('=');
      vars[key[0]]= key[1];
    }

    return vars;
}


// function terbilang(nilai){
// 	var bilangan = nilai.toString().split("");
// 	var kalimat = "";
// 	var angka = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0')
// 	var kata = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
// 	var tingkat = ['', 'ribu', 'juta', 'milyar', 'triliun'];
// 	var pjg_bilangan = bilangan.length

// 	// console.log("pjg_bilangan ", bilangan.length)
// 	console.log(pjg_bilangan)

// 	/*Pengujian panjang bilangan*/
// 	if (pjg_bilangan > 15) {
// 		kalimat = "Tak terhingga"
// 	} 
// 	else {
// 		/* Mengambil angka-angka yang ada dalam bilangan, dimasukkan kedalam array */
// 		for(i=1; 1<=pjg_bilangan; i++){
// 			angka[i] = bilangan[i-1]
// 		}

// 		var i=1; j=0;

// 		/* mulai proses iterasi terhadap array angka */
// 		while(i <= pjg_bilangan){
// 			subkalimat = ""
// 			kata1 = ""
// 			kata2 = ""
// 			kata3 = ""

// 			/* untuk ratusan */
// 			if (angka[i+2] != "0") {
// 				if (angka[i+2] == "1") {
// 					kata1 = "seratus";
// 				}
// 				else{
// 					kata1 = kata[angka[i+2]] + " ratus";
// 				}
// 			}

// 			/* untuk puluhan atau belasan */
// 			if (angka[i+1] != "0") {
// 				if (angka[i+1] == "1") {
// 					if (angka[i] == "0") {
// 						kata2 = "sepuluh";
// 					}
// 					else if(angka[i] == "1"){
// 						kata2 = "sebelas"
// 					}
// 					else{
// 						kata2 = kata[angka[i]] + " belas";
// 					}
// 				}
// 				else{
// 					kata2 = kata[angka[i+1]] + " puluh";
// 				}
// 			}

// 			/*untuk satuan*/
// 			if (angka[i] != "0") {
// 				if (angka[i+1] != "1") {
// 					kata3 = kata[angka[i]];
// 				}
// 			}

// 			/* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat*/
// 			if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
// 				subkalimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
// 			}

// 			// gabungkan variabel sub kalimat (untuk satu blok 3 angka) ke variabel kalimat 
// 			kalimat = subkalimat + kalimat;
// 			i = i + 3;
// 			j = i + 1;

// 		}

// 		/* mengganti satu ribu jadi seribu jika diperlukan */
// 		if ((angka[5] == "0") && (angka[6] == "0")) {
// 			kalimat = kalimat.replace("satu ribu", "seribu");
// 		}
// 	}

// 	return kalimat;
// }
var daftarAngka = new Array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');

function terbilang(nilai)
{
	var temp = ""
	var hasilBagi, sisaBagi;
	var batas = 3; //batas untuk ribuan

	var maxBagian = 5; 
	var gradeNilai = new Array("", "Ribu", 'Juta', 'Milyar', 'Trilyun');

	nilai = hapusNolDidepan(nilai);
	var nilaiTemp = ubahStringKeArray(batas, maxBagian, nilai);

	var j = nilai.length;

	var banyakBagian = (j%batas) == 0 ? (j/batas) : Math.round(j/batas + 0.5)

	var h=0;

	for (var i = banyakBagian - 1; i>=0; i--) {
		var nilaiSementara = parseInt(nilaiTemp[h]);
		if (nilaiSementara == 1 && i == 1) {
			temp += "seribu";
		} else {
			temp += ubahRatusanKeHuruf(nilaiTemp[h])+" ";

			// cek apakah string bernilai 000, maka jangan tanbahkan gradeNilai[i]
			if (nilaiTemp[h] != "000") {
				temp += gradeNilai[i]+" ";
			}
		}

		h++;
	}

	return temp
}

function ubahStringKeArray(batas, maxBagian, kata)
{
	// maksimal 999 milyar
	var temp = new Array(maxBagian)
	console.log("ds " +kata)
	var j = kata.length

	// menentukan batas array
	var banyakBagian = (j % batas) == 0 ? (j / batas) : Math.round(j / batas+0.5);
	for (var i = banyakBagian-1; i >= 0; i--) {
		var k = j-batas;
		if (k < 0) k=0;
			temp[i] = kata.substring(k, j)
		j=k;
		if (j==0) 
		break;
	}
	return temp;
}

function ubahRatusanKeHuruf(nilai)
{
	// maksimal 3 karakter
	var batas = 2;

	// membagi string menjadi 2 bagian, misal 123 ==> 1 dan 23
	var maxBagian = 2;
	var temp = ubahStringKeArray(batas, maxBagian, nilai)
	var j= nilai.length;
	var hasil = "";

	// menentukan batas array
	var banyakBagian = (j%batas) == 0 ? (j/batas) : Math.round(j/batas+0.5);
	for (var i = 0; i < banyakBagian; i++) {
		// cek string yang memiliki panjang lebih dari satu ==> belasan atau puluhan	
		if (temp[i].length > 1) {
			// cek untuk yang bernilai belasan ===> angka pertama 1 dan angka kedua 0-9, seperti 11,16 dst
			if (temp[i].charAt(0) == '1') {
				if (temp[i].charAt(1) == '1') {
					hasil += "Sebelas";
				}
				else if(temp[i].charAt(1) == '0') {
					hasil += "Sepuluh";
				}
				else{
					hasil += daftarAngka[temp[i].charAt(1)-'0'] + " Belas ";
				}
			}
			// cek untuk string dengan format angka pertama 0 ==> 09,05 dst
			else if(temp[i].charAt(0) == '0'){
				hasil += daftarAngka[temp[i].charAt(1) - '0'];
			}
			else{
				hasil += daftarAngka[temp[i].charAt(0) - '0'] + " Puluh "+daftarAngka[temp[i].charAt(1) - '0'];
			}
		}
		else{
			// cek string yang memiliki pabhabf = 1 dan berada pada posisi ratusan
			if (i==0 && banyakBagian != 1) {
				if (temp[i].charAt(0) == '1') {
					hasil += " seratus ";
				}
				else if(temp[i].charAt(0) == '0'){
					hasil += " "
				}
				else{
					hasil += daftarAngka[parseInt(temp[i])] + " Ratus ";
				}
			} else {
				hasil += daftarAngka[parseInt(temp[i])];
			}
		}
	}
	return hasil;
}

function hapusNolDidepan(nilai) 
{
	while(nilai.indexOf("0") == 0){
		nilai = nilai.substring(1, nilai.length)
	}

	return nilai;
}

function ubah(nilai)
{	
	if (nilai.length > 15) {
		return "Jumlah Tak terhingga"
	}
	var hasil = terbilang(nilai);
	return hasil
}

function formatKodeAkun(data) 
{        
    if (data.value.length > 5 || data.value.length >= 9) {
        num = data.value;
        num = num.replace(/[^\d.]/g,"");
        arr = num.split('.').toString().replace(',', '');
        num = arr;

        num = num.substr(0,4)+'.'+num.substr(4,4);
        data.value = num;
    }
}

function format(num) 
{
	val = num.value;
	val = val.replace(/[^\d.]/g,"");
	arr = val.split('.');
	lftsde = arr[0];
	rghtsde = arr[1];
	result = "";
	lng = lftsde.length;
	j = 0;
	for (i = lng; i > 0; i--){
		j++;
		if (((j % 3) == 1) && (j != 1)){
			result = lftsde.substr(i-1,1) + "," + result;
		}else{
			result = lftsde.substr(i-1,1) + result;
		}
	}
	if(rghtsde==null){
		num.value = result;
	}else{
		num.value = result+'.'+arr[1];
	}
} 

function isNumberAlphaKey(evt) 
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	// alert(charCode)
	if (charCode >= 48 && charCode <= 57 || charCode >= 65 && charCode <= 90 || charCode >= 97 && charCode <= 122 || charCode == 32 || charCode == 47)
		return true;
			
	return false;
};

function isNumberAlphaDotKey(evt) 
{
	var charCode = (evt.which) ? evt.which : event.keyCode

	if (charCode >= 48 && charCode <= 57 || charCode >= 65 && charCode <= 90 || charCode >= 97 && charCode <= 122 || charCode == 46)
		return true;
			
	return false;
};

function exportTableToCSV($table, filename)
{
    var $rows = $table.find('tr:has(td), tr:has(th)'), 
        tmpColDelim = String.fromCharCode(11),
        tmpRowDelim = String.fromCharCode(0),
        colDelim    = '","',
        rowDelim    = '"\r\n"';

    var csv = '"' + $rows.map(function (i, row){
            var $row    = $(row), 
                $cols   = $row.find('td, th');

            return $cols.map(function(j, col) {
                var $col    = $(col), 
                    text    = $col.text();

                return text.replace(/"/g, '""');
                
            }).get().join(tmpColDelim);

        }).get().join(tmpRowDelim)
                    .split(tmpRowDelim).join(rowDelim)
                    .split(tmpColDelim).join(colDelim) + '"';

    var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

    console.log(csv);

    if (window.navigator.msSaveBlob) {

        window.navigator.msSaveOrOpenBlob( new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv");
    }
    else{
        $(this).attr({'download':filename, 'href':csvData, 'target':'_blank'});
    }
}

function isNumberWithDot(evt) 
{
	var charCode = (evt.which) ? evt.which : event.keyCode

	if (charCode == 46)
		return true;
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
				
	return true;
};

function isNumberPhone(evt) 
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode == 40 || charCode == 41 || charCode == 45)
		return true;
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	
	return true;
};

function ctrlT(evt) 
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	
	if(charCode == 17 && charCode == 84)
		return false;
	
	return true;
}

function number_format (number, decimals, dec_point, thousands_sep) 
{
	// From: http://phpjs.org/functions
	// +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +     bugfix by: Michael White (http://getsprink.com)
	// +     bugfix by: Benjamin Lupton
	// +     bugfix by: Allan Jensen (http://www.winternet.no)
	// +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// +     bugfix by: Howard Yeend
	// +    revised by: Luke Smith (http://lucassmith.name)
	// +     bugfix by: Diogo Resende
	// +     bugfix by: Rival
	// +      input by: Kheang Hok Chin (http://www.distantia.ca/)
	// +   improved by: davook
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +      input by: Jay Klehr
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +      input by: Amir Habibi (http://www.residence-mixte.com/)
	// +     bugfix by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: Theriault
	// +      input by: Amirouche
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// *     example 1: number_format(1234.56);
	// *     returns 1: '1,235'
	// *     example 2: number_format(1234.56, 2, ',', ' ');
	// *     returns 2: '1 234,56'
	// *     example 3: number_format(1234.5678, 2, '.', '');
	// *     returns 3: '1234.57'
	// *     example 4: number_format(67, 2, ',', '.');
	// *     returns 4: '67,00'
	// *     example 5: number_format(1000);
	// *     returns 5: '1,000'
	// *     example 6: number_format(67.311, 2);
	// *     returns 6: '67.31'
	// *     example 7: number_format(1000.55, 1);
	// *     returns 7: '1,000.6'
	// *     example 8: number_format(67000, 5, ',', '.');
	// *     returns 8: '67.000,00000'
	// *     example 9: number_format(0.9, 0);
	// *     returns 9: '1'
	// *    example 10: number_format('1.20', 2);
	// *    returns 10: '1.20'
	// *    example 11: number_format('1.20', 4);
	// *    returns 11: '1.2000'
	// *    example 12: number_format('1.2000', 3);
	// *    returns 12: '1.200'
	// *    example 13: number_format('1 000,50', 2, '.', ' ');
	// *    returns 13: '100 050.00'
	// Strip all characters but numerical ones.
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
   		var k = Math.pow(10, prec);
   		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function ChangeCase(elem) 
{
    elem.value = elem.value.toUpperCase();
}

function ChangeLower(elem) 
{
   	elem.value = elem.value.toLowerCase();
}

function addDays(startDate,numberOfDays) 
{
	var returnDate = new Date(
							  startDate.getFullYear(),
							  startDate.getMonth(),
							  startDate.getDate()+numberOfDays,
							  startDate.getHours(),
							  startDate.getMinutes(),
							  startDate.getSeconds()
							  );
	return returnDate;
}

function convertToRupiah(angka)
{
	// var rupiah = '';
	// var angkarev = angka.toString().split('').reverse().join('');
	// for(var i = 0; i<angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	// return rupiah.split('',rupiah.length-1).reverse().join('');

	var rupiah = new Intl.NumberFormat('id',{style:'decimal', minimumFractionDigits:2, maximumFractionDigits:2}).format(angka);

	return rupiah;
}

function convertTanggal(tgl)
{
	var periode = tgl.split('-');
	var tanggal = periode[0];
	var tahun = periode[2];
	var bulan = periode[1];

	switch(bulan){
		case '01':
			bulan = 'Januari';
			break;
		case '02':
			bulan = 'Februari';
			break;
		case '03':
			bulan = 'Maret';
			break;
		case '04':
			bulan = 'April';
			break;
		case '05':
			bulan = 'Mei';
			break;
		case '06':
			bulan = 'Juni';
			break;
		case '07':
			bulan = 'Juli';
			break;
		case '08':
			bulan = 'Agustus';
			break;
		case '09':
			bulan = 'September';
			break;
		case '10':
			bulan = 'Oktober';
			break;
		case '11':
			bulan = 'November';
			break;
		case '12':
			bulan = 'Desember';
			break;
		default:
			bulan='err';
	}

	periode = tanggal+' '+bulan+' '+tahun;
	return periode;
}

function rupiah()
{
	var nominal = document.getElementById("saldokas").value;
	var rupiah = convertToRupiah(nominal);
	document.getElementById("saldokas").value = rupiah;
}

function disablelink(linkID)
{
	var hlink = document.getElementById(linkID);
	if(!hlink)
	return;
	hlink.href = '#';
	hlink.className = "disableLink";
}

function pad(str, maxs)
{
	str = str.toString();
	return str.length < maxs ? pad("0" + str, maxs) : str;
}

function formatNumber(num)
{
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,'$1,')
}

function resetSearch()
{
    // alert('hshsdhsh')
    $('input[name=searchText]').val("")
    $('#submit').click()
}


function isValidDate(tgl, hariini)
{
	// console.log(tgl)
	var parts = tgl.split("-");
	var day = parseInt(parts[0], 10);
	var month = parseInt(parts[1], 10)
	var year = parseInt(parts[2], 10)
	
	if (!/^\d{1,2}\-\d{1,2}\-\d{4}$/.test(tgl)) {
		$.smallBox({
			title : "Error",
			content : "Tanggal Tidak Sesuai Dengan Format! <p class='text-align-right'><a href='javascript:void(0);' class='btn btn-default btn-sm'>OK</a></p>",
			color : "#c26565",
			icon : "fa fa-times swing animated"
		});
		$('#mydate').val(hariini);
		return false;
	}

	if (year < 1000 || year > 3000 || month == 0 || month > 12) {
		$.smallBox({
			title : "Error",
			content : "Tanggal Tidak Sesuai Dengan Format! <p class='text-align-right'><a href='javascript:void(0);' class='btn btn-default btn-sm'>OK</a></p>",
			color : "#c26565",
			icon : "fa fa-times swing animated"
		});
		$('#mydate').val(hariini);
		return false;
	}

	var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

	//
	if (year%400 == 0 || (year%100 != 0 && year % 4 == 0)) {
		monthLength[1] = 29;
	}

	if (!(day > 0 && day <= monthLength[month - 1])) {
		$.smallBox({
			title : "Error",
			content : "Tanggal Tidak Sesuai Dengan Format! <p class='text-align-right'><a href='javascript:void(0);' class='btn btn-default btn-sm'>OK</a></p>",
			color : "#c26565",
			icon : "fa fa-times swing animated"
		});
		$('#mydate').val(hariini);
		return false;
	}
	return true
}

// $(document).ready(function(){
$('[data-toggle="tooltip"]').tooltip();
// });
$('.table-container').scrollLeft(12000);

// preview image before upload
$('#filefoto').on('change', function()
{
    readURL(this)
}) 
function readURL(input){
	if(input.files && input.files[0]){
		var reader = new FileReader();

		reader.onload = function(e){
			$('#preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

//delete image
$(document).ready(function() 
{	
	$('html').bind('keypress', function(e){
		if (e.keyCode == 13) {
			return false
		}
	});
	
	$('#del-img').on('click', function(){
		// alert('Gambar dihapus');
		var host = window.location.origin;
		$('#filefoto').val('');
		$('#fotoOld').val('empthy');
		$('#preview').attr('src', host+"/siserp/images/comp/no_image.png");
	})

	// $('.yearpicker').datepicker()
})