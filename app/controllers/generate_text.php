<?php
    //inclusion de la bilbiotheque
    include('../autoload/autoload.php');
    include('../models/PdfModel.php');

    if(!empty($_POST)){
        $site = json_decode($_POST['site']);

        var_dump($site->name);
    }


    $pdfSheet = new PdfModel();
    $pdfSheet->addPage();
    $pdfSheet->addText('salut!',100,75,12);
    $pdfSheet->addLine(75, 150, 25, 25);
    $pdfSheet->addLine(150, 150, 25, 40);
    $pdfSheet->addLine(75, 150, 40, 40);
    echo $pdfSheet->generatePdf();

    exit();

// Set some content to print
//$html = <<<EOD 
//EOD;

// Print text using writeHTMLCell()
//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------
