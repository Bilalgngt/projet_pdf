<?php
include('../library/examples/tcpdf_include.php');
class PdfModel {

    private $_pdf;

    public function __construct() {
        $this->_pdf = new TCPDF();
        $this->init();
    }
    public function init(){
        $this->_pdf->SetFont('dejavusans', '', 14, '', true);
        $this->_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->setInformation();
        $this->footer();

    }
    public function setInformation(){
        $this->_pdf->SetCreator(PDF_CREATOR);
        $this->_pdf->SetAuthor('Nicola Asuni');
        $this->_pdf->SetTitle('TCPDF Example 001');
        $this->_pdf->SetSubject('TCPDF Tutorial');
        $this->_pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    }

    public function Header($link, $logo){
        $this->_pdf->MultiCell(/*taille de la case de l'image */50,/*taille de la case de l'image */ 45, '', 1, '', 0, 0, /*padding x*/5, /*padding y*/5, true, 0, false, true, 0);
        $this->_pdf->MultiCell(/*taille de la case du contenu x*/150,/*taille de la case du contenu y*/ 45, '', 1, '', 0, 0, /*padding x*/55, /*padding y*/5, true, 0, false, true, 0);
        $this->_pdf->MultiCell(/*taille de la case du contenu x*/40,/*taille de la case du contenu y*/ 23, '', 1, '', 0, 0, /*padding x*/-45, /*padding y*/5, true, 0, false, true, 0);
        $image_file = K_PATH_IMAGES.$logo;
        $this->_pdf->Image($image_file, 10, 10, 40, 35, 'JPG', $link, 'T', false, 300, '', false, false, 1, false, false, false);
        $this->_pdf->SetFont('helvetica', 'B', 20);
        $this->_pdf->Cell(0, 15,'' , 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

    public function Footer(){
        
    }
    public function addPage(){
        $this->_pdf->SetPrintHeader(false);
        $this->_pdf->SetPrintFooter(false);
        $this->_pdf->AddPage();
        $this->_pdf->MultiCell(/*taille de la case du corps*/200,/*taille de la case du corps y*/ 190, '', 1, '', 0, 0, /*padding x*/5, /*padding y*/50, true, 0, false, true, 0);
        $this->_pdf->MultiCell(/*taille de la case du corps*/200,/*taille de la case du corps y*/ 52, '', 1, '', 0, 0, /*padding x*/5, /*padding y*/240, true, 0, false, true, 0);
    }

    public function addLine($x1, $y1, $x2, $y2 ){
        $style = array('width' => 0.7, 'cap' => 'round', 'join' => 'round', 'dash' => '4,10', 'color' => array(192, 192, 192));
        $this->_pdf->Line($x1, $y1, $x2, $y2,  $style);

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