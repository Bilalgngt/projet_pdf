<?php

try {
    $db = new PDO(
        "mysql:host=" . DB_SOIL_DATA['host'] . ";port=" . DB_SOIL_DATA['port'] . ";dbname=" . DB_SOIL_DATA['name'] . ";charset=utf8",
        DB_SOIL_DATA['user'],
        DB_SOIL_DATA['password'],
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
        ]);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}