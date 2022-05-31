<?php
include('../../library/examples/tcpdf_include.php');
class PdfModel extends TCPDF {

    private $_scale;
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
    public function setScale($scale){
        $this->_scale = $scale;
    }
    public function getScale(){
        return $this->_scale;
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
        $this->MultiCell($this->convertPixelToMm(591),$this->convertPixelToMm(532), '', 1, '', 0, 0, $this->convertPixelToMm(24), $this->convertPixelToMm(24), true, 0, false, true, 0);
        /*Graand entête*/
        $this->MultiCell($this->convertPixelToMm(1843),$this->convertPixelToMm(532), '', 1, '', 0, 0, $this->convertPixelToMm(615), $this->convertPixelToMm(24), true, 0, false, true, 0);
        /*Petit encadré*/
        $this->SetFont('helvetica', 'B', 20);
        $this->MultiCell($this->convertPixelToMm(473), $this->convertPixelToMm(272),$header->name, 1, 'C', 0, 0, $this->convertPixelToMm(1984), $this->convertPixelToMm(24), true, 0, false, true, 25, 'M');
        /*logo */
        $image_file = K_PATH_IMAGES.$company['logo'];
        $this->Image($image_file, $this->convertPixelToMm(80), $this->convertPixelToMm(80), $this->convertPixelToMm(473), $this->convertPixelToMm(414), 'JPG', $company['link'], 'T', false, 300, '', false, false, 1, false, false, false);
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
        $this->MultiCell(0,$this->convertPixelToMm(100), $title, 0, 'L', 0, 0, $this->convertPixelToMm(650), $this->convertPixelToMm(25), true, 0, false, true, 0);
        $this->SetFont('helvetica', '0', 15);
        $this->MultiCell(0,$this->convertPixelToMm(100), 'Campagne: '.$newDate.' (Initiale)', 0, 'L', 0, 0, $this->convertPixelToMm(650), $this->convertPixelToMm(200), true, 0, false, true, 0);
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
            $this->MultiCell($this->convertPixelToMm(921.5),$this->convertPixelToMm(87), $firstTable[$i] , 1, 'L', 0, 0, $this->convertPixelToMm(615), $this->convertPixelToMm($firstPadding), true, 0, false, true, 0);
            $firstPadding+=87;
        }
        for ($j=0; $j < 3 ; $j++) { 
            $this->MultiCell($this->convertPixelToMm(921.5),$this->convertPixelToMm(87), $secondTable[$j] , 1, 'L', 0, 0, $this->convertPixelToMm(1536.5), $this->convertPixelToMm($secondPadding), true, 0, false, true, 0);
            $secondPadding+=87;
        }
        $this->setCellPaddings(0, 0, 0, 0);

    }

    public function drawBody($scaleDetails, $sampleDetails, $pollutantDetails){
        $this->MultiCell($this->convertPixelToMm(2434),$this->convertPixelToMm(2280), '', 1, '', 0, 0, $this->convertPixelToMm(24), $this->convertPixelToMm(556), true, 0, false, true, 0);
        $this->SetFont('times', '', 12);
        $this->MultiCell($this->convertPixelToMm(0),$this->convertPixelToMm(50), 'Prof. (m)', 0, 'L', 0, 0, $this->convertPixelToMm(32), $this->convertPixelToMm(575), true, 0, false, true, 0);
        $this->StartTransform();
        $this->Rotate(90,$this->convertPixelToMm(390), $this->convertPixelToMm(770));
        $this->MultiCell($this->convertPixelToMm(250),$this->convertPixelToMm(150), 'analyses effectuées', 0, 'C', 0, 0, $this->convertPixelToMm(362), $this->convertPixelToMm(750), true, 0, false, true, 0);
        $this->StopTransform();
        $this->MultiCell($this->convertPixelToMm(0),$this->convertPixelToMm(50), 'Lithologie', 0, 'L', 0, 0, $this->convertPixelToMm(700), $this->convertPixelToMm(575), true, 0, false, true, 0);
        $this->MultiCell($this->convertPixelToMm(0),$this->convertPixelToMm(50), 'Commentaires', 0, 'L', 0, 0, $this->convertPixelToMm(1200), $this->convertPixelToMm(575), true, 0, false, true, 0);
        $this->addLine(225, 565, 225, 2830, 'dot');
        $this->addLine(362, 565, 362, 2830, 'dot');
        $this->addLine(502, 565, 502, 2850, 'dot');
        $this->addLine(1081, 565, 1081, 2830, 'dot');
        $this->addLine(1672, 565, 1672, 2830, 'dot');
        $this->drawScale($scaleDetails);
        $this->drawSamples($sampleDetails);
        $this->writePollutantNames($pollutantDetails);
    }

    public function drawScale($scaleDetails){
        $this->SetFont('times', '', 9);
        $scaler = $this->getMaxScale($this->getScale());
        $numSpacing = $this->convertPixelToMm(790);
        $posY = 810;
        $spacing = 15;
        for($i=0; $i<120;$i++){
            if($i % 10 === 0){
                $this->MultiCell($this->convertPixelToMm(75),$this->convertPixelToMm(50), number_format(($i/$scaler),1), 0, 'C', 0, 0, $this->convertPixelToMm(100), $this->convertPixelToMm($posY-20), true, 0, false, true, 0);
                $this->addLine(180, $posY, 225, $posY, 'normal');
            }
            else{
                $this->addLine(195, $posY, 225, $posY, 'normal');
            }
            $posY+=$spacing;
        }
    }


    public function writePollutantNames($pollutantDetails){
        $cellWitdh = 757/sizeof($pollutantDetails);
        $spacingX = 1700;
        foreach($pollutantDetails as $pollutantDetail){
            $this->MultiCell($this->convertPixelToMm($cellWitdh),$this->convertPixelToMm(145), $pollutantDetail->name, 1, 'C', 0, 0, $this->convertPixelToMm($spacingX), $this->convertPixelToMm(665), true, 0, false, true, $this->convertPixelToMm(145), 'M');
            $this->addLine(180,810,1700,810, 'normal');
            $spacingX+= $cellWitdh;
        }
    }

    public function drawSamples($sampleDetails){
        foreach($sampleDetails as $key => $sample){
            $scaler = $this->getMaxScale($this->getScale());
            $startY=810+$sample->start*15*$scaler;
            $endY = 810+$sample->end*15*$scaler;
            $centerY = ($startY+$endY)/2;
            $spacing = ($endY - $startY);
            $cellWitdh = 757/$sample->pollutantToDraw;
            $pollutantSpacing = 1700;
            if($sample->analysis === 1){
                $this->Rect($this->convertPixelToMm(400),$this->convertPixelToMm($centerY),$this->convertPixelToMm(20),$this->convertPixelToMm(20), 'DF', '', array(220,0,0));
            }
            $this->addLine(180,$startY,2457,$startY, 'normal');
            $this->addLine($pollutantSpacing,810,$pollutantSpacing,$endY, 'normal');
            $this->MultiCell($this->convertPixelToMm(579),$this->convertPixelToMm($spacing), $sample->sampleName, 0, 'C', 0, 0, $this->convertPixelToMm(502), $this->convertPixelToMm($startY), true, 0, false, true, $this->convertPixelToMm($spacing), 'M');
            $this->MultiCell($this->convertPixelToMm(579),$this->convertPixelToMm($spacing), $sample->comment, 0, 'C', 0, 0, $this->convertPixelToMm(1081), $this->convertPixelToMm($startY), true, 0, false, true, $this->convertPixelToMm($spacing), 'M');
            $this->addLine(180,$endY,2457,$endY, 'normal');
            foreach($sample->concentrations as $index => $concentration ){
                $this->MultiCell($this->convertPixelToMm($cellWitdh),$this->convertPixelToMm($spacing), $concentration, 0, 'C', 0, 0, $this->convertPixelToMm($pollutantSpacing), $this->convertPixelToMm($startY), true, 0, false, true, $this->convertPixelToMm($spacing), 'M');
                $pollutantSpacing+= $cellWitdh;
                $this->addLine($pollutantSpacing,810,$pollutantSpacing,$endY, 'normal');
            }
        }
    }

    public function drawFooter(){
        $this->SetFont('times', 'I', 10);
        $this->MultiCell(/*taille de la case du footer x*/$this->convertPixelToMm(2434),/*taille de la case du footer y*/ $this->convertPixelToMm(567), 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 1, 'C', 0, 0, $this->convertPixelToMm(24), $this->convertPixelToMm(2834.64), true, 0, false, true, 55, 'B');
        $this->ImageSVG($file='../../public/css/kiwi-maps_logo.svg', $this->convertPixelToMm(2159), $this->convertPixelToMm(3401.5), $this->convertPixelToMm(295.3), $this->convertPixelToMm(82.7), $link='http://www.kiwi-maps.com/', 'R', '', 0,false);
        $this->SetFont('times', '', 12);
    }
    public function newPage(){
        $this->SetPrintHeader(false);
        $this->SetPrintFooter(false);
        $this->AddPage();
    }

    public function addLine($x1, $y1, $x2, $y2, $style ){
        if ($style === 'dot'){
            $style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'mitter', 'dash' => '5,5', 'color' => array(0, 0, 0));
            $this->Line($this->convertPixelToMm($x1), $this->convertPixelToMm($y1), $this->convertPixelToMm($x2),$this->convertPixelToMm($y2),  $style);
            $style = array('width' => 0, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
            $this->Line(0,0,0,0,$style);
        }
        elseif ($style === 'normal'){
            $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
            $this->Line($this->convertPixelToMm($x1), $this->convertPixelToMm($y1), $this->convertPixelToMm($x2),$this->convertPixelToMm($y2),  $style);
            $style = array('width' => 0, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
            $this->Line(0,0,0,0,$style);
        }
    }


    public function generatePdf(){
        $this->Output(RELATIVE_PATH['temp']. 'example06.pdf','F');
        $output = RELATIVE_PATH['temp']. 'example06.pdf';
        if (@file_exists($output)) {
            return '<br><br><br><br><br><br>La conversion a réussi. Vous pouvez '.
            '<a href="../storage/temp/example06.pdf"> ouvrir le fichier</a>';
        }
        else{
            return "Erreur de conversion";
        }
    }

    public function getMaxScale($scale){
        if($scale <= 5){
            return 20;
        }
        elseif($scale > 5){
            return 10;
        }
        elseif($scale > 10){
            return 5;
        }
        elseif($scale > 20){
            return 2;
        }
        else{return 1;}
        
    }

    private function convertPixelToMm($px){
        return $px * 0.084666667;
    }
}
