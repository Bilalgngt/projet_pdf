<?php
//require '../database/database.php';
//
//$company = $_POST['Companies'];
//$sql = $pdo->query("SELECT * FROM company WHERE id = '$company'");
//
//$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
//

class CompanyModel
{
    public function getCompanyById($companyId)
    {
        require '../database/database.php';
        $query = $pdo->prepare("SELECT * 
                                FROM company
                                WHERE id = ?");
        $query->execute([
            $companyId,
        ]);
        return $query->fetchAll();
    }
}