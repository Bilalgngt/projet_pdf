<?php 

/* Definition des chemins relatif et absolu */
define('ABSOLUTE_PATH', substr(__DIR__, 0, -12));
const RELATIVE_PATH = [
    'controllers' => ABSOLUTE_PATH . 'app/controllers/',
    'models' => ABSOLUTE_PATH . 'app/models/',
    'classes' => ABSOLUTE_PATH . 'classes/',
    'views' => ABSOLUTE_PATH . 'app/views/',
    'temp' => ABSOLUTE_PATH . 'storage/temp/',
];