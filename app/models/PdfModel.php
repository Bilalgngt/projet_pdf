<?php
include('../../library/examples/tcpdf_include.php');
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

    public function drawHeader($company, $siteName, $header){
        /*encadrement logo*/
        $this->MultiCell($this->convertPixel(590.55),$this->convertPixel(531.5), '', 1, '', 0, 0, $this->convertPixel(23.62), $this->convertPixel(23.62), true, 0, false, true, 0);
        /*Graand entête*/
        $this->MultiCell($this->convertPixel(1842.21),$this->convertPixel(531.5), '', 1, '', 0, 0, $this->convertPixel(614.17), $this->convertPixel(23.62), true, 0, false, true, 0);
        /*Petit encadré*/
        $this->SetFont('helvetica', 'B', 20);
        $this->MultiCell($this->convertPixel(472.44), $this->convertPixel(271.65),$header->name, 1, 'C', 0, 0, $this->convertPixel(1984), $this->convertPixel(23.62), true, 0, false, true, 25, 'M');
        /*logo */
        $image_file = K_PATH_IMAGES.$company['logo'];
        $this->Image($image_file, $this->convertPixel(80), $this->convertPixel(80), $this->convertPixel(472.44), $this->convertPixel(413.4), 'JPG', $company['link'], 'T', false, 300, '', false, false, 1, false, false, false);
        /*Titre */
        $this->printTitle($siteName, $header->date_campaign, $header->name);
        /*tableau des détails */
        $this->printTable($header);
        }
    
    public function printTitle($title, $date, $name){
        //chagement format de la date
        $newDate = date("d-m-Y", strtotime($date));  
        $date = str_replace('-"', '/', $newDate);
        $newDate = date("d/m/Y", strtotime($date));
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->MultiCell(0,$this->convertPixel(100), $title, 0, 'L', 0, 0, $this->convertPixel(650), $this->convertPixel(25), true, 0, false, true, 0);
        $this->SetFont('helvetica', '0', 15);
        $this->MultiCell(0,$this->convertPixel(100), 'Campagne: '.$newDate.' (Initiale)', 0, 'L', 0, 0, $this->convertPixel(650), $this->convertPixel(200), true, 0, false, true, 0);
    }

    public function printTable($header){
        $this->SetFont('helvetica', '', 12);
        $firstPadding = 295;
        $secondPadding = 295;
        $firstTable = array($header->longitude, $header->latitude, 'Altitude (NGF): '.$header->altitude.'m');
        //condition pour l'adaptation de l'affichage des 3 cases de droite
        if (is_null($header->strainer_zMin)) {
            $secondTable = array($header->waterAltitude,'', '');
        }
        else{
            $secondTable = array($header->waterAltitude, 'Début crépine: '.$header->strainer_zMin.'m', 'Fin crépine: '.$header->strainer_zMax.'m');
        }
        $this->setCellPaddings(5, 1, '', '');
        //dessin des 6 cases en fonction de leur contenu
        for ($i=0; $i < 3 ; $i++) { 
            $this->MultiCell($this->convertPixel(921),$this->convertPixel(87), $firstTable[$i] , 1, 'L', 0, 0, $this->convertPixel(614), $this->convertPixel($firstPadding), true, 0, false, true, 0);
            $firstPadding+=87;
        }
        for ($j=0; $j < 3 ; $j++) { 
            $this->MultiCell($this->convertPixel(921),$this->convertPixel(87), $secondTable[$j] , 1, 'L', 0, 0, $this->convertPixel(1535), $this->convertPixel($secondPadding), true, 0, false, true, 0);
            $secondPadding+=87;
        }
        $this->setCellPaddings(0, 0, 0, 0);

    }

    public function drawFooter(){
        $this->SetFont('times', 'I', 10);
        $this->MultiCell(/*taille de la case du footer x*/$this->convertPixel(2432.76),/*taille de la case du footer y*/ $this->convertPixel(567), 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 1, 'C', 0, 0, $this->convertPixel(23.62), $this->convertPixel(2834.64), true, 0, false, true, 55, 'B');
        $this->ImageSVG($file='../../public/css/kiwi-maps_logo.svg', $this->convertPixel(2159), $this->convertPixel(3401.5), $this->convertPixel(295.3), $this->convertPixel(82.7), $link='http://www.kiwi-maps.com/', 'R', '', 0,false);
        $this->SetFont('times', '', 12);
    }
    public function newPage(){
        $this->SetPrintHeader(false);
        $this->SetPrintFooter(false);
        $this->AddPage();
    }

    public function drawBody(){
    $this->MultiCell($this->convertPixel(2432.76),$this->convertPixel(2280), '', 1, '', 0, 0, $this->convertPixel(23.62), $this->convertPixel(555.12), true, 0, false, true, 0);
    $this->SetFont('times', '', 12);
    $this->MultiCell($this->convertPixel(150),$this->convertPixel(50), 'Prof. (m)', 1, 'L', 0, 0, $this->convertPixel(25), $this->convertPixel(570), true, 0, false, true, 0);
    $this->addLine(197, 565, 197, 2850);
    $this->addLine(362, 565, 362, 2850);
    $this->addLine(502, 565, 502, 2850);
    $this->addLine(1081, 565, 1081, 2850);
    $this->addLine(1672, 565, 1672, 2850);
    }

    public function addLine($x1, $y1, $x2, $y2 ){
        $style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'mitter', 'dash' => ' 5,5', 'color' => array(0, 0, 0));
        $this->Line($this->convertPixel($x1), $this->convertPixel($y1), $this->convertPixel($x2),$this->convertPixel($y2),  $style);
        $style = array('width' => 0, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(0,0,0,0,$style);

    }

    public function addText($content, $x, $y,  $size){
        $this->SetFont('times', 'B', $size);
        $this->MultiCell(55, 5, '[FIT CELL] '.$content."\n", 1, 'L', 0, 0, $this->convertPixel($x), $this->convertPixel($y), true, 0, false, true, 60, 'M', true);
    }
    public function generatePdf(){
        $this->Output(RELATIVE_PATH['temp']. 'example06.pdf','F');
        $output = RELATIVE_PATH['temp']. 'example06.pdf';
        if (@file_exists($output)) {
            return 'La conversion a réussi. Vous pouvez '.
            '<a href="../storage/temp/example06.pdf"> ouvrir le fichier</a>';
        }
        else{
            return "Erreur de conversion";
        }
    }

    private function convertPixel($px){
        return $px * 0.084666667;
    }
}
