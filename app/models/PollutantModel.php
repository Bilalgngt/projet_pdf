<?php

class PollutantModel
{

    private $_language;

    public function __construct()
    {
        $this->setLanguage();
    }

    private function setLanguage()
    {
        $this->_language = 'name_fr';
    }

    private function getLanguage()
    {
        return $this->_language;
    }

    // Fonction utilisée par le générateur, ne pas supprimer
    public function getAllPollutantNames()
    {
        require '../database/database.php';
        $query = $pdo->prepare("SELECT " . $this->getLanguage() . ",id, 0 AS custom
                               FROM pollutant_names
                               UNION
                               SELECT " . $this->getLanguage() . ",id, 1 AS custom
                               FROM pollutant_custom
                               WHERE 1
                               ");
        $query->execute();
        return $query->fetchAll();
    }

    public function findPollutantNameByIdCustom($pollutantId, $custom, $pollutantNames)
    {
        foreach ($pollutantNames as $pollutantName) {
            if ($pollutantName['id'] === $pollutantId && $pollutantName['custom'] === $custom) {
                return $pollutantName[$this->getLanguage()];
            }
        }
        return 'error';
    }

}