<?php
    include('../autoload/autoload.php');

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
            //Appel de la base de données pour les données de l'entreprise
            $Company = new CompanyModel();
            $company = $Company->getCompanyById($_POST['company']);
            //Appel de la base de données pour la geologie
            $Geology = new GeologyModel();
            $geologyNames = $Geology->getAllGeologyNames();

            // déclaration de variables
            $pages = json_decode($_POST['pages']);
            $maxDepths = json_decode($_POST['maxDepths']);
            $waterZmin = json_decode($_POST['waterZmin']);
            $sampleDetails = json_decode($_POST['sampleDetails']);
            $pollutantDetails = loadPollutantNames($_POST['pollutantDetails']);
            $siteName = $_POST['site'];

            //création de chaque page, une par une via foreach()
            $pdfSheet = new PdfModel();

            foreach ($pages as $pageNumber => $page ) {
                    loadGeologyNames($sampleDetails[$pageNumber],$geologyNames,$Geology);
                    $pdfSheet->newPage($maxDepths[$pageNumber]);
                    $pdfSheet->drawHeader($company, $siteName, $page, $pageNumber);
                    $pdfSheet->drawBody($sampleDetails[$pageNumber], $pollutantDetails, $waterZmin[$pageNumber]);
                    $pdfSheet->drawFooter();
            }
            $fileName = $siteName.'.pdf';
            if($pdfSheet->generatePdf($fileName,RELATIVE_PATH['temp'])){
                echo '<br><br><br><br><br><br>La conversion a réussi. Vous pouvez ' .
                    '<a href="storage/temp/'.$fileName.'"> ouvrir le fichier</a>';
            }else{
                echo 'Erreur de conversion';
            }
            exit();
        }
    }

    function loadPollutantNames($pollutantDetails){
        $Pollutant = new PollutantModel();
        $pollutantNames = $Pollutant->getAllPollutantNames();
        $pollutantDetails = json_decode($pollutantDetails);
        foreach($pollutantDetails as $pollutant ){
            $pollutant->name = $Pollutant->findPollutantNameByIdCustom($pollutant->id_pollutant_name,$pollutant->custom,$pollutantNames) . "\n mg/kg MS";
        }
        return $pollutantDetails;
    }

    function loadGeologyNames($sampleDetail,$geologyNames,$Geology){
        foreach($sampleDetail as $sample){
            $sample->geologyName = $Geology->findGeologyNameByIdCustom($sample->geologyId,$sample->geologyCustom,$geologyNames);
        }
    }

