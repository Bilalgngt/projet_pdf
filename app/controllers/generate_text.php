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
        /*$Pollutant = new PollutantModel();
        $pollutantNames = $Pollutant->getAllPollutantNames();
        var_dump($Pollutant->findPollutantNameByIdCustom('49','0',$pollutantNames));
        $Geology = new GeologyModel();
        $geologyNames = $Geology->getAllGeologyNames();
        var_dump($Geology->findGeologyNameByIdCustom('201','0',$geologyNames));
        var_dump($Geology->findPatternIdByIdCustom('201','0',$geologyNames));
        $GlobalParameter = new GlobalParameterModel();
        $globalParameterNames = $GlobalParameter->getAllGlobalParameterNamesOptions();
        var_dump($GlobalParameter->findParameterNameByIdCustom('10','0',$globalParameterNames));*/
        /*****************************************************/


        if($_POST['action'] === 'generatePdf'){
            // déclaration de variables
            $headers = json_decode($_POST['headers']);
            $scaleDetails = json_decode($_POST['scaleDetails']);
            $sampleDetails = json_decode($_POST['sampleDetails']);
            //var_dump($geologyDetails);
            $siteName = $_POST['site'];
            //Appel de la base de données pour les données de l'entreprise
            $Company = new CompanyModel();
            $company = $Company->getCompanyById($_POST['company']);
            //Appel de la base de données pour la geologie
            $Geology = new GeologyModel();
            $geologyNames = $Geology->getAllGeologyNames();
            //Appel de la base de donnée pour les polluants à récuperer
            $Pollutant = new PollutantModel();
            $pollutantNames = $Pollutant->getAllPollutantNames();
            //création de chaque page, une par une via foreach()
            $pdfSheet = new PdfModel();
            foreach ($headers as $key => $header ) {
                    foreach($sampleDetails[$key] as $sample){
                        $sample->sampleName = $Geology->findGeologyNameByIdCustom($sample->sampleId,$sample->sampleCustom,$geologyNames);
                        foreach($sample->pollutantDetails as $pollutant ){
                        $pollutant->name = $Pollutant->findPollutantNameByIdCustom($pollutant->id,$pollutant->custom,$pollutantNames) . "\n mg/kg MS";
                        }
                    }
                    $pdfSheet->newPage();
                    $pdfSheet->drawHeader($company, $siteName, $header);
                    $pdfSheet->setScale($scaleDetails[$key]->maxDepth);
                    $pdfSheet->drawBody($scaleDetails[$key], $sampleDetails[$key]);
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
