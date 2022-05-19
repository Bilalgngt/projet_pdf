<?php
    //inclusion de la bilbiotheque
    include('../autoload/autoload.php');
    include('../models/CompanyModel.php');
    include('../models/PdfModel.php');

    $sql = new CompanyModel();
    $company = $sql->getCompanyById($_POST['Companies']);
    $companyName = $company['0']['cname'];
    $companyLink = $company['0']['link'];
    $companyLogo = $company['0']['logo'];
    $pdfSheet = new PdfModel();
    $pdfSheet->newPage();
    $pdfSheet->drawHeader($companyLink, $companyLogo);
    $pdfSheet->drawFooter();
    $pdfSheet->drawBody();
    echo $pdfSheet->generatePdf();

    exit();