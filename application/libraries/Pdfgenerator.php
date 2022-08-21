<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once("./application/third_party/dompdf/autoload.inc.php");
use Dompdf\Dompdf;

class Pdfgenerator {

  public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait")
  {
    header("HTTP/1.1 200 OK");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

    header("Cache-Control: private", false);

    header("Content-type: application/pdf");
    // header("Content-Disposition: attachment; filename=\"temporaryPdf.pdf\"");

    header("Content-Transfer-Encoding: binary");

    $dompdf = new DOMPDF();
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
        exit(0);
    } else {
        return $dompdf->output();
    }
  }
}