<?php
    //inclusion de la bilbiotheque
    include('../autoload/autoload.php');
    include('../models/CompanyModel.php');
    include('../models/PdfModel.php');

    if(!empty($_POST)){
        if($_POST['action'] === 'generatePdf'){
            // déclaration de variables
            $headers = json_decode($_POST['headers']);
            $coordinates = json_decode($_POST['coordinates']);
            $waterAltitude = json_decode($_POST['waterAltitude']);
            $siteName = $_POST['site'];
            //Appel de la base de données
            $Company = new CompanyModel();
            $company = $Company->getCompanyById($_POST['company']);
            //création de chaque page une par une via foreach()
            $pdfSheet = new PdfModel();
            foreach ($headers as $key => $header ) {
                    $pdfSheet->newPage();
                    $pdfSheet->drawHeader($company, $siteName, $header);
                    $pdfSheet->drawBody();
                    $pdfSheet->drawFooter();
            }
            echo $pdfSheet->generatePdf();
            exit();

            //brouillon
        }elseif($_POST['action'] === 'chooseCompany') {
            $Company = new CompanyModel();
            $company = $Company->getCompanyById($_POST['Company']);


            $pdfSheet = new PdfModel();
            $pdfSheet->newPage();
            $pdfSheet->drawHeader($company['link'], $company['logo']);
            $pdfSheet->drawFooter();
            $pdfSheet->drawBody();
            echo $pdfSheet->generatePdf();
            exit();
        }
    }


    // par defaut 0 à 5m -> 10 affichages
    //10
    //20
    //50

    //chercher profondeur max
    //d'eau d'air 