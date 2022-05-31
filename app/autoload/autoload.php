<?php 

/* Definition des chemins relatif et absolu */
define('ABSOLUTE_PATH', substr(__DIR__, 0, -12));
const RELATIVE_PATH = [
    'controllers' => ABSOLUTE_PATH . 'app/controllers/',
    'models' => ABSOLUTE_PATH . 'app/models/',
    'classes' => ABSOLUTE_PATH . 'classes/',
    'views' => ABSOLUTE_PATH . 'app/views/',
    'temp' => ABSOLUTE_PATH . 'storage/temp/',
    'public' => ABSOLUTE_PATH . 'public/',
    'library' => ABSOLUTE_PATH . 'library/',
    'config' => ABSOLUTE_PATH . 'config/',
    'logos' => ABSOLUTE_PATH . 'storage/logos/',
];

/* Chargement des modèles */
include(RELATIVE_PATH['models'].'CompanyModel.php');
include(RELATIVE_PATH['models'].'PollutantModel.php');
include(RELATIVE_PATH['models'].'GeologyModel.php');
include(RELATIVE_PATH['models'].'GlobalParameterModel.php');
include(RELATIVE_PATH['models'].'PdfModel.php');