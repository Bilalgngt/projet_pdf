<?php
    //inclusion de la bilbiotheque
    include('../autoload/autoload.php');
    include('../models/CompanyModel.php');
    include('../models/PdfModel.php');

    if(!empty($_POST)){
        if($_POST['action'] === 'generatePdf'){
            $site = json_decode($_POST['site']);
            echo ($_POST['company']);
            exit();
        }elseif($_POST['action'] === 'chooseCompany') {
            $Company = new CompanyModel();
            $company = $Company->getCompanyById($_POST['Company']);
            $companyName = $company['cname'];
            $companyLink = $company['link'];
            $companyLogo = $company['logo'];


            $pdfSheet = new PdfModel();
            $pdfSheet->newPage();
            $pdfSheet->drawHeader($company['link'], $company['logo']);
            $pdfSheet->drawFooter();
            $pdfSheet->drawBody();
            echo $pdfSheet->generatePdf();
            exit();
        }
    }