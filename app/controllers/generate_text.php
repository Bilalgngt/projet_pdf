<?php
    //inclusion de la bilbiotheque
    include('../autoload/autoload.php');
    include('../models/CompanyModel.php');
    include('../models/PdfModel.php');

    
    $pdfSheet = new PdfModel();
    $pdfSheet->addPage();
    $pdfSheet->Header($rows['0']['link'], $rows['0']['logo']);
    $pdfSheet->addLine(25, 50.5, 25, 240);
    $pdfSheet->addLine(45, 50.5, 45, 240);
    $pdfSheet->addLine(60, 50.5, 60, 240);
    $pdfSheet->addLine(100, 50.5, 100, 240);
    $pdfSheet->addLine(150, 50.5, 150, 240);
    echo $pdfSheet->generatePdf();

    exit();