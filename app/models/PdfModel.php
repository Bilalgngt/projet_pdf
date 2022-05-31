<?php
include(RELATIVE_PATH['config'] . 'tcpdf_include.php');

class PdfModel extends TCPDF
{

    private $_maxDepth;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function init()
    {
        $this->SetFont('dejavusans', '', 14, '', true);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->setInformation();

    }

    public function setMaxDepth($maxDepth)
    {
        $this->_maxDepth = $maxDepth;
    }

    public function getMaxDepth()
    {
        return $this->_maxDepth;
    }

    public function setInformation()
    {
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Nicola Asuni');
        $this->SetTitle('TCPDF Example 001');
        $this->SetSubject('TCPDF Tutorial');
        $this->SetKeywords('TCPDF, PDF, example, test, guide');
    }

    public function drawHeader($company, $siteName, $header)
    {
        /*encadrement logo*/
        $this->MultiCell($this->convertPixelToMm(591), $this->convertPixelToMm(532), '', 1, '', 0, 0, $this->convertPixelToMm(24), $this->convertPixelToMm(24), true, 0, false, true, 0);
        /*Grand entête*/
        $this->MultiCell($this->convertPixelToMm(1843), $this->convertPixelToMm(532), '', 1, '', 0, 0, $this->convertPixelToMm(615), $this->convertPixelToMm(24), true, 0, false, true, 0);
        /*Petit encadré*/
        $this->SetFont('helvetica', 'B', 20);
        $this->MultiCell($this->convertPixelToMm(473), $this->convertPixelToMm(272), $header->name, 1, 'C', 0, 0, $this->convertPixelToMm(1984), $this->convertPixelToMm(24), true, 0, false, true, 25, 'M');
        /*logo */
        $image_file = RELATIVE_PATH['logos'] . $company['logo'];
        $this->Image($image_file, $this->convertPixelToMm(80), $this->convertPixelToMm(80), $this->convertPixelToMm(473), $this->convertPixelToMm(414), 'JPG', $company['link'], 'T', false, 300, '', false, false, 1, false, false, false);
        $this->printTitle($siteName, $header->date_campaign, $header->name);
        $this->printLogDetails($header);
    }

    public function printTitle($title, $date, $name)
    {
        $newDate = $this->changeDateFormat($date);
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->MultiCell(0, $this->convertPixelToMm(100), $title, 0, 'L', 0, 0, $this->convertPixelToMm(650), $this->convertPixelToMm(25), true, 0, false, true, 0);
        // Campagne
        $this->SetFont('helvetica', '0', 15);
        $this->MultiCell(0, $this->convertPixelToMm(100), 'Campagne: ' . $newDate . ' (Initiale)', 0, 'L', 0, 0, $this->convertPixelToMm(650), $this->convertPixelToMm(200), true, 0, false, true, 0);
    }

    private function changeDateFormat($date)
    {
        $newDate = date("d-m-Y", strtotime($date));
        $date = str_replace('-"', '/', $newDate);
        return date("d/m/Y", strtotime($date));
    }

    public function printLogDetails($header)
    {
        $this->SetFont('helvetica', '', 12);
        $firstPadding = 295;
        $secondPadding = 295;
        $firstTable = array($header->longitude, $header->latitude, 'Altitude (NGF): ' . $header->altitude . 'm');
        //condition pour l'adaptation de l'affichage des 3 cases de droite
        if (is_null($header->strainer_zMin)) {
            $secondTable = array($header->waterAltitude, '', '');
        } else {
            $secondTable = array($header->waterAltitude, 'Début crépine: ' . $header->strainer_zMin . 'm', 'Fin crépine: ' . $header->strainer_zMax . 'm');
        }
        $this->setCellPaddings(5, 1, '', '');
        //dessin des 6 cases en fonction de leur contenu
        for ($i = 0; $i < 3; $i++) {
            $this->MultiCell($this->convertPixelToMm(921.5), $this->convertPixelToMm(87), $firstTable[$i], 1, 'L', 0, 0, $this->convertPixelToMm(615), $this->convertPixelToMm($firstPadding), true, 0, false, true, 0);
            $firstPadding += 87;
        }
        for ($j = 0; $j < 3; $j++) {
            $this->MultiCell($this->convertPixelToMm(921.5), $this->convertPixelToMm(87), $secondTable[$j], 1, 'L', 0, 0, $this->convertPixelToMm(1536.5), $this->convertPixelToMm($secondPadding), true, 0, false, true, 0);
            $secondPadding += 87;
        }
        $this->setCellPaddings(0, 0, 0, 0);

    }

    public function drawBody($sampleDetails, $pollutantDetails)
    {
        $startY = 565;
        $endY = 2830;
        $posX = array(225, 362, 502, 1081, 1672);
        $this->MultiCell($this->convertPixelToMm(2434), $this->convertPixelToMm(2280), '', 1, '', 0, 0, $this->convertPixelToMm(24), $this->convertPixelToMm(556), true, 0, false, true, 0);
        $this->SetFont('times', '', 12);
        $this->MultiCell($this->convertPixelToMm(0), $this->convertPixelToMm(50), 'Prof. (m)', 0, 'L', 0, 0, $this->convertPixelToMm(32), $this->convertPixelToMm(575), true, 0, false, true, 0);
        $this->StartTransform();
        $this->Rotate(90, $this->convertPixelToMm(390), $this->convertPixelToMm(770));
        $this->MultiCell($this->convertPixelToMm(250), $this->convertPixelToMm(150), 'analyses effectuées', 0, 'C', 0, 0, $this->convertPixelToMm(362), $this->convertPixelToMm(750), true, 0, false, true, 0);
        $this->StopTransform();
        $this->MultiCell($this->convertPixelToMm(0), $this->convertPixelToMm(50), 'Lithologie', 0, 'L', 0, 0, $this->convertPixelToMm(700), $this->convertPixelToMm(575), true, 0, false, true, 0);
        $this->MultiCell($this->convertPixelToMm(0), $this->convertPixelToMm(50), 'Commentaires', 0, 'L', 0, 0, $this->convertPixelToMm(1200), $this->convertPixelToMm(575), true, 0, false, true, 0);
        $this->addLine(180, 810, 2457, 810, 'normal');
        foreach ($posX as $x) {
            $this->addLine($x, $startY, $x, $endY, 'dot');
        }
        $this->drawScale();
        $this->drawSamples($sampleDetails);
        if (sizeof($pollutantDetails) > 0) {
            $this->writePollutantNames($pollutantDetails);
        }
    }

    public function drawScale()
    {
        $this->SetFont('times', '', 9);
        $scaler = $this->getMaxScale($this->getMaxDepth());
        $posY = 810;
        $spacing = 15;
        for ($i = 0; $i < 120; $i++) {
            if ($i % 10 === 0) {
                $this->MultiCell($this->convertPixelToMm(75), $this->convertPixelToMm(50), number_format(($i / $scaler), 1), 0, 'C', 0, 0, $this->convertPixelToMm(100), $this->convertPixelToMm($posY - 20), true, 0, false, true, 0);
                $this->addLine(180, $posY, 225, $posY, 'normal');
            } else {
                $this->addLine(195, $posY, 225, $posY, 'normal');
            }
            $posY += $spacing;
        }
    }


    public function writePollutantNames($pollutantDetails)
    {
        $cellWitdh = 757 / sizeof($pollutantDetails);
        $spacingX = 1700;
        foreach ($pollutantDetails as $pollutantDetail) {
            $this->MultiCell($this->convertPixelToMm($cellWitdh), $this->convertPixelToMm(145), $pollutantDetail->name, 1, 'C', 0, 0, $this->convertPixelToMm($spacingX), $this->convertPixelToMm(665), true, 0, false, true, $this->convertPixelToMm(145), 'M');
            $spacingX += $cellWitdh;
        }
    }

    public function drawSamples($sampleDetails)
    {
        foreach ($sampleDetails as $key => $sample) {
            $scaler = $this->getMaxScale($this->getMaxDepth());
            $startY = 810 + $sample->start * 15 * $scaler;
            $endY = 810 + $sample->end * 15 * $scaler;
            $centerY = ($startY + $endY) / 2;
            $spacing = ($endY - $startY);
            $this->addLine(180, $startY, 2457, $startY, 'normal');

            $this->MultiCell($this->convertPixelToMm(579), $this->convertPixelToMm($spacing), $sample->geologyName, 0, 'C', 0, 0, $this->convertPixelToMm(502), $this->convertPixelToMm($startY), true, 0, false, true, $this->convertPixelToMm($spacing), 'M');
            $this->MultiCell($this->convertPixelToMm(579), $this->convertPixelToMm($spacing), $sample->comment, 0, 'C', 0, 0, $this->convertPixelToMm(1081), $this->convertPixelToMm($startY), true, 0, false, true, $this->convertPixelToMm($spacing), 'M');
            $this->addLine(180, $endY, 2457, $endY, 'normal');
            if ($sample->analysis === 1) {
                $this->Rect($this->convertPixelToMm(400), $this->convertPixelToMm($centerY), $this->convertPixelToMm(20), $this->convertPixelToMm(20), 'DF', '', array(220, 0, 0));
            }
            if ($sample->pollutantToDraw > 0) {
                $cellWidth = 757 / $sample->pollutantToDraw;
                $pollutantSpacing = 1700;
                $this->addLine($pollutantSpacing, 810, $pollutantSpacing, $endY, 'normal');
                foreach ($sample->concentrations as $index => $concentration) {
                    $this->MultiCell($this->convertPixelToMm($cellWidth), $this->convertPixelToMm($spacing), $concentration, 0, 'C', 0, 0, $this->convertPixelToMm($pollutantSpacing), $this->convertPixelToMm($startY), true, 0, false, true, $this->convertPixelToMm($spacing), 'M');
                    $pollutantSpacing += $cellWidth;
                    $this->addLine($pollutantSpacing, 810, $pollutantSpacing, $endY, 'normal');
                }
            }
        }
    }

    public function drawFooter()
    {
        $this->SetFont('times', 'I', 10);
        $this->MultiCell(/*taille de la case du footer x*/ $this->convertPixelToMm(2434),/*taille de la case du footer y*/ $this->convertPixelToMm(567), 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 1, 'C', 0, 0, $this->convertPixelToMm(24), $this->convertPixelToMm(2834.64), true, 0, false, true, 55, 'B');
        $this->ImageSVG($file = RELATIVE_PATH['public'] . '/css/kiwi-maps_logo.svg', $this->convertPixelToMm(2159), $this->convertPixelToMm(3401.5), $this->convertPixelToMm(295.3), $this->convertPixelToMm(82.7), $link = 'http://www.kiwi-maps.com/', 'R', '', 0, false);
        $this->SetFont('times', '', 12);
    }

    public function newPage($maxDepth)
    {
        $this->setMaxDepth($maxDepth);
        $this->SetPrintHeader(false);
        $this->SetPrintFooter(false);
        $this->AddPage();
    }

    public function addLine($x1, $y1, $x2, $y2, $style)
    {
        if ($style === 'dot') {
            $style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'mitter', 'dash' => '5,5', 'color' => array(0, 0, 0));
            $this->Line($this->convertPixelToMm($x1), $this->convertPixelToMm($y1), $this->convertPixelToMm($x2), $this->convertPixelToMm($y2), $style);
            $style = array('width' => 0, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
            $this->Line(0, 0, 0, 0, $style);
        } elseif ($style === 'normal') {
            $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
            $this->Line($this->convertPixelToMm($x1), $this->convertPixelToMm($y1), $this->convertPixelToMm($x2), $this->convertPixelToMm($y2), $style);
            $style = array('width' => 0, 'cap' => 'butt', 'join' => 'mitter', 'dash' => 0, 'color' => array(0, 0, 0));
            $this->Line(0, 0, 0, 0, $style);
        }
    }

    public function generatePdf($fileName,$pathFile)
    {
        $output = $pathFile . $fileName;
        $this->Output($output, 'F');
        return @file_exists($output);
    }

    public function getMaxScale($maxDepth)
    {
        if ($maxDepth <= 5) {
            return 20;
        } elseif ($maxDepth < 10) {
            return 10;
        } elseif ($maxDepth < 20) {
            return 5;
        } elseif ($maxDepth < 50) {
            return 2;
        } else {
            return 1;
        }

    }

    private function convertPixelToMm($px)
    {
        return $px * 0.084666667;
    }
}
