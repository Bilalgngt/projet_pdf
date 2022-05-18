<?php
include('../library/examples/tcpdf_include.php');


class PdfModel {

    private $_pdf;
    private $_logo;

    public function __construct() {
        $this->_pdf = new TCPDF('P','mm', 'A4');
        $this->init();

    }
    public function init(){
        $this->_pdf->SetFont('dejavusans', '', 14, '', true);
        $this->_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->setInformation();
        $this->header();
        $this->footer();

    }
    public function setInformation(){
        $this->_pdf->SetCreator(PDF_CREATOR);
        $this->_pdf->SetAuthor('Nicola Asuni');
        $this->_pdf->SetTitle('TCPDF Example 001');
        $this->_pdf->SetSubject('TCPDF Tutorial');
        $this->_pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    }

    public function header(){
        $this->_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    }

    public function footer(){
        $this->_pdf->setFooterData(array(0,64,0), array(0,64,128));
        $this->_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    }
    public function addPage(){
        $this->_pdf->AddPage();
    }

    public function addLine($x, $y, $c1/*inclinaison premier point*/, $c2 /*inclinaison second point*/){
        $style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(0, 0, 0));
        $this->_pdf->Line($c1, $x, $c2, $y,  $style2);
        //$this->_pdf->Cell(55, 120, "\n", 1, 1, 'R', 0, '', 1);

    }

    public function addText($content, $x, $y,  $size){
        $this->_pdf->SetFont('times', 'B', $size);
        $this->_pdf->MultiCell(55, 5, '[FIT CELL] '.$content."\n", 1, 'J', 0, 0, $x, $y, true, 0, false, true, 60, 'M', true);
    }
    public function generatePdf(){
        $this->_pdf->Output(RELATIVE_PATH['temp']. 'example06.pdf','F');
        $output = RELATIVE_PATH['temp']. 'example06.pdf';
        if (@file_exists($output)) {
            echo 'La conversion a réussi. Vous pouvez ';
            echo '<a href="../../storage/temp/example06.pdf"> ouvrir le fichier</a>';
        }
        else{
            echo "Erreur de conversion";
        }
    }
}
/*
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->AddPage();

$pdf->SetFont('dejavusans', '', 14, '', true);
*/