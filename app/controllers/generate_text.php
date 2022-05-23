<?php
    //inclusion de la bilbiotheque
    include('../autoload/autoload.php');
    include('../models/CompanyModel.php');
    include('../models/PollutantModel.php');
    include('../models/GeologyModel.php');
    include('../models/GlobalParameterModel.php');
    include('../models/PdfModel.php');

    if(!empty($_POST)){

        /* Exemples d'utilisation des modèles */
        $Pollutant = new PollutantModel();
        $pollutantNames = $Pollutant->getAllPollutantNames();
        var_dump($Pollutant->findPollutantNameByIdCustom('49','0',$pollutantNames));
        $Geology = new GeologyModel();
        $geologyNames = $Geology->getAllGeologyNames();
        var_dump($Geology->findGeologyNameByIdCustom('101','0',$geologyNames));
        var_dump($Geology->findPatternIdByIdCustom('101','0',$geologyNames));
        $GlobalParameter = new GlobalParameterModel();
        $globalParameterNames = $GlobalParameter->getAllGlobalParameterNamesOptions();
        var_dump($GlobalParameter->findParameterNameByIdCustom('10','0',$globalParameterNames));
        /*****************************************************/

        if($_POST['action'] === 'generatePdf'){
            // déclaration de variables
            $headers = json_decode($_POST['headers']);
            $siteName = $_POST['site'];
            $bodyDetails = $_POST['body'];
            //Appel de la base de données
            $Company = new CompanyModel();
            $company = $Company->getCompanyById($_POST['company']);
            //création de chaque page une par une via foreach()
            $pdfSheet = new PdfModel();
            foreach ($headers as $key => $header ) {
                    $pdfSheet->newPage();
                    $pdfSheet->drawHeader($company, $siteName, $header);
                    $pdfSheet->drawBody($bodyDetails[$key]);
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