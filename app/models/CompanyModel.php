<?php
require '../database/database.php';

$company = $_POST['Companies'];
$sql = $pdo->query("SELECT * FROM company WHERE cname = '$company'");

$rows = $sql->fetchAll(PDO::FETCH_ASSOC);