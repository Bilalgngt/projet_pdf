<?php

    require_once RELATIVE_PATH['config'].'connection_db.php';

	$hostname = DB_SOIL_DATA['host'];	// essayer 127.0.0.1 (ajouter même le port :80) avec linux s'il ne reconais pas localhost
	$port =  DB_SOIL_DATA['port'];
    $base=  DB_SOIL_DATA['name'];
	$loginBD=  DB_SOIL_DATA['user'];
	$passBD= DB_SOIL_DATA['password'];
	try {
		// DSN (Data Source Name)pour se connecter à MySQL sous Wamp/Windows
		$dsn = "mysql:server=$hostname ; port=$port; dbname=$base";
		$pdo = new PDO ($dsn, $loginBD, $passBD,
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		// Le dernier argument sert à ce que toutes les chaines de caractères 
		// en entrée et sortie de MySql soit dans le codage UTF-8
		
		// On active le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e) {
		// affiche (en UTF8) le message d'erreur associé à l'exception
		echo utf8_encode("Echec de connexion : " . $e->getMessage() . "\n");
		die(); // On arrête tout.
	}