<?php
include('../library/examples/tcpdf_include.php');
class PdfModel extends TCPDF {


    public function __construct() {
        parent::__construct();
        $this->init();
    }
    public function init(){
        $this->SetFont('dejavusans', '', 14, '', true);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->setInformation();

    }
    public function setInformation(){
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Nicola Asuni');
        $this->SetTitle('TCPDF Example 001');
        $this->SetSubject('TCPDF Tutorial');
        $this->SetKeywords('TCPDF, PDF, example, test, guide');
    }

    public function drawHeader($link, $logo){
        $this->MultiCell($this->convertPixel(590.55),/*taille de la case de l'image */$this->convertPixel(531.5), '', 1, '', 0, 0, /*padding x*/$this->convertPixel(23.62), /*padding y*/$this->convertPixel(23.62), true, 0, false, true, 0);
        $this->MultiCell(/*taille de la case du contenu x*/$this->convertPixel(1842.21),/*taille de la case du contenu y*/$this->convertPixel(531.5), '', 1, '', 0, 0, /*padding x*/$this->convertPixel(614.17), $this->convertPixel(23.62), true, 0, false, true, 0);
        $this->MultiCell(/*taille de la case du contenu x*/$this->convertPixel(472.44),/*taille de la case du contenu y*/ $this->convertPixel(271.65), '', 1, '', 0, 0, /*padding x*/$this->convertPixel(-496.88), /*padding y*/$this->convertPixel(23.62), true, 0, false, true, 0);
        $image_file = K_PATH_IMAGES.$logo;
        $this->Image($image_file, $this->convertPixel(80), $this->convertPixel(80), $this->convertPixel(472.44), $this->convertPixel(413.4), 'JPG', $link, 'T', false, 300, '', false, false, 1, false, false, false);
        
        }

    public function drawFooter(){
        $this->SetFont('times', 'I', 10);
        $this->MultiCell(/*taille de la case du footer x*/$this->convertPixel(2390),/*taille de la case du footer y*/ $this->convertPixel(567), 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 1, 'C', 0, 0, /*padding x*/$this->convertPixel(23.62), /*padding y*/$this->convertPixel(2834.64), true, 0, false, true, 55, 'B');
        $this->ImageSVG($file='../../public/css/kiwi-maps_logo.svg', $this->convertPixel(2159), $this->convertPixel(3401.5), $this->convertPixel(295.3), $this->convertPixel(82.7), $link='http://www.kiwi-maps.com/', 'R', '', 0,false);
    }
    public function newPage(){
        $this->SetPrintHeader(false);
        $this->SetPrintFooter(false);
        $this->AddPage();
        $this->MultiCell(/*taille de la case du corps*/$this->convertPixel(2840),/*taille de la case du corps y*/ $this->convertPixel(2280), '', 1, '', 0, 0, /*padding x*/$this->convertPixel(23.62), /*padding y*/$this->convertPixel(555.12), true, 0, false, true, 0);
        
    }

    public function drawBody(){
    $this->addLine(197, 565, 197, 2850);
    $this->addLine(362, 565, 362, 2850);
    $this->addLine(502, 565, 502, 2850);
    $this->addLine(1081, 565, 1081, 2850);
    $this->addLine(1672, 565, 1672, 2850);
    }

    public function addLine($x1, $y1, $x2, $y2 ){
        $style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'mitter', 'dash' => ' 5,5', 'color' => array(0, 0, 0));
        $this->Line($this->convertPixel($x1), $this->convertPixel($y1), $this->convertPixel($x2),$this->convertPixel($y2),  $style);

    }

    public function addText($content, $x, $y,  $size){
        $this->SetFont('times', 'B', $size);
        $this->MultiCell(55, 5, '[FIT CELL] '.$content."\n", 1, 'J', 0, 0, $this->convertPixel($x), $this->convertPixel($y), true, 0, false, true, 60, 'M', true);
    }
    public function generatePdf(){
        $this->Output(RELATIVE_PATH['temp']. 'example06.pdf','F');
        $output = RELATIVE_PATH['temp']. 'example06.pdf';
        if (@file_exists($output)) {
            echo 'La conversion a réussi. Vous pouvez ';
            echo '<a href="../../storage/temp/example06.pdf"> ouvrir le fichier</a>';
        }
        else{
            echo "Erreur de conversion";
        }
    }

    private function convertPixel($px){
        return $px * 0.084666667;
    }
}


//fontion convertir pixel to mm